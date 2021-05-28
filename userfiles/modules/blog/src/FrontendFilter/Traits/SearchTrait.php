<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait SearchTrait {

    public function search($template = false)
    {
        $fullUrl = URL::current();

        $searchUri = $this->queryParams;
        $searchUri['search'] = '';
        $searchUri = $fullUrl . '?'. http_build_query($searchUri);

        $search = $this->getRequest()->get('search', false);

        $moduleId = $this->params['moduleId'];

        return view($template, compact('searchUri', 'search', 'moduleId'));
    }
}
