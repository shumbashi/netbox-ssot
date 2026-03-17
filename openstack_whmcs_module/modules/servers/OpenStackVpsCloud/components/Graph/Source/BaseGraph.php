<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Source;

use ModulesGarden\OpenStackVpsCloud\Components\Form\AbstractForm;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Models\DataSet;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Models\Options;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Options\SubTitleOptions;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Options\TitleOptions;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Series\ExtendedSeries;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Toolbar\Toolbar;
use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\AjaxTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ComponentsContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TitleTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxOnLoadInterface;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Constants\Formatters;

abstract class BaseGraph extends AbstractComponent implements AjaxOnLoadInterface
{
    use AjaxTrait;
    use TitleTrait;
    use ComponentsContainerTrait;

    public const COMPONENT = 'Graph';

    protected Options $options;

    public function __construct()
    {
        parent::__construct();

        $this->options = new Options();
    }

    /**
     * @deprecated - use addSeries instead
     */
    public function addDataSet(DataSet $dataSet)
    {
        $series = new ExtendedSeries($dataSet->getLabel(), $dataSet->getData());

        if ($color = $dataSet->toArray()['borderColor'])
        {
            $series->setColor($color);
        }

        $this->addSeries($series);

        return $this;
    }

    public function loadHtml(): void
    {
        $this->buildToolbar();
    }

    /**
     * Override to create custom toolbar
     * @return void
     */
    protected function buildToolbar()
    {
        if ($form = $this->defineEditOption())
        {
            $toolbar = new Toolbar();
            $toolbar->setForm($form);

            $this->addElement($toolbar);
        }
    }

    /**
     * Override to enable edit option for the graphs
     * @return AbstractForm|null
     */
    protected function defineEditOption(): ?AbstractForm
    {
        return null;
    }

    protected function optionsSlotBuilderJson()
    {
        return $this->options->toArray();
    }

    protected function ajaxOnLoadSlotBuilder(): ?bool
    {
        return empty($this->options->series);
    }

    /**
     * @param $labels
     * @return $this
     */
    public function setLabels(array $labels = [])
    {
        $this->options->xAxis->categories = $labels;

        return $this;
    }

    public function getOptions(): Options
    {
        return $this->options;
    }

    public function setOptions(Options $options)
    {
        $this->options = $options;
    }

    public function setType(string $type):self
    {
        $this->options->chart->type = $type;

        return $this;
    }

    public function configureAsSparkline():self
    {
        $this->options->chart->addAdditionalParameter('height', 140);
        $this->options->chart->addAdditionalParameter('sparkline', ['enabled' => true]);
        $this->options->tooltip->addAdditionalParameter('fixed', ['enabled' => false]);
        $this->options->tooltip->addAdditionalParameter('y', ['title' => ["formatter" =>  "function (seriesName) { return '' }"]]);
        $this->options->tooltip->addAdditionalParameter('marker', ['show' => false]);

        $this->options->title->addAdditionalParameter('style', ['fontSize' => "16px"]);
        $this->options->title->addAdditionalParameter('offsetX', -10);
        $this->options->subTitle->addAdditionalParameter('style', ['fontSize' => "12px"]);
        $this->options->subTitle->addAdditionalParameter('offsetX', -10);
        $this->options->subTitle->addAdditionalParameter('offsetY', 20);
        $this->options->stroke->addAdditionalParameter('width', "1");

        $this->options->yAxis->show = false;
        $this->options->legend->show = false;

        return $this;
    }

    public function configureAsInline():self
    {
        $this->setSlot('isInline', true);

        $this->options->title = new TitleOptions();
        $this->options->subTitle = new SubTitleOptions();
        $this->options->tooltip->addAdditionalParameter('y', ['title' => ['formatter' => "function (seriesName) { return '';}"]]);
        $this->options->chart->addAdditionalParameter('height', "35px");
        $this->options->chart->addAdditionalParameter('width', "150px");

        return $this;
    }

    public function limitDisplayedXAxisLabels(int $ticksAmount):self
    {
        $this->getOptions()->xAxis->addAdditionalParameter('tickAmount',  $ticksAmount);

        return $this;
    }

    public function limitDisplayedYAxisLabels(int $ticksAmount):self
    {
        $this->getOptions()->yAxis->addAdditionalParameter('tickAmount',  $ticksAmount);

        return $this;
    }

    public function showOnlyIntegersOnXAxis():self
    {
        $this->options->xAxis->addAdditionalParameter('labels', ["formatter" => Formatters::SHOW_ONLY_INTEGERS]);

        return $this;
    }

    public function showOnlyIntegersOnYAxis():self
    {
        $this->options->yAxis->addAdditionalParameter('labels', ["formatter" => Formatters::SHOW_ONLY_INTEGERS]);

        return $this;
    }
}
