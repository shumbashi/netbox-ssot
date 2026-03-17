<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields;

use Illuminate\Database\Capsule\Manager as DB;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Source\FieldsSectionInterface;
use WHMCS\Database\Capsule;

class BaseFields implements FieldsSectionInterface
{
    protected $model;
    protected $instance = null;
    protected array $modelColumns = [];
    protected array $columns = [];

    protected int $id = 0;

    const ACCESS_OBJECT = 'object';
    const ACCESS_ARRAY = 'array';

    public function __construct($id = 0)
    {
        $this->id = is_null($id) ? 0 : $id;
    }

    public function loadColumns(): self
    {
        $columns = DB::select('SELECT DISTINCT COLUMN_NAME as columnName FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?', [(new $this->model)->getTable(), Capsule::connection()->getDatabaseName()]);
        foreach ($columns as $column) {
            $this->modelColumns[] = $column->columnName;
            $this->columns[] = ['name' => $column->columnName, 'access' => self::ACCESS_OBJECT];
        }

        return $this;
    }

    public function loadValues(): self
    {
        if (!$this->id) {
            return $this;
        }

        if (!$this->model) {
            return $this;
        }

        $this->instance = $this->model::select($this->modelColumns)->where('id', $this->id)->first();
        if ($this->instance) {
            $this->instance = $this->instance->toArray();
        }

        return $this;
    }

    public function load():self
    {
        try
        {
            $this->loadColumns();
            $this->loadValues();

            return $this;
        }
        catch (\Exception $e)
        {
            return $this;
        }
    }

    public function getModelColumns(): array
    {
        return $this->modelColumns;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return null
     */
    public function getInstance()
    {
        return $this->instance;
    }
}

