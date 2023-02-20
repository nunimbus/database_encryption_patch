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
use OCP\Authentication\IProvideUserSecretBackend;
use OC_App;
use OC;
use OC\DB\QueryBuilder\Events\BeforeQueryExecuted;

class Application extends App implements IBootstrap {
	public const APP_ID = 'database_encryption_patch';

	private $password = null;

	public function __construct(array $urlParams = []) {
		parent::__construct(self::APP_ID, $urlParams);

		// Need to load the helper app and its classes
		OC_App::loadApp('patch_assets');

//		// Need to make the class available to the migrations if the app is not yet installed
//		if (! (OC::$server->getAppManager()->isInstalled(self::APP_ID) && class_exists('OCA\\PatchAssets\\InstallFunctions'))) {
//			$classMap = OC::$composerAutoloader->getClassMap();
//			$classMap['OCA\\PatchAssets\\InstallFunctions'] = OC::$server->getAppManager()->getAppPath(self::APP_ID) . '/lib/assets/InstallFunctions.php';
//			OC::$composerAutoloader->addClassMap($classMap);
//		}
	}

	public function getPassword() {
		return $this->password;
	}

	public function register(IRegistrationContext $context): void {
		$context->registerEventListener(BeforeQueryExecuted::class, QueryBuilderListener::class);
	}

	public function boot(IBootContext $context): void {
		$server = $context->getServerContainer();

		// Get the user or system secret for the encryption calls
		$password = $server->getConfig()->getSystemValue('secret');

		if (
			$server->getUserSession()->isLoggedIn() &&
			$server->getUserSession()->getUser()->getBackend() instanceof IProvideUserSecretBackend
		) {
			$userId = $server->getUserSession()->getUser()->getUID();
			$pass = $server->getUserSession()->getUser()->getBackend()->getCurrentUserSecret();

			if (OC::$server->getUserManager()->checkPasswordNoLogging($userId, $pass)) {
				$password = $pass;
			}
			else if (isset(OC::$server->getRequest()->server['PHP_AUTH_PW']) || array_key_exists('PHP_AUTH_PW', OC::$server->getRequest()->server)) {
				$token = OC::$server->getRequest()->server['PHP_AUTH_PW'];
				$provider = OC::$server->get('OC\Authentication\Token\IProvider');
				$dbToken = $provider->getToken($token);
				$pass = $provider->getPassword($dbToken, $token);
	
				if (OC::$server->getUserManager()->checkPasswordNoLogging($userId, $pass)) {
					$password = $pass;
				}
				else {
					throw new PasswordUnavailableException();
				}
			}
		}

		$this->password = $password;

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