<?php
/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */

namespace SqlProcessing\Utils;

class Strings
{
    public static function startsWith($haystack, $needle)
    {
        return (substr($haystack, 0, strlen($needle)) === $needle);
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return false;
        }

        return (substr($haystack, -$length) === $needle);
    }
}