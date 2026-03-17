<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Breadcrumbs;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Breadcrumbs\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Translations\Pages\TranslationsPage;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Pages\LangsTable;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Containers\AlertContainer;

class EasyTranslator extends AbstractController implements AdminAreaInterface
{
    public function index()
    {
        return Helper\view()->addElement(TranslationsPage::class);
    }

    public function editLanguage()
    {
        $language = Request::get('language');
        Lang::language($language)->firstOrFail();

        $breadCrumbs = Breadcrumbs::get();
        array_pop($breadCrumbs);

        $itemName = Translator::get('admin.breadcrumbs.EasyTranslator_editLanguage') . ": " . ucfirst($language);
        $breadCrumbs[] = new Item($itemName);

        Breadcrumbs::set($breadCrumbs);

        return Helper\view()->addElement(AlertContainer::class)->addElement(LangsTable::class);
    }
}