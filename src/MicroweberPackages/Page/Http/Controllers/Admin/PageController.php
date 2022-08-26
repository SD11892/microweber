<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Page\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Page\Repositories\PageRepository;

class PageController extends AdminController
{
    public $repository;

    public function __construct(PageRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function index(Request $request) {
        return $this->view('page::admin.page.index');
    }

    public function create() {

        return $this->view('page::admin.page.edit', [
            'content_id'=>0
        ]);
    }

    public function edit(Request $request, $id) {

        return $this->view('page::admin.page.edit', [
            'content_id'=>intval($id)
        ]);
    }
}
