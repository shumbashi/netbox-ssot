<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\CopyTextInline\CopyTextInline;
use ModulesGarden\OpenStackVpsCloud\Components\TreeListContainer\TreeListContainer;
use ModulesGarden\OpenStackVpsCloud\Components\TreeListItem\TreeListItem;
use ModulesGarden\OpenStackVpsCloud\Components\TreeListSubItem\TreeListSubItem;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Factories\CustomFieldsSectionFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\BaseFields;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\ClientFields;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\HostingFields;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\OrderFields;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\ParamsFields;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields\ProductFields;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemConfig;

class ReplacementFieldsTreeList extends TreeListContainer
{
    public function loadHtml(): void
    {
        $this->addBaseFields();
        $this->addCustomFields();
    }

    protected function addBaseFields(): void
    {
        $orderFields = $this->replacementListItem("order", OrderFields::class);
        $productFields = $this->replacementListItem("product", ProductFields::class);
        $hostingFields = $this->replacementListItem("service", HostingFields::class);
        $clientFields = $this->replacementListItem("client", ClientFields::class);
        $paramsFields = $this->replacementListItem("params", ParamsFields::class);
        $configFields = $this->configReplacementListItem();

        $this->addElement($clientFields)
            ->addElement($hostingFields)
            ->addElement($orderFields)
            ->addElement($paramsFields)
            ->addElement($productFields)
            ->addElement($configFields);
    }

    protected function addCustomFields(): void
    {
        $customFieldSections = CustomFieldsSectionFactory::factory();
        foreach ($customFieldSections as $section) {
            $this->addElement($this->replacementListItem($section->getName(), $section::class));
        }
    }

    protected function replacementListItem(string $title, string $fields): TreeListItem
    {
        $treeItem = new TreeListItem();
        $treeItem->setTitle($this->translate($title));

        $replacementFields = (new $fields)->loadColumns();

        $columns = $replacementFields->getColumns();
        usort($columns, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        foreach ($columns as $field) {

            $text = '';
            switch ($field['access']) {
                case BaseFields::ACCESS_OBJECT:
                    $text = sprintf('{$%s.%s}', $title, $field['name']);
                    break;

                case BaseFields::ACCESS_ARRAY:
                    $text = sprintf('{$%s[\'%s\']}', $title, $field['name']);
                    break;
            }

            $treeItem->addElement((new TreeListSubItem())
                ->addElement((new CopyTextInline())
                    ->hideIcon()
                    ->setTargetFocused()
                    ->setText($text))
            );
        }

        return $treeItem;
    }

    protected function configReplacementListItem(): TreeListItem
    {
        $treeItem = new TreeListItem();
        $treeItem->setTitle($this->translate('config'));

        $itemId = Request::get('id', 0);
        $formData = Request::get('formData', []);

        $columns = ItemConfig::select('setting')
            ->where('item_id', $itemId)
            ->pluck('setting')
            ->toArray();

        usort($columns, function ($a, $b) {
            return strcmp($a, $b);
        });

        foreach ($columns as $column) {
            if (isset($formData['setting']) && $formData['setting'] == $column) {
                continue;
            }

            $treeItem->addElement((new TreeListSubItem())
                ->addElement((new CopyTextInline())
                    ->hideIcon()
                    ->setTargetFocused()
                    ->setText(sprintf('{$config.%s}', $column)))
            );
        }

        return $treeItem;
    }
}