<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Configure paths required to find CakePHP + general filepath
 * constants
 */
require __DIR__ . '/paths.php';

// Use composer to load the autoloader.
require ROOT . DS . 'vendor' . DS . 'autoload.php';

/**
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

// You can remove this if you are confident that your PHP version is sufficient.
if (version_compare(PHP_VERSION, '5.5.9') < 0) {
    trigger_error('You PHP version must be equal or higher than 5.5.9 to use CakePHP.', E_USER_ERROR);
}

// You can remove this if you are confident you have intl installed.
if (!extension_loaded('intl')) {
    trigger_error('You must enable the intl extension to use CakePHP.', E_USER_ERROR);
}

// You can remove this if you are confident you have mbstring installed.
if (!extension_loaded('mbstring')) {
    trigger_error('You must enable the mbstring extension to use CakePHP.', E_USER_ERROR);
}

use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Network\Request;
use Cake\Routing\DispatcherFactory;
use Cake\Utility\Inflector;
use Cake\Utility\Security;

/**
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

// Load an environment local configuration file.
// You can use a file like app_local.php to provide local overrides to your
// shared configuration.
//Configure::load('app_local', 'default');

// When debug = false the metadata cache should last
// for a very very long time, as we don't want
// to refresh the cache while users are doing requests.
if (!Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+1 years');
    Configure::write('Cache._cake_core_.duration', '+1 years');
}

/**
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 */
date_default_timezone_set('Etc/GMT+3');

/**
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/**
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale','pt_BR');

/**
 * Register application error and exception handlers.
 */
$isCli = PHP_SAPI === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

// Include the CLI bootstrap overrides.
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/**
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

Cache::config(Configure::consume('Cache'));
ConnectionManager::config(Configure::consume('Datasources'));
Email::configTransport(Configure::consume('EmailTransport'));
Email::config(Configure::consume('Email'));
Log::config(Configure::consume('Log'));
Security::salt(Configure::consume('Security.salt'));

/**
 * The default crypto extension in 3.0 is OpenSSL.
 * If you are migrating from 2.x uncomment this code to
 * use a more compatible Mcrypt based implementation
 */
//Security::engine(new \Cake\Utility\Crypto\Mcrypt());

/**
 * Setup detectors for mobile and tablet.
 */
Request::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isMobile();
});
Request::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isTablet();
});

/**
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 *
 * Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
 * Inflector::rules('irregular', ['red' => 'redlings']);
 * Inflector::rules('uninflected', ['dontinflectme']);
 * Inflector::rules('transliteration', ['/å/' => 'aa']);
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on Plugin to use more
 * advanced ways of loading plugins
 *
 * Plugin::loadAll(); // Loads all plugins at once
 * Plugin::load('Migrations'); //Loads a single plugin named Migrations
 *
 */

Plugin::load('Migrations');

// Only try to load DebugKit in development mode
// Debug Kit should not be installed on a production system
if (Configure::read('debug')) {
    Plugin::load('DebugKit', ['bootstrap' => true]);
}

/**
 * Connect middleware/dispatcher filters.
 */
DispatcherFactory::add('Asset');
DispatcherFactory::add('Routing');
DispatcherFactory::add('ControllerFactory');

/**
 * Enable default locale format parsing.
 * This is needed for matching the auto-localized string output of Time() class when parsing dates.
 *
 * Also enable immutable time objects in the ORM.
 */
Type::build('time')
    ->useImmutable()
    ->useLocaleParser();
Type::build('date')
    ->useImmutable()
    ->useLocaleParser();
Type::build('datetime')
    ->useImmutable()
    ->useLocaleParser();

Cake\I18n\FrozenDate::setToStringFormat('dd-MM-YYYY');

Plugin::load('Produto', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('ContaAzul', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Promocao', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Cupom', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Configuracao', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('FormaPagamento', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('CursoPresencial', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Instrutor', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Carrinho', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Entidade', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('FormaPagamentoSuperPay', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('FormaPagamentoSuperPayRecorrencia', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('FormaPagamentoFastConnect', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('FormaPagamentoSuperPayV3', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('FormaPagamentoCieloApi2', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('FormaPagamentoCieloApi3', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('FormaPagamentoBoletoPhp', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('FormaPagamentoBoletoRegistrado', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Vendas', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Repasse', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Imagem', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Publicidade', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('WebService', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Convenio', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('DuvidasFrequentes', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Indicacao', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Newsletter', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Relatorio', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('FormaPagamentoPagarMe', ['bootstrap' => false, 'routes' => true, 'autoload' => true]);
Plugin::load('Josegonzalez/Upload');//Plugin para upload de arquivos
Plugin::load('ADmad/JwtAuth');//Altenticação via token
