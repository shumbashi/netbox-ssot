<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models;

use Illuminate\Contracts\Support\Arrayable;

class Model implements Arrayable
{
    public function fill(array $data)
    {
        foreach ($data as $key => $value) {
            $setter = "set" . ucfirst($key);
            if (property_exists($this, $key) && method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }

        return $this;
    }

    public function fillMissing(array $data)
    {
        foreach ($data as $key => $value) {
            $setter = "set" . ucfirst($key);
            if (property_exists($this, $key) && method_exists($this, $setter) && $value !== null && !isset($this->$key)) {
                $this->$setter($value);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        throw new \BadMethodCallException('Not implemented');
    }
}
