<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Cron;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductCustomFields;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\NetworkInterfacesManager;
use ModulesGarden\OpenStackVpsCloud\Core\CommandLine\AbstractCommand;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Facades\Schema;

class SynchronizeNetworks extends AbstractCommand
{
    protected $name = "synchronize:networks";

    protected $description = "Tool used to synchronize OpenStack network information with WHMCS";

    protected function process(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        $services = Service::where('domainstatus', 'Active')
            ->join('tblservers', 'tblhosting.server', '=', 'tblservers.id')
            ->where('tblservers.type', ModuleConstants::getModuleName())
            ->select('tblhosting.*')
            ->get();

        if ($count = $services->count()) {
            $io->success(sprintf('Found %d services. Starting synchronization...', $count));
        }
        else {
            $io->info('No services found. Exiting...');
        }

        foreach ($services as $service)
        {
            try {
                $productCustomFields = new ProductCustomFields($service->packageid, $service->id);
                $vmId = $productCustomFields->getCustomFieldsValue('vmID');

                if (empty($vmId)) {
                    $io->error(sprintf('Failed to synchronize service #%s %s due to empty vm id.', $service->id, $service->domain));
                    continue;
                }

                try {
                    $params = WhmcsParamsHelper::getWhmcsParamsByHostingId($service->id);
                    $interfacesManager = new NetworkInterfacesManager($vmId, $params);
                    $ips = $interfacesManager->getServiceIps($service->dedicatedip);

                    DB::statement("UPDATE tblhosting SET dedicatedip = ?, assignedips = ? WHERE id = ?", [
                        $ips['dedicatedip'],
                        $ips['assignedips'],
                        $service->id]);
                }
                catch (\Exception $ex){
                    $io->error(sprintf('Failed to synchronize service #%s %s: %s', $service->id, $service->domain, $ex->getMessage()));
                    continue;
                }
                $io->success(sprintf('Successfully synchronized service #%s %s', $service->id, $service->domain));
            }
            catch (\Exception $exception)
            {
                $io->error(sprintf('Failed to synchronize service #%s %s: %s', $service->id, $service->domain, $exception->getMessage()));
            }
        }

        $io->success("Synchronization finished.");
    }
}