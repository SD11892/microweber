<?php

namespace MicroweberPackages\Translation;

use Illuminate\Translation\FileLoader;
use MicroweberPackages\Translation\Models\Translation;
use MicroweberPackages\Translation\Models\TranslationKey;

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
        $allTranslations = [];

        // Load translations from files
        $fileTranslations = parent::load($locale, $group, $namespace);
        if (!is_null($fileTranslations)) {
            $allTranslations = $fileTranslations;
        }

        // Load translations from database
        if (mw_is_installed()) {
            $getTranslations = TranslationKey::where('translation_group', $group)->where('translation_namespace', $namespace)->get();
            if ($getTranslations !== null) {
                foreach ($getTranslations as $translation) {
                  //  $allTranslations[$translation->translation_key] = $translation->translation_key;
                }
            }
        }

        return $allTranslations;
    }

    /*
    private function loadLanguageFiles($locale, $group, $namespace)
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
    }*/

}
