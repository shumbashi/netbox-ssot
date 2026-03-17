<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\Creation;


class Serializer
{
    public function toArray(bool $changeToSnakeCase = false)
    {
        $out = [];

        foreach (get_class_vars(get_called_class()) as $property => $value)
        {
            if ($property == "params")
            {
                continue;
            }

            if (!isset($this->{$property}))
            {
                continue;
            }
            if (is_object($this->$property) && method_exists($this->$property, 'toArray'))
            {
                $propertyName       = $changeToSnakeCase ? strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property)) : $property;
                $out[$propertyName] = $this->{$property}->toArray();
            }
            else
            {
                $propertyName       = $changeToSnakeCase ? strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property)) : $property;
                $out[$propertyName] = $this->{$property};
            }
        }
        return $out;
    }
}