<?php
namespace MicroweberPackages\Category\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Category\Models\ModelFilters\CategoryFilter;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class Category extends Model
{
    use CacheableQueryBuilderTrait;
    use Filterable;

    protected $table = 'categories';

  //  public $timestamps = false;

    /**
     * The model's default values for attributes.
     * @var array
     */
    protected $attributes = [
        'data_type' => 'category',
        'rel_type' => 'content'
    ];

    public $fillable = [
        "rel_type",
        "rel_id",
        "data_type",
        "parent_id",
        "title",
        "content",
        "description",
        "category-parent-selector",
        "position",
        "thumbnail",
        "url",
        "users_can_create_content",
        "category_subtype",
        "category_meta_title",
        "category_meta_description",
        "category_meta_keywords"
    ];

    public $cacheTagsToClear = ['content', 'content_fields_drafts', 'menu', 'content_fields', 'categories'];

    public $translatable = ['title','url','description','content'];

    public function modelFilter()
    {
        return $this->provideFilter(CategoryFilter::class);
    }

    public function items()
    {
        return $this->hasMany(CategoryItem::class, 'parent_id');
    }

    public function children()
    {
         return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function link()
    {
        return category_link($this->id);
    }

//    public static function getLinks()
//    {
//        $allCategories =  self::all();
//
//        return $allCategories;
//    }

    public static function hasActiveProductInSubcategories($category)
    {

        if(empty($category) || count($category->items) == 0) {
            return false;
        }

        foreach($category->items as $item) {
            $product = \MicroweberPackages\Product\Models\Product::find($item->rel_id);

            if($product->in_stock) {
                return true;
            }
        }

        if(count($category->children) > 0) {
            foreach($category->children as $childCat) {
                if(self::hasActiveProductInSubcategories($childCat)) {
                    return true;
                }
            }
        }

        return false;
    }
}
