<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Models\Keypairs;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Decorators\SshKeyFileDecorator;
use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Exceptions\PageNotFound;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Exceptions\UserException;
use ModulesGarden\OpenStackVpsCloud\Core\Http\BinaryFileResponse;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Authentication\CurrentUser;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;

use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class KeyDownloadProvider extends CrudProvider
{
    public function read()
    {
        parent::read();

        $serviceId = (int)Request::get('id', 0);

        $exists = Service::where('userid', (new CurrentUser)->client()->id)
        ->where('domainstatus', 'Active')
        ->where('id', $serviceId)
        ->exists();

        if (!$exists) {
            throw new PageNotFound();
        }

        $keyPair = (new Keypairs())
            ->where('hid', $serviceId)
            ->first();

        $key = '';
        $fileName = '';

        switch ($this->formData['type'])
        {
            case Keypairs::KEY_PUBLIC:
                $fileName = SshKeyFileDecorator::decoratePublicKeyName($serviceId);
                $key = decrypt($keyPair->publicKey);
                break;

            case Keypairs::KEY_PRIVATE:
                $fileName = SshKeyFileDecorator::decoratePrivateKeyName($serviceId);
                $key = decrypt($keyPair->key);
                break;
        }

        $filePath = sprintf('/tmp/%s', $fileName);
        $wrote = file_put_contents($filePath, $key);

        if ($wrote === false) {
            Logger::critical(LoggerMessages::UNABLE_TO_STORE_FILE,
                ['path' => $filePath]);

            throw new UserException($filePath);
        }

        if ($this->formData['type'] == Keypairs::KEY_PRIVATE &&
            (new ProductConfiguration($serviceId))->getDeleteKeypair())
        {
            $keyPair->update(['key' => encrypt('')]);
        }

        return (new BinaryFileResponse($filePath))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName)
            ->deleteFileAfterSend(true);
    }
}