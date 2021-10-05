<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    public $testEnvironment = 'dusk_testing';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $this->setupConfigFolder();

        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();


        $testEnvironment = $this->testEnvironment;
        $app->detectEnvironment(function () use ($testEnvironment) {
            return $testEnvironment;
        });
        $app['config']->set('cache.default', 'file');
        $app['config']->set('cache.stores.file',
            [
                'driver' => 'file',
                'path' => storage_path('framework/cache'),
                'separator' => '~#~'
            ]
        );


        echo 1;

        return $app;
    }

    public function setupConfigFolder()
    {
        $configFolder = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
        $configFolder = $configFolder . $this->testEnvironment . DIRECTORY_SEPARATOR;

        if (!is_dir($configFolder)) {
            mkdir($configFolder);
        }

        $mwFile = $configFolder . 'microweber.php';
        $mwFileDatabase = $configFolder . 'database.php';
        $mwFile = $this->normalizePath($mwFile, false);

        if (!defined('MW_DUSK_TEST_CONF_FILE_CREATED')) {
            @unlink($mwFileDatabase);
            file_put_contents($mwFile, "<?php return array (
                'dusk_testing' => 1,
                'environment' => '$this->testEnvironment',
                'is_installed' => 0,
                'compile_assets' => 0,
                'install_default_template' => 'default',
                'install_default_template_content' => 1,
            );"
            );
        }

    }

    private function normalizePath($path, $slashIt = true)
    {
        $pathOriginal = $path;

        $s = DIRECTORY_SEPARATOR;
        $path = preg_replace('/[\/\\\]/', $s, $path);
        $path = str_replace($s . $s, $s, $path);

        if (strval($path) == '') {
            $path = $pathOriginal;
        }

        if ($slashIt == false) {
            $path = rtrim($path, DIRECTORY_SEPARATOR);
        } else {
            $path .= DIRECTORY_SEPARATOR;
            $path = rtrim($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
        }

        if (strval(trim($path)) == '' or strval(trim($path)) == '/') {
            $path = $pathOriginal;
        }

        if ($slashIt) {
            $path = $path . DIRECTORY_SEPARATOR;
            $path = $this->reduce_double_slashes($path);
        }

        return $path;

    }

    /**
     * Removes double slashes from sting.
     *
     * @param $str
     *
     * @return string
     */
    private function reduce_double_slashes($str)
    {
        return preg_replace('#([^:])//+#', '\\1/', $str);
    }


}
