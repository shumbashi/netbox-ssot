<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\App;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Services\AppConfiguration;

/**
 * Class ImageBuilder
 */
class ImageBuilder extends BaseBuilder
{
    /**
     * @return mixed
     * @throws Exception
     */
    public function build()
    {
        /**
         * ID from product config saved in DB - it does not have to be appropriate for the image from the selected region
         * ID or Name from Custom Field
         */

        $appConfig = new AppConfiguration($this->params['serviceid']);
        $app = $appConfig->getApp();

        return $this->buildFromApp($app);
    }

    public function buildFromApp(?App $app)
    {
        $appConfig = $app->getConfigArray();

        $imageIdOrName = $appConfig['name'];
        if (empty($imageIdOrName)) {
            $imageIdOrName = $appConfig['UUID'];
        }

        if (empty($imageIdOrName))
        {
            throw new \Exception('Invalid image. Please ensure that either the name or UUID is provided.');
        }

        $imageIdOrName = $appConfig['name'] ?: $appConfig['UUID'];

        /**
         * $imageId is image ID appropriate for the image from selected region
         */

        $imageId = $this->getServiceIdFromSelectedRegionResources(
            $imageIdOrName,
            Servers::IMAGE,
            Servers::AVAILABLE_IMAGES,
            Api::getInstance()->image()->listImages(),
            $imageFriendlyName ?? null
        );
        return $this->tenant->image($imageId);
    }
}