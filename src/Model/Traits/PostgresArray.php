<?php

namespace Smiarowski\Postgres\Model\Traits;

use Illuminate\Database\Eloquent\Builder;
use Smiarowski\Postgres\Helper;

trait PostgresArray
{

    /**
     * Mutates php array to acceptable format of postgreSQL array field
     * @param array $array
     * @return mixed
     */
    public static function mutateToPgArray(array $array)
    {
        return Helper::phpArrayToPostgresArray($array);
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
     * @param string $column
     * @param mixed $value
     * @return Builder
     */
    public function scopeWherePgArrayContains(Builder $query, $column, $value)
    {
        $value = self::mutateToPgArray((array)$value);

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
        $value = self::mutateToPgArray((array)$value);

        return $query->whereRaw("$column && ?", [$value]);
    }

}