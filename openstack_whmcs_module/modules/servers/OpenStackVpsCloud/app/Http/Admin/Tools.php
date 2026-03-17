<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Breadcrumbs;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Breadcrumbs\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Http\Admin\EasyTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Http\Admin\Logs;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Http\Admin\MediaLibrary;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Http\Admin\Queue;

class Tools extends AbstractController implements AdminAreaInterface
{
    use TranslatorTrait;

    public function index()
    {
        return $this->queue();
    }

    public function logs()
    {
        Breadcrumbs::clear();
        Breadcrumbs::add($this->getToolsBreadCrumb());
        Breadcrumbs::add(new Item($this->translate('logs')));
        return (new Logs())->index();
    }

    public function easyTranslator()
    {
        Breadcrumbs::clear();
        Breadcrumbs::add($this->getToolsBreadCrumb());
        Breadcrumbs::add(new Item($this->translate('easyTranslator')));
        return (new EasyTranslator())->index();
    }

    public function editLanguage()
    {
        Breadcrumbs::clear();
        Breadcrumbs::add($this->getToolsBreadCrumb());
        Breadcrumbs::add(new Item($this->translate('easyTranslator_editLanguage')));
        Breadcrumbs::add(new Item($this->translate('breadcrumbsToDelete')));
        return (new EasyTranslator())->editLanguage();
    }

    public function mediaLibrary()
    {
        Breadcrumbs::clear();
        Breadcrumbs::add($this->getToolsBreadCrumb());
        Breadcrumbs::add(new Item($this->translate('mediaLibrary')));
        return (new MediaLibrary())->index();
    }

    public function queue()
    {
        Breadcrumbs::clear();
        Breadcrumbs::add($this->getToolsBreadCrumb());
        Breadcrumbs::add(new Item($this->translate('queue')));
        return (new Queue())->index();
    }

    private function getToolsBreadCrumb(): Item
    {
        $url = BuildUrl::getUrl("Tools", "index");
        return new Item($this->translate('Tools'), $url);
    }
}