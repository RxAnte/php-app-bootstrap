<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Container\ContainerInterface;
use RxAnte\AppBootstrap\Dependencies\BuildContainer;

abstract class TestCase extends BaseTestCase
{
    public function getContainer(): ContainerInterface
    {
        return BuildContainer::build();
    }
}
