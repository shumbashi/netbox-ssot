<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models;

use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppItemSource;

use Illuminate\Contracts\Support\Arrayable;

class AppConfigItem extends Model
{
    protected ?string $setting = null;
    protected $value = null;
    protected ?bool $visible = false;
    protected ?string $source = AppItemSource::SOURCE_LOADER;
    protected ?bool $protected = null;
    protected ?string $field = null;
    protected $loader = null;
    protected ?string $description = '';
    protected ?array $validator = null;
    protected ?array $options = null;


    public function __construct(?string $setting = null,
                                $value = null)
    {
        $this->setting = $setting;
        $this->value = $value;
    }

    public function setSetting(?string $setting): AppConfigItem
    {
        $this->setting = $setting;
        return $this;
    }

    public function getSetting(): ?string
    {
        return $this->setting;
    }

    public function setValue($value): AppConfigItem
    {
        $this->value = $value;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setLoader($loader)
    {
        $this->loader = $loader;
        return $this;
    }

    public function getLoader()
    {
        return $this->loader;
    }

    public function setSource(?string $source): AppConfigItem
    {
        $this->source = $source;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setProtected(?bool $protected = true): AppConfigItem
    {
        $this->protected = $protected;
        return $this;
    }

    public function getProtected(): ?bool
    {
        return $this->protected;
    }

    public function setDescription(?string $description): AppConfigItem
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(?string $field): AppConfigItem
    {
        $this->field = $field;
        return $this;
    }

    public function setVisible(?bool $visible): AppConfigItem
    {
        $this->visible = $visible;
        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setValidator(?array $validator): AppConfigItem
    {
        $this->validator = $validator;
        return $this;
    }

    public function getValidator(): ?array
    {
        return $this->validator;
    }

    public function setOptions(?array $options): AppConfigItem
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function toArray(): array
    {
        return [
            'setting' => $this->setting,
            'value' => $this->value,
            'source' => $this->source,
            'field' => $this->field,
            'options' => $this->options,
            'validator' => $this->validator,
            'loader' => $this->loader,
            'protected' => $this->protected,
            'description' => $this->description,
            'visible' => $this->visible
        ];
    }
}
