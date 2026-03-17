<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Builders;

class ServiceUrlBuilder
{
    protected ?string $url;

    protected ?array $data = [];
    protected ?array $regions = [];

    const URL_DATA_REGEX = '/^(http(?<secure>s?):\/\/)?(?<domain>[a-zA-Z0-9\.\-]+)(:(?<port>[0-9]+))?(\/(?<version>v[0-9\.]+))?(\/(?<tenantID>[0-9A-Za-z]{32}))?/';

    public function __construct($url)
    {
        $this->url = $url;

        preg_match(self::URL_DATA_REGEX, $url, $this->data);
    }

    public function setTenantId(?string $tenantId)
    {
        if (empty($this->data['tenantId'])) {
            return $this;
        }

        $this->url = str_replace('/' . $this->data['tenantId'], '/' . $tenantId, $this->url);

        return $this;
    }

    public function getTenantId()
    {
        return $this->data['tenantId'];
    }

    public function setRegion(?string $region)
    {
        $regions = array_diff($this->regions, [$region]);
        if (empty($regions)) {
            return $this;
        }

        $replacePattern = '/' . implode('|', array_map('preg_quote', $regions)) . '/';

        $this->url = preg_replace($replacePattern, $region, $this->url, 1);

        return $this;
    }

    public function setVersion(?string $version)
    {
        if (!isset($this->data['version'])) {
            $this->url = rtrim($this->url, '/') . '/' . $version;
        }

        return $this;
    }

    public function setRegions(?array $regions)
    {
        $this->regions = $regions;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getData(): ?array
    {
        return $this->data;
    }
}