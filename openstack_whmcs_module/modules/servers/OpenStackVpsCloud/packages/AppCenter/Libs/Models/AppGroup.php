<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models;

use Illuminate\Contracts\Support\Arrayable;

class AppGroup extends Model
{
    protected ?string $name = null;
    protected ?string $description = null;

    public function setName(?string $name): AppGroup
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription(?string $description): AppGroup
    {
        $this->description = $description;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}