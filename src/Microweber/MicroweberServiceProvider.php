<?php

namespace Microweber;

use App;
use Cache;
use Microweber\Providers\DatabaseManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Cache\TaggableFileCacheServiceProvider;
use MicroweberPackages\CartManager\CartManagerServiceProvider;
use MicroweberPackages\CategoryManager\CategoryManagerServiceProvider;
use MicroweberPackages\CheckoutManager\CheckoutManagerServiceProvider;
use MicroweberPackages\ClientsManager\ClientsManagerServiceProvider;
use MicroweberPackages\Config\ConfigSave;
use MicroweberPackages\ContentManager\Content;
use MicroweberPackages\ContentManager\ContentManagerServiceProvider;
use MicroweberPackages\DatabaseManager\DatabaseManagerServiceProvider;
use MicroweberPackages\EventManager\EventManagerServiceProvider;
use MicroweberPackages\FormsManager\FormsManagerServiceProvider;
use MicroweberPackages\Helpers\Format;
use MicroweberPackages\Helpers\HelpersServiceProvider;
use MicroweberPackages\InvoicesManager\InvoicesManagerServiceProvider;
use MicroweberPackages\MediaManager\Media;
use MicroweberPackages\MediaManager\MediaManagerServiceProvider;
use MicroweberPackages\MenuManager\MenuManagerServiceProvider;
use MicroweberPackages\OptionManager\OptionManagerServiceProvider;
use MicroweberPackages\OrderManager\OrderManagerServiceProvider;
use MicroweberPackages\ShopManager\ShopManagerServiceProvider;
use MicroweberPackages\TagsManager\TagsManagerServiceProvider;
use MicroweberPackages\TaxManager\TaxManagerServiceProvider;
use MicroweberPackages\TemplateManager\TemplateManagerServiceProvider;
use MicroweberPackages\UserManager\UserManagerServiceProvider;
use MicroweberPackages\Utils\System\ClassLoader;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

if (! defined('MW_VERSION')) {
    include_once __DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'bootstrap.php';
}

class MicroweberServiceProvider extends ServiceProvider
{
    protected $aliasInstance;

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
        'Microweber\App\Providers\Illuminate\ViewServiceProvider',
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
        ClassLoader::addDirectories([
            base_path() . DIRECTORY_SEPARATOR . 'userfiles' . DIRECTORY_SEPARATOR . 'modules',
            __DIR__,
        ]);

        ClassLoader::register();

        spl_autoload_register([$this, 'autoloadModules']);

        $this->aliasInstance = AliasLoader::getInstance();

        parent::__construct($app);
    }

    public function register()
    {
        $this->registerLaravelProviders();

        $this->registerLaravelAliases();

        $this->setEnvironmentDetection();

        $this->registerUtils();

        $this->registerSingletonProviders();

        $this->registerHtmlCollective();

        $this->registerMarkdown();

        $this->app->instance('config', new ConfigSave($this->app));

        $this->app->register('Conner\Tagging\Providers\TaggingServiceProvider');

        // $this->app->register(TaggableFileCacheServiceProvider::class);

        $this->app->register(EventManagerServiceProvider::class);
        $this->app->register(OptionManagerServiceProvider::class);
        $this->app->register(HelpersServiceProvider::class);
        $this->app->register(ContentManagerServiceProvider::class);
        $this->app->register(CategoryManagerServiceProvider::class);
        $this->app->register(TagsManagerServiceProvider::class);
        $this->app->register(MediaManagerServiceProvider::class);
        $this->app->register(MenuManagerServiceProvider::class);
        $this->app->register(ShopManagerServiceProvider::class);
        $this->app->register(TaxManagerServiceProvider::class);
        $this->app->register(OrderManagerServiceProvider::class);
        $this->app->register(InvoicesManagerServiceProvider::class);
        $this->app->register(ClientsManagerServiceProvider::class);
        $this->app->register(CheckoutManagerServiceProvider::class);
        $this->app->register(CartManagerServiceProvider::class);
        $this->app->register(TemplateManagerServiceProvider::class);
        $this->app->register(FormsManagerServiceProvider::class);
        $this->app->register(UserManagerServiceProvider::class);

        $this->aliasInstance->alias('Carbon', 'Carbon\Carbon');
    }

    protected function registerLaravelProviders()
    {
        foreach ($this->laravel_providers as $provider) {
            $this->app->register($provider);
        }

        $this->app->bind('Illuminate\Contracts\Bus\Dispatcher', 'Illuminate\Bus\Dispatcher');

        $this->app->bind('Illuminate\Contracts\Queue\Queue', 'Illuminate\Contracts\Queue\Queue');

        $this->app->singleton(
            'Illuminate\Cache\StoreInterface',
            'Utils\Adapters\Cache\CacheStore'
        );

        $this->app->singleton(
            'Illuminate\Contracts\Console\Kernel',
            'Microweber\App\Console\Kernel'
        );
    }

    protected function registerLaravelAliases()
    {
        foreach ($this->laravel_aliases as $alias => $provider) {
            $this->aliasInstance->alias($alias, $provider);
        }
    }

    protected function setEnvironmentDetection()
    {
        if (! is_cli()) {
            if(isset($_SERVER['HTTP_HOST'])){
            $domain = $_SERVER['HTTP_HOST'];
            } else if(isset($_SERVER['SERVER_NAME'])){
                $domain = $_SERVER['SERVER_NAME'];
            }

            return $this->app->detectEnvironment(function () use ($domain) {
                if (getenv('APP_ENV')) {
                    return getenv('APP_ENV');
                }

                $port = explode(':', $domain);

                $domain = str_ireplace('www.', '', $domain);

                if (isset($port[1])) {
                    $domain = str_ireplace(':' . $port[1], '', $domain);
                }

                return strtolower($domain);
            });
        }

        if (defined('MW_UNIT_TEST')) {
            $this->app->detectEnvironment(function () {
                if (! defined('MW_UNIT_TEST_ENV_FROM_TEST')) {
                    return 'testing';
                }

                return MW_UNIT_TEST_ENV_FROM_TEST;
            });
        }
    }

    protected function registerUtils()
    {
        $this->app->bind('http', function ($app) {
            return new Utils\Http($app);
        });

        $this->app->singleton('format', function ($app) {
            return new Format($app);
        });

        $this->app->singleton('parser', function ($app) {
            return new Utils\Parser($app);
        });
    }

    protected function registerSingletonProviders()
    {
        $providers = [
            'lang_helper' => 'Helpers\Lang',
            'ui' => 'Ui',
            'update' => 'UpdateManager',
            'cache_manager' => 'CacheManager',
            'config_manager' => 'ConfigurationManager',
            'notifications_manager' => 'NotificationsManager',
            'log_manager' => 'LogManager',
            'modules' => 'Modules',
            'layouts_manager' => 'LayoutsManager',
            'captcha_manager' => 'CaptchaManager',
        ];

        foreach ($providers as $alias => $class) {
            $this->app->singleton($alias, function ($app) use ($class) {
                $class = 'Microweber\\Providers\\' . $class;

                return new $class($app);
            });
        }
    }

    protected function registerHtmlCollective()
    {
        $this->app->register('Collective\Html\HtmlServiceProvider');

        $this->aliasInstance->alias('Form', 'Collective\Html\FormFacade');
        $this->aliasInstance->alias('HTML', 'Collective\Html\HtmlFacade');
    }

    protected function registerMarkdown()
    {
        $this->app->register('GrahamCampbell\Markdown\MarkdownServiceProvider');

        $this->aliasInstance->alias('Markdown', 'GrahamCampbell\Markdown\Facades\Markdown');
    }

    public function boot()
    {
        App::instance('path.public', base_path());

        Cache::extend('file', function () {
            return new Utils\Adapters\Cache___\CacheStore();
        });

        $this->app->database_manager->add_table_model('content', Content::class);
        $this->app->database_manager->add_table_model('media', Media::class);

        // If installed load module functions and set locale
        if (mw_is_installed()) {
            load_all_functions_files_for_modules();

            $this->commands('Microweber\Commands\OptionCommand');

            $language = get_option('language', 'website');

            if ($language != false) {
                set_current_lang($language);
            }

            if (is_cli()) {

                $this->commands('Microweber\Commands\ResetCommand');
                $this->commands('Microweber\Commands\UpdateCommand');
                $this->commands('Microweber\Commands\ModuleCommand');
                $this->commands('Microweber\Commands\PackageInstallCommand');

            }
        } else {
            // Otherwise register the install command
            $this->commands('Microweber\Commands\InstallCommand');
        }

        $this->loadRoutes();

        $this->app->event_manager->trigger('mw.after.boot', $this);
    }

    private function loadRoutes()
    {
        $routesFile = __DIR__ . '/routes.php';

        if (file_exists($routesFile)) {
            include $routesFile;
        }
    }

    public function autoloadModules($className)
    {
        $filename = modules_path() . $className . '.php';
        $filename = normalize_path($filename, false);

        if (! class_exists($className, false) && is_file($filename)) {
            require_once $filename;
        }
    }
}
