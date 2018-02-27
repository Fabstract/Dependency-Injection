<?php

namespace Fabs\Component\DependencyInjection;

use Fabs\Component\DependencyInjection\Exception\TypeConflictException;

class Assert extends \Fabs\Component\Assert\Assert
{
    /**
     * @param Definition $definition
     */
    public static function assertDefinition($definition)
    {
        Assert::assertType($definition, Definition::class, 'definition');
        Assert::assertNonNull($definition->getName(), 'definition.name');

        if (
            $definition->getClassName() === null &&
            $definition->getFactoryClassName() === null &&
            $definition->getCreator() === null &&
            (!$definition->isShared() || $definition->getInstance() === null)
        ) {
            self::throwException($definition->getName(), 'a valid definition', 'not a valid definition');
        }
    }

    /**
     * @param string $name
     * @param string $expected
     * @param string $given
     * @return TypeConflictException
     */
    protected static function generateException($name, $expected, $given)
    {
        /** @var \Exception $parent_exception */
        $parent_exception = parent::generateException($name, $expected, $given);
        return new TypeConflictException(
            $parent_exception->getMessage(),
            $parent_exception->getCode(),
            $parent_exception
        );
    }
}
