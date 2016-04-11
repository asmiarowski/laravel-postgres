<?php

namespace Smiarowski\Postgres\Model\Traits;

use Illuminate\Database\Eloquent\Builder;
use Smiarowski\Postgres\Helper;

class PostgresJsonb
{

    /**
     * Where database json $column has exactly $value in specified $key
     * $key is either index of an array or named key of json object
     * @param Builder $query
     * @param string $column
     * @param mixed $value
     * @param string $sign
     * @return mixed
     */
    public function scopeWherePgJsonb(Builder $query, $column, $value, $sign = '=')
    {
        if (is_array($value) && $sign === '=') $sign = '@>';
        $value = json_encode($value);
        $column = Helper::nestedJsonColumn($column);

        return $query->whereRaw("$column $sign ?", [$value]);
    }

    public function scopeWherePgJsonbContain(Builder $query, $column, $value)
    {
        return $this->scopeWherePgJson($query, $column, $value, '@>');
    }


    /**
     * Check if jsonb field has key/element specified in $key
     * @param Builder $query
     * @param string $column
     * @param mixed $key
     * @return mixed
     */
    public function scopeWherePgJsonbKeyExist(Builder $query, $column, $key)
    {
        if (is_array($key)) return $this->scopeWherePgJsonbKeysExist($query, $column, $key);

        $column = Helper::nestedJsonColumn($column);
        return $query->whereRaw("$column \\? ?", [$key]);
    }

    /**
     * Get only records where key/element doesn't exist in json field
     * @param Builder $query
     * @param string $column
     * @param string|int $key
     * @return mixed
     */
    public function scopeWherePgJsonbKeyNotExist(Builder $query, $column, $key)
    {
        $column = Helper::nestedJsonColumn($column);
        return $query->whereRaw("($column->>?) is null", [$key]);
    }

    /**
     * Check if jsonb field has all or any of the keys/elements specified in $keys array
     * @param Builder $query
     * @param string $column
     * @param array $keys
     * @param string $has all|any
     * @return Builder
     */
    public function scopeWherePgJsonbKeysExist(Builder $query, $column, array $keys, $has = 'all')
    {
        $mark = '&';
        if ($has === 'any') $mark = '|';
        $keys = Helper::phpArrayToPostgresArray($keys);
        $column = Helper::nestedJsonColumn($column);

        return $query->whereRaw("$column \\?$mark ?", [$keys]);
    }
}