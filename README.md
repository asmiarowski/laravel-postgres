# laravel-postgres

Adds support for postgreSQL fields for Eloquent.

<h3>Installation</h3>
```
composer require asmiarowski/laravel-postgres
```

<h3>Array field</h3>
<p>Add trait to your model:</p>
```
use Illuminate\Database\Eloquent\Model;
use Smiarowski\Postgres\Model\Traits\PostgresArray;

class ExampleModel extends Model
{
    use PostgresArray;
}
```
<p>Set up accessor and mutator for your array field like so:</p>
```
public function setArrayField(array $value)
{
    $this->array_field = self::mutateToPgArray($value);
}
public function getArrayField()
{
    return self::accessPgArray($this->array_field);
}
```
<p>Query scopes available for builder:</p>
<p><b>wherePgArrayContains(string $column, mixed $value)</b>: Adds where query part, $column has all of the elements in $value. $value can be array, integer or string</p>
<p><b>wherePgArrayOverlap(string $column, mixed $value)</b>: Adds where query part, $column has any (at least one) of the elements in $value. $value can be array, integer or string</p>

<p>For example, let's say you have an array of strings as tags for restaurants. If you would want to find all restaurants that serve pizza or lasagne, you would build your query like so:</p>
```
$restaurants = Restaurant::wherePgArrayOverlap('tag', ['pizza', 'lasagne'])->get();
```
<p>Above example would return only thoes restaurants that have tags pizza <b>or</b> lasagne in their defined tags field. If you would want only restaurants that have all of the tags specified, you would use <b>wherePgArrayContains</b> instead.</p>
