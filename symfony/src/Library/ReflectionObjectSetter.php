<?php

namespace App\Library;

class ReflectionObjectSetter
{
    /**
     * @param object $object
     * @param array $parameters
     */
    public function fillObject($object, $parameters = [])
    {
        $reflectionObject = new \ReflectionObject($object);
        $properties = $reflectionObject->getProperties();
        $propertiesAssigned = 0;
        foreach ($properties as $property) {
            $name = $property->getName();
            if (array_key_exists($name, $parameters)) {
                if (!$property->isPublic()) {
                    $property->setAccessible(true);
                }
                $property->setValue($object, $parameters[$name]);
                if (!$property->isPublic()) {
                    $property->setAccessible(false);
                }
                $propertiesAssigned++;
            }
        }
        if (count($parameters) !== $propertiesAssigned) {
            $message = sprintf(
                'Unassigned %d properties from %d.',
                (count($parameters) - $propertiesAssigned),
                $propertiesAssigned
            );
            throw new \InvalidArgumentException($message);
        }
    }
}
