<?php

/**
 * This . . . is a truly awful class. It dumps QueryBuilder elements to files based on the query type with each query
 * part formatted and assigned to a variable. Then, the queries can be retrieved by specifying the number of the query
 * desired to be played back. I am ashamed of this code, but it works, damn it.
 */

namespace OCA\DatabaseEncryptionPatch\Test;

use Doctrine\DBAL\Query\QueryBuilder;

class TestQueryHelper {
	private $query;

	public function __construct($query) {
		$this->query = $query;
	}

	public function dump() {
		$from       = $this->stringify('from');
		$distinct   = $this->stringify('distinct');
		$select     = $this->stringify('select');
		$join       = $this->stringify('join');
		$set        = $this->stringify('set');
		$where      = $this->stringify('where');
		$groupBy    = $this->stringify('groupBy');
		$having     = $this->stringify('having');
		$orderBy    = $this->stringify('orderBy');
		$values     = $this->stringify('values');
		$params     = $this->stringify('params');
		$paramTypes = $this->stringify('paramTypes');

		if ($this->query->getType() == \Doctrine\DBAL\Query\QueryBuilder::SELECT) {
			$file = __DIR__ . '/select.php';
			$count = substr_count(file_get_contents($file, true), '$sql = ');
			$this->lineInFile("\t\$sql = \"$this->query\";", $file) ?: file_put_contents($file, "if (\$query == $count) {\n\t\$sql = \"$this->query\";\n\t$from\n\t$distinct\n\t$select\n\t$join\n\t$where\n\t$groupBy\n\t$having\n\t$orderBy\n}\n\n", FILE_APPEND);
		}
		else if ($this->query->getType() == \Doctrine\DBAL\Query\QueryBuilder::DELETE) {
			$file = __DIR__ . '/delete.php';
			$count = substr_count(file_get_contents($file, true), '$sql = ');
			$this->lineInFile("\t\$sql = \"$this->query\";", $file) ?: file_put_contents($file, "if (\$query == $count) {\n\t\$sql = \"$this->query\";\n\t$from\n\t$where\n}\n\n", FILE_APPEND);
		}
		else if ($this->query->getType() == \Doctrine\DBAL\Query\QueryBuilder::UPDATE) {
			$file = __DIR__ . '/update.php';
			$count = substr_count(file_get_contents($file, true), '$sql = ');
			$this->lineInFile("\t\$sql = \"$this->query\";", $file) ?: file_put_contents($file, "if (\$query == $count) {\n\t\$sql = \"$this->query\";\n\t$from\n\t$set\n\t$where\n\t$params\n\t$paramTypes\n}\n\n", FILE_APPEND);
		}
		else if ($this->query->getType() == \Doctrine\DBAL\Query\QueryBuilder::INSERT) {

			$file = __DIR__ . '/insert.php';
			$count = substr_count(file_get_contents($file, true), '$sql = ');
			$this->lineInFile("\t\$sql = \"$this->query\";", $file) ?: file_put_contents($file, "if (\$query == $count) {\n\t\$sql = \"$this->query\";\n\t$from\n\t$values\n\t$params\n\t$paramTypes\n\n}\n\n", FILE_APPEND);
		}
	}

	public function get($type, $query) {
		if ($type == 'select') {
			include(__DIR__ . '/select.php');
		}
		else if ($type == 'delete') {
			include(__DIR__ . '/delete.php');
		}
		else if ($type == 'update') {
			include(__DIR__ . '/update.php');
		}
		else if ($type == 'insert') {
			include(__DIR__ . '/insert.php');
		}

		$this->query->resetQueryParts();
		if (! empty($select)) {
			$this->query->select($select);
			if (! empty($from)) {
				foreach ($from as $table) {
					if (is_array($table)) {
						$this->query->from($table['table'], $table['alias']);
					}
				}
			}
		}
		// This is an UPDATE
		else if (! empty($set)) {
			foreach ($set as $val) {
				$setParts = explode('=', $val);
				$this->query->set(trim($setParts[0]), trim($setParts[1]));
			}
			$this->query->setParameters($params, $paramTypes);
			if (! empty($from)) {
				foreach ($from as $table) {
					if (! is_array($table)) {
						$this->query->update($table);
						break;
					}
				}
			}
		}

		// This is an INSERT
		else if (! empty($values)) {
			$this->query->values($values);
			$this->query->setParameters($params, $paramTypes);
			if (! empty($from)) {
				foreach ($from as $table) {
					if (! is_array($table)) {
						$this->query->insert($table);
						break;
					}
				}
			}
		}

		// This is a delete
		else {
			$this->query->setParameters($params, $paramTypes);
			foreach ($from as $table) {
				if (! is_array($table)) {
					$this->query->delete($table);
					break;
				}
			}
		}

		if (! empty($distinct)) { $this->query->distinct($distinct); }
		if (! empty($join)) {
			foreach ($join as $key=>$joinTableGroup) {
				foreach ($joinTableGroup as $joinTable) {
					if ($joinTable['joinType'] == 'left') {
						$this->query->leftJoin($key, $joinTable['joinTable'], $joinTable['joinAlias'], $joinTable['joinCondition']);
					}
					else if ($joinTable['joinType'] == 'right') {
						$this->query->rightJoin($key, $joinTable['joinTable'], $joinTable['joinAlias'], $joinTable['joinCondition']);
					}
					else if ($joinTable['joinType'] == 'inner') {
						$this->query->innerJoin($key, $joinTable['joinTable'], $joinTable['joinAlias'], $joinTable['joinCondition']);
					}
					else {
						$this->query->join($key, $joinTable['joinTable'], $joinTable['joinAlias'], $joinTable['joinCondition']);
					}
				}
			}
		}
		if (! empty($where)) { $this->query->where($where); }
		if (! empty($groupBy)) { $this->query->groupBy($groupBy); }
		if (! empty($having)) { $this->query->having($having); }
		if (! empty($orderBy)) { $this->query->orderBy($orderBy[0], $orderBy[1]); }
		$this->query->getSQL();
	}

	public function debugBreakpoints() {
		if ($this->query->getType() == \Doctrine\DBAL\Query\QueryBuilder::SELECT) {
			$i = 1;
		}
		if ($this->query->getType() == \Doctrine\DBAL\Query\QueryBuilder::DELETE) {
			$i = 1;
		}
		if ($this->query->getType() == \Doctrine\DBAL\Query\QueryBuilder::UPDATE) {
			$i = 1;
		}
		if ($this->query->getType() == \Doctrine\DBAL\Query\QueryBuilder::INSERT) {
			$i = 1;
		}
		if ($this->query->getQueryPart('select')) {
			$i = 1;
		}
		if ($this->query->getQueryPart('distinct')) {
			$i = 1;
		}
		if ($this->query->getQueryPart('from')) {
			$i = 1;
		}
		if ($this->query->getQueryPart('join')) {
			if (sizeof($this->query->getQueryPart('join')) > 1) {
				$i = 1;
			}
		}
		if ($this->query->getQueryPart('set')) {
			$i = 1;
		}
		if ($this->query->getQueryPart('where')) {
			$i = 1;
		}
		if ($this->query->getQueryPart('groupBy')) {
			if (sizeof($this->query->getQueryPart('groupBy')) > 1) {
				$i = 1;
			}
			$i = 1;
		}
		if ($this->query->getQueryPart('having')) {
			$i = 1;
		}
		if ($this->query->getQueryPart('orderBy')) {
			$i = 1;
		}
		if ($this->query->getQueryPart('values')) {
			$i = 1;
		}
	}

	private function stringify($part) {
		if ($part == 'distinct') {
			return ($this->query->getQueryPart('distinct')) ? '$distinct = true;' : '$distinct = false;';
		}

		if ($part == 'params') {
			$dumpedPart = var_export($this->query->getParameters(),1);
		}
		else if ($part == 'paramTypes') {
			$dumpedPart = var_export($this->query->getParameterTypes(),1);
		}
		else {
			$dumpedPart = var_export($this->query->getQueryPart($part),1);
		}

		$result = preg_replace('/OC\\\DB/', 'new \OC\DB', $dumpedPart);
		$result = preg_replace('/Doctrine/', 'new \Doctrine', $result);
		$result = preg_replace('/\)\)/', ')', $result);
		$result = preg_replace('/\n|::__set_state\(array|\'name\' =>|\'type\' =>|\'parts\' =>|\'compositeExpression\' =>|[0-9]+ =>/', '', $result);
		$result = preg_replace('/\( +/', '(', $result);
		$result = preg_replace('/, +/', ',', $result);
		//$result = preg_replace('/,\)/', ')', $result) . ';|||';
		$result = preg_replace('/,\)/', ')', $result);
		return "\$$part = $result;";
	}

	private function lineInFile($line, $file) {
		if (! file_exists($file)) {
			touch($file);
			file_put_contents($file, "<?php\n\n");
		}

		$handle = fopen($file, 'r');
		$valid = false;
		while (($buffer = fgets($handle)) !== false) {
			if (strpos($buffer, $line) !== false) {
				$valid = true;
				break;
			}
		}
		fclose($handle);

		return $valid;
	}
}