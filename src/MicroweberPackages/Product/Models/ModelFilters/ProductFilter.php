<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace MicroweberPackages\Product\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByContentData;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByKeywordTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByPriceTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByTitleTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByUrlTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByQtyTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\OrderByTrait;

class ProductFilter extends ModelFilter
{
    use OrderByTrait;
    use FilterByTitleTrait;
    use FilterByQtyTrait;
    use FilterByUrlTrait;
    use FilterByPriceTrait;
    use FilterByKeywordTrait;
    use FilterByContentData;

}