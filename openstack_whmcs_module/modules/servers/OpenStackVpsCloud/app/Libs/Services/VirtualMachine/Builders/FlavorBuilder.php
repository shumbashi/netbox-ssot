<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;

use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Api\OpenStackVPS\ComputeApiService;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Decorators\FlavorDecorator;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\FlavorModel;
use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;
use ModulesGarden\OpenStackVpsCloud\App\Models\Whmcs\HostingConfigOptions;

/**
 * Class FlavorBuilder
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders
 */
class FlavorBuilder extends BaseBuilder
{

    const FLAVOR = 'flavor';

    /**
     * @var string
     */
    protected $flavorID;

    /**
     * @return FlavorModel|null
     * @throws OSException
     * @throws Exception
     */
    public function build()
    {
        /**
         * ID from product config saved in DB - it does not have to be appropriate for the flavor from the selected region
         * ID or Name from Custom Field
         */
        $flavorIdOrName = $this->productConfig->getFlavor();

        if ($this->params['configoptions']['flavor'])
        {
            $flavorIdOrName = $this->params['configoptions']['flavor'];

            $flavorFriendlyName = (new HostingConfigOptions())->getConfigurableOptionName($this->params['configoptions']['flavor'], $this->params['serviceid']);
            $flavorFriendlyName = explode('|', $flavorFriendlyName)[1];
        }

        /**
         * $this->flavorId is flavor ID appropriate for the flavor from selected region
         */
        $this->flavorID = $this->getServiceIdFromSelectedRegionResources(
            $flavorIdOrName,
            self::FLAVOR,
            Servers::AVAILABLE_FLAVORS,
            Api::getInstance()->compute()->listFlavors(),
            $flavorFriendlyName ?? null);

        /**
         * In case change package action, 'existingFlavor' variable will be exist. This is a flavor that has been used until now.
         */
        $existingFlavor = $this->getExistingFlavor();

        /**
         * Flavor that is created based on the current configuration.
         */
        $newFlavor = $this->tenant->flavor($this->flavorID);

        if ($this->shouldSetPrivateFlavor($existingFlavor))
        {
            $newFlavor = $this->buildPrivateFlavor($newFlavor, $existingFlavor);
            return $newFlavor;
        }

        return $newFlavor;
    }

    /**
     * @return FlavorModel|null
     * @throws OSException
     * @throws \OSException
     */
    protected function getExistingFlavor()
    {
        if (!$this->productConfig->getCustomFields()['vmID'])
        {
            return null;
        }

        $vm = $this->tenant->VPS($this->productConfig->getCustomFields()['vmID']);

        return $vm->getFlavor();
    }

    /**
     * @param FlavorModel|null $existingFlavor
     * @return bool
     */
    protected function shouldSetPrivateFlavor(FlavorModel $existingFlavor = null)
    {
        if ($existingFlavor && $this->params['customfields']['privateFlavor'])
        {
            return true;
        }

        if (!$existingFlavor && (($this->productConfig->getVcpus() ||
                                  $this->productConfig->getRam()) ||
                                 ($this->productConfig->getUseVolumes() == 0 && $this->productConfig->getDisk())))
        {
            return true;
        }

        return false;
    }

    /**
     * @param FlavorModel $newFlavor
     * @param FlavorModel|null $existingFlavor
     * @return FlavorModel|null
     * @throws Exception
     */
    protected function buildPrivateFlavor(FlavorModel $newFlavor, FlavorModel $existingFlavor = null)
    {
        if (!$existingFlavor)
        {
            $newFlavor = clone $this->tenant->flavor($this->flavorID);
        }

        $newFlavor->setName(FlavorDecorator::nameDecorator($this->params['domain']));
        $newFlavor->setVcpus($this->productConfig->getVcpus() ?: null);
        $newFlavor->setDisk($this->productConfig->getDisk() ?: 0);
        $newFlavor->setRam($this->productConfig->getRam() ?: null);

        if ($existingFlavor && $this->isFlavorsIdentical($newFlavor, $existingFlavor))
        {
            return null;
        }

        $newFlavor->setPublic(false);
        $newFlavor->create();
        $this->createExtraSpecs($newFlavor, $this->productConfig->getFlavorSpecification());
        $this->productCustomFields->updateFieldValue('privateFlavor', $newFlavor->getUUID());

        return $existingFlavor ? $newFlavor : clone $newFlavor;
    }

    /**
     * @param FlavorModel $newFlavor
     * @param FlavorModel|null $existingFlavor
     * @return bool
     */
    protected function isFlavorsIdentical(FlavorModel $newFlavor, FlavorModel $existingFlavor)
    {
        if ($newFlavor->getVcpus() != $existingFlavor->getVcpus() ||
            $newFlavor->getDisk() != $existingFlavor->getDisk() ||
            $newFlavor->getRam() != $existingFlavor->getRam() ||
            $newFlavor->getRxtxFactor() != $existingFlavor->getRxtxFactor())
        {
            return false;
        }

        return true;
    }

    /**
     * @param FlavorModel $newFlavor
     * @param string $flavorSpecification
     * @throws Exception
     */
    protected function createExtraSpecs(FlavorModel $newFlavor, string $flavorSpecification)
    {
        if(!$flavorSpecification || empty($flavorSpecification))
        {
            return;
        }

        $specsArray = $this->convertExtraSpecs($flavorSpecification);

        $newFlavor->setExtraSpecs($specsArray);
    }

    /**
     * @param string $flavorSpecification
     * @return array
     */
    protected function convertExtraSpecs(string $flavorSpecification)
    {
        $specsArray = [];
        foreach(explode(PHP_EOL, $flavorSpecification) as $spec)
        {
            if(strpos($spec, '=') === false)
            {
                continue;
            }

            $data = explode('=', $spec, 2);
            if(empty($data))
            {
                continue;
            }

            $specsArray[$data[0]] = trim($data[1]);
        }

        return $specsArray;
    }
}
