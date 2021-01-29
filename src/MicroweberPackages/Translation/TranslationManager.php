<?php

namespace MicroweberPackages\Translation;

use _HumbugBox58fd4d9e2a25\VARIANT;
use Illuminate\Translation\FileLoader;
use MicroweberPackages\App\Managers\Helpers\Lang;
use MicroweberPackages\Translation\Models\Translation;

class TranslationManager extends FileLoader
{

    public $translatedLanguageLines = [];

    /**
     * Load the messages for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     *
     * @return array
     */
    public function load($locale, $group, $namespace = null): array
    {
        $fileTranslations = parent::load($locale, $group, $namespace);

        if (!is_null($fileTranslations)) {
            return $fileTranslations;
        }

    }

    private function ___loadLanguageFiles($locale, $group, $namespace)
    {
        if (isset($this->translatedLanguageLines[$locale])) {
            return $this->translatedLanguageLines[$locale];
        }

        $languageFiles = [];
        $languageFiles[] = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $locale . '.json';

        if (empty($locale) || $locale == 'en') {
            $languageFiles[] = mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . 'en.json';
        } else {
            $languageFiles[] = normalize_path(mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . $locale . '.json', false);
        }

        foreach ($languageFiles as $languageFile) {
            if (is_file($languageFile)) {
                $languageContent = file_get_contents($languageFile);
                $languageVariables = json_decode($languageContent, true);
                if (isset($languageVariables) and is_array($languageVariables)) {
                    foreach ($languageVariables as $languageVariableKey => $languageVariableValue) {
                        $this->translatedLanguageLines[$locale][$languageVariableKey] = $languageVariableValue;
                    }
                }
            }
        }

        return $this->translatedLanguageLines[$locale];
    }
}
