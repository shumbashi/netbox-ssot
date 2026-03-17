## Dostęp do konfiguracji serwisu 

Paczka udostępnia kilka klas ułatwiających dostęp do paramsów i konfiguracji produktu.

Factory:

```php
ConfigurationFactory::fromProduct(1);
ConfigurationFactory::fromService(75);
ConfigurationFactory::fromParams(['someParams' => 123]);
```
Zwracają one obiekt `ConfigurationContainer` , który jest kontenerem na paramsy i który ma metody:

```php
$configurationContainer = ConfigurationFactory::fromService(75);

$configurationContainer->getCustomField('customFieldName');
$configurationContainer->setCustomField('customFieldName', 555);
$configurationContainer->getConfigurableOption('coName');
$configurationContainer->getProductSetting('productSettingName');
$configurationContainer->get('someSetting'); //szuka najpierw w CustomFieldach potem Co a na końcu w Produkcie
$configurationContainer->getConfig('settingName'); //zwraca CO albo product setting albo je sumuje
$configurationContainer->pluck('XXX', 'YYY','ZZZ'); // działa jak get tylko zwraca array z wartościami dla XXX,YYY,ZZZ
```

Dodatkowo mamy do dyspozycji fasadę `Configuration` która jest powiązana z serwisem Configuration a on jest singletoem - nie trzeba ładować configa tylko jest on od razu dostępny

Użycie fasady:
```php
Configuration::getConfiguration()->getCustomField("text")
```


## Sidebars
Sidebary definiujemy w configu paczki - products.php pod kluczem `sidebars`

Możemy zdefiniować wiele sidebarów, a ich kolejność zależy od kolejności definicji w tym configu.

Nazwą itemka sidebara jest przetłumaczony klucz, pod którym został zerejestrowany. Tradycyjnie zawiera on jeszcze url i order

URL itemu może być zarówno stringiem na sztywno:

```php
'productDetails'      => [
                'order' => 1,
                'uri'   => URL\Client::productDetails(Request::get('id', 0));
            ],
```
Ale też funkcją:

```php
'productDetails'      => [
                'order' => 1,
                'uri'   => function() {
                    return URL\Client::productDetails(Request::get('id', 0));
                }
            ],
```

Przykład definicji sidebara:

```php
<?php

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\URL;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\AbstractConfigurableOption;

return [
    'sidebars'                  => [
        'someTestSidebar' => [
            'productDetails'      => [
                'order' => 1,
                'uri'   => function() {
                    return URL\Client::productDetails(Request::get('id', 0));
                }
            ],
            'productDetailsModop' => [
                'order' => 2,
                'uri'   => function() {
                    URL\Client::productDetails(Request::get('id', 0), ['modop' => 'custom']);
                }
            ],
            'tickets'             => [
                'order' => 3,
                'uri'   => 'supporttickets.php'
            ],
            'backup'              => [
                'order' => 4,
                'uri'   => function() {
                    URL\Client::productDetails(Request::get('id', 0), [
                        'modop'   => 'custom',
                        'a'       => 'management',
                        'mg-page' => 'backup',
                    ]);
                }
            ],
        ]
    ],
];
```

#### Użycie 

Sidebar z paczki product to tylko Fasada budująca komponent Sidebar i sama z siebie nie dodaje nic do WHMCSa. Dodawanie trzeba odpalić w wybranym miejscu, np. na hooku `ClientAreaPrimarySidebar`

Przykład: 

```php
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Facades\Sidebar;

$hookManager->register(
    function($sidebarWhmcs) {

        if (!class_exists(Sidebar::class)) {
            return;
        }

        $sidebars = Sidebar::getAll();

        foreach ($sidebars as $sidebar)
        {
            $newPanel = [
                'label' => $sidebar->getName(),
                'order' => 1
            ];

            $childPanel = $sidebarWhmcs->addChild($sidebar->getName(), $newPanel);

            foreach ($sidebar->getItems() as $sidebarItem)
            {
                $newItem = [
                    'label'   => $sidebarItem->getName(),
                    'uri'     => $sidebarItem->getUrl(),
                    'order'   => $sidebarItem->getOrder(),
                    "current" => $sidebarItem->isActive()
                ];
                $childPanel->addChild($sidebarItem->getName(), $newItem);
            }
        }
    },
    100
);
```

## Rozszerzona konfiguracja serwera

Rozszerzona konfiguracja pozwala na rozbudowanie WHMCS-owej konfiguracji serwera o dodatkowy przycisk, który otwiera nasz formularz. Przydaje się to w sytuacjach, gdy pola dostarczane przez WHMCS nie są wystarczające. Dane z rozszerzonej konfiguracji są przechowywane w formie JSON-a w accesshashu serwera.

Konfigurację zaczynamy od zmian w pliku ```app/Config/product.php```. Pod kluczem ```ServerConfigForm``` wskazujemy formularz, który będzie zawierał nasze pola.

Przykład:

```php
'ServerConfigForm' => 
\ModulesGarden\NAZWA_TWOJEGO_MODULU\App\UI\Actions\ServerConfig\Forms\ServerConfigForm::class
```

### Przykładowa implementacja formularza `ServerConfigForm`.
**Provider tego formularza można z powodzeniem nadpisać i dodać własne akcje.**

```php 
use ModulesGarden\NAZWA_TWOJEGO_MODULU\Packages\Product\UI\Forms\ServerConfiguration;

class ServerConfigForm extends ServerConfiguration implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setId('server_config_form');

        $this->builder = BuilderCreator::twoColumns($this);

        /* Nasze pola rozszerzonej konfiguracji, te dane będą zapisane jako extended configuration. */
        $this->builder->createField(FormInputText::class, 'serverconfig[tenantId]');
        $this->builder->createField(FormInputText::class, 'serverconfig[domain]');
        $this->builder->createField(FormInputText::class, 'serverconfig[certificate]');
        $this->builder->createField(FormInputText::class, 'serverconfig[projectName]');

        /* Grupa configservers, przechowuje dane z konfiguracji servera w WHMCSie. 
         * Nie musimy tworzyć tych fieldów jeśli nie będą nam potrzebne w providerze. 
         */
        $this->builder->createField(HiddenField::class, 'configservers[hostname]');
        $this->builder->createField(HiddenField::class, 'configservers[ipaddress]');
        $this->builder->createField(HiddenField::class, 'configservers[password]');
        $this->builder->createField(HiddenField::class, 'configservers[username]');
        $this->builder->createField(HiddenField::class, 'configservers[secure]');

        //...
    }
}
```

### Efekt

![Nagranie_z_ekranu_2025-06-13_o_12.28.40](uploads/b48e31f80e078d8c074c25c3f0016daf/Nagranie_z_ekranu_2025-06-13_o_12.28.40.gif)

### Dostęp do konfiguracji:

#### **Ważne:**
Nazwy pól rozszerzonej konfiguracji należy poprzedzić prefixem **'extended'**. Np:

```
'extended.<nazwa_pola>'
'extended.tenantId'
```

Natomiast parametry z WHMCSowej konfiguracji serwera możemy dostać bezpośrednio.

```
'serverusername'
'serverpassword'
'serverhostname'
'serverip'
//itp
```

#### **Przykłady**

Factory:  
```php
ServerConfigurationFactory::fromServiceId(int $serviceId): Container
ServerConfigurationFactory::fromServerId(int $serverId): Container
ServerConfigurationFactory::fromParams(array $params): Container

$serverConfig = ServerConfigurationFactory::fromServiceId(63);
$serverConfig->get('extended.tenantId'); 
$serverConfig->get('serverusername');
```

Facade:
```php
ServerConfiguration::get('extended.tenantId');
ServerConfiguration::get('serverusername');
```