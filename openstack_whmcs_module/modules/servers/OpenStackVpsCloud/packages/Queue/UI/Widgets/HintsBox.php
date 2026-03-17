<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Widgets;

use ModulesGarden\OpenStackVpsCloud\App\Http\Actions\MetaData;
use ModulesGarden\OpenStackVpsCloud\Components\Hint\Hint;
use ModulesGarden\OpenStackVpsCloud\Components\HintsBox\HintsBox as HintsBosComponent;
use ModulesGarden\OpenStackVpsCloud\Components\PreBlock\PreBlock;
use ModulesGarden\OpenStackVpsCloud\Components\Text\Text;
use ModulesGarden\OpenStackVpsCloud\Components\TextParagraph\TextParagraph;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\ConfigSettings;

class HintsBox extends HintsBosComponent
{
    public function loadHtml(): void
    {
        $cronInfo = new Hint();

        $cronInfo->setTitle($this->translate('cronJobInfoHintTitle'));

        $cronInfo->addElement((new TextParagraph())
            ->setText($this->translate('cronJobInfoHintExecuteCycle', [
                "moduleName"    => Arr::get((new MetaData())->execute(), 'DisplayName', ModuleConstants::getModuleName()),
                "minutesCount"  => Config::get(ConfigSettings::CRON_MINUTES_CYCLE, 5)
            ])));

        $cronInfo->addElement((new PreBlock())->setContent(sprintf("php -q %s queue", ModuleConstants::getFullPath('cron', 'cron.php'))));

        $cronInfo->addElement((new Text())->setText($this->translate('cronJobInfoHintAdditionalInfo')));

        $this->addHint($cronInfo);
    }
}