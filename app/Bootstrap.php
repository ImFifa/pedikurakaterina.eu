<?php declare(strict_types = 1);

namespace App;

use Nette\Application\ApplicationException;
use Nette\Configurator;
use Nette\Neon\Neon;

class Bootstrap
{

	public static function boot(): Configurator
	{
		define('WWW', str_replace('app', 'www', __DIR__));

		$debug = Neon::decode(file_get_contents(__DIR__ . '/config/config.local.neon'))['parameters']['debug'];
		$debug = (isset($_COOKIE[$debug['key']]) && $_COOKIE[$debug['key']] === $debug['value']) ? $debug['enable'] : false;

		$configurator = new Configurator();

		$configurator->setDebugMode($debug);
		$configurator->enableDebugger(__DIR__ . '/../log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(__DIR__ . '/../temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->addDirectory(__DIR__ . '/../modules')
			->register();

		if (file_exists($modulesNeonPath = __DIR__ . '/config/modules.neon')) {
			$modulesNeon = Neon::decode(file_get_contents($modulesNeonPath));

			if (isset($modulesNeon['parameters']['modules'])) {
				foreach ($modulesNeon['parameters']['modules'] as $module) {
					$module = ucfirst(strtolower($module));
					if (file_exists($path = __DIR__ . '/../modules/' . $module . 'Module/config/config.neon')) {
						$configurator->addConfig($path);
						$configurator->addParameters([ 'module' => ['' . strtolower($module) => str_replace('app', 'modules', __DIR__) . '/' . $module . 'Module' ]]);
					} else {
						throw new ApplicationException("Bootstrap - can't add " . $module . "Module's config.neon, file not found");
					}
				}
			} else {
				throw new ApplicationException("Bootstrap - File Module.neon must contain 'parameters' and nested 'modules' section");
			}
		} else {
			throw new ApplicationException("Bootstrap - File 'modules.neon' not found");
		}

		$configurator->addConfig(__DIR__ . '/config/config.neon');
		$configurator->addConfig(__DIR__ . '/config/modules.neon');
		$configurator->addConfig(__DIR__ . '/config/config.local.neon');
		$configurator->addParameters([
			'rootDir' => str_replace('app', '', __DIR__),
			'wwwDir' => str_replace('app', 'www', __DIR__),
			'moduleDir' => str_replace('app', 'modules', __DIR__),
		]);

		return $configurator;
	}

}
