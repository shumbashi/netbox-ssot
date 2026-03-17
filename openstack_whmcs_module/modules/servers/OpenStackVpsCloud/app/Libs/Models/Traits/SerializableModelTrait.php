<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Models\Traits;

trait SerializableModelTrait
{
    public function jsonSerialize()
    {
        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties(
            \ReflectionProperty::IS_STATIC |
            \ReflectionProperty::IS_PUBLIC |
            \ReflectionProperty::IS_PROTECTED |
            \ReflectionProperty::IS_PRIVATE);

        $propsIterator = function() use ($props) {
            foreach ($props as $prop) {

                if (is_null($this->{$prop->getName()})) {
                    continue;
                }

                yield $prop->getName() => $this->{$prop->getName()};
            }
        };

        return iterator_to_array($propsIterator());
    }

    public static function fromArray(?array $array) {
        $reflect = new \ReflectionClass(static::class);
        $props = $reflect->getProperties(
            \ReflectionProperty::IS_STATIC |
            \ReflectionProperty::IS_PUBLIC |
            \ReflectionProperty::IS_PROTECTED |
            \ReflectionProperty::IS_PRIVATE
        );

        $model = $reflect->newInstanceWithoutConstructor();

        if (empty($array)) {
            return $model;
        }

        foreach ($props as $prop) {
            if (array_key_exists($prop->getName(), $array)) {
                $prop->setAccessible(true);
                $prop->setValue($model, $array[$prop->getName()]);
            }
        }

        return $model;
    }
}