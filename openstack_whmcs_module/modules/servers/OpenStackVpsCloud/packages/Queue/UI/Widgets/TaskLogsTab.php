<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\Tab\Tab;
use ModulesGarden\OpenStackVpsCloud\Components\TableSimple\TableSimple;
use ModulesGarden\OpenStackVpsCloud\Components\Text\Text;
use ModulesGarden\OpenStackVpsCloud\Components\Text\TextNoWrap;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\JobLog;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Others\LogTypeLabel;

class TaskLogsTab extends Tab implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $taskId = Request::get('formData')['id'];
        $logs   = JobLog::select('id', 'type', 'message', 'updated_at')
            ->where('job_id', $taskId)
            ->orderBy('updated_at', "desc")
            ->get();

        if ($logs->count())
        {
            $element = new TableSimple();
            $element->setTextCentered();

            $logsParsed = $logs->toArray();

            foreach ($logsParsed as &$value)
            {
                $value['id'] = (new TextNoWrap())->setText($value['id']);
                $value['updated_at'] = (new TextNoWrap())->setText($value['updated_at']);
                $value['type'] = (new LogTypeLabel())->create($value['type']);
            }

            //@TODO
            //Status as HTML
            $element->setRecords($logsParsed);
        }
        else
        {
            $element = new Text();
            $element->setText($this->translate("noLogsInfo"));
            $element->setSlot('style', "display: block; text-align: center");
        }

        $this->addElement($element);
        $this->setTitle($this->translate('logs'));
    }
}