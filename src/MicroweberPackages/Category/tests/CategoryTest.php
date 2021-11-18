<?php

namespace MicroweberPackages\Media\tests;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Category\Models\CategoryItem;
use MicroweberPackages\Category\Traits\CategoryTrait;
use MicroweberPackages\Core\tests\TestCase;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;


class ContentTestModelForCategories extends Model
{
    use CategoryTrait;

    protected $table = 'content';

}

class CategoryTest extends TestCase
{
    public function testRender()
    {

      /*  $page = new Page();
        $page->title = 'my-new-page-'.uniqid();
        $page->content_type = 'page';
        $page->url = 'my-new-page';
        $page->subtype = 'dynamic';
        $page->save();*/

        $mainCategory = new Category();
        $mainCategory->title = 'Category level 1' . uniqid();
        $mainCategory->save();

        $categoryTitle = category_title($mainCategory->id);
        $this->assertEquals($mainCategory->title, $categoryTitle);

        $post = new Post();
        $post->title = 'my-new-post-'.uniqid();
        $post->content_type = 'post';
        $post->url = 'my-new-post';
        $post->category_ids = $mainCategory->id;
        $post->save();

        $categoryItems = get_category_items($mainCategory->id);
        $this->assertEquals(1, count($categoryItems));
        $this->assertEquals($mainCategory->id, $categoryItems[0]['parent_id']);
        $this->assertEquals('content', $categoryItems[0]['rel_type']);
        $this->assertEquals($post->id, $categoryItems[0]['rel_id']);

    }

    public function testAddcategoriesToModel()
    {

        $title = 'New cat for my custom model'.uniqid();

        $category = new Category();
        $category->title = $title;
        $category->save();



        $newPage = new ContentTestModelForCategories();
        $newPage->title = 'Content with cats ';

         $newPage->category_ids = $category->id;

//       $newPage->setCategories  (['kotka', 'horo']);
//
//        $newPage->setCategory([
//              'title' => 'kotka',
//              'url' => 'kotka-slug'
//        ]);

        $newPage->save();

        $cat = $newPage->categories->first();

        $this->assertNotEmpty($cat );
        $this->assertEquals($cat->parent->title,$title );

    }

}
