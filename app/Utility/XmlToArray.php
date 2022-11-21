<?php

namespace App\Utility;

use Illuminate\Support\Str;

class XmlToArray
{
    public static function convert(string $xml, bool $outputRoot = false, bool $flagAttributes = false, array $withoutNamespaces = [])
    {
        $array = self::xmlStringToArray($xml, $flagAttributes, $withoutNamespaces);
        if (!$outputRoot && array_key_exists('@root', $array)) {
            unset($array['@root']);
        }
        return $array;
    }

    protected static function xmlStringToArray(string $xmlstr, bool $flagAttributes, array $withoutNamespaces)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xmlstr);
        $root = $doc->documentElement;
        $output = self::domNodeToArray($root, $flagAttributes, $withoutNamespaces);
        $output['@root'] = $root->tagName;
        return $output;
    }

    protected static function domNodeToArray($node, bool $flagAttributes, array $withoutNamespaces)
    {
        $output = [];
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = self::domNodeToArray($child, $flagAttributes, $withoutNamespaces);
                    if (isset($child->tagName)) {
                        $t = $child->tagName;
                        $t = self::removeSelectedNamespaces($withoutNamespaces, $t);
                        $t = self::removeSelectedNamespaces($withoutNamespaces, $t);
                        if (!isset($output[$t])) {
                            $output[$t] = [];
                        }
                        $output[$t][] = $v;
                    } elseif ($v || $v === '0') {
                        $output = (string) $v;
                    }
                }
                if ($node->attributes->length && !is_array($output)) { // Has attributes but isn't an array
                    $output = ['@content' => $output]; // Change output into an array.
                }
                if (is_array($output)) {
                    if ($node->attributes->length) {
                        $a = [];
                        foreach ($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        if ($flagAttributes) {
                            $output['@attributes'] = $a;
                        } else {
                            $output = array_merge($output, $a);
                        }
                    }
                    foreach ($output as $t => $v) {
                        if (is_array($v) && count($v) == 1 && $t != '@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }

    /**
     * @param array $withoutNamespaces
     * @param string $tagName
     * @return string
     */
    protected static function removeSelectedNamespaces(array $withoutNamespaces, string $tagName): string
    {
        if (!empty($withoutNamespaces)) {
            $namespace = Str::beforeLast($tagName, ':');
            if (in_array($namespace, $withoutNamespaces)) {
                $tagName = Str::afterLast($tagName, ':');
            }
        }
        return $tagName;
    }
}
