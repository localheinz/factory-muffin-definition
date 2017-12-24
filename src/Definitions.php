<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/localheinz/factory-muffin-definition
 */

namespace Localheinz\FactoryMuffin\Definition;

use League\FactoryMuffin\FactoryMuffin;
use Localheinz\Classy;

final class Definitions
{
    /**
     * @var Definition[]
     */
    private $definitions = [];

    private function __construct()
    {
    }

    /**
     * Creates a new instance of this class, and collects all definitions found in the specified directory.
     *
     * @param string $directory
     *
     * @throws Exception\InvalidDirectory
     * @throws Exception\InvalidDefinition
     *
     * @return self
     */
    public static function in(string $directory)
    {
        if (!\is_dir($directory)) {
            throw Exception\InvalidDirectory::notDirectory($directory);
        }

        $instance = new self();

        $constructs = Classy\Constructs::fromDirectory($directory);

        foreach ($constructs as $construct) {
            $className = $construct->name();

            try {
                $reflection = new \ReflectionClass($className);
            } catch (\ReflectionException $exception) {
                continue;
            }

            if (!$reflection->isSubclassOf(Definition::class) || !$reflection->isInstantiable()) {
                continue;
            }

            try {
                $definition = $reflection->newInstance();
            } catch (\Exception $exception) {
                throw Exception\InvalidDefinition::fromClassNameAndException(
                    $className,
                    $exception
                );
            }

            $instance->definitions[] = $definition;
        }

        return $instance;
    }

    /**
     * Registers all found definitions with the specified factory muffin.
     *
     * @param FactoryMuffin $factoryMuffin
     */
    public function registerWith(FactoryMuffin $factoryMuffin)
    {
        foreach ($this->definitions as $definition) {
            $definition->accept($factoryMuffin);
        }
    }
}
