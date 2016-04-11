<?php

namespace spec\Smiarowski\Postgres\Model\Example;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ModelArraySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Smiarowski\Postgres\Model\Example\ModelArray');
    }

    function it_sets_one_dimension_array_field_in_postgres_format()
    {
        $this->mutateToPgArray([0, 1, 2])->shouldReturn('{0,1,2}');
        $this->mutateToPgArray(['test', 'test2', 'test3'])->shouldReturn('{test,test2,test3}');
    }

    function it_sets_multi_dimension_array_field_in_postgres_format()
    {
        $this->mutateToPgArray([[0, 1], [2, 3]])->shouldReturn('{{0,1},{2,3}}');
        $this->mutateToPgArray([[['test', 'test2'], ['test3', 'test4']], [['test5', 'test6']]])
            ->shouldReturn('{{{test,test2},{test3,test4}},{{test5,test6}}}');
    }

    function it_gets_one_dimension_array_from_postgres_format()
    {
        $this->accessPgArray('{0,1,2,3}')->shouldReturn([0, 1, 2, 3]);
        $this->accessPgArray('{0.5,1.15,2.00005,3.253323}')->shouldReturn([0.5, 1.15, 2.00005, 3.253323]);
        $this->accessPgArray('{test,test2,test3}')->shouldReturn(['test', 'test2', 'test3']);
    }

    function it_gets_multi_dimension_array_from_postgres_format()
    {
        $this->accessPgArray('{{0,1},{2,3}}')->shouldReturn([[0, 1], [2, 3]]);
        $this->accessPgArray('{{{test,test2},{test3,test4}},{{test5,test6}}}')
            ->shouldReturn([[['test', 'test2'], ['test3', 'test4']], [['test5', 'test6']]]);
    }

    function it_works_with_named_array_keys()
    {
        $this->mutateToPgArray(['a' => 0, 'b' => 1, 'c' => 2])
            ->shouldReturn('{0,1,2}');
        $this->mutateToPgArray(['a' => ['x' => 0, 'y' => 1], 'b' => ['x' => 2, 'y' => 3]])
            ->shouldReturn('{{0,1},{2,3}}');
    }
}
