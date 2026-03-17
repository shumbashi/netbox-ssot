Formularze to nie tylko kontener na elementy takie jak input, textarea czy select, ale też pewien sposób ich wyświetlania.\
Sam formularz nie ogranicza w żaden sposób dodawania elementów, ale przez to nie posiada metod które w łatwy sposób pozwoliłby na zbudowanie wyglądu.\
Do tego celu należy wykorzystać `Builder`, który na odpowiednie metody

### Podstawowa budowa formularza

```php
<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\DataTable\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\DataTable\Providers\Provider;
use ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\DataTable\Providers\UserProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Form\FormBuilder;
use ModulesGarden\OpenStackVpsCloud\Components\FormGroup\FormGroupHalfWidth;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputEmail\FormInputEmail;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\SubmitButton;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Interfaces\AjaxElementInterface;

class UserCreate extends Form implements AjaxElementInterface
{
    protected $provider = UserProvider::class;
    protected $providerAction = 'create';

    public function loadHtml(): void
    {
        $builder = BuilderCreator::oneColumn($this);

        $builder->createField(FormInputText::class, 'firstname')
            ->required();
        $builder->createField(FormInputText::class, 'lastname')
            ->required();
        $builder->createField(FormInputText::class, 'address1')
            ->required();
        $builder->createField(FormInputText::class, 'city', false, new FormGroupHalfWidth())
            ->required();
        $builder->createField(FormInputText::class, 'state', false, new FormGroupHalfWidth())
            ->required();
        $builder->createField(FormInputEmail::class, 'email')
            ->required()
            ->email();
    }
}
```

1. Budowę formularza należy rozpocząć od stworzenia własnej klasy, która dziedziczy po `ModulesGarden\\OpenStackVpsCloud\\Components\\Form\\Form`
2. Następnie należy zdefiniować `provider` który informuje formularz z jakiego providera ma skorzystać, oraz `providerAction`, które mówi o akcji jaka ma zostać wywoła przy wysłaniu formularza.
3. Kolejnym krokiem jest przygotowanie metody `loadHtml`, która zawiera tworzenie struktury HTML. Najważniejszym etapem jest utworzenie buildera, który pozwoli szybko tworzyć pola w formularzu `$builder = BuilderCreator::oneColumn($this);`. Następnie używając builder dodajemy nowe pole.

### Budowa struktury

Wygenerowana struktura HTML zależy głównie od użytego builder, ale można na nią wpływać poprzez dodatkowe parametry w metodzie `createField` lub `addField`. Wyróżniamy kilka builderów:

- `simple` - jest to builder który nie wymusza zbytnio struktury. Przyda się się wtedy kiedy chcemy sami zbudować bardziej skomplikowany wygląd. Jak np Tab-y w formularzu oraz inne widgety.   
- `oneColumn` - podstawy wygląd dla popupów oraz prostych formularzy. Jest to element który zakłada że większość pół w formularzu będzie w osobnej linii. W przypadku potrzeby ustawienia wybranych elementów jednej linii, należy przekazać własny typ `FormGroup` jako parametry w `createField` albo `addField`   
![image](uploads/6af61849b015ade24e2e5c7b24f124cf/image.png)       

- `twoColumns` - działa podobnie jak `oneColumn` tylko zakłada, że dwa elementy będą w jednym wierszu   
![image](uploads/3f55cffaa9aee95bacfe9c460dc0165f/image.png)   

- `oneColumnHalfPage` - jedna kolumna do połowy szerokości kontenera 

Jak już wspomniano mamy dwie podstawowe metody które pozwalaja na dodawanie pól do formularza:
- `createField` - tworzy obiekt na podstawie przekazanej pełnej nazwy (wraz z przestrzenią nazw) oraz ustawia mu nazwę, następnie dodaje go do formularza
- `addField` - tylko i wyłącznie dodaje element do formularza. Nie zapomnij ustawić zdefiniować nazwy w samodzielnie utworzonym elemencie! 

### Budowanie własnej struktury

W tym celu należy wykorzystać builder typu `simple`, który nie wymusi na nas układu.

```php
        $builder = BuilderCreator::simple($this);
        $builder->setDefaultFormGroup(new FormGroupHalfWidth());

        $this->tabsContainer = new \ModulesGarden\OpenStackVpsCloud\Components\TabsContainer\TabsContainer();
        $this->addElement($this->tabsContainer);

    //Create Tab
        $tab = new Tab();
        $tab->setTitle($this->translate('first_tab'));
        $this->tabsContainer->addTab($tab);

        //Text
        $builder->createFieldInContainer($tab, FormInputText::class, 'text', true);
        //Password
        $builder->createFieldInContainer($tab, FormInputPassword::class, 'password');
        //Switcher
        $builder->createFieldInContainer($tab, Switcher::class, 'switcher');
        //Dropdown
        $dropdown = (new Dropdown())
            ->setName('dropdown')
            ->setOptions([
                '1' => 'First',
                '2' => 'Second'
            ]);
        $builder->addFieldInContainer($tab, $dropdown);
        //Textarea
        $builder->createFieldInContainer($tab, TextArea::class, 'textarea');
```

1. Tworzymy nowe builder oraz definiujemy, że domyślnie elementy będą wbudowane w `FormGroupHalfWidth`

```php
        $builder = BuilderCreator::simple($this);
        $builder->setDefaultFormGroup(new FormGroupHalfWidth());
```

2. Tworzymy kontener dla zakładek, następnie kontener dodajemy do formularza bezpośrednio

```php
        $this->tabsContainer = new \ModulesGarden\OpenStackVpsCloud\Components\TabsContainer\TabsContainer();
        $this->addElement($this->tabsContainer);
```

3. Kolejnym etapem jest utworzenie zakładki

```php
        $tab = new Tab();
        $tab->setTitle($this->translate('first_tab'));
        $this->tabsContainer->addTab($tab);
```

4. Ostatni etapem jest utworzenie elementów formularzu w określonym elemencie (w tym przypadku jest to `Tab` utworzy punkt wyżej. Aby utworzyć element formularza w konkretnym elemencie należy użyć metody `createFieldInContainer` lub `addFieldInContainer`. Obie działają podobnie jak `addField` lub `createField` z tą różnicą, że jako pierwszy parametr należy podać element w których chcemy dodać pola formularza.

### Walidacja danych
Formularze pozwalają na zdefiniowanie walidacji dla pól w momencie ich dodawania. Walidacja uruchamia się automatycznie w momencie wywołania akcji zdefiniowaniem w atrybucie `$providerActionsToValidate`. Domyślne walidowane są akcje `create` oraz `update`.

We framework jest wbudowane kilka domyślnych typów walidacji, które znajdują się w `\\ModulesGarden\\OpenStackVpsCloud\\Core\\Components\\Traits\\ValidatorRulesTrait`.\
Nie jest to pełna lista dostępnych elementów, wszystkie pozostałe znajdziesz w dokumentacji Laravela https://laravel.com/docs/5.4/validation#rule-numeric\
Dodatkowe walidatory możemy ustawić poprzez metodę `setValidators`:

```php
        $builder->createField(FormInputText::class, 'firstname')
            ->setValidators(['required']);
```
Gdy używasz jednego z validatorów typu **greaterThen** , **lowerThen** i **between** dla fieldów numerycznych to koniecznie trzeba dodać walidator **numeric** lub **integer**. <br>
Bez tego wartość z fielda zostanie potraktowana jako string i wartością walidowaną będzie długość tego stringa. Węcej: http://git.mglocal/whmcs-products/module-framework/-/issues/607

````php
$this->builder->createField(Number::class, 'enabled_from', false)
                    ->setMin(0)->setMax(99999)->setDefaultValue(0)->numeric()->greaterThen(0);
````

### Data Provider

Pora na wykonanie właściwe akcji po przesłaniu formularza lub też dostarczenie danych do formularza.\
Każdy provider musi implementować `\\ModulesGarden\\OpenStackVpsCloud\\Core\\Contracts\\CrudProviderInterface` a najlepiej będzie po prostu dziedziczyć po `\\ModulesGarden\\OpenStackVpsCloud\\Core\\DataProviders\\AbstractCrudProvider`

```php
class UserProvider extends AbstractCrudProvider
{
    public function read()
    {
        parent::read();
    }

    public function create()
    {
        API::run('AddClient', [
            'firstname' => $this->formData['firstname'],
            'lastname'  => $this->formData['lastname'],
            'address1'  => $this->formData['address1'],
            'email'     => $this->formData['email'],
            'city'      => $this->formData['city'],
            'state'     => $this->formData['state'],
            'postcode'  => $this->formData['postcode']
        ]);
    }

    public function update()
    {
        Client::where('id', $this->formData['id'])
            ->update([
                'firstname' => $this->formData['firstname'],
                'lastname'  => $this->formData['lastname'],
                'address1'  => $this->formData['address1'],
                'email'     => $this->formData['email']
            ]);
    }

    public function delete()
    {
        throw new Exception('Delete option is disabled');
    }
}
```

Budując swój formularz nie musisz implementować wszystkich metod, nie jesteś tak, że ograniczony tylko do podstawowych metod jak `created`, `read`, `update`, `delete`. Możesz dodawać własne, tylko nie zapomnij ich wykorzystać w formularzach :)\
W formularzach najważniejsze są dla ciebie:

- `$this->formData` jest to obiekt który zawiera dane przesłane przez formularz, lub dane przesłane przy ładowaniu formularza. W przypadku formularza w `DataTable` do metody `ready` zostaną przekazane wszystkie wartości danego wiersza
- `$this->data` są to dane jakie przekazujesz do formularza. Nazwy danych muszą być takie same jakich używasz w swoim formularzu.

Wbudowane metody oraz ich zastosowanie
- `create` - tworzenie danych
- `read` - pobieranie informacji, np: pobranie informacji użytkowniku. Domyślnie kopiuje wartości przesłane w parametrach GET/POST do `$this->data` 
- `update` - aktualizacja danych
- `delete` - usuwanie danych

### Data Provider a otwieranie modala
Modale mogą być albo otwierane albo ładowane. Użycie `modalOpen` oznacza że cała treść modala (czyli też formularz) jest generowany.    
W takim przypadku należy zablokować domyslne ładowanie providera poprzez nadpisanie `$providerDefaultAction` w formularzu. Przykładowo:
```php
class DeleteLogForm extends Form
{
    protected string $provider = DeleteLogProvider::class;
    protected string $providerAction = CrudProvider::ACTION_DELETE;
    protected ?string $providerDefaultAction = null;

    public function loadHtml(): void
    {
        $this->builder->createField(HiddenField::class, 'id');
    }
}
```

### Obsługa błędów
#### Własny walidator
##### Wykorzystanie funkcji anonimowej

```php
        Validator::validate($this->formData->toArray(), [
            'types' => [
                'sometimes',
                function(string $attribute, $value, \Closure $fail) {
                    $error = false;
                    if (is_array($value))
                    {
                        foreach ($value as $val)
                        {
                            if (in_array($val, LogTypes::getAvailable()))
                            {
                                $error = true;
                                break;
                            }
                        }
                    }
                    else
                    {
                        $error = true;
                    }

                    if ($error)
                    {
                        $fail(Translator::get('invalid_value', [
                            'field' => $attribute
                        ]));
                    }
                }]
        ]);
```
ten sam efekt osiągniemy przy użycie bezpośrednio biblioteki z Laravela 
```php
        Validator::validate($this->formData->toArray(), [
            'types.*'               => [
                'sometimes',
                Rule::in(array_keys(LogTypes::getAvailable()))
            ],
            'auto_prune'            => ['sometimes', Rule::in('on', 'off')],
            'auto_prune_older_than' => ['required_if:auto_prune,on', 'numeric', 'min:1']
        ]);

```
Więcej przykładow znajdziesz tutaj: https://laravel.com/docs/9.x/validation



Zwracanie błędów dla danego pola formularza należy zrobić poprzez walidatory. Globalne błędy można zwrócić poprzez rzucenie wyjątkiem z treścią błędu.
```php
\throw new \Exception('invalid input')
```
Czasem może zajść potrzeba stworzenia bardziej skomplikowanej walidacji niż ta którą oferują formularze. Wtedy można wykorzystać metodę `validate`.
```php
        \ModulesGarden\OpenStackVpsCloud\Core\validator()->validate(request()->getAll(), [
            'text'     => 'required',
            'dropdown' => 'required'
        ]);
```
która jako pierwszy elementy przyjmuje dane, które chcemy walidować. Natomiast jaki drugi parametr przyjmuje zestaw reguł, którego dokumentacja znajduje się tutaj: https://laravel.com/docs/5.4/validation#available-validation-rules
   
Jeżeli nie znalazłeś odpowiedniego walidatora, a potrzebujesz zgłosić własny błąd do konkretnego pola możesz:
 - stworzyć własny validator `not implemented yet` 
 - lub rzucić wyjątkiem dziedziczącym po `Illuminate\Validation\ValidationException`, który zawiera informacje o błędach dla konkretnych pól formularza. 
 `
TODO: dać przykład
 `


### Zwracanie własnych danych 
Każda z metod providera, która jest wykorzystywana przez formularza może zwrócić własne dane. W tym celu należy zwrócić obiekt implementujący `\ModulesGarden\OpenStackVpsCloud\Core\Contracts\ResponseInterface`, przykładowo może być to obiekt typu `\ModulesGarden\OpenStackVpsCloud\Core\UI\ResponseTemplates\Response`.   
```php
        return (new Response())
            ->setActions([
                Action::reloadParent(),
            ])
            ->setData([])
            ->setError('changesHasBeenSaved');
```
Przykładowy kod znajdziesz tutaj: http://git.mglocal/modulesgardenlive/mgmoduleframework/blob/new-age/components/Form/Form.php#L132

### Customowe nazwy fieldów i wartości 

Czyli w przypadku walidatorów o fieldach zależnych np. `The field is required when switcher is 1` można nadać swoje własne nazwy fieldów oraz wartości. Więcej tutaj: http://git.mglocal/whmcs-products/module-framework/-/issues/883#note_392944

### Rzeczy których nie należy robić:   
- nie pobieraj danych bezpośrednio w formularzu, dane należy pobrać w providerze 
- nie twórz skomplikowanych elementów w konstruktorze providera
- nie nawiązuj połączenia z API, bazą danych w konstruktorze providera