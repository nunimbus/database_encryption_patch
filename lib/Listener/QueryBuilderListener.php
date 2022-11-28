<?php
/**
 * @copyright Copyright (c) 2022, Andrew Summers
 *
 * @author Andrew Summers
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\DatabaseEncryptionPatch\Listener;

require_once __DIR__ . '/../../vendor/autoload.php';

use OCA\DatabaseEncryptionPatch\Test\TestQueryHelper;
use OC\DB\QueryBuilder\CompositeExpression as OCCompositeExpression;
use Doctrine\DBAL\Query\Expression\CompositeExpression as DBALCompositeExpression;
use Doctrine\DBAL\Query\QueryBuilder;
use OC;

use \PHPSQLParser\processors\DefaultProcessor;
use \PHPSQLParser\Options;
use \PHPSQLParser\processors\SQLChunkProcessor;

//use \PhpMyAdmin\SqlParser\Parser;
//use \PhpMyAdmin\SqlParser\Components\Condition;
//use \PhpMyAdmin\SqlParser\Lexer;

class QueryBuilderListener {
	private $password = null;
	private $cryptOpts = "";
	private $encryptFunc;
	private $decryptFunc;
	private $sql;
	private $query;
	private $encryptMap = array();
	private $matchingParts = array();

	public function __construct($params) {
		$this->query = $params['queryBuilder'];
		$server = OC::$server;
		$parent = $server->get('OCA\DatabaseEncryptionPatch\AppInfo\Application');
		$this->password = $parent->getPassword();

		if ($server->getConfig()->getSystemValue('dbtype') == 'pgsql') {
			$this->cryptOpts = ", 'cipher-algo=aes256'";
			$this->encryptFunc = 'pgp_sym_encrypt';
			$this->decryptFunc = 'pgp_sym_decrypt';
		}
		else if ($server->getConfig()->getSystemValue('dbtype') == 'mysql') {
			$this->encryptFunc = 'AES_ENCRYPT';
			$this->decryptFunc = 'AES_DECRYPT';
		}

		// Get the encryptMap from the config file and clear out any prefixes
		$encryptMap = $server->getConfig()->getSystemValue('dbencrypt') ?: array();
		$dbtableprefix = $server->getConfig()->getSystemValue('dbtableprefix');
		$encryptMapFlat = array();

		foreach ($encryptMap as $app) {
			$encryptMapFlat = array_merge($encryptMapFlat, $app);
		}

		$encryptMap = $encryptMapFlat;

		foreach ($encryptMap as $table=>$columns) {
			if (substr($table, 0, 8) == '*PREFIX*') {
				$this->encryptMap[substr($table, 8)] = $columns;
			}
			else if (substr($table, 0, strlen($dbtableprefix)) == $dbtableprefix) {
				$this->encryptMap[substr($table, strlen($dbtableprefix))] = $columns;
			}
			else {
				$this->encryptMap[$table] = $columns;
			}
		}

		// Polyfill for PHP < 8
		if (!function_exists('str_contains')) {
			function str_contains($haystack, $needle) {
				return $needle !== '' && mb_strpos($haystack, $needle) !== false;
			}
		}
	}

	public static function eventHandler($params) {
		$handler = new \OCA\DatabaseEncryptionPatch\Listener\QueryBuilderListener($params);
		$handler->handle();
	}

	public function handle() {
		$sqlOrig = $this->query->getSQL();

		// This is a simple (and sloppy) class that helps dump queries to disk so they can be replayed
		 //$testQueryHelper = new TestQueryHelper($this->query);

		// Inside the TestQueryHelper class are statements which are useful for setting brreakpoints to target specific query types
		// $testQueryHelper->debugBreakpoints();

		// This dumps each query to disk in the __DIR__ . '/test' directory
		// $testQueryHelper->dump();

		// Queries can be replayed by specifying the query type and which query it is inside __DIR__ . '/test/<query-type.php>'
		//$testQueryHelper->get('select', 60);
		
		// "SELECT `id`, `uri`, `lastmodified`, `etag`, `size`, `carddata`, `uid` FROM `*PREFIX*cards` WHERE `addressbookid` = :dcValue1";
		//$testQueryHelper->get('select', 17);

		// "SELECT `id`, `uri`, `lastmodified`, `etag`, `size`, `carddata`, `uid` FROM `*PREFIX*cards` WHERE (`addressbookid` = :dcValue1) AND (`uri` = :dcValue2) LIMIT 1";
		//$testQueryHelper->get('select', 18);

		// "SELECT DISTINCT `cp`.`cardid` FROM `*PREFIX*cards_properties` `cp` WHERE (`cp`.`addressbookid` = :dcValue1) AND ((`cp`.`name` = :dcValue2) OR (`cp`.`name` = :dcValue3)) LIMIT 25";
		//$testQueryHelper->get('select', 33);

		// "SELECT COUNT(DISTINCT `cp`.`cardid`) FROM `*PREFIX*cards_properties` `cp` WHERE (`cp`.`addressbookid` = :dcValue1) AND ((`cp`.`name` = :dcValue2) OR (`cp`.`name` = :dcValue3)) LIMIT 25";
		//$testQueryHelper->get('select', 71);

		// "SELECT COUNT(*) FROM `*PREFIX*cards_properties` `cp` WHERE (`cp`.`addressbookid` = :dcValue1) AND ((`cp`.`name` = :dcValue2) OR (`cp`.`name` = :dcValue3)) LIMIT 25";
		//$testQueryHelper->get('select', 72);

		// "SELECT COUNT(*) AS `num_cards` FROM `*PREFIX*cards_properties` `cp` WHERE (`cp`.`addressbookid` = :dcValue1) AND ((`cp`.`name` = :dcValue2) OR (`cp`.`name` = :dcValue3)) LIMIT 25";
		//$testQueryHelper->get('select', 73);

		// "SELECT * FROM `*PREFIX*cards_properties`";
		//$testQueryHelper->get('select', 74);

		$this->processQuery();

		if ($sqlOrig != $this->query->getSQL()) {
			$this->query->getSQL();
		}

		return;
	}

	private function processQuery() {
		$this->handleFrom();

		if (sizeof($this->matchingParts) > 0) {
			if (sizeof($this->query->getQueryPart('select')) > 0) {
				$this->handleSelect();
			}
			if ($this->query->getQueryPart('where')) {
				$this->handleWhere();
			}
			// These don't seem to need to be wrapped in decryption code to work
			// if ($this->query->getQueryPart('groupBy')) {
			// 	$this->handleGroupBy();
			// }
			// if ($this->query->getQueryPart('orderBy')) {
			// 	$this->handleOrderBy();
			// }
		}
	}

	private function handleFrom() {
		$resetPart = false;
		$fromTable = json_decode(str_replace('`', "", json_encode($this->query->getQueryPart('from'))), true);
		$setColumns = $this->query->getQueryPart('set');
		$values = $this->query->getQueryPart('values');
		$type = $this->query->getType();

		// Yes, there can only be one FROM table, but using a loop simplifies the issue of the $key in the case of UPDATE/INSERT
		foreach ($fromTable as $key=>$table) {
			foreach ($this->encryptMap as $encryptMapTableName=>$encryptMapColumns) {
				if (is_array($table) && $table['table'] == "*PREFIX*$encryptMapTableName") {
					$resetPart = true;
					$table['table'] = $table['table'] . '_enc';
					$fromTable[$key] = $table;

					$table['columns'] = $encryptMapColumns;
					$this->matchingParts[$table['table']] = $table;
				}
				// Handle UPDATE and INSERT
				else if ($table == "*PREFIX*$encryptMapTableName") {
					$resetPart = true;
					$table = $table . '_enc';
					$fromTable[$key] = $table;

					$matchingPart = [
						'table' => $table,
						'alias' => NULL,
						'columns' => $encryptMapColumns,
					];

					$this->matchingParts[$table] = $matchingPart;
				}
			}
		}

		if ($resetPart) {
			$this->query->resetQueryPart('from');

			foreach ($fromTable as $table) {
				if (is_array($table)) {
					$table['table'] = '`' . $table['table'] . '`';
					if (! is_null($table['alias']) && strlen($table['alias']) > 0) {
						$table['alias'] = '`' . $table['alias'] . '`';
					}
				}

				if ($type == \Doctrine\DBAL\Query\QueryBuilder::SELECT) {
					$this->query->from($table['table'], $table['alias']);

					// Only a SELECT statement can contain a JOIN
					$this->handleJoin();
				}
				else if ($type == \Doctrine\DBAL\Query\QueryBuilder::DELETE) {
					$this->query->delete($table);
					break;
				}
				else if ($type == \Doctrine\DBAL\Query\QueryBuilder::UPDATE) {
					$this->query->update($table);
					$this->handleUpdate();
					break;
				}
				else if ($type == \Doctrine\DBAL\Query\QueryBuilder::INSERT) {
					$this->query->insert($table);
					$this->handleInsert();
					break;
				}
			}
		}
		$this->query->getSQL();
	}

	private function handleJoin() {
		$resetJoin = false;
		$resetFrom = false;
		$joinTables = json_decode(str_replace(['`'," "], "", json_encode($this->query->getQueryPart('join'))), true);
		$fromTable = json_decode(str_replace('`', "", json_encode($this->query->getQueryPart('from')[0])), true);
		$this->query->resetQueryPart('join');

		// Check if the FROM table has been renamed (required for the if statement at the top of the next foreach)
		foreach ($this->matchingParts as $matchingTable=>$matchingPart) {
			if ($fromTable['table'] == $matchingTable) {
				$resetFrom = true;
			}
		}

		foreach ($joinTables as $joinKey=>$joinTableGroup) {
			// If a FROM table alias isn't used on the JOIN statement, the JOIN table group name needs to be renamed
			if ($resetFrom && $joinKey == substr($fromTable['table'], 0, -10)) {
				$resetJoin = true;
				$joinTables[$joinKey . '_enc`'] = $joinTables[$joinKey];
				unset($joinTables[$joinKey]);
				$joinKey = $joinKey . '_enc`';
			}

			foreach ($joinTableGroup as $tableKey=>$join) {
				$joinConditionParts = explode('=', $join['joinCondition']);
				$joinPart0 = explode('.', $joinConditionParts[0]);
				$joinPart1 = explode('.', $joinConditionParts[1]);
				$joinPart = $joinPart0;
				$fromPart = $joinPart1;

				// Need to make sure that the correct half of the join condition is identified
				if ($join['joinAlias'] == $joinPart1[0] || $join['joinTable'] == $joinPart1[0]) {
					$joinPart = $joinPart1;
					$fromPart = $joinPart0;
				}

				foreach ($this->encryptMap as $encryptMapTableName=>$encryptMapColumns) {
					foreach ($encryptMapColumns as $encryptMapColumnName) {
						if ($join['joinTable'] == "*PREFIX*$encryptMapTableName" && $encryptMapColumnName == $joinPart[1]) {
							$resetJoin = true;
							$joinPart = $this->decryptFunc . '(`' . implode('`.`', $joinPart) . '`,"' . $this->password . '"' . $this->cryptOpts . ')';
						}

						// If the FROM condition needs to be encrypted/renamed,
						if ($fromTable['table'] == "*PREFIX*$encryptMapTableName" . '_enc' && $encryptMapColumnName == $fromPart[1]) {
							$fromPart = $this->decryptFunc . '(`' . implode('`.`', $fromPart) . '`,"' . $this->password . '"' . $this->cryptOpts . ')';
						}
					}

					if ($join['joinTable'] == "*PREFIX*$encryptMapTableName") {
						// Even if there isn't a column match in the JOIN condition, the joinTable needs to be renamed if it matches
						$join['joinTable'] = "*PREFIX*$encryptMapTableName" . '_enc';

						$matchingPart = [
							'table' => $join['joinTable'],
							'alias' => $join['joinAlias']
						];
						$matchingPart['columns'] = $encryptMapColumns;

						$this->matchingParts[$matchingPart['table']] = $matchingPart;
					}
				}

				if (is_array($fromPart)) {
					$fromPart = '`' . implode('`.`', $fromPart) . '`';
				}

				if (is_array($joinPart)) {
					$joinPart = '`' . implode('`.`', $joinPart) . '`';
				}

				$join['joinCondition'] = $joinPart . ' = ' . $fromPart;
				$join['joinTable'] = '`' . $join['joinTable'] . '`';

				// Handle empty JOIN and FROM table alias
				if (strlen($join['joinAlias']) > 0) {
					$join['joinAlias'] = '`' . $join['joinAlias'] . '`';
				}
				if (strlen($joinKey) > 0) {
					$joinKey = "`$joinKey`";
				}

				if ($join['joinType'] == 'left') {
					$this->query->leftJoin($joinKey, $join['joinTable'], $join['joinAlias'], $join['joinCondition']);
				}
				else if ($join['joinType'] == 'right') {
					$this->query->rightJoin($joinKey, $join['joinTable'], $join['joinAlias'], $join['joinCondition']);
				}
				else if ($join['joinType'] == 'inner') {
					$this->query->innerJoin($joinKey, $join['joinTable'], $join['joinAlias'], $join['joinCondition']);
				}
				else {
					$this->query->join($joinKey, $join['joinTable'], $join['joinAlias'], $join['joinCondition']);
				}
			}
		}
	}

	// Handles DISTINCT, COUNT, UNIQUE, etc as well as splitting `table.column` syntax 
	private function safeColumn($column) {
		$result = [
			'prefix' => array(),
			'column' => NULL,
			'table'	=> NULL,
			'suffix' => array(),
		];

		$column = str_replace('(', '|||(|||', $column);
		$column = str_replace(')', '|||)|||', $column);
		$column = str_replace(" ", '|||', $column);
		$column = trim($column, '|||');
		$selectParts = explode('|||', $column);
		$columnEl = NULL;

		if (sizeof($selectParts) > 1) {
			foreach ($selectParts as $key=>$selectPart) {
				if (is_null($columnEl) && (sizeof($selectParts) <= $key + 1 || $selectParts[$key + 1] == ')')) {
					$columnEl = $selectParts[$key];
				}
				else if (! is_null($columnEl)) {
					array_push($result['suffix'], $selectParts[$key]);
				}
				else {
					array_push($result['prefix'], $selectParts[$key]);
				}
			}
		}
		else {
			$columnEl = $selectParts[0];
		}

		$result['column'] = $columnEl;
		$selectParts = explode('.', $columnEl);

		if (sizeof($selectParts) > 1) {
			$result['column'] = $selectParts[0];
			$result['table'] = $selectParts[1];
		}

		$result['prefix'] = implode($result['prefix']) . " " == " " ? "" : implode($result['prefix']) . " ";
		$result['suffix'] = implode(" ", $result['suffix']) == " " ? "" : implode(" ", $result['suffix']);
		$result['table'] = ($result['table'] == "") ? (NULL) : ($result['table']);

		return $result;
	}

	private function handleSelect() {
		$resetPart = false;
		$selectColumns = json_decode(str_replace('`', "", json_encode($this->query->getQueryPart('select'))), true);
		$fromTable = json_decode(str_replace('`', "", json_encode($this->query->getQueryPart('from')[0])), true);

		// Handle wildcard selections
		$columnQuery = OC::$server->getDatabaseConnection()->getInner()->getSchemaManager();
		$wildcardTables = array();

		foreach ($selectColumns as $key=>$column) {
			$safeColumn = $this->safeColumn($column);
			$selectColumn = $safeColumn['column'];
			$selectTable = $safeColumn['table'];
			$selectPrefix = $safeColumn['prefix'];
			$selectSuffix = $safeColumn['suffix'];

			// These can just be the column name - no need to do `table`.`column` because there is only one table
			if ($column == '*') {
				$wildcardTables[$fromTable['table']] = $fromTable['alias'];
				unset($selectColumns[$key]);
				$resetPart = true;
			}
			else {
				foreach ($this->matchingParts as $matchingTable=>$matchingPart) {
					foreach ($matchingPart['columns'] as $matchingColumn) {
						$break = false;
						// No alias or table name is used, just the column. This must be a simple select from a single table
						if (is_null($selectTable) && $selectColumn == $matchingColumn) {
							$selectColumns[$key] = $selectPrefix . "$this->decryptFunc(`$selectColumn`, '$this->password' $this->cryptOpts) AS `$selectColumn`" . $selectSuffix;
							$resetPart = true;
							$break = true;
						}
						// Handle select statements that 1) match an encrypted column and 2) use the `alias`.`column` format
						else if ($selectTable == $matchingPart['alias'] && $selectColumn == $matchingColumn) {
							$selectColumns[$key] = $selectPrefix . "$this->decryptFunc(`$selectTable`.`$selectColumn`, '$this->password' $this->cryptOpts) AS `$selectColumn`" . $selectSuffix;
							$resetPart = true;
							$break = true;
						}
						// Find any select statements that 1) match an encrypted column and 2) use the `full-table-name`.`column` format
						else if ($selectTable . "_enc.$selectColumn"  == "$matchingTable.$matchingColumn") {
							// Change the `full-table-name` to `full-table-name_enc`
							$selectColumns[$key] = $selectPrefix . "$this->decryptFunc(`$selectTable" . "_enc`.`$selectColumn`, '$this->password' $this->cryptOpts) AS `$selectColumn`" . $selectSuffix;
							$resetPart = true;
							$break = true;
						}
						// Find any select statements that 1) match and encrypted TABLE (but not column - no decryption
						// needed; just rename the table) and 2) use the `full-table-name`.`column` format
						// TODO: test this
						else if ($selectColumn  == $matchingTable) {
							$selectColumns[$key] = "`$selectTable" . "_enc`.`$selectColumn`";
							$resetPart = true;
							$break = true;
						}
						// Handle wildcards on single tables
						else if (
							$selectTable == '*' &&
							($selectColumn == $matchingPart['alias'] || $selectTable . "_enc" == $matchingTable)
						) {
							$wildcardTables[$matchingTable] = $matchingPart['alias'];
							unset($selectColumns[$key]);
							$resetPart = true;
							$break = true;
						}
						if ($break) {
							break;
						}
					}
					if ($break) {
						break;
					}
				}
			}
		}

		if ($resetPart) {
			$this->query->resetQueryPart('select');

			foreach ($wildcardTables as $table=>$alias) {
				$columnList = $columnQuery->listTableColumns($table);
				$columns = array_keys($columnList);

				foreach ($columns as $column) {
					$decryptColumn = false;

					foreach ($this->matchingParts[$table]['columns'] as $matchingColumn) {
						if ($matchingColumn == $column) {
							$decryptColumn = true;
							break;
						}
					}

					if (sizeof($this->query->getQUeryPart('join')) == 0) {
						$column = "`$column`";
					}
					else if ($alias == "") {
						$column = "`$table`.`$column`";
					}
					else {
						$column = "`$alias`.`$column`";
					}

					if ($decryptColumn) {
						$column = "$this->decryptFunc($column, '$this->password' $this->cryptOpts)";
					}

					array_push($selectColumns, $column);
				}
			}

			$this->query->select($selectColumns);
		}
	}

	private function handleUpdate() {
		$params = $this->query->getParameters();
		$paramTypes = $this->query->getParameterTypes();
		$setColumns = json_decode(str_replace(['`', " "], "", json_encode($this->query->getQueryPart('set'))), true);

		// Clear out the params to remap them
		$this->query->resetQueryPart('set');
		$this->query->setParameters(array());

		foreach ($setColumns as $key=>$setColumn) {
			$setColumnParts = explode('=', $setColumn);

			foreach ($this->matchingParts as $matchingTable=>$matchingPart) {
				if (in_array($setColumnParts[0], $matchingPart['columns'], true)) {
					$setColumnParts[1] = "$this->encryptFunc($setColumnParts[1], '$this->password' $this->cryptOpts)";
				}
			}

			$this->query->set("`$setColumnParts[0]`", $setColumnParts[1]);
		}

		$this->query->setParameters($params, $paramTypes);
	}

	private function handleInsert() {
		$params = $this->query->getParameters();
		$paramTypes = $this->query->getParameterTypes();
		$values = $this->query->getQueryPart('values');
		$newValues = array();

		// Clear out the params to remap them
		$this->query->resetQueryPart('values');
		$this->query->setParameters(array());

		foreach ($values as $key=>$val) {
			$column = trim($key, '`');

			// It should just be :dcValue1, :dcValue2, etc, but just to be sure . . .
			$value = $val->__toString();

			foreach ($this->matchingParts as $matchingTable=>$matchingPart) {
				if (in_array($column, $matchingPart['columns'], true)) {
					$value = "$this->encryptFunc($value, '$this->password' $this->cryptOpts)";
					$newValues["`$column`"] = new \OC\DB\QueryBuilder\Parameter($value);
				}
				else {
					$newValues[$key] = $val;
				}
			}
		}

		$this->query->values($newValues);
		$this->query->setParameters($params, $paramTypes);
	}

	private function handleWhere() {
		$where = $this->query->getQueryPart('where')->__toString();
		$whereParts = $this->explodeWhere($where);
		$where = $this->implodeWhere($whereParts, $this->matchingParts);
		$this->query->where($where);
	}

	private function explodeWhere($where) {
		$defaultProcessor = new DefaultProcessor;
		$tokens = $defaultProcessor->splitSQLIntoTokens($where);

		$args = [
			'WHERE' => $tokens,
		];

		$sqlChunkProcessor = new SQLChunkProcessor(new Options([]));
		return $sqlChunkProcessor->process($args)['WHERE'];
	}

	private function implodeWhere($whereParts) {
		$expression = "";
		$parts = array();
		$operator = null;

		foreach ($whereParts as $part) {
			if ($part['expr_type'] == 'in-list') {
				$expression .= $part['base_expr'] . " ";
			}
			else if (is_array($part['sub_tree']) ) {
				$imploded = $this->implodeWhere($part['sub_tree']);
				if ($imploded instanceof DBALCompositeExpression) {
					$imploded = new OCCompositeExpression($imploded);
				}

				array_push($parts, $imploded);
			}
			else if ($part['base_expr'] == 'AND' || $part['base_expr'] == 'OR') {
				$operator = $part['base_expr'];
			}
			else {
				if ($expression == "") {
					$baseExprParts = explode('.', $part['base_expr']);
					foreach ($baseExprParts as $key=>$baseExprPart) {
						$baseExprParts[$key] = trim($baseExprPart, '`');
					}

					foreach ($this->matchingParts as $matchingTable=>$matchingPart) {
						foreach ($matchingPart['columns'] as $matchingColumn) {
							$break = false;
							if ($baseExprParts[0] == $matchingColumn) {
								$part['base_expr'] = "$this->decryptFunc(`$baseExprParts[0]`, '$this->password' $this->cryptOpts)";
								$break = true;
							}
							else if ($matchingPart['alias'] && $baseExprParts[0] == $matchingPart['alias'] && $baseExprParts[1] == $matchingColumn) {
								$part['base_expr'] = "$this->decryptFunc(`$baseExprParts[0]`.`$baseExprParts[1]`, '$this->password' $this->cryptOpts)";
								$break = true;
							}
							else if ($baseExprParts[0] == $matchingTable && $baseExprParts[1] == $matchingColumn) {
								$part['base_expr'] =  "$this->decryptFunc(`$baseExprParts[0]`.`$baseExprParts[1]`, '$this->password' $this->cryptOpts)";
								$break = true;
							}
							if ($break) {
								break;
							}
						}
						if ($break) {
							break;
						}
					}
					$i = 1;
				}
				$expression .= $part['base_expr'] . " ";
			}
		}

		if ($operator) {
			return new DBALCompositeExpression($operator, $parts);
		}
		else {
			return trim($expression);
		}
	}

	// This should probably be rewritten to use the more maintained phpMyAdmin library
	private function explodeWherePhpMyAdmin($where) {
		$parser = new Parser();
		$lexer = new Lexer($where);
		$tokens = $lexer->list;
		$condition = new Condition($where);
		$whereParts = $condition->parse($parser, $tokens);

		foreach ($whereParts as $wherePart) {
			if (! $wherePart->isOperator) {
				$exprParts = explode('(', $wherePart->expr);

				foreach ($exprParts as $key=>$exprPart) {
					if ($exprPart == "") {
						$exprParts[$key] = '(';
					}
					else {
						$i = 1;
					}
				}
			}
		}

		$str = "";

		foreach ($whereParts as $wherePart) {
			$str .= " $wherePart->expr";
		}
	}

	// These seem to be unnecessary
	private function handleGroupBy() {
	}

	private function handleOrderBy() {
	}
}