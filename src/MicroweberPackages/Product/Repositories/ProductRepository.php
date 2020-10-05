<?php

namespace MicroweberPackages\Product\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;
use MicroweberPackages\Product\Events\ProductIsCreating;
use MicroweberPackages\Product\Events\ProductIsUpdating;
use MicroweberPackages\Product\Events\ProductWasCreated;
use MicroweberPackages\Product\Events\ProductWasDeleted;
use MicroweberPackages\Product\Events\ProductWasUpdated;
use MicroweberPackages\Product\Product;

class ProductRepository extends BaseRepository
{

    public function create($request)
    {
        event($event = new ProductIsCreating($request));

        $product = Product::create($request);

        event(new ProductWasCreated($request, $product));


        return $product->id;
    }

    public function update($product, $request)
    {
        event($event = new ProductIsUpdating($request, $product));

        $product->update($request);

        event(new ProductWasUpdated($request, $product));

        return $product->id;
    }


    public function destroy($product)
    {
        event(new ProductWasDeleted($product));

        return $product->delete();
    }


    public function find($id)
    {
        return Product::find($id);
    }

}
