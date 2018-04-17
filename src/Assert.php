<?php

namespace Fabstract\Component\DependencyInjection;

use Fabstract\Component\DependencyInjection\Exception\TypeConflictException;

class Assert extends \Fabstract\Component\Assert\Assert
{
    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param ServiceDefinition $definition
     */
    public static function isDefinition($definition)
    {
        Assert::isType($definition, ServiceDefinition::class, 'definition');
        Assert::isNotNull($definition->getName(), 'definition.name');

        if (
            $definition->getClassName() === null &&
            $definition->getFactoryClassName() === null &&
            $definition->getCreator() === null &&
            (!$definition->isShared() || $definition->getInstance() === null)
        ) {
            /** @noinspection PhpUnhandledExceptionInspection */
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
