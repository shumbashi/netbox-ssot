### Struktura
```php
<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\DataTable\Pages;

use ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\DataTable\Buttons\UserCreate;
use ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\DataTable\Buttons\UserDelete;
use ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\DataTable\Buttons\UserEdit;
use ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\DataTable\Switchers\TaxExempt;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCreate;
use ModulesGarden\OpenStackVpsCloud\Components\FormLabel\FormLabel;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Widget\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use WHMCS\User\Client;

class DataTable extends \ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable implements AdminArea, AjaxElementInterface
{
    public function loadHtml() : void
    {
        $this
            ->addColumn((new Column('id'))
                ->setOrderable(DataProvider::SORT_DESC)
                ->setSearchable(true, Column::TYPE_INT))
            ->addColumn((new Column('firstname'))
                ->setOrderable(true)
                ->setSearchable(true))
            ->addColumn((new Column('taxexempt')));

        $this->addActionButton(new  UserEdit());
        $this->addActionButton(new  UserDelete());
        $this->addToToolbar(new UserCreate());
    }

    public function loadData()
    {
        $clients = Client::select('id', 'firstname', 'lastname', 'taxexempt');

        $dataProv = new QueryDataProvider($clients);
        $dataProv->setDefaultSorting('id', 'DESC');
        $this->setDataProvider($dataProv);
    }

    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('taxexempt', function ($fieldName, $row, $fieldValue) {
            $label = new FormLabel();
            $label->addElement((new TaxExempt('XXX'))->setValue($fieldValue));

            return $label;
        });

        $this->dataSet->modifyRecords();
    }
}
```

### Struktura tabeli 
```php
        $this
            ->addColumn((new Column('id'))
                ->setOrderable(DataProvider::SORT_DESC)
                ->setSearchable(true, Column::TYPE_INT))
            ->addColumn((new Column('firstname'))
                ->setOrderable(true)
                ->setSearchable(true))
            ->addColumn((new Column('taxexempt')));
```

### Pobieranie danych
```php
    public function loadData()
    {
        $clients = Client::select('id', 'firstname', 'lastname', 'taxexempt');

        $dataProv = new QueryDataProvider($clients);
        $dataProv->setDefaultSorting('id', 'DESC');
        $this->setDataProvider($dataProv);
    }
```
W QueryDataProviderze nie ma możliwości przekazania czystego SQLa, a parametrem, który przekazujemy w konstruktorze musi być obiekt klasy Builder.

I tak, chcąc przekazać jakiś cutomowy SQL i tak trzeba go zbudować na metodach eloquenta np. selectRaw() 

```php
    public function loadData()
    {
        $rawSql = "`tblclients`.`id`, `tblclients`.`firstname`, `lastname`, `taxexempt`, `companyname`";
        $query = DB::table('tblclients')->selectRaw(DB::raw($rawSql));


        $dataProv = new QueryDataProvider($query);
        $dataProv->setDefaultSorting('id', 'DESC');
        $this->setDataProvider($dataProv);
    }
```

Dodatkowo subquery można przekazywać w ten sposób

```php
$reserves = Commodity::select(
        'commodities.*',
        'bins.reserved'
    )->leftJoin(
        DB::raw("
            (select
                `bins`.`commodity_id`,
                sum(`bins`.`capacity`) as `reserved`
            from `bins`
            where `bins`.`location_id` = ?
            group by `bins`.`commodity_id`) `bins`
        "), 'commodities.id', '=', 'bins.commodity_id'
    )
    ->addBinding($locationId, 'select')
    ->get();
```

### Akcje wiersza
```php
        $this->addActionButton(new  UserEdit());
        $this->addActionButton(new  UserDelete());
```

### Masowe akcje 

### Toolbar (dodatkowe przyciski)
```php
        $this->addToToolbar(new UserCreate());
```

### Modyfikacja wybranych danych
```php
    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('taxexempt', function ($fieldName, $row, $fieldValue) {
            $label = new FormLabel();
            $label->addElement((new TaxExempt('XXX'))->setValue($fieldValue));

            return $label;
        });

        $this->dataSet->modifyRecords();
    }
```