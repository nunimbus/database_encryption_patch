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

namespace OCA\DatabaseEncryptionPatch\AppInfo;

use OCA\PatchAssets\InstallFunctions;
use OCA\DatabaseEncryptionPatch\Listener\QueryBuilderListener;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OC_App;
use OC;

class Application extends App implements IBootstrap {
	public const APP_ID = 'database_encryption_patch';

	private $password = null;

	public function __construct(array $urlParams = []) {
		parent::__construct(self::APP_ID, $urlParams);

		// Need to load the helper app and its classes
		OC_App::loadApp('patch_assets');

		// Get the user or system secret for the encryption calls
		$server = OC::$server;
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

	public function getPassword() {
		return $this->password;
	}

	public function register(IRegistrationContext $context): void {
		\OCP\Util::connectHook('QueryBuilder', 'preExecuteSql', '\OCA\DatabaseEncryptionPatch\Listener\QueryBuilderListener', 'eventHandler');
	}

	public function boot(IBootContext $context): void {
		$server = $context->getServerContainer();

		if (
			$server->getRequest()->getRequestUri() == '/index.php/settings/apps/disable' &&
			in_array(self::APP_ID, $server->getRequest()->getParams()['appIds'])
		) {
			InstallFunctions::uninstall();

			/* This is probably extremely unwise. If the app is disabled, all tables and data will be dropped.
			$schema = OC::$server->get('OC\DB\SchemaWrapper');
			$encryptMap = $server->getConfig()->getSystemValue('dbencrypt');

			foreach ($encryptMap as $appName=>$tables) {
				foreach ($tables as $table=>$columns) {
					if ($schema->hasTable($table . '_enc')) {
						$schema->dropTable($table . '_enc');
					}
				}
			}

			$schema->performDropTableCalls();
			*/
		}
	}
}