<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Pages;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Models\Keypairs;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\BackupsButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\ChangePasswordButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\ChangeProtectionButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\ConsoleButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\DownloadPrivateKeyButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\DownloadPublicKeyButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\FirewallButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\HardRebootVMModalButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\PauseVMModalButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\AppTemplatesButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\RescueVMModalButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\ResumeVMModalButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\SnapshotsButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\SoftRebootVMModalButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\StartVMModalButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\StopVMModalButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\UnpauseVMButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\UnrescueVMModalButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers\ServiceActionsProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\TileButton\TileButton;
use ModulesGarden\OpenStackVpsCloud\Components\TilesBar\TilesBar;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\FormSubmit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxAutoReloadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxOnLoadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class ServiceActionsForm extends Form implements ClientAreaInterface, AdminAreaInterface, AjaxAutoReloadInterface, AjaxOnLoadInterface
{
    public const ID = "serviceActionsForm";

    protected string $provider = ServiceActionsProvider::class;
    protected string $providerAction = 'reload';

    protected $isRebuildTaskActive = false;
    protected $productConfig;
    protected $productConfiguration;

    protected function isAdmin()
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin();
    }

    public function loadHtml(): void
    {
        $this->setId(self::ID);

        $this->isRebuildTaskActive = JobManager::areCirticalBeingPerformed(Params::get('serviceid'));

        $actionTilebar = new TilesBar();
        $actionTilebar->setTitle($this->translate("actions"));

        $miscTilebar = new TilesBar();
        $miscTilebar->setTitle($this->translate("management"));

        $this->productConfig = new ProductConfiguration(Params::get('serviceid'));
        $this->productConfiguration = (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration(Params::get('packageid')))->get();

        $this->addTile($actionTilebar, $this->getStartVmButton());
        $this->addTile($actionTilebar, $this->getStopVmBUtton());
        $this->addTile($actionTilebar, $this->getSoftRebootVMButton());
        $this->addTile($actionTilebar, $this->getHardRebootVMButton());
        $this->addTile($actionTilebar, $this->getPauseVMButton());
        $this->addTile($actionTilebar, $this->getUnpauseVMButton());
        $this->addTile($actionTilebar, $this->getRescueVMButton());
        $this->addTile($actionTilebar, $this->getUnrescueVMButton());
        $this->addTile($actionTilebar, $this->getResumeVMButton());

        $this->addTile($miscTilebar, $this->getBackupsButton());
        $this->addTile($miscTilebar, $this->getChangePasswordButton());
        $this->addTile($miscTilebar, $this->getChangeProtectionButton());
        $this->addTile($miscTilebar, $this->getConsole());
        $this->addTile($miscTilebar, $this->getFirewallButton());
        $this->addTile($miscTilebar, $this->getReinstallButton());
        $this->addTile($miscTilebar, $this->getSnapshotsButton());

        $key = (new Keypairs)->byHostingId(Params::get('serviceid'))->first();
        if ($key && decrypt($key->key)) {
            $this->addTile($miscTilebar, $this->getDownloadPrivateKeyButton());
        }

        if ($key && decrypt($key->publicKey)) {
            $this->addTile($miscTilebar, $this->getDownloadPublicKeyButton());
        }

        $this->addElement($actionTilebar);
        $this->addElement($miscTilebar);
    }



    protected function getStartVmButton()
    {
        $status = $this->provider()->getValueById("status");

        $button = new StartVMModalButton();

        if ($status !== VPSModel::STATUS_SHUT_OFF) {
            $button->setDisabled(true);
        }

        return $button;
    }

    protected function getStopVmBUtton()
    {
        $button = new StopVMModalButton();

        $status = $this->provider()->getValueById("status");
        if ($status !== VPSModel::STATUS_ACTIVE) {
            $button->setDisabled(true);
        }

        if (!$this->isAdmin() || ($this->isAdmin() && $this->productConfig->getAafStop())) {
            return $button;
        }

        return null;
    }

    protected function getPauseVMButton()
    {
        $button = new PauseVMModalButton();

        $status = $this->provider()->getValueById("status");

        if ($status !== VPSModel::STATUS_ACTIVE) {
            $button->setDisabled(true);
        }

        if (!$this->isAdmin() || ($this->isAdmin() && $this->productConfig->getAafPause())) {
            return $button;
        }

        return null;
    }

    protected function getUnpauseVMButton()
    {
        $button = new UnpauseVMButton();

        $status = $this->provider()->getValueById("status");
        if ($status !== VPSModel::STATUS_PAUSED) {
            $button->setDisabled(true);
        }

        return $button;
    }

    protected function getSoftRebootVMButton()
    {
        $button = new SoftRebootVMModalButton();

        $status = $this->provider()->getValueById("status");
        if ($status !== VPSModel::STATUS_ACTIVE) {
            $button->setDisabled(true);
        }

        if (!$this->isAdmin() && $this->productConfig->getCafSoftreboot()) {
            return $button;
        }

        if ($this->isAdmin() && $this->productConfig->getAafSoftreboot()) {
            return $button;
        }

        return null;
    }

    protected function getHardRebootVMButton()
    {
        $button = new HardRebootVMModalButton();

        $status = $this->provider()->getValueById("status");
        if ($status !== VPSModel::STATUS_ACTIVE) {
            $button->setDisabled(true);
        }

        if (!$this->isAdmin() && $this->productConfig->getCafHardreboot()) {
            return $button;
        }

        if ($this->isAdmin() && $this->productConfig->getAafHardreboot()) {
            return $button;
        }

        return null;
    }

    protected function getRescueVMButton()
    {
        $button = new RescueVMModalButton();
        $status = $this->provider()->getValueById("status");
        if (!in_array($status, [VPSModel::STATUS_SHUT_OFF, VPSModel::STATUS_ACTIVE])) {
            $button->setDisabled(true);
        }

        if (!$this->isAdmin() && $this->productConfig->getCafRescue()) {
            return $button;
        }

        if ($this->isAdmin() && $this->productConfig->getAafRescue()) {
            return $button;
        }

        return null;
    }

    protected function getUnrescueVMButton()
    {
        $button = new UnrescueVMModalButton();
        $status = $this->provider()->getValueById("status");
        if ($status !== VPSModel::STATUS_RESCUE) {
            $button->setDisabled();
        }

        if (!$this->isAdmin() && $this->productConfig->getCafRescue()) {
            return $button;
        }

        if ($this->isAdmin() && $this->productConfig->getAafRescue()) {
            return $button;
        }

        return null;
    }

    protected function getChangeProtectionButton()
    {
        $button = new ChangeProtectionButton();

        if (!$this->isAdmin() && $this->productConfig->getCafProtectVm()) {
            return $button;
        }

        if ($this->isAdmin() && $this->productConfig->getAafProtectVm()) {
            return $button;
        }

        return null;
    }

    protected function getResumeVMButton()
    {
        $button = new ResumeVMModalButton();

        $status = $this->provider()->getValueById("status");
        if ($status !== VPSModel::STATUS_SUSPENDED) {
            return null;
        }

        if ($this->isAdmin()) {
            return $button;
        }

        if (!$this->isAdmin() && $this->productConfig->getCafResume()) {
            return $button;
        }

        return null;
    }

    protected function getBackupsButton()
    {
        $button = new BackupsButton();

        if (!$this->isAdmin() && $this->productConfig->getCafBackups()) {
            return $button;
        }

        return null;
    }

    protected function getConsole()
    {
        $status = $this->provider()->getValueById("status");
        $button = null;

        if ($this->isAdmin() && $this->productConfig->getAafConsole() ||
            !$this->isAdmin() && $this->productConfig->getCafConsole()) {
            $button = new ConsoleButton();
        }

        if (!$button) {
            return $button;
        }

        if (!in_array($status, [VPSModel::STATUS_ACTIVE, VPSModel::STATUS_RESCUE])) {
            return $button->setDisabled(true);
        }

        return $button->onClick((new FormSubmit($this))
            ->setCustomAction('console'));
    }

    protected function getFirewallButton()
    {
        $button = new FirewallButton();

        if (!$this->isAdmin() && $this->productConfig->getCafFirewall()) {
            return $button;
        }

        return null;
    }

    protected function getSnapshotsButton()
    {
        $button = new SnapshotsButton();

        if (!$this->isAdmin() && $this->productConfig->getCafSnapshots()) {
            return $button;
        }

        return null;
    }

    protected function getReinstallButton()
    {
        $button = new AppTemplatesButton();

        if (!$this->isAdmin() && $this->productConfig->getCafRebuild()) {
            return $button;
        }

        return null;
    }

    protected function getDownloadPrivateKeyButton()
    {
        $button = new DownloadPrivateKeyButton();

        if (!$this->isAdmin()) {
            return $button;
        }

        return null;
    }

    protected function getDownloadPublicKeyButton()
    {
        $button = new DownloadPublicKeyButton();

        if (!$this->isAdmin()) {
            return $button;
        }

        return null;
    }

    protected function getChangePasswordButton()
    {
        $button = new ChangePasswordButton();

        if (!$this->isAdmin() && $this->productConfiguration['caf_changepassword']) {
            return $button;
        }

        if ($this->isAdmin() && $this->productConfiguration['aaf_changepassword']) {
            return $button;
        }

        return null;
    }

    protected function fixStatus(TileButton $tile)
    {
        $status = $this->provider()->getValueById("status");

        if (in_array($status, [VPSModel::STATUS_REBUILD]) ||
            $this->isRebuildTaskActive) {
            $tile->setDisabled(true);
        }

        return $tile;
    }

    protected function addTile(?TilesBar &$tilesBar, ?TileButton $tile)
    {
        if (!$tile) {
            return;
        }

        $tilesBar->addElement($this->fixStatus($tile));
    }
}
