<?php return array (
  'debug' => 1,
  'url' => 'http://127.0.0.1:8000/',
  'timezone' => 'UTC',
  'locale' => 'en_US',
  'fallback_locale' => 'en',
  'key' => 'base64:bDJnd2JybUU1clpXSHRldHV5NnoxQWxkT3lmRVExMnY=',
  'cipher' => 'AES-256-CBC',
  'log' => 'daily',
  'providers' =>
  array (
    0 => 'MicroweberPackages\\App\\Providers\\AppServiceProvider',
    1 => 'MicroweberPackages\\App\\Providers\\EventServiceProvider',
    2 => 'MicroweberPackages\\App\\Providers\\RouteServiceProvider',
  ),
  'manifest' => storage_path().DIRECTORY_SEPARATOR.'framework',
);
