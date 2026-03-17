<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Configuration\Addon\Update\Patch;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductCustomFields;
use ModulesGarden\OpenStackVpsCloud\Core\Database\FileLoader;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product;
use ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Models\Commands;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Helpers\RequiredCustomFieldsGenerator;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Models\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Update\Patch\AbstractPatch;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Server;
use Illuminate\Database\Capsule\Manager as DB;

class M3M0P0 extends AbstractPatch
{
    public function execute()
    {
        try {
            $this->runSchema();
        } catch (\Exception $e) {}

        try {
            $this->runData();
        } catch (\Exception $e) {}

        $this->migrateCommands();
        $this->createCustomFields();
        $this->migrateFirewall();
        $this->migrateConfiguration();
        $this->patchServerConfiguration();
    }

    protected function migrateCommands(): void
    {
        DB::statement(sprintf('DROP TABLE IF EXISTS `%s`', (new Commands())->getTable()));
        (new FileLoader())->performQueryFromFile(ModuleConstants::getFullPath('packages', 'CommandLine', 'resources', 'database', 'schema.sql'));
    }

    protected function patchServerConfiguration(): void
    {
        $servers = Server::where('type', ModuleConstants::getModuleName())->get();
        foreach ($servers as $server) {
            if (!$server->accesshash) {
                continue;
            }

            $configuration = json_decode(html_entity_decode($server->accesshash), true);
            if (!$configuration) {
                continue;
            }

            /*Encode endpoints into base64*/
            foreach ($configuration as $key => &$value) {
                if (substr($key, -8) !== 'Endpoint') {
                    continue;
                }

                $endpoint = json_decode($value, true);
                if (!$endpoint) {
                    continue;
                }

                $endpoint['selected'] = 0;
                $value = base64_encode(json_encode($endpoint));
            }

            $server->accesshash = json_encode($configuration, JSON_PRETTY_PRINT);
            $server->save();
        }
    }

    protected function migrateConfiguration(): void
    {
        $productSettings = DB::table('OpenStackVpsCloud_ProductSettings')->get();

        foreach ($productSettings as $productSetting) {

            if (!Product::where('id', $productSetting->pid)->exists()) {
                continue;
            }

            if ($productSetting->setting === 'delayEmail') {
                $productSetting->setting = 'sendWelcomeEmail';
            }

            if ($productSetting->setting === 'rebuildEmailTemplate' && !empty($productSetting->value) ) {
                ProductConfiguration::updateOrCreate(
                    [
                        'product_id' => $productSetting->pid,
                        'setting' => 'sendRebuildEmail',
                    ],
                    ['value' => json_encode('1')]);
            }

            ProductConfiguration::updateOrCreate(
                [
                    'product_id' => $productSetting->pid,
                    'setting' => $productSetting->setting,
                ],
                ['value' => $this->migrateConfigValue($productSetting->value)]);
        }
    }

    protected function migrateConfigValue(string $value)
    {
        $decoded = json_decode($value);
        if (is_array($decoded))
        {
                if (empty($decoded))
                {
                    return json_encode([""]);
                }

                return $value;
        }

        return json_encode($value);
    }

    protected function createCustomFields()
    {
        $products = Product::where('servertype', ModuleConstants::getModuleName())
            ->select('id')
            ->get();

        foreach ($products as $product) {
            RequiredCustomFieldsGenerator::addRequiredProductCustomFields($product->id);
        }
    }

    protected function migrateFirewall()
    {
        $firewallSettings = DB::table('OpenStackVpsCloud_Settings as os')
            ->join('tblhosting as h', 'os.serviceID', '=', 'h.id')
            ->where('os.setting', 'firewall')
            ->select('os.serviceID as serviceid', 'h.packageid', 'os.value')
            ->get();

        foreach ($firewallSettings as $firewallSetting)
        {
            (new ProductCustomFields($firewallSetting->packageid, $firewallSetting->serviceid))
                ->updateFieldValue('firewallSecurityGroupID|Firewall Security Group ID', $firewallSetting->value);
        }
    }
}
