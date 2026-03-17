<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Builder;

use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Factories\CustomFieldsSectionFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\BaseFields;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\ClientFields;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\HostingFields;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\OrderFields;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\ParamsFields;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\ProductFields;

class ServiceFieldsParserBuilder extends BaseFieldsParserBuilder
{
    protected function buildServiceFields(): self
    {
        $hostingFields = (new HostingFields($this->service->id))->load();
        $this->parser->addReplacement(HostingFields::NAME, $hostingFields->getInstance());

        /*Support older versions*/
        $this->parser->addReplacement(HostingFields::LEGACY_NAME, $hostingFields->getInstance());

        return $this;
    }

    protected function buildOrderFields(): self
    {
        $hostingFields = (new OrderFields($this->service->order->id))->load();
        $this->parser->addReplacement(OrderFields::NAME, $hostingFields->getInstance());

        return $this;
    }

    protected function buildProductFields(): self
    {
        $productFields = (new ProductFields($this->service->packageid))->load();
        $this->parser->addReplacement(ProductFields::NAME, $productFields->getInstance());

        return $this;
    }

    protected function buildParamsFields(): self
    {
        $paramsFields = (new ParamsFields())->load();
        $this->parser->addReplacement(ParamsFields::NAME, $paramsFields->getInstance());

        return $this;
    }

    protected function buildClientFields(): self
    {
        $clientFields = (new ClientFields($this->service->userid))->load();
        $this->parser->addReplacement(ClientFields::NAME, $clientFields->getInstance());

        return $this;
    }

    public function buildStandardFields(): void
    {
       $this->buildClientFields()
            ->buildOrderFields()
            ->buildProductFields()
            ->buildParamsFields()
            ->buildServiceFields();
    }

    public function buildCustomFields(): self
    {
        $customSections = CustomFieldsSectionFactory::factory();
        foreach ($customSections as $section) {
            if (!$section instanceof BaseFields) {
                continue;
            }

            $section->setId($this->service->id);
            $section->load();
            $this->parser->addReplacement($section->getName(), $section->getInstance());
        }
        return $this;
    }

    public function build(): self
    {
        $this->buildStandardFields();
        $this->buildCustomFields();

        return $this;
    }
}