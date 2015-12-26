<?php

namespace Smiarowski\Postgres\Model\Traits;

use Illuminate\Database\Query\Builder;

trait PostgresArray
{

    /**
     * Mutates php array to acceptable format of postgreSQL array field
     * @param array $array
     * @return mixed
     */
    public static function mutateToPgArray(array $array)
    {
        $array = self::removeKeys($array);
        $array = json_encode($array);

        return str_replace('[', '{', str_replace(']', '}', $array));
    }

    /**
     * Changes postgreSQL array field returned from PDO to php array
     * @param string $value
     * @return array
     */
    public static function accessPgArray($value)
    {
        $value = str_replace('{', '[', str_replace('}', ']', $value));

        return json_decode($value);
    }

    /**
     * Where database array $column has all of the elements in $value
     * @param Builder $query
     * @param $column
     * @param $value
     * @return Builder
     */
    public function scopeWherePgArrayContains(Builder $query, $column, $value)
    {
        $value = self::mutateToPgArray((array) $value);

        return $query->whereRaw("$column @> ?", [$value]);
    }

    /**
     * Where database array $column has any of the elements in $value
     * @param Builder $query
     * @param string $column
     * @param mixed $value
     * @return Builder
     */
    public function scopeWherePgArrayOverlap(Builder $query, $column, $value)
    {
        $value = self::mutateToPgArray((array) $value);

        return $query->whereRaw("$column && ?", [$value]);
    }

    /**
     * Remove named keys from arrays
     * @param array $array
     * @return array
     */
    private static function removeKeys(array $array)
    {
        $array = array_values($array);
        foreach ($array as &$value)
        {
            if (is_array($value)) $value = static::removeKeys($value);
        }

        return $array;
    }


}