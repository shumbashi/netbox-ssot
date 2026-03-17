<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\PasswordGenerator;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\Translators\ActionValidatorTranslator;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\Traits\ApiTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;

class RescueProvider extends CrudProvider
{
    use ApiTrait;
    const ACTION_RESCUE = 'rescue';
    const ACTION_UNRESCUE = 'unrescue';

    public function read()
    {
        $this->data['password'] = (new PasswordGenerator())
            ->generate();
    }

    public function rescue()
    {
        try {
            if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
                return (new Response())
                    ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
            }

            $productConfig = (new ProductConfiguration(Params::get('packageid')))
                ->get();

            $this->api->compute()->rescueVPS(Params::get('customfields.vmID'), $this->formData['password'], $productConfig['rescue_image_ref']);

            return (new Response())
                ->setSuccess($this->translate('VmRescueSuccessfully'))
                ->setActions([new ModalClose()]);

        } catch (\Exception $exc) {
            return (new Response())->setError($exc->getMessage());
        }
    }

    public function unrescue()
    {
        try {
            if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
                return (new Response())
                    ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
            }

            $this->api->compute()->unrescueVPS(Params::get('customfields.vmID'));

            return (new Response())->setSuccess($this->translate('VmUnrescueSuccessfully'))
                ->setActions([new ModalClose()]);

        } catch (\Exception $exc) {
            return (new Response())->setError($exc->getMessage());
        }
    }
}