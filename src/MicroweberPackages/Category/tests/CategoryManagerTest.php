<?php
namespace MicroweberPackages\Category\tests;

use MicroweberPackages\Content\Content;
use MicroweberPackages\Core\tests\TestCase;

use MicroweberPackages\Category\Models\Category;


class CategoryManagerTest extends TestCase
{





    public function testSelectOnlyFieldsWithCategoryFilter()
    {

        $category = new Category();
        $category->title = 'New cat for my content to test filter';
        $category->save();


        $cont_title =  'Content with cats to test filter';
        $newPage = new Content();
        $newPage->title =$cont_title;
        $newPage->category_ids = $category->id;
        $newPage->save();


        $contentForCategories = get_content(array(
            "categories"=>[$category->id],
            "fields"=>'title,id'
        ));

        $this->assertEquals($cont_title, $contentForCategories[0]['title']);
        $this->assertEquals($newPage->id, $contentForCategories[0]['id']);
        $this->assertEquals(2,count( $contentForCategories[0]));


        //delete from cat
        $newPage->category_ids = 0;
        $newPage->save();
        //check if deleted
        $contentForCategories = get_content(array(
            "categories"=>[$category->id],
            "fields"=>'title,id'
        ));

        $this->assertEquals(null, $contentForCategories);

    }


    /*public function testCategoryTree()
    {

        $category1 = new Category();
        $category1->title = 'Cat level 1';
        $category1->data_type = 'category';
        $category1->save();

        $category2 = new Category();
        $category2->title = 'Cat level 2';
        $category2->data_type = 'category';

        $category2->parent_id =$category1->id;
        $category2->save();


        $tree = category_tree('use_cache=0&ul_class=test_ul_class&li_class=test_li_class&li_class_deep=test_li_class_deep&dsfreturn_data=1&all=1');
         var_dump($tree);
//        dump($category2);
//        var_dump($tree);
//        var_dump('sadsad');
       // $this->assertTrue(str_contains($tree,'test_ul_class'));

       // $this->assertTrue(str_contains($tree,'test_li_class'));



    }*/


}