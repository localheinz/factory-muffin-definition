<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @link https://github.com/localheinz/factory-muffin-definition
 */

namespace Localheinz\FactoryMuffin\Definition\Test\Fixture\Definition\DoesNotImplementInterface;

use League\FactoryMuffin\FactoryMuffin;
use Localheinz\FactoryMuffin\Definition\Test\Fixture\Entity;

/**
 * Is not acceptable as it does not implement the DefinitionInterface.
 */
final class UserDefinition
{
    public function accept(FactoryMuffin $factoryMuffin)
    {
        $factoryMuffin->define(Entity\User::class);
    }
}