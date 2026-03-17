<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models;

use Illuminate\Contracts\Support\Arrayable;

class App extends Model
{
    protected ?string $name = null;
    protected ?string $type = null;
    protected ?string $description = null;
    protected ?string $image = null;
    protected ?string $status = null;
    protected ?AppGroup $group = null;
    protected ?array $config = null;

    public function setName(?string $name): App
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription(?string $description): App
    {
        $this->description = $description;
        return $this;
    }

    public function setImage(?string $image): App
    {
        $this->image = $image;
        return $this;
    }

    public function setType(?string $type): App
    {
        $this->type = $type;
        return $this;
    }

    public function setConfig(?array $config): App
    {
        $this->config = $config;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getConfig(): ?array
    {
        return $this->config;
    }

    public function getConfigArray(): array
    {
        $keyValue = [];
        foreach ($this->getConfig() ?: [] as $config)
        {
            $keyValue[$config->getSetting()] = $config->getValue();
        }

        return $keyValue;
    }

    public function setStatus(?string $status): App
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setGroup(?AppGroup $group): App
    {
        $this->group = $group;
        return $this;
    }

    public function getGroup(): ?AppGroup
    {
        return $this->group;
    }


    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'image' => $this->image,
            'status' => $this->status,
            'config' => $this->config,
        ];
    }

}