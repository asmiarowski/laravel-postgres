<?php

namespace Smiarowski\Postgres\Model\Example;

use Illuminate\Database\Eloquent\Model;
use Smiarowski\Postgres\Model\Traits\PostgresArray;

class ModelArray extends Model
{
    use PostgresArray;

    /**
     * Mutator for array field, sets up postgres format for array field
     * @param array $value
     */
    public function setArrayField(array $value)
    {
        $this->array_field = self::mutateToPgArray($value);
    }

    /**
     * Accessor for postgres array field, creates php array from postgres array
     * @return array
     */
    public function getArrayField()
    {
        return self::accessPgArray($this->array_field);
    }
}
