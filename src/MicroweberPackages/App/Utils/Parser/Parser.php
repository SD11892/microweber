<?php
namespace MicroweberPackages\App\Utils\Parser;

use Illuminate\View\View;
use MicroweberPackages\App\Utils\Parser\Traits\ParserHelperTrait;

final class Parser extends ParserModule
{
    use ParserHelperTrait;

    public function process($layout, $options = false, $coming_from_parent = false, $coming_from_parent_id = false, $previous_attrs = false)
    {
        echo $this->recursive_parse_modules($layout);
    }

    public function replace_url_placeholders($layout)
    {
        if (defined('TEMPLATE_URL')) {
            $replaces = array(
                '{TEMPLATE_URL}',
                '{THIS_TEMPLATE_URL}',
                '{DEFAULT_TEMPLATE_URL}',
                '%7BTEMPLATE_URL%7D',
                '%7BTHIS_TEMPLATE_URL%7D',
                '%7BDEFAULT_TEMPLATE_URL%7D',
            );

            $replaces_vals = array(
                TEMPLATE_URL,
                THIS_TEMPLATE_URL,
                DEFAULT_TEMPLATE_URL,
                TEMPLATE_URL,
                THIS_TEMPLATE_URL,
                DEFAULT_TEMPLATE_URL
            );

            $layout = str_replace_bulk($replaces, $replaces_vals, $layout);
        }

        return $layout;
    }
}
