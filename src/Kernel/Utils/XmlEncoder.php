<?php


namespace Kernel\Utils;


class XmlEncoder
{
    public static function encode(array $input)
    {
        $encoded = [];
        $encoded[] = '<?xml version="1.0" encoding="utf-8"?>';
        $encoded[] = self::encodeItems($input);

        return implode("\n", $encoded);
    }

    private static function encodeItems(array $input): string
    {
        $items = [];

        foreach ($input as $name => $content) {
            $items[] = self::getItem($name, $content);
        }

        return implode("\n", $items);
    }

    private static function getItem($name, $content): string
    {
        if (!is_array($content)) {
            return "<{$name}>\n\t{$content}\n</{$name}>";
        }

        $attributes = '';
        if (isset($content['attributes'])) {
            $attributes = self::getAttributes($content['attributes']);
        }

        $value = '';
        if (isset($content['content'])) {
            $value = self::encodeItems($content['content']);
            $value = "\n{$value}\n";
        }
        return "<{$name} $attributes>{$value}</{$name}>";
    }

    private static function getAttributes(array $attributes): string
    {
        $attributes = array_map(function ($key, $value) {
            return "{$key}=\"{$value}\"";
        }, array_keys($attributes), $attributes);

        return implode(' ', $attributes);
    }
}