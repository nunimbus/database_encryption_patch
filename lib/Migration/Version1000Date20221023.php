<?php

declare(strict_types=1);

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

namespace OCA\DatabaseEncryptionPatch\Migration;

use OCA\PatchAssets\InstallFunctions;
use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;
use OC;

class Version1000Date20221023 extends SimpleMigrationStep {

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 */
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		$schema = $schemaClosure();
		InstallFunctions::uninstallConflicts(['circles', 'federation']);
		InstallFunctions::install();

		$server = OC::$server;
		$encryptMap = $server->getConfig()->getSystemValue('dbencrypt');
		$innerSchema = $server->getDatabaseConnection()->getInner()->getSchemaManager();
		$enable = false;

		foreach ($encryptMap as $appName=>$tables) {
			InstallFunctions::installDependencies([$appName]);

			foreach ($tables as $table=>$columns) {
				if (!$schema->hasTable($table . '_enc')) {
					$enable = true;
					$newTable = $schema->createTable($table . '_enc');
					$tableDetails = $innerSchema->listTableDetails("*PREFIX*$table");

					foreach ($tableDetails->getColumns() as $column) {
						$newTable->addColumn($column->getName(), in_array($column->getName(), $columns) ? 'blob' : $column->getType()->getName(), [
							'length'              => $column->getLength(),
							'precision'           => $column->getPrecision(),
							'scale'               => $column->getScale(),
							'unsigned'            => $column->getUnsigned(),
							'fixed'               => $column->getFixed(),
							'notnull'             => $column->getNotnull(),
							'default'             => $column->getDefault(),
							'autoincrement'       => $column->getAutoincrement(),
							'platformOptions'     => $column->getPlatformOptions(),
							'columnDefinition'    => $column->getColumnDefinition(),
							'comment'             => $column->getComment(),
							'customSchemaOptions' => $column->getCustomSchemaOptions(),
						]);
					}
		
					$newTable->setComment($tableDetails->getComment());

					foreach ($tableDetails->getOptions() as $key=>$option) {
						$newTable->addOption($key, $option);
					}

					foreach ($tableDetails->getIndexes() as $name=>$index) {
						if ($index->isPrimary()) {
							$newTable->setPrimaryKey($tableDetails->getPrimaryKey()->getColumns(), $tableDetails->getPrimaryKey()->getName());
						}
						else if ($index->isUnique()) {
							$newTable->addUniqueIndex($index->getColumns(), $name . "_enc");
						}
						else {
							$newTable->addIndex($index->getColumns(), $name . "_enc");
						}
					}
				}
			}
		}

		if ($enable && $server->getConfig()->getSystemValue('dbtype') == 'pgsql') {
			$query = $server->getDatabaseConnection()->getQueryBuilder();
			$query->getConnection()->executeQuery("CREATE EXTENSION IF NOT EXISTS pgcrypto;");
		}

		return $schema;
	}
}
