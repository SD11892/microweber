<?php

namespace Microweber;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Cache;
use App;
use Microweber\Utils\Adapters\Config\ConfigSave as ConfigSave;
use Microweber\Utils\ClassLoader;

if (!defined('MW_VERSION')) {
    include_once __DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'bootstrap.php';
}

class MicroweberServiceProvider extends ServiceProvider
{

    /*
	* Application Service Providers...
	*/
    public $laravel_providers = [
        'Microweber\App\Providers\Illuminate\ArtisanServiceProvider',
        'Microweber\App\Providers\Illuminate\AuthServiceProvider',
        'Microweber\App\Providers\Illuminate\CacheServiceProvider',
        'Microweber\App\Providers\Illuminate\ConsoleSupportServiceProvider',
        'Microweber\App\Providers\Illuminate\CookieServiceProvider',
        'Microweber\App\Providers\Illuminate\DatabaseServiceProvider',
        'Microweber\App\Providers\Illuminate\EncryptionServiceProvider',
        'Microweber\App\Providers\Illuminate\FilesystemServiceProvider',
        'Microweber\App\Providers\Illuminate\FoundationServiceProvider',
        'Microweber\App\Providers\Illuminate\HashServiceProvider',
        'Microweber\App\Providers\Illuminate\MailServiceProvider',
        'Microweber\App\Providers\Illuminate\PaginationServiceProvider',
        'Microweber\App\Providers\Illuminate\QueueServiceProvider',
        'Microweber\App\Providers\Illuminate\RedisServiceProvider',
        'Microweber\App\Providers\Illuminate\PasswordResetServiceProvider',
        'Microweber\App\Providers\Illuminate\SessionServiceProvider',
        'Microweber\App\Providers\Illuminate\TranslationServiceProvider',
        'Microweber\App\Providers\Illuminate\ValidationServiceProvider',
        'Microweber\App\Providers\Illuminate\ViewServiceProvider'
    ];

    /*
	|--------------------------------------------------------------------------
	| Class Aliases
	|--------------------------------------------------------------------------
	|
	| This array of class aliases will be registered when this application
	| is started. However, feel free to register as many as you wish as
	| the aliases are "lazy" loaded so they don't hinder performance.
	|
	*/

    public $laravel_aliases = [
        'App' => 'Microweber\App\Providers\Illuminate\Support\Facades\App',
        'Artisan' => 'Microweber\App\Providers\Illuminate\Support\Facades\Artisan',
        'Auth' => 'Microweber\App\Providers\Illuminate\Support\Facades\Auth',
        'Blade' => 'Microweber\App\Providers\Illuminate\Support\Facades\Blade',
        'Cache' => 'Microweber\App\Providers\Illuminate\Support\Facades\Cache',
        'Config' => 'Microweber\App\Providers\Illuminate\Support\Facades\Config',
        'Cookie' => 'Microweber\App\Providers\Illuminate\Support\Facades\Cookie',
        'Crypt' => 'Microweber\App\Providers\Illuminate\Support\Facades\Crypt',
        'DB' => 'Microweber\App\Providers\Illuminate\Support\Facades\DB',
        'Event' => 'Microweber\App\Providers\Illuminate\Support\Facades\Event',
        'File' => 'Microweber\App\Providers\Illuminate\Support\Facades\File',
        'Hash' => 'Microweber\App\Providers\Illuminate\Support\Facades\Hash',
        'Input' => 'Microweber\App\Providers\Illuminate\Support\Facades\Input',
        'Lang' => 'Microweber\App\Providers\Illuminate\Support\Facades\Lang',
        'Log' => 'Microweber\App\Providers\Illuminate\Support\Facades\Log',
        'Mail' => 'Microweber\App\Providers\Illuminate\Support\Facades\Mail',
        'Paginator' => 'Microweber\App\Providers\Illuminate\Support\Facades\Paginator',
        'Password' => 'Microweber\App\Providers\Illuminate\Support\Facades\Password',
        'Queue' => 'Microweber\App\Providers\Illuminate\Support\Facades\Queue',
        'Redirect' => 'Microweber\App\Providers\Illuminate\Support\Facades\Redirect',
        'Redis' => 'Microweber\App\Providers\Illuminate\Support\Facades\Redis',
        'Request' => 'Microweber\App\Providers\Illuminate\Support\Facades\Request',
        'Response' => 'Microweber\App\Providers\Illuminate\Support\Facades\Response',
        'Route' => 'Microweber\App\Providers\Illuminate\Support\Facades\Route',
        'Schema' => 'Microweber\App\Providers\Illuminate\Support\Facades\Schema',
        'Session' => 'Microweber\App\Providers\Illuminate\Support\Facades\Session',
        'URL' => 'Microweber\App\Providers\Illuminate\Support\Facades\URL',
        'Validator' => 'Microweber\App\Providers\Illuminate\Support\Facades\Validator',
        'View' => 'Microweber\App\Providers\Illuminate\Support\Facades\View',
    ];

    public function __construct($app)
    {
        ClassLoader::addDirectories(array(
            base_path() . DIRECTORY_SEPARATOR . 'userfiles' . DIRECTORY_SEPARATOR . 'modules',
            __DIR__,
        ));

        ClassLoader::register();
        spl_autoload_register(array($this, 'autoloadModules'));

        parent::__construct($app);
    }

    public function register()
    {

        foreach ($this->laravel_providers as $provider) {
            $this->app->register($provider);
        }

        foreach ($this->laravel_aliases as $alias => $provider) {
            AliasLoader::getInstance()->alias($alias, $provider);
        }


        // Set environment
        if (!is_cli()) {
            $domain = $_SERVER['HTTP_HOST'];
            $this->app->detectEnvironment(function () use ($domain) {
                if (getenv('APP_ENV')) {
                    return getenv('APP_ENV');
                }
                $port = explode(':', $_SERVER['HTTP_HOST']);
                $domain = str_ireplace('www.', '', $domain);
                if (isset($port[1])) {
                    $domain = str_ireplace(':' . $port[1], '', $domain);
                }
                $domain = strtolower($domain);
                return $domain;
            });
        } else {
            if (defined('MW_UNIT_TEST')) {
                $this->app->detectEnvironment(function () {
                    if (!defined('MW_UNIT_TEST_ENV_FROM_TEST')) {
                        return 'testing';
                    } else {
                        return MW_UNIT_TEST_ENV_FROM_TEST;
                    }
                });
            }
        }





        $this->app->instance('config', new ConfigSave($this->app));



        $this->app->singleton(
            'Illuminate\Cache\StoreInterface',
            'Utils\Adapters\Cache\CacheStore'
        );

        $this->app->bind('Illuminate\Contracts\Bus\Dispatcher', 'Illuminate\Bus\Dispatcher');
        $this->app->bind('Illuminate\Contracts\Queue\Queue', 'Illuminate\Contracts\Queue\Queue');
        // $this->app->register('Illuminate\Auth\AuthServiceProvider');

//        $this->app->singleton(
//            'Illuminate\Contracts\Debug\ExceptionHandler',
//            'Microweber\App\Exceptions\Handler'
//        );

        $this->app->singleton(
            'Illuminate\Contracts\Console\Kernel',
            'Microweber\App\Console\Kernel'
        );

        $this->app->singleton('lang_helper', function ($app) {
            return new Providers\Helpers\Lang($app);
        });

        $this->app->singleton('event_manager', function ($app) {
            return new Providers\Event($app);
        });
        $this->app->singleton('database_manager', function ($app) {
            return new Providers\DatabaseManager($app);
        });

        $this->app->singleton('format', function ($app) {
            return new Utils\Format($app);
        });
        $this->app->singleton('parser', function ($app) {
            return new Utils\Parser($app);
        });

        $this->app->bind('http', function ($app) {
            return new Utils\Http($app);
        });

        $this->app->bind('captcha', function ($app) {
            return new Utils\Captcha($app);
        });

        $this->app->singleton('url_manager', function ($app) {
            return new Providers\UrlManager($app);
        });
        $this->app->singleton('ui', function ($app) {
            return new Providers\Ui($app);
        });
        $this->app->singleton('content_manager', function ($app) {
            return new Providers\ContentManager($app);
        });
        $this->app->singleton('update', function ($app) {
            return new Providers\UpdateManager($app);
        });
        $this->app->singleton('cache_manager', function ($app) {
            return new Providers\CacheManager($app);
        });
        $this->app->singleton('config_manager', function ($app) {
            return new Providers\ConfigurationManager($app);
        });
        $this->app->singleton('media_manager', function ($app) {
            return new Providers\MediaManager($app);
        });
        $this->app->singleton('fields_manager', function ($app) {
            return new Providers\FieldsManager($app);
        });

        $this->app->singleton('data_fields_manager', function ($app) {
            return new Providers\Content\DataFieldsManager($app);
        });


        $this->app->singleton('tags_manager', function ($app) {
            return new Providers\Content\TagsManager($app);
        });

        $this->app->singleton('attributes_manager', function ($app) {
            return new Providers\Content\AttributesManager($app);
        });
        $this->app->singleton('forms_manager', function ($app) {
            return new Providers\FormsManager($app);
        });


        $this->app->singleton('notifications_manager', function ($app) {
            return new Providers\NotificationsManager($app);
        });
        $this->app->singleton('log_manager', function ($app) {
            return new Providers\LogManager($app);
        });
        $this->app->singleton('option_manager', function ($app) {
            return new Providers\OptionManager($app);
        });

        $this->app->singleton('template', function ($app) {
            return new Providers\Template($app);
        });
        $this->app->singleton('modules', function ($app) {
            return new Providers\Modules($app);
        });
        $this->app->singleton('category_manager', function ($app) {
            return new Providers\CategoryManager($app);
        });

        $this->app->singleton('menu_manager', function ($app) {
            return new Providers\MenuManager($app);
        });
        $this->app->singleton('user_manager', function ($app) {
            return new Providers\UserManager($app);
        });

        // Shop

        $this->app->singleton('shop_manager', function ($app) {
            return new Providers\ShopManager($app);
        });

        $this->app->singleton('cart_manager', function ($app) {
            return new Providers\Shop\CartManager($app);
        });

        $this->app->singleton('order_manager', function ($app) {
            return new Providers\Shop\OrderManager($app);
        });

        $this->app->singleton('tax_manager', function ($app) {
            return new Providers\Shop\TaxManager($app);
        });

        $this->app->singleton('checkout_manager', function ($app) {
            return new Providers\Shop\CheckoutManager($app);
        });


        // Other

        $this->app->singleton('layouts_manager', function ($app) {
            return new Providers\LayoutsManager($app);
        });

        $this->app->singleton('template_manager', function ($app) {
            return new Providers\TemplateManager($app);
        });


        $this->app->register('Collective\Html\HtmlServiceProvider');

        AliasLoader::getInstance()->alias('Form', 'Collective\Html\FormFacade');
        AliasLoader::getInstance()->alias('HTML', 'Collective\Html\HtmlFacade');

        $this->app->register('GrahamCampbell\Markdown\MarkdownServiceProvider');
        AliasLoader::getInstance()->alias('Markdown', 'GrahamCampbell\Markdown\Facades\Markdown');
        AliasLoader::getInstance()->alias('Carbon', 'Carbon\Carbon');


        $this->app->register('Conner\Tagging\Providers\TaggingServiceProvider');


        // $this->app->register('SocialiteProviders\Manager\ServiceProvider');
    }

    public function boot(Request $request)
    {
        //parent::boot();

//        $environment = App::environment();
//        $path_storage_base = storage_path();
//        $path_storage = $path_storage_base . DS . $environment;
//       $this->app->useStoragePath($path_storage);


        // public = /
        App::instance('path.public', base_path());

        Cache::extend('file', function ($app) {
            return new Utils\Adapters\Cache\CacheStore();
        });

        // If installed load module functions and set locale
        if (mw_is_installed()) {
            $modules = load_all_functions_files_for_modules();
            $this->commands('Microweber\Commands\OptionCommand');

            $language = get_option('language', 'website');
            if ($language != false) {
                set_current_lang($language);
            }
            if (is_cli()) {
                $this->commands('Microweber\Commands\UpdateCommand');
            }

        } else {
            // Otherwise register the install command
            $this->commands('Microweber\Commands\InstallCommand');
        }

        // Register routes
        $this->registerRoutes();
        $this->app->event_manager->trigger('mw.after.boot', $this);
    }


    private function registerRoutes()
    {
        $routesFile = __DIR__ . '/routes.php';
        if (file_exists($routesFile)) {
            include $routesFile;

            return true;
        }

        return false;
    }

    public function autoloadModules($className)
    {
        $filename = modules_path() . $className . '.php';
        $filename = normalize_path($filename, false);

        if (!class_exists($className, false)) {
            if (is_file($filename)) {
                require_once $filename;
            }
        }
    }
}
