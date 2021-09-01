<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */
namespace MicroweberPackages\Multilanguage\TranslateTables;

class TranslateContent extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'content';

    protected $columns = [
        'title',
        'url',
        'description',
        'content',
        'content_body',
        'content_meta_title',
        'content_meta_keywords'
    ];

    protected $repositoryClass = MicroweberPackages\Content\Repositories\ContentRepository::class;
    protected $repositoryMethods = [
        'getById',
        'getByParams'
    ];
}
