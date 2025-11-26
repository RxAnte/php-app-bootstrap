<?php

declare(strict_types=1);

namespace RxAnte\Tests\RuntimeConfig;

enum RuntimeConfigOptions
{
    case TEST_1;
    case TEST_2;
    case TEST_3;
    case TEST_4;
    case TEST_FALSY;
    case TEST_TRUTHY;
    case TEST_FALSY_ENV;
    case TEST_TRUTHY_ENV;
    case TEST_INT;
    case TEST_INT_ENV;
}
