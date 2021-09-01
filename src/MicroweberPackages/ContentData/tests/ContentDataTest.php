<?php

namespace MicroweberPackages\ContentData\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Product\Models\Product;

class ContentDataTest extends TestCase
{
    public function testContentDataOnNewProduct()
    {
        $newProduct3 = new Product();
        $newProduct3->title = 'my-second-new-product-zero-for-filter-test-' . uniqid();
        $newProduct3->content_type = 'product';
        $newProduct3->subtype = 'product';
        $newProduct3->setCustomField(
            [
                'type'=>'price',
                'name'=>'price',
                'value'=>'0',
            ]
        );

        $newProduct3->setContentData(
            [
                'qty'=>'1',
                'sku'=>'skubidu',
                'rich'=>'bobi'
            ]
        );
        $newProduct3->save();

        $product = Product::find($newProduct3->id);
        $contentData = $product->getContentData();

        $this->assertEquals('bobi',$contentData['rich']);
        $this->assertEquals('1',$contentData['qty']);
        $this->assertEquals('skubidu',$contentData['sku']);
    }

    public function testContentData()
    {
        $product = new Product();
        $product->title = 'Test product with content data';
        $product->save();

        $prod_id = $product->id;
        $product = Product::find($prod_id);
        $product->setContentData(['phone' => 'nokia', 'sku' => 5]);
        $product->save();

        $content_data  = content_data($prod_id);
        $this->assertEquals($content_data['sku'],   5);
        $this->assertEquals($content_data['phone'],   'nokia');


        $product = Product::find($prod_id);
        $contentData = $product->getContentData(['phone', 'sku']);

        $this->assertEquals($contentData['phone'], 'nokia');
        $this->assertEquals($contentData['sku'], '5');

        $product = Product::find($prod_id);
        $product->deleteContentData(['phone']);
        $product->save();


        $contentData = $product->getContentData(['phone', 'sku']);

        $this->assertFalse(isset($contentData['phone']));
        $this->assertEquals($contentData['sku'], '5');



        $product = Product::whereContentData(['sku' => '5'])->first();
        $this->assertEquals($product['title'],   'Test product with content data');



    }
}
