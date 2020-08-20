<?php
/**
 * Created by PhpStorm.
 * Page: Bojidar
 * Date: 8/19/2020
 * Time: 2:53 PM
 */

namespace MicroweberPackages\Multilanguage\Observers;


use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Multilanguage\MultilanguageTranslations;

class MultilanguageObserver
{
    protected static $fieldsToSave = [];

    public function retrieved(Model $model)
    {
        if ($this->getLocale() == $this->getDefaultLocale()) {
            return;
        }

        if (isset($model->translatable) && is_array($model->translatable)) {
            foreach ($model->translatable as $fieldName) {

                if (empty($model->$fieldName)) {
                    continue;
                }

                $findTranslate = MultilanguageTranslations::where('field_name', $fieldName)
                    ->where('rel_type', $model->getTable())
                    ->where('rel_id', $model->id)
                    ->where('locale', $this->getLocale())
                    ->first();

                if ($findTranslate) {
                    $model->$fieldName = $findTranslate->field_value;
                }
            }
        }
    }

    public function saving(Model $model)
    {
        if ($this->getLocale() == $this->getDefaultLocale()) {
            return;
        }

        if (isset($model->translatable) && is_array($model->translatable)) {
            foreach ($model->translatable as $fieldName) {
                self::$fieldsToSave[$fieldName] = $model->$fieldName;
                unset($model->$fieldName);
            }
        }
    }

    /**
     * Handle the Page "saving" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function saved(Model $model)
    {
        if ($this->getLocale() == $this->getDefaultLocale()) {
            return;
        }

        if (isset($model->translatable) && is_array($model->translatable)) {
            foreach ($model->translatable as $fieldName) {

                $findTranslate = MultilanguageTranslations::where('field_name', $fieldName)
                    ->where('rel_type', $model->getTable())
                    ->where('rel_id', $model->id)
                    ->where('locale', $this->getLocale())
                    ->first();

                if ($findTranslate) {
                    $findTranslate->field_value = self::$fieldsToSave[$fieldName];
                    $findTranslate->save();
                } else {
                    MultilanguageTranslations::create([
                        'field_name' => $fieldName,
                        'field_value' => self::$fieldsToSave[$fieldName],
                        'rel_type' => $model->getTable(),
                        'rel_id' => $model->id,
                        'locale' => $this->getLocale()
                    ]);
                }
            }
            self::$fieldsToSave = [];
        }
    }

    protected function getDefaultLocale()
    {
        return strtolower(mw()->lang_helper->default_lang());
    }

    protected function getLocale()
    {
        return strtolower(mw()->lang_helper->current_lang());
    }
}