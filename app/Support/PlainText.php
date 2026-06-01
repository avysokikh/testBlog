<?php

declare(strict_types=1);

namespace App\Support;

final class PlainText
{
    public static function sanitize(string $value): string
    {
        $value = preg_replace('/<\s*br\s*\/?>/i', "\n", $value);
        $value = preg_replace('/<\/\s*p\s*>/i', "\n\n", $value);
        $value = strip_tags($value);
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = preg_replace("/[ \t]+\n/", "\n", $value);
        $value = preg_replace("/\n{3,}/", "\n\n", $value);

        return trim($value);
    }
}
