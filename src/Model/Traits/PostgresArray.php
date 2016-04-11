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
        return self::pgArrayParse($value);
    }

    protected static function pgArrayParse($s, $start = 0, &$end = null)
    {
        if (empty($s) || $s[0] != '{') return null;
        $return = [];
        $string = false;
        $quote = '';
        $len = strlen($s);
        $v = '';
        for ($i = $start + 1; $i < $len; $i++) {
            $ch = $s[$i];

            if (!$string && $ch == '}') {
                if ($v !== '' || !empty($return)) {
                    $return[] = $v;
                }
                $end = $i;
                break;
            } else
                if (!$string && $ch == '{') {
                    $v = self::pgArrayParse($s, $i, $i);
                } else
                    if (!$string && $ch == ',') {
                        $return[] = $v;
                        $v = '';
                    } else
                        if (!$string && ($ch == '"' || $ch == "'")) {
                            $string = true;
                            $quote = $ch;
                        } else
                            if ($string && $ch == $quote && $s[$i - 1] == "\\") {
                                $v = substr($v, 0, -1) . $ch;
                            } else
                                if ($string && $ch == $quote && $s[$i - 1] != "\\") {
                                    $string = false;
                                } else {
                                    $v .= $ch;
                                }
        }

        foreach ($return as &$r) {
            if (is_numeric($r)) {
                if (ctype_digit($r)) $r = (int)$r;
                else $r = (float)$r;
            }
        }
        return $return;
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