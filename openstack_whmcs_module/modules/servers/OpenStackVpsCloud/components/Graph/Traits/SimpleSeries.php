<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Traits;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Models\DataSet;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Options\LabelsOptions;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Series\SimpleSeries as Series;

trait SimpleSeries
{
    public function addDataSet(DataSet $dataSet)
    {
        foreach ($dataSet->getData() as $element)
        {
            $this->addSeries(new Series($element));
        }

        return $this;
    }

    public function addSeries(Series $series):self
    {
        $this->options->addSeries($series);

        return $this;
    }

    public function setLabels(array $labels = []):self
    {
        $this->options->labels = new LabelsOptions($labels);

        return $this;
    }
}