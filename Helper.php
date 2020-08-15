<?php

namespace Land3;

use DateTime;

class Helper
{
    static function format(string $message, array $context)
    {
        $ks = array_map(function ($k) {
            return "/{{$k}}/";
        }, array_keys($context));
        $vs = array_map(function ($v) {
            if (is_string($v)) {
                return $v;
            } else if (is_null($v)) {
                $v = "null";
            } else if (is_bool($v)) {
                $v = $v ? "true" : "false";
            } else if (is_resource($v)) {
                $v = stream_get_contents($v);
            } else if ($v instanceof DateTime) {
                $v = $v->getTimestamp();
            } else if (is_float($v) || is_int($v) || is_object($v)) {
                $v = (string)$v;
            } else if (is_array($v)) {
                $objects = [];
                foreach ($v as $objectK => $objectV) {
                    $objects[] = "$objectK => $objectV";
                }
                $v = implode(',', $objects);
            }
            return $v;
        }, array_values($context));
        return preg_replace($ks, $vs, $message);
    }
}