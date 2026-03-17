<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components;

use ModulesGarden\OpenStackVpsCloud\Components\Form\AbstractForm;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\FormSubmit;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalFormSubmit;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalOpen;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Redirect;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Reload;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadById;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadParent;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ComponentInterface;

/**
 * @todo to na razie jest tylko wersja robocza, przerobić później na pod obiekty (factory)
 */
class Action
{
    public static function modalLoad($modal): ModalLoad
    {
        return new ModalLoad($modal);
    }

    public static function modalOpen($modal): ModalOpen
    {
        return new ModalOpen($modal);
    }

    public static function redirect(string $url): Redirect
    {
        return new Redirect($url);
    }

    public static function reload(ComponentInterface $element): Reload
    {
        return new Reload($element);
    }

    public static function reloadById($componentId): ReloadById
    {
        return new ReloadById($componentId);
    }

    public static function reloadParent(): ReloadParent
    {
        return new ReloadParent();
    }
}
