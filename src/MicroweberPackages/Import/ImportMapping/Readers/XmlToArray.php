<?php

namespace MicroweberPackages\Import\ImportMapping\Readers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class XmlToArray implements iReader
{
    public function readXml($content)
    {
        $dom = $this->loadDom($content);

        return $this->domToArray($dom);
    }

    public function loadDom($content) {

        $previousValue = libxml_use_internal_errors(true);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->loadXml($content);

        libxml_use_internal_errors($previousValue);

        if (libxml_get_errors()) {
            return [];
        }
        return $dom;
    }

    private function domToArray($root)
    {
        $result = array();

        if (is_object($root) && $root->hasChildNodes()) {
            $children = $root->childNodes;
            if ($children->length == 1) {
                $child = $children->item(0);
                if (in_array($child->nodeType, [XML_TEXT_NODE, XML_CDATA_SECTION_NODE])) {
                    $result['_value'] = $child->nodeValue;
                    return count($result) == 1
                        ? $result['_value']
                        : $result;
                }

            }
            $groups = array();
            foreach ($children as $child) {

                if ($child->nodeName == '#comment') {
                    continue;
                }

                if (!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = $this->domToArray($child);
                } else {
                    if (!isset($groups[$child->nodeName])) {
                        $result[$child->nodeName] = array($result[$child->nodeName]);
                        $groups[$child->nodeName] = 1;
                    }
                    $result[$child->nodeName][] = $this->domToArray($child);
                }
            }
        }
        return $result;
    }

    public function getTargetTags($content)
    {
        $tags = [];
        $array = $this->readXml($content);

        if (!empty($array)) {
            foreach ($array as $key=>$value) {
                if (is_string($key)) {

                    $arrKf = array_key_first($value);

                    dd($value[$arrKf]);

                    if (!is_string($arrKf)) {
                        // no key found
                        continue;
                    }
                    if (!isset($value[$arrKf][0])) {
                        // its not itteratable
                        continue;
                    }
                    $tags[] = $key .'.'. $arrKf;
                    break;
                }
            }
        }

        return $tags;
    }
}
