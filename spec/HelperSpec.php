<?php

namespace spec\Smiarowski\Postgres;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HelperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smiarowski\Postgres\Helper');
    }

    function it_should_change_array_format()
    {
        $this->phpArrayToPostgresArray(['test', 'test2', 'test3'])->shouldReturn('{test,test2,test3}');
        $this->phpArrayToPostgresArray([0, 1, 2])->shouldReturn('{0,1,2}');
    }

    function it_should_remove_keys_from_array()
    {
        $this->removeKeys(['test', 'test2', 'test3'])->shouldReturn(['test', 'test2', 'test3']);
        $this->removeKeys(['a' => 'test', 'b' => 'test2', 'c' => 'test3'])->shouldReturn(['test', 'test2', 'test3']);
        $this->removeKeys(['a' => 1, 'b' => 2, 'c' => 3])->shouldReturn([1, 2, 3]);
        $this->removeKeys([5 => 1, 6 => 2, 7 => 3])->shouldReturn([1, 2, 3]);
    }

    function it_should_format_nested_json_column_from_dot_notation()
    {
        $this->nestedJsonColumn('test.test.0')->shouldReturn("'test'->'test'->0");
        $this->nestedJsonColumn('test.test.a')->shouldReturn("'test'->'test'->'a'");
        $this->nestedJsonColumn('0.1.0')->shouldReturn("0->1->0");
    }
}
