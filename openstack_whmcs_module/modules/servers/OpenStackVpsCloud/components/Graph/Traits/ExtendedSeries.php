<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Traits;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Models\DataSet;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Series\ExtendedSeries as Series;

trait ExtendedSeries
{
    /**
     * @deprecated - use addSeries instead
     */
    public function addDataSet(DataSet $dataSet)
    {
        $series = new Series($dataSet->getLabel(), $dataSet->getData());

        if ($color = $dataSet->toArray()['borderColor'])
        {
            $series->setColor($color);
        }

        $this->addSeries($series);

        return $this;
    }

    public function addSeries(Series $series):self
    {
        $this->options->addSeries($series);

        return $this;
    }
}