<?php
namespace MicroweberPackages\Page;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Scopes\PostScope;
use MicroweberPackages\Menu\Traits\HasMenuItem;

class Post extends Model
{
    use HasMenuItem;

    protected $table = 'content';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'url',
        'parent',
        'description',
        'position',
        'content',
        'content_body',
        'is_active',
        'is_home',
        'is_shop',
        'is_deleted',
        'status',
        'add_content_to_menu'
    ];

    public $translatable = ['title','description','content','content_body'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new PostScope());
    }
}
