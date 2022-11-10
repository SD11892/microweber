<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Product\Models\Product;

class ExportWizardController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        return $this->view('import_export_tool::admin.render-livewire', [
            'component'=>'import_export_tool::export_wizard'
        ]);
    }

    public function file($id)
    {
        $multilanguageEnabled = MultilanguageHelpers::multilanguageIsEnabled();

        $findExportFeed = ExportFeed::where('id', $id)->first();
        if ($findExportFeed) {
            if ($findExportFeed->export_type == 'products') {
                $getAllProducts = Product::all();
                if ($findExportFeed->export_format == 'xlsx') {
                    $firstLevelArray = [];
                    foreach ($getAllProducts as $product) {

                        $appendProduct = [];
                        $appendProduct['id'] = $product['id'];
                        $appendProduct['parent_id'] = $product['parent'];

                        if ($multilanguageEnabled) {

                            if (isset($product['multilanguage'])) {
                                foreach ($product['multilanguage'] as $locale=>$mlFields) {
                                    foreach ($mlFields as $mlFieldKey=>$mlFieldValue) {
                                        $appendProduct[$mlFieldKey.'_'.strtolower($locale)] = $mlFieldValue;
                                    }
                                }
                            }

                        } else {
                            $appendProduct['title'] = $product['title'];
                            $appendProduct['url'] = $product['url'];
                            $appendProduct['content_body'] = $product['content_body'];
                            $appendProduct['content_meta_title'] = $product['content_meta_title'];
                            $appendProduct['content_meta_keywords'] = $product['content_meta_keywords'];
                        }

                        $appendProduct['price'] = $product['price'];
                        $appendProduct['special_price'] = $product['special_price'];
                        $appendProduct['qty'] = $product['qty'];

                        $appendProduct['in_stock'] = 0;
                        if ($product['in_stock']) {
                            $appendProduct['in_stock'] = 1;
                        }

                        $appendProduct['is_active'] = $product['is_active'];

                        $firstLevelArray[] = $appendProduct;
                    }

                    dd($firstLevelArray);
                }
            }
        }
    }
}
