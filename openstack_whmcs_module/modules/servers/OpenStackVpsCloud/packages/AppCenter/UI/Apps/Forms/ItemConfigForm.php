<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputPassword\FormInputPassword;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Components\Tagger\Tagger;
use ModulesGarden\OpenStackVpsCloud\Components\TextArea\TextArea;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Reload;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\FieldFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\AppConfigItem;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories\AppConfigItemFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers\ItemConfigProvider;

class ItemConfigForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = ItemConfigProvider::class;

    protected function buildFieldsInContainer()
    {
        $this->provider()->read();
        $protected = $this->provider()->getValueById('protected') ?: false;

        $this->builder->addField((new FormInputText())
            ->setName('setting')
            ->required()
            ->setReadOnly($protected), true);

        $this->builder->addField((new Dropdown())->setName('field')->required()
            ->setOptions([
                TextArea::class => $this->translate('text_area'),
                FormInputText::class => $this->translate('text'),
                FormInputPassword::class => $this->translate('password'),
                Dropdown::class => $this->translate('dropdown'),
                Switcher::class => $this->translate('switcher')
            ])
            ->setReadOnly($this->provider()->getValueById('protected') ?: false)
            ->setDefaultValueAsFirstOption()
            ->onChange(new Reload($this)), true);

        $this->addValueFieldWithBuilder();

        $this->builder->addField((new Switcher())->setName('visible')->required(), true);
    }

    protected function addValueFieldWithBuilder()
    {
        $config = new AppConfigItem();
        if ($this->providerAction === CrudProvider::ACTION_UPDATE) {
            $config = AppConfigItemFactory::forItemId($this->provider()->getValueById('id'));
        }

        $field = $this->provider()->getValueById('field');
        switch ($field)
        {
            case Dropdown::class:
                $options = $this->provider()->getValueById('options');

                if ($config->getProtected()) {
                    $config->setOptions($options);
                    $config->setField(Dropdown::class);
                    break;
                }

                /*Selected options*/
                $values = $this->provider()->getValueById('options[options]');
                if (empty($values)) {
                    $values = [];
                } else {
                    $values = array_combine($values, $values);
                }

                $this->builder->addField((new Switcher())
                    ->setName('options[multiple]')
                    ->onChange(new Reload($this)));

                $this->builder->addField((new Tagger())
                    ->setName('options[options]')
                    ->setOptions($values)
                    ->setValue($values)
                    ->onChange(new Reload($this))
                    ->required());

                $options['options'] = $values;

                $config->setOptions($options);
                $config->setField(Dropdown::class);

                break;
            case FormInputText::class:
            case FormInputPassword::class:
            case TextArea::class:
            case Switcher::class:
                $config->setField($field);
                break;
            default:
                $config->setField(TextArea::class);
                break;
        }

        $validators = $this->provider()->getValueById('validator') ?: [];
        $validators = array_combine($validators, $validators);

        $field = FieldFactory::forItem($config, false)
            ->setName('value');

        $this->builder->addField($field);

        $this->builder->addField((new Tagger())->setName('validator')
            ->setOptions($validators), true);
    }
}