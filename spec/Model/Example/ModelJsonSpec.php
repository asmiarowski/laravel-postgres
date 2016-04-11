<?php

namespace spec\Smiarowski\Postgres\Model\Example;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ModelJsonSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smiarowski\Postgres\Model\Example\ModelJson');
    }
}
