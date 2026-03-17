<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\UsageWidget;

use ModulesGarden\OpenStackVpsCloud\Components\ProgressBar\ProgressBar;
use ModulesGarden\OpenStackVpsCloud\Components\ProgressBar\ProgressBarDanger;
use ModulesGarden\OpenStackVpsCloud\Components\ProgressBar\ProgressBarSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\ProgressBar\ProgressBarWarning;
use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TitleTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\HasIconTrait;

class UsageWidget extends AbstractComponent
{
    use TitleTrait;
    use HasIconTrait;

    public const COMPONENT = "UsageWidget";

    protected float $usage = 0.0;
    protected float $limit = 0.0;
    protected float $stepLow = 60;
    protected float $stepHeight = 85;

    public function __construct()
    {
        parent::__construct();

        $this->setTranslations([
            'current_usage',
            'limit_usage',
        ]);
    }

    public function setUsage(float $usage): self
    {
        $this->usage = $usage;

        return $this;
    }

    public function setLimit(float $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function setSteps(float $stepLow, float $stepHeight): self
    {
        $this->stepLow    = $stepLow;
        $this->stepHeight = $stepHeight;

        return $this;
    }

    public function setUnit(string $unit): self
    {
        $this->setSlot('unit', $unit);

        return $this;
    }

    public function disableLabels(bool $disableLabels = true): self
    {
        $this->setSlot('disableLabels', $disableLabels);

        return $this;
    }

    public function usageSlotBuilder():float
    {
        return $this->usage;
    }

    public function limitSlotBuilder():float
    {
        return $this->limit;
    }

    public function visualisationElementSlotBuilder():AbstractComponent
    {
        return $this->getProgressbarBySteps()
            ->disableLabel()
            ->setFill($this->getFillPercentValue());
    }

    protected function getProgressbarBySteps():ProgressBar
    {
        $percentageUsage = $this->getFillPercentValue();

        if ($percentageUsage < $this->stepLow)
        {
            return new ProgressBarSuccess();
        }
        elseif ($percentageUsage < $this->stepHeight)
        {
            return new ProgressBarWarning();
        }

        return new ProgressBarDanger();
    }

    protected function getFillPercentValue():float
    {
        if ($this->limit <= 0)
        {
            return 0;
        }

        return ($this->usage / $this->limit) * 100;
    }
}