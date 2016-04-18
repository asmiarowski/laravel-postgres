<?php
namespace Smiarowski\Postgres;

class Helper
{
    /**
     * Changes PHP array to PostgreSQL array format
     * @param array $array
     * @return string
     */
    public static function phpArrayToPostgresArray(array $array)
    {
        $array = self::removeKeys($array);
        $array = json_encode($array, JSON_UNESCAPED_UNICODE);

        return str_replace('[', '{', str_replace(']', '}', str_replace('"', '', $array)));
    }

    /**
     * Remove named keys from arrays
     * @param array $array
     * @return array
     */
    public static function removeKeys(array $array)
    {
        $array = array_values($array);
        foreach ($array as &$value) {
            if (is_array($value)) $value = static::removeKeys($value);
        }

        return $array;
    }

    /**
     * Dot separation for nested elements in json field
     * @param string $column
     * @return string
     */
    public static function nestedJsonColumn($column)
    {
        $parts = explode('.', $column);
        foreach ($parts as &$part) {
            if (!ctype_digit($part)) $part = sprintf("'%s'", $part);
        }
        return implode('->', $parts);
    }
}
