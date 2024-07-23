<?php

namespace Tests;

use Genl\BroadcastTesting\CanTestBroadcasting;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CanTestBroadcasting;
}
