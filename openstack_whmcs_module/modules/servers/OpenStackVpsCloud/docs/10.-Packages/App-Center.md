# App Center
### Konfiguracja paczki

Tworzymy plik ```app/Config/appCenter.php``` w którym zdefiniujemy typy aplikacji. 


```php
return [
    'Apps' => [
        \ModulesGarden\VultrVps\App\Libs\AppCenter\Apps\AppTemplate\AppTemplate::class,
    ],
];

```

### Aplikacje

Przykładowa klasa definiująca typ aplikacji może wyglądać w ten sposób:

```php
class SampleApp extends BaseApp
{
    //fetch or generate applications of SampleApp type
    public function load(): array
    {
        $apps = [];

        $app = (new \ModulesGarden\ModuleName\Packages\AppCenter\Libs\Models\App())
                ->setName("Sample app name")
                ->setStatus(AppStatus::STATUS_ACTIVE)
                ->setConfig([
                    new ProtectedAppConfigItem('id', 1234),
                    new AppConfigItem('name', 'this is sample parameter called name'),
                ]);

        $apps[] = $app;
        
        return $apps;
    }

    //install from app center
    public function install(Container $formData, array $params, App $app)
    {
        return true;
    }

    public function getDefaultConfig(): array
    {
        return [
            (new ProtectedAppConfigItem())
                ->setSetting('id')
                ->setValue(0)
                ->setField(FormInputText::class)
                ->setDescription("Please provide user data here"),
            (new ProtectedAppConfigItem())
                ->setSetting('user_data')
                ->setValue('')
                ->setField(TextArea::class)
                ->setDescription("Please provide user data here")
        ];
    }
}
```

#### Dodawanie aplikacji

Metoda ```load()``` odpowiada za automatyczne generowanie aplikacji zdefiniowanego przez nas typu. Np. pobierając z API listę dostępnych do instalacji systemów operacyjnych.

Tworząc nową aplikację tworzymy obiekt modelu App

```php
$app = (new \ModulesGarden\ModuleName\Packages\AppCenter\Libs\Models\App())
```

Używając ```->setConfig``` ustawiamy pola konfiguracyjne aplikacji, są ich dwa typy:

 - ProtectedAppConfigItem co oznacza że pole jest absolutnie kluczowe przy instalacji aplikacji i admin z GUI nie może go usunąć.

oraz

 - AppConfigItem który definiuje nam zwykłe nieobowiązkowe pole konfiguracyjne.

Na każdym z nich oprócz nazwy i początkowej wartości możemy używając setterów ustawić od razu kilka innych atrybutów:
 - ```setField()``` - typ pola/komponentu wyświetlanego klientowi/adminowi.

 - ```setVisible()``` - widoczność pola dla klienta podczas instalacji.

 - ```setDescription()``` - opis dla admina pojawiający się w konfiguracji apki prez admina w addonie.

 - ```setStatus()``` ```AppStatus::STATUS_ACTIVE / STATUS_DISABLED``` - globalny status aplikacji, w przypadku STATUS_DISABLED aplikacja nie pojawi się klientowi nawet po dodaniu do grupy.

 - ```setSource()``` - źródło aplikacji, to znaczy czy została wygenerowana przez nas czy admin sam ją dodał. Domyślnie ustawione jest ```AppItemSource::SOURCE_LOADER``` .

 - ```setValidator``` - walidacja poprawności danych wprowdzonych przez klienta. Tutaj używamy [validatorów z laravela](https://laravel.com/docs/11.x/validation#available-validation-rules) podając je jako tablice stringów.
 
#### Instalacja aplikacji

Metoda ```install(Container $formData, array $params, App $app)``` odpowiada za instalację aplikacji przez klienta z App Center. Jak widać mamy tu trzy parametry:

- container ```$formData``` który jest niczym innym jak fromData z formy instalacji apki,
- tablice ```$params``` gdzie znajdują się WHMCS Params
- obiekt ```$app``` który jest modelem instalowanej aplikacji, posiada on metodę ```getConfigArray()``` która zwraca nam konfigurację apki w formie tablicy asocjacyjnej ```['nazwa_pola' => 'wartosc']```

Możemy z tej metody zwrócić ```true``` co oznacza że instalacja przebiegła pomyślnie, ```false``` błędnie, lub ```...\Core\Components\Response\Response``` z własnym komunikatem, lub akcją.

Instalacja aplikacji w akcji CreateAccount nie powinna przebiegać z użyciem tej metody, trzeba to zrobić samemu, konfigurację aplikacji możemy uzyskać w ten sposób:

```php

use ModulesGarden\ModuleName\Packages\AppCenter\Services\AppConfiguration;

$app = (new AppConfiguration(Params::get('serviceid')))->getApp();

$app->getConfigArray();

```

#### Domyślne pola.

Zostaje jeszcze metoda ```getDefaultConfig``` która zwraca domyślną konfigurację aplikacji, zwrócone w ten sposób config itemy zostaną dodane do każdej apki o ile już nie zostały dodane w metodzie load.

Możemy tutaj również ustawić loader pola który umożliwia nam dokładne dostosowanie typu pola lub wygenerowanie do niego danych.

Przykład:

```php
(new ProtectedAppConfigItem())
                ->setSetting('user_data')
                ->setValue('')
                ->setField(TextArea::class)
                ->setLoader(function ($item, $field) { 
                     (new TextArea())->setPlaceholder($item->getValue());
                 })
                ->setDescription("Please provide user data here");
```

W tym przypadku ```$item``` jest instancją naszego ProtectedAppConfigItem, a ```$field``` typu pola czyli TextArea. Loader uruchamiany jest przy każdym wyświetleniu pola.

**UWAGA: Nie można użyć settera ->setLoader w metodzie load().**

#### Ładowanie aplikacji

W celu załadowania aplikacji i wykonania metody `load()` przechodzimy do zakładki ```App Templates / Applications``` w addonie, powinna tam znajdować się zakładka z nazwą typu aplikacji oraz button do odświeżenia listy: 

![scrn2](uploads/266f5d9c8b0e6e4960d72e57033de7fb/scrn2.png)

Po kliknięciu na button pojawi się modal wywołujący `load()`.

![scrn3](uploads/748f5edbd00375746a1becf0348f6f59/scrn3.png)

### Integracja konfiguracji produktu.

Aby możliwe było ustawienie domyślnej aplikacji oraz grupy dla produktu, należy dodać do jego konfiguracji w module `AppConfigurationWidget` z paczki.

```php
use ModulesGarden\ModuleName\Packages\AppCenter\UI\ProductConfiguration\Pages\AppConfigurationWidget;

$this->addElement((new Row())->addElement((new AppConfigurationWidget())));
```

#### Efekt:

![image](uploads/b871109714f5740d519f892107fc24d8/image.png)

### Integracja App Center w CA.

Aby zintegrować AppCenter z CA dodajemy do kontrolera
```ModulesGarden\ModuleName\Packages\AppCenter\UI\Install\Widgets\ItemsTabsWidget```

```php
use ModulesGarden\ModuleName\Packages\AppCenter\UI\Install\Widgets\ItemsTabsWidget;
use function ModulesGarden\ModuleName\Core\Helper\view;

class AppCenter extends AbstractClientController implements ClientAreaInterface
{
    public function index()
    {
        return view()->addElement(ItemsTabsWidget::class);
    }
}
```

### Related Fields

W polach konfiguracyjnych aplikacji można używać składni Smarty – paczka dostarcza własne, gotowe zmienne:

![image](uploads/821bea24a08e44607c8daf28bc82a889/image.png)  

- ```Client Related Fields``` - pola związane z modelem użytkownika (`tblclients`)
- ```Service Related Fields``` - pola związane z modelem serwisu (`tblhosting`)
- ```Order Related Fields``` - pola związane z modelem zamówienia serwisu (`tblorders`)
- ```Params Related Fields``` - parametry serwisu przekazywane przez WHMCS
- ```Product Related Fields``` - pola związane z modelem produktu (`tblproducts`)

- `Config Related Field` – zawiera dodatkowe pola konfiguracyjne aplikacji, zdefiniowane w metodzie load() lub przez App Templates w addonie. Te Related Fieldy są szczególnie przydatne, gdy chcemy umożliwić klientowi modyfikację części zawartości innego pola.

#### Przykład: Chcemy użyć w user_data nazwy użytkownika i hasła podanego przez klienta w formularzu instalacyjnym:

Tworzymy dwa pola:

![image](uploads/30840bd3991cb1a364d6f336160c5cfc/image.png)  
![image](uploads/aefd8ae59463ed78db90ef921c36071f/image.png)

**UWAGA: Jeżeli pole zostanie wyświetlone klientowi, wtedy wszystko, co zostanie znalezione w jego zawartości przez wyrażenie regularne `/\{[^}]*\}/` zostanie usunięte, aby zapobiec SSTI.**

W configu user_data użyjemy stworzonych pól:

![Zrzut_ekranu_2025-06-16_o_13.22.19](uploads/b692a2f60255e35361772629fd4f0210/Zrzut_ekranu_2025-06-16_o_13.22.19.png)  

Klient przy instalacji zobaczy wyświetlone przez nas pola:

![image](uploads/14588ef068674ef030db0ac557440f6e/image.png)

Zwrócona z ```$app->getConfigArray()``` tablica z konfiguracją będzie wyglądać w ten sposób:

```
array(11) {
  ["user_data"]=>
  string(183) "#!/bin/bash

# Create user
useradd -m -s /bin/bash username

# Set password
echo &quot;username:password1234&quot; | chpasswd

# Add to sudo group (optional)
usermod -aG sudo username"
  ["password"]=>
  string(12) "password1234"
  ["username2"]=>
  string(8) "username"
}
```

Dostaliśmy gotową konfigurację z podanymi przez usera danymi.

### Custom Related Fields

Jeżeli zachodzi taka potrzeba możemy dodać własne related fields, tworzymy klasę która będzie definiować nasze pola.

```php
use ModulesGarden\NAZWA_TWOJEGO_MODULU\Packages\AppCenter\Libs\ReplacementFields\Fields\BaseFields;
use ModulesGarden\NAZWA_TWOJEGO_MODULU\Packages\AppCenter\Libs\ReplacementFields\Source\CustomFieldsSectionInterface;

class VmFieldsSection extends BaseFields implements CustomFieldsSectionInterface
{
    const NAME = 'vm';
    public function getName(): string
    {
        return self::NAME;
    }

    public function loadColumns(): self
    {
        $this->columns = [
            ['name' => 'sample_vm_var', 'access' => self::ACCESS_OBJECT]
        ];
       
        /* self::ACCESS_OBJECT -> wyświetlaj replacement field w tree list jako obiekt z atrybutem ({$vm.sample_vm_var})
         * self::ACCESS_ARRAY -> wyświetlaj replacement field w tree list tablice z kluczem ({$vm["sample_vm_var"]})
         */

        return $this;
    }

    /*Wykonywane w momencie budowania AppModel, np. `AppConfiguration::getApp()`, czy przed wykonaniem metody `install()` apki*/
    public function loadValues(): self
    {
        /* Do dyspozycji mamy tutaj $this->id, tu znajduje się id serwisu dla którego budujemy parametry*/
        $this->instance['sample_vm_var'] = 'sample_vm_var_value';
        return $this;
    }
}
```

Dodajemy sekcje do konfiguracji w ```app/Config/appCenter.php``` pod kluczem ```CustomRelatedFields```

```php
    'CustomRelatedFields' => function ()
    {
        return [
            new \ModulesGarden\NAZWA_TWOJEGO_MODULU\App\Libs\AppCenter\RelatedFieldsSections\VmFieldsSection()
        ];
    }
```

### Efekt:
![Zrzut_ekranu_2025-06-17_o_14.30.52](uploads/54abe94c9472949c64ea74d1e1c7301f/Zrzut_ekranu_2025-06-17_o_14.30.52.png)


### Customowy Related Fields Builder

**UWAGA: Nie trzeba tego robić aby Custom Related Fields działały jak powinny**

Jeżeli potrzebujemy zmienić domyślne zachowanie buildera Related Fields to możemy go nadpisać, aby to zrobić tworzymy własny builder.

```php
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Builder

/*ServiceFieldsParserBuilder to domyślny provider, możemy też zbudować swój bazując bezprośrednio na BaseFieldsParserBuilder*/

class Builder extends ServiceFieldsParserBuilder
{
    protected function buildClientFields(): self
    {
        return parent::buildClientFields(); 
    }

    public function build(): self
    {
        return parent::build();
    }
}

```


W pliku  ```app/Config/appCenter.php``` pod kluczem ```RelatedFieldsBuilder``` dodajemy swój builder.

```php
    'RelatedFieldsBuilder' => \ModulesGarden\NAZWA_TWOJEGO_MODULU\App\Libs\AppCenter\RelatedFields\Builder::class,
```
