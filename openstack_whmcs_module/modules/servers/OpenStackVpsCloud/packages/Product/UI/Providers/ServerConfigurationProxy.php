<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Modals\ServerConfiguration as ServerConfigModal;

class ServerConfigurationProxy extends CrudProvider
{
    use TranslatorTrait;

    public function create()
    {
        $loadModal = new ModalLoad(new ServerConfigModal());
        $standardConfiguration = $this->formatFormDataGroup('configservers', $this->formData->toArray());

        $accessHash = $this->formData->get('accesshash', false);
        if (!$accessHash) {
            return (new Response())->setActions([
                $loadModal->withParams($standardConfiguration)
            ]);
        }

        $data = json_decode(html_entity_decode($accessHash), true) ?: [];
        $extendedConfiguration = $this->formatFormDataGroup('serverconfig', $data);

        return (new Response())
            ->setActions([
                $loadModal->withParams(array_merge($standardConfiguration, $extendedConfiguration))
            ]);
    }

    protected function formatFormDataGroup(string $groupName, array $data): array
    {
        $formData = [];
        foreach ($data as $setting => $value)
        {
            $formData[sprintf('%s[%s]', $groupName, $setting)] = $value;

            if (is_array($value))
            {
                foreach ($value as $key => $subValue)
                {
                    $formData[sprintf("%s[%s][%s]", $groupName, $setting, $key)] = $subValue;
                }
            }
        }

        return $formData;
    }
}