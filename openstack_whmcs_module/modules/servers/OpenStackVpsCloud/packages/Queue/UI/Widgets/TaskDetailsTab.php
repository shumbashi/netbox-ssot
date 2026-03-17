<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\ListInfo\ListInfo;
use ModulesGarden\OpenStackVpsCloud\Components\ListInfo\ListInfoItem;
use ModulesGarden\OpenStackVpsCloud\Components\Tab\Tab;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\JobNameTranslator;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\RelatedItemLink as RelatedItemsFormatter;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Others\JobTypeLabel;

class TaskDetailsTab extends Tab implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $jobModel = new Job();

        $jobsFormatter         = new JobNameTranslator();
        $relatedItemsFormatter = new RelatedItemsFormatter();
        $dateFormatter         = new \ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\Date\Date();
        $dateTimeFormatter     = new \ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\Date\DateTime();

        $job = $jobModel->findOrFail(Request::get('formData')['id']);

        $listInfo = new ListInfo();
        $listInfo->addItem(new ListInfoItem($this->translate('id'), $job->id));
        $listInfo->addItem(new ListInfoItem($this->translate('job'), $jobsFormatter->format($job->job)));
        $listInfo->addItem(new ListInfoItem($this->translate('queue'), $job->queue));
        $listInfo->addItem(new ListInfoItem($this->translate('retryCount'), $job->retry_count));

        $listInfo->addItem(new ListInfoItem($this->translate('parentId'), $job->parent_id ?: '-'));
        $listInfo->addItem(new ListInfoItem($this->translate('relType'), $job->rel_type ?: '-'));
        $listInfo->addItem(new ListInfoItem($this->translate('relatedItem'),
            !empty($job->rel_type) & !empty($job->rel_id) ? $relatedItemsFormatter->format($job->rel_type, $job->rel_id) : '-'));
        $listInfo->addItem(new ListInfoItem($this->translate('relCustom'), $job->rel_custom ?: '-'));
        $listInfo->addItem(new ListInfoItem($this->translate('status'), (new JobTypeLabel())->create($job->status)));

        $listInfo->addItem(new ListInfoItem($this->translate('retryAfter'),
            $job->isRetryAfterSet() ? $dateTimeFormatter->format($job->retry_after) : '-'));
        $listInfo->addItem(new ListInfoItem($this->translate('createdAt'), $dateFormatter->format($job->created_at)));
        $listInfo->addItem(new ListInfoItem($this->translate('updatedAt'), $dateFormatter->format($job->updated_at)));

        $this->setTitle($this->translate('details'));
        $this->addElement($listInfo);
    }
}