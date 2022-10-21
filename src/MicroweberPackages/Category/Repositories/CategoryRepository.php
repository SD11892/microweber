<?php

namespace MicroweberPackages\Category\Repositories;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Category\Models\CategoryItem;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Repository\MicroweberQuery;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

class CategoryRepository extends AbstractRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Category::class;


    public function getByUrl($url)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($url) {

            $getCategory = \DB::table('categories')->where('url', $url)->get();
            $getCategory = collect($getCategory)->map(function ($item) {
                return (array)$item;
            })->toArray();

            return $getCategory;
        });

    }

    public function getByColumnNameAndColumnValue($columnName, $columnValue)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($columnName, $columnValue) {

            $getCategory = \DB::table('categories')->where($columnName, $columnValue)->first();
            if ($getCategory != null) {

                $getCategory = (array)$getCategory;

                $hookParams = [];
                $hookParams['data'] = $getCategory;
                $hookParams['hook_overwrite_type'] = 'single';
                $overwrite = app()->event_manager->response(get_class($this) . '\\getByColumnNameAndColumnValue', $hookParams);

                if (isset($overwrite['data'])) {
                    $getCategory = $overwrite['data'];
                }

                return $getCategory;

            } else {
                return false;
            }
        });
    }


    /**
     * Find category media by category id.
     *
     * @param mixed $id
     *
     * @return array
     */
    public function getMedia($id)
    {

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            $item = $this->findById($id);
            if ($item) {
                $get = $item->media;
                if ($get) {
                    return $get->toArray();
                }
            }
            return [];

        });
    }

    /**
     *
     * @param mixed $categoryId
     *
     * @return boolean|array
     */
    public function getSubCategories($categoryId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($categoryId) {

            $getCategory = \DB::table('categories')
                ->select(['id', 'parent_id'])
                ->where('data_type', 'category');

            if (is_array($categoryId)) {
                $getCategory->whereIn('parent_id', $categoryId);
            } else {
                $getCategory->where('parent_id', $categoryId);
            }

            $getCategory = $getCategory->get();

            if ($getCategory != null) {


                $getCategory = collect($getCategory)->map(function ($item) {
                    return (array)$item;
                })->toArray();


                return $getCategory;

            }

            return false;
        });
    }

    /**
     * Check if category has products in stock.
     *
     * @param mixed $categoryId
     *
     * @return boolean
     */
    public function hasProductsInStock($categoryId)
    {
        if($this->getCategoryProductsInStockCount($categoryId) > 0){
            return true;
        }


    }


    public function getCategoryItemsCountAll()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {

            $categoryItemsCountGroupedByRelType = [];
            $categoryItemsCountData = $this->getCategoryItemsCountQueryBuilder()
                ->get();

            if ($categoryItemsCountData) {
                foreach ($categoryItemsCountData as $key => $value) {
                    $categoryItemsCountGroupedByRelType[$value->parent_id] = $value->count;
                }
            }
            return $categoryItemsCountGroupedByRelType;
        });

    }

    public function getCategoryItemsInStockCountAll()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {

            $categoryItemsCountGroupedByRelType = [];
            $query = $this->getCategoryItemsCountQueryBuilder();
            $query->whereIn('categories_items.rel_id',
                Product::select(['content.id'])
                    ->filter(['inStock' => 1])
                    ->select(['content.id'])
            );

            $categoryItemsCountData = $query->get();
            if ($categoryItemsCountData) {
                foreach ($categoryItemsCountData as $key => $value) {
                    $categoryItemsCountGroupedByRelType[$value->parent_id] = $value->count;
                }
            }
            return $categoryItemsCountGroupedByRelType;
        });

    }

    public function getCategoryContentItemsCount($categoryId)
    {
        $categoryItemsCountGroupedByRelType = $this->getCategoryItemsCountAll();

        if (isset($categoryItemsCountGroupedByRelType) and isset($categoryItemsCountGroupedByRelType[$categoryId])) {
            return $categoryItemsCountGroupedByRelType[$categoryId];
        }

        return 0;
    }

    public function getCategoryProductsInStockCount($categoryId)
    {
        $categoryItemsCountGroupedByRelType = $this->getCategoryItemsInStockCountAll();

        if (isset($categoryItemsCountGroupedByRelType) and isset($categoryItemsCountGroupedByRelType[$categoryId])) {
            return $categoryItemsCountGroupedByRelType[$categoryId];
        }

        return 0;
     }


    private function getCategoryItemsCountQueryBuilder()
    {
        $model = (new CategoryItem())->newQuery();
        $model->rightJoin('content', function ($join) {
            $join->on('content.id', '=', 'categories_items.rel_id')
                ->where('content.is_deleted', '=', 0)
                ->where('content.is_active', '=', 1);
        })
            ->select(['categories_items.parent_id', 'categories_items.rel_type', DB::raw('count( DISTINCT `content`.`id` ) as count')])
            ->where('categories_items.rel_type', 'content')
            ->groupBy('categories_items.parent_id');


        return $model;

    }

}
