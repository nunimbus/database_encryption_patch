<?php
/**
 * @copyright Copyright (c) 2021, Andrew Summers
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

namespace OCA\DatabaseEncryption\AppInfo;

use OCA\DatabaseEncryption\Listener\QueryBuilderListener;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

use OCP\EventDispatcher\IEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Application extends App implements IBootstrap {

	public const APP_ID = 'database_encryption';

	private $password = null;

	public function __construct(array $urlParams = []) {
		parent::__construct(self::APP_ID, $urlParams);

		// Get the user or system secret for the encryption calls
		$server = \OC::$server;
		$password = $server->getConfig()->getSystemValue('secret');

		if (
			$server->getUserSession()->isLoggedIn() &&
			$server->getUserSession()->getUser()->getBackendClassName() == "user_saml" &&
			$secret = $server->query('OCA\User_SAML\UserBackend')->getCurrentUserSecret()
		) {
				$password = $secret;
		}

		// Hash the password so it isn't accidentally written to a log
		$hasher = $server->getHasher();
		$password = $hasher->hash($password);
		$password = base64_encode($password);
		$password = substr($password, 0, 44);
		$this->password = $password;
	}

	public function register(IRegistrationContext $context): void {
		// This is the wrong way to go about checking for columns and modifying the DB, but for now, it works.
		$server = \OC::$server;
		$query = $server->getDatabaseConnection()->getQueryBuilder();
		$prefix = $server->getSystemConfig()->getValue('dbtableprefix');

		$columns = [
			$prefix . 'cards'					=> 'carddata_encrypted',
			$prefix . 'cards_properties'		=> 'value_encrypted',
			$prefix . 'calendarobjects'			=> 'calendardata_encrypted',
			$prefix . 'calendarobjects_props'	=> 'value_encrypted',
		];

		if ($server->getAppConfig()->getValue('database_encryption', 'enabled') == 'no') {
			foreach ($columns as $table=>$column) {
				$sql = "SHOW COLUMNS FROM `$table` LIKE '$column';";
				$result = $query->getConnection()->executeQuery($sql);
				if ($result->fetchOne() != $column) {
					$sql = "ALTER TABLE `$table` ADD COLUMN $column LONGBLOB;";
					$query->getConnection()->executeQuery($sql);
				}
			}
		}
		else if (
			$server->getRequest()->getRequestUri() == '/index.php/settings/apps/disable' &&
			in_array(self::APP_ID, $server->getRequest()->getParams()['appIds'])
		) {
			foreach ($columns as $table=>$column) {
				$sql = "SHOW COLUMNS FROM `$table` LIKE '$column';";
				$result = $query->getConnection()->executeQuery($sql);
				if ($result->fetchOne() == $column) {
					$sql = "ALTER TABLE `$table` DROP COLUMN $column;";
					$query->getConnection()->executeQuery($sql);
				}
			}
		}

		\OCP\Util::connectHook('QueryBuilder', 'preExecuteSql', $this, 'eventHandler');
	}

	public function boot(IBootContext $context): void {
		$serverContainer = $context->getServerContainer();
	}

	public function eventHandler($eventParams) {
		$query = $eventParams['queryBuilder'];
		$sql = $query->getSQL();

		$encryptedColumns = [
			'`*PREFIX*calendarobjects`' => ['`calendardata`'],
			'`*PREFIX*calendarobjects_props`' => ['`value`'],
			'`*PREFIX*cards`' => ['`carddata`'],
			'`*PREFIX*cards_properties`' => ['`value`'],			
		];

/*		$encryptedColumns = [
			'`*PREFIX*calendarobjects`' => ['`calendardata`'],
			'`*PREFIX*calendarobjects_props`' => ['`value`'],
			'`*PREFIX*cards`' => ['`carddata`'],
			'`*PREFIX*cards_properties`' => ['`value`', '`cardid`'],			
			'`*PREFIX*appconfig`' => ['`configvalue`'],
			'`*PREFIX*authtoken`' => ['`login_name`'],
			'`*PREFIX*filecache`' => ['`path`', '`name`', '`fileid`', '`parent`', '`permissions`'],
			'`*PREFIX*storages`' => ['`id`'],			
		];
*/

		if ($query->getType() == \Doctrine\DBAL\Query\QueryBuilder::UPDATE) {
			$i = 1;
		}
		if ($query->getType() == \Doctrine\DBAL\Query\QueryBuilder::INSERT) {
			$i = 1;
		}

		// Get all columns that could be present in the query based on the FROM and JOIN tables (normalized by table aliases)
		$encryptedQueryColumns = array();
		$setValues = $query->getQueryPart('set');
		$valuesValues = $query->getQueryPart('values');

		foreach ($query->getQueryPart('from') as $table) {
			foreach ($encryptedColumns as $encryptedTable=>$columns) {
				if (is_array($table) && $table['table'] == $encryptedTable) {
					foreach ($columns as $column) {
						if ($table['alias'] != null) {
							array_push($encryptedQueryColumns, $table['alias'] . '.' . $column);
						}
						else {
							array_push($encryptedQueryColumns, $column);
						}
					}
				}
				// Handle SET and VALUES
				else if ($table == $encryptedTable) {
					foreach ($columns as $column) {
						foreach ($setValues as $key=>$setValue) {
							$setValueParts = explode(' = ', $setValue);
							$setValues[$setValueParts[0]] = $setValueParts[1];
							unset($setValues[$key]);

							if ($setValueParts[0] == $column) {
								array_push($encryptedQueryColumns, $column);
							}
						}
						foreach ($valuesValues as $key=>$values) {
							if ($key == $column) {
								array_push($encryptedQueryColumns, $column);
							}
						}
					}
				}
			}
		}

		foreach ($query->getQueryPart('join') as $table) {
			if (sizeof($table) != 1) {
				throw new \Exception('File: ' . __FILE__ . ', Line: ' . __LINE__ . ' - Somehow, a JOIN table entity is != 1.');
			}
			foreach ($encryptedColumns as $encryptedTable=>$columns) {
				if ($table[0]['joinTable'] == $encryptedTable) {
					foreach ($columns as $column) {
						if ($table[0]['joinAlias'] != null) {
							array_push($encryptedQueryColumns, $table[0]['joinAlias'] . '.' . $column);
						}
						else {
							array_push($encryptedQueryColumns, $column);
						}
					}
				}
			}
		}

		// Fields in this query probably need to be encrypted
		if (sizeof($encryptedQueryColumns) > 0) {
			$sqlOrig = $sql; // for logging
/***/
			// Handle SET
			if ($setValues) {
				$params = $query->getParameters();
				$paramTypes = $query->getParameterTypes();
				$setValuesOrig = $setValues;

				foreach ($setValues as $key=>$setValue) {
					if (in_array($key, $encryptedQueryColumns, true)) {
						$newColumnName = substr($key, 0, -1) . '_encrypted`';
						$setValues[$newColumnName] = $setValue;
						unset($setValues[$key]);
					}
				}

				// Clear out the params to remap them
				$query->resetQueryPart('set');
				$query->setParameters(array());

				foreach ($setValues as $key=>$setValue) {
					$setValue = trim($setValue, ':');
					//$key = trim($key, '`');

					$paramValue = $params[$setValue];;
					$paramType = isset($paramTypes[$setValue]) || array_key_exists($setValue, $paramTypes) ? $paramTypes[$setValue] : 2;
					$query->set($key, $query->createNamedParameter($params[$setValue]));
					unset($params[$setValue]);
					unset($paramTypes[$setValue]);
				}	

				// Rebuild the query with the new parameter values
				$params = array_merge($params, $query->getParameters());
				$paramTypes = array_merge($paramTypes, $query->getParameterTypes());
				$query->setParameters($params, $paramTypes);
				$sql = $query->getSQL();

				$setValues = $query->getQueryPart('set');
				foreach ($setValues as $key=>$setValue) {
					$setValueParts = explode(' = ', $setValue);
					$setValues[$setValueParts[0]] = $setValueParts[1];
					unset($setValues[$key]);
				}

				foreach ($setValuesOrig as $key=>$setValue) {
					if (in_array($key, $encryptedQueryColumns, true)) {
						$newColumnName = substr($key, 0, -1) . '_encrypted`';
						//$newColumnName = trim($newColumnName, '`');
						$sql = preg_replace("/$newColumnName = $setValues[$newColumnName]/", "$newColumnName = AES_ENCRYPT($setValues[$newColumnName], '$this->password')", $sql);
						$i = 1;
					}
				}
			}
/***/
/***/
			// Handle VALUES
			if ($valuesValues) {
				$params = $query->getParameters();
				$paramTypes = $query->getParameterTypes();
				$valuesValuesOrig = $valuesValues;

				foreach ($valuesValues as $key=>$valuesValue) {
					if (in_array($key, $encryptedQueryColumns, true)) {
						$newColumnName = substr($key, 0, -1) . '_encrypted`';
						$valuesValues[$newColumnName] = $valuesValue;
						unset($valuesValues[$key]);
					}
				}

				// Clear out the params to remap them
				$query->resetQueryPart('values');
				$query->setParameters(array());

				foreach ($valuesValues as $key=>$valuesValue) {
					$valuesValue = trim($valuesValue, ':');
					//$key = trim($key, '`');

					$paramValue = $params[$valuesValue];
					$paramType = isset($paramTypes[$valuesValue]) || array_key_exists($valuesValue, $paramTypes) ? $paramTypes[$valuesValue] : 2;
					$query->setValue($key, $query->createNamedParameter($paramValue, $paramType));
					unset($params[$valuesValue]);
					unset($paramTypes[$valuesValue]);
				}

				// Rebuild the query with the new parameter values
				$params = array_merge($params, $query->getParameters());
				$paramTypes = array_merge($paramTypes, $query->getParameterTypes());
				$query->setParameters($params, $paramTypes);
				$sql = $query->getSQL();

				$valuesValues = $query->getQueryPart('values');
				foreach ($valuesValuesOrig as $key=>$valuesValue) {
					if (in_array($key, $encryptedQueryColumns, true)) {
						$newColumnName = substr($key, 0, -1) . '_encrypted`';
						//$newColumnName = trim($newColumnName, '`');
						$sql = preg_replace("/$valuesValues[$newColumnName]/", "AES_ENCRYPT($valuesValues[$newColumnName], '$this->password')", $sql);
						$i = 1;
					}
				}
			}
/***/

			/* Handle the SELECT fields */
			$selectColumns = $query->getQueryPart('select');

			// Deal with DISTINCT, UNIQUE, and TOP
			foreach ($selectColumns as $key=>$column) {
				$column = preg_split('/(DISTINCT|UNIQUE|TOP) /', $column);
				$column = end($column);
				$column = trim($column);
				$selectColumns[$key] = $column;
			}

			$selectWildcardColumns = array();

			// Handle wildcard selections
			foreach ($selectColumns as $selectColumnKey=>$column) {
				if (mb_strpos($column, '*') !== false) {
					$columnQuery = \OC::$server->getDatabaseConnection()->getInner()->getSchemaManager();
					$columnParts = explode('.', $column);
					$queryTable = null;
					$alias = "";

					// Table has an alias - resolve the table name
					if (sizeof($columnParts) > 1) {
						$alias = $columnParts[0];
						$queryTable = null;

						foreach ($query->getQueryPart('from') as $table) {
							if ($table['alias'] == $alias) {
								$queryTable = $table['table'];
							}
						}
						foreach ($query->getQueryPart('join') as $table) {
							if ($table[0]['joinAlias'] == $alias) {
								$queryTable = $table[0]['joinTable'];
							}
						}
					}
					else {
						$queryTable = $query->getQueryPart('from')[0]['table'];
					}

					if (! $queryTable) {
						// Could not resolve table name from alias
						throw new \Exception('File: ' . __FILE__ . ', Line: ' . __LINE__ . ' - Could not find table for * select fields.');
					}

					// Look up the table columns and get their names (resolving aliases if necessary)
					else {
						$wildcardColumns = $columnQuery->listTableColumns(trim($queryTable, '`'));
						$wildcardColumns = array_keys($wildcardColumns);

						if ($alias != "") {
							$alias .= '.';
						}

						foreach ($wildcardColumns as $wcColumnKey=>$wcColumn) {
							$wildcardColumns[$wcColumnKey] = $alias . '`' . $wcColumn . '`';
						}
					}
					unset($selectColumns[$selectColumnKey]);
					$selectWildcardColumns = array_merge($selectWildcardColumns, $wildcardColumns);
					$wildcardColumnsString = implode(', ', $wildcardColumns);
					$column = preg_replace('/\*/', '\\*', $column);
					$sql = preg_replace("/$column(.*FROM)/", "$wildcardColumnsString$1", $sql);
				}
			}

			$selectColumns = array_merge($selectColumns, $selectWildcardColumns);
			$encryptedSelectColumns = array();

			// Go back through all columns that need to be encrypted, see if they are in the array of SELECT columns,
			// and add them to a new array (don't just do a merge - we need to capture any column aliases)
			foreach ($encryptedQueryColumns as $key=>$esColumn) {
				foreach ($selectColumns as $column) {
					if (strpos($column, $esColumn) === 0) {
						array_push($encryptedSelectColumns, $column);
					}
				}
			}

			foreach ($encryptedSelectColumns as $column) {
				$newColumnName = substr($column, 0, -1) . '_encrypted`';
				$columnAlias = explode('.', $column);
				$columnAlias = end($columnAlias);

				// Again, deal with column aliases
				$currentColumnAlias = preg_split("/ as /i", $column);

				if (sizeof($currentColumnAlias) > 1) {
					$columnAlias = end($currentColumnAlias);
					$newColumnName = substr($currentColumnAlias[0], 0, -1) . '_encrypted`';
				}

				$sql = preg_replace("/$column(.*FROM)/", "AES_DECRYPT($newColumnName, '$this->password') AS $columnAlias$1", $sql);
			}

			/* Handle JOIN conditions */
			foreach ($query->getQueryPart('join') as $join) {
				$join = $join[0];

				if (isset($encryptedColumns[$join['joinTable']]) || array_key_exists($join['joinTable'], $encryptedColumns)) {
					$joinCondition = $join['joinCondition'];
					$joinColumns = explode(' = ', $joinCondition);

					if (in_array($joinColumns[0], $encryptedQueryColumns, true)) {
						$newJoinCondition = "AES_DECRYPT($joinColumns[0], '$this->password') = $joinColumns[1]";
						$sql = preg_replace("/$joinCondition/", "$newJoinCondition", $sql);
						$joinCondition = $newJoinCondition;
					}
					if (in_array($joinColumns[1], $encryptedQueryColumns, true)) {
						$sql = preg_replace("/$joinCondition/", "$joinColumns[0] = AES_DECRYPT($joinColumns[1], '$this->password')", $sql);
					}
				}
			}

			/* Handle WHERE conditions */
			if ($query->getQueryPart('where')) {
				$conditions = $query->getQueryPart('where')->__toString();
				$conditions = preg_split('/ (AND|OR) /', $conditions);

				// This is kinda dumb, but this simplifies the process of preventing regex below from replacing fields in GROUP/ORDER BY statements
				$sqlParts = explode(' BY ', $sql);

				foreach ($conditions as $condition) {
					$condition = trim($condition, '()');
					$conditionParts = explode(" ", $condition);
					$column = $conditionParts[0];
					$value = $conditionParts[2];

					if (in_array($column, $encryptedQueryColumns, true)) {
						$newColumnName = substr($column, 0, -1) . '_encrypted`';

						if (sizeof($sqlParts) > 1) {
							$i = 1;
						}
						$sqlParts[0] = preg_replace("/(WHERE.*)$column/", "$1CAST(AES_DECRYPT($newColumnName, '$this->password') AS CHAR)", $sqlParts[0]);
					}
					if (sizeof(explode('.', $column)) > 1) {
						$i = 1;
					}
				}
				if (sizeof($sqlParts) > 1) {
					$i = 1;
				}

				$sql = implode(' BY ', $sqlParts);
			}

			if ($sqlOrig != $sql) {

				switch ($query->getType()) {
					case \Doctrine\DBAL\Query\QueryBuilder::UPDATE:
					case \Doctrine\DBAL\Query\QueryBuilder::INSERT:
//						file_put_contents(\OC::$server->getLogger()->getLogPath() . '.sql', "$sqlOrig\n", FILE_APPEND);
//						file_put_contents(\OC::$server->getLogger()->getLogPath() . '.sql', "$sql\n", FILE_APPEND);
//						file_put_contents(\OC::$server->getLogger()->getLogPath() . '.sql', "'" . implode("'\n'", $params) . "'\n\n", FILE_APPEND);

						$stmt = $query->getConnection()->prepare($sql);
						$eventParams['result'] = $stmt->execute($query->getParameters());
						break;
					default:
						$eventParams['result'] = $query->getConnection()->executeQuery($sql, $query->getParameters(), $query->getParameterTypes());
						break;
				}
			}

			$i = 1;
		}
		$i = 1;
		return;
	}
}
