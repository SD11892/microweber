<?php
namespace MicroweberPackages\Multilanguage\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Multilanguage\Observers\MultilanguageObserver;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Multilanguage\MultilanguageApi;

class MultilanguageContentTest extends MultilanguageTestBase
{

    public function testSaveContentFromApiController()
    {

        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(1);

        $params = [
            'for_module' => 'multilanguage'
        ];
        app()->module_manager->set_installed($params);
        $test = app()->module_manager->is_installed($params['for_module']);
        $this->assertEquals(true, $test);

        add_supported_language('en_US', 'English');
        add_supported_language('bg_BG', 'Bulgarian');
        add_supported_language('ar_SA', 'Arabic');
        add_supported_language('ru_RU', 'Russian');

        // Set default lang
        $option = array();
        $option['option_value'] = 'en_US';
        $option['option_key'] = 'language';
        $option['option_group'] = 'website';
        save_option($option);

        $option = array();
        $option['option_value'] = 'y';
        $option['option_key'] = 'is_active';
        $option['option_group'] = 'multilanguage_settings';
        save_option($option);

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $rand = time().rand(111,999);

        $apiContentStore = [];
        $apiContentStore['title'] = 'Content ' . $rand;
        $apiContentStore['description'] = 'Content description' .$rand;

        $apiContentStore['multilanguage']['title']['bg_BG'] = 'Съдържание'.$rand;
        $apiContentStore['multilanguage']['description']['bg_BG'] = 'Съдържание описание'.$rand;

        $apiContentStore['multilanguage']['title']['ar_SA'] = 'فئة'.$rand;
        $apiContentStore['multilanguage']['description']['ar_SA'] = 'وصف التصنيف'.$rand;


        $apiContentStore['multilanguage']['title']['ru_RU'] = 'Съдържание'.$rand;
        $apiContentStore['multilanguage']['description']['ru_RU'] = 'Описание Съдържание'.$rand;

        $response = $this->call(
            'POST',
            route('api.content.store', $apiContentStore)
        );
        $this->assertEquals(201, $response->status());
        $contentSaved = $response->getData()->data;


        $getContent = Content::where('id', $contentSaved->id)->first();

        $this->assertEquals($getContent->multilanguage['bg_BG']['title'], $apiContentStore['multilanguage']['title']['bg_BG']);
        $this->assertEquals($getContent->multilanguage['bg_BG']['description'], $apiContentStore['multilanguage']['description']['bg_BG']);

        $this->assertEquals($getContent->multilanguage['ar_SA']['title'], $apiContentStore['multilanguage']['title']['ar_SA']);
        $this->assertEquals($getContent->multilanguage['ar_SA']['description'], $apiContentStore['multilanguage']['description']['ar_SA']);

        $this->assertEquals($getContent->multilanguage['ru_RU']['title'], $apiContentStore['multilanguage']['title']['ru_RU']);
        $this->assertEquals($getContent->multilanguage['ru_RU']['description'], $apiContentStore['multilanguage']['description']['ru_RU']);

        // TEST BULGARIAN

        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'bg_BG'
        ]);

        $getContent = Content::where('id', $contentSaved->id)->first();
        $this->assertEquals($getContent->title, $apiContentStore['multilanguage']['title']['bg_BG']);
        $this->assertEquals($getContent->description, $apiContentStore['multilanguage']['description']['bg_BG']);


        // TEST ARABIC

        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'ar_SA'
        ]);

        $getContent = Content::where('id', $contentSaved->id)->first();
        $this->assertEquals($getContent->title, $apiContentStore['multilanguage']['title']['ar_SA']);
        $this->assertEquals($getContent->description, $apiContentStore['multilanguage']['description']['ar_SA']);


        // TEST RUSSIAN
        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'ru_RU'
        ]);

        $getContent = Content::where('id', $contentSaved->id)->first();
        $this->assertEquals($getContent->title, $apiContentStore['multilanguage']['title']['ru_RU']);
        $this->assertEquals($getContent->description, $apiContentStore['multilanguage']['description']['ru_RU']);


    }

}
