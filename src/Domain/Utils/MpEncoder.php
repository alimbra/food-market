<?php

namespace App\Domain\Utils;

class MpEncoder
{
    public const UTF_8 = 'UTF-8';
    public const ISO_8859_1 = 'ISO-8859-1';

    public static function utf8Encode(string $content): string
    {
        if (mb_detect_encoding($content, self::UTF_8, true)) {
            // do not encode string already in UTF-8
            return $content;
        }

        return (string) mb_convert_encoding($content, self::UTF_8, self::ISO_8859_1);
    }
}
