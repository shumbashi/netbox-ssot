
### Definicja Opcji

Opcja dropdowna ma 4 klucze: `value`, `name`, `search`, `group`

`search` -  to wartość po której opcja jest wyszukiwana

Przykład:

```php
$this->builder->addField((new Dropdown())
            ->setName('dropdownName')
            ->setOptions([
                [
                    'value'  => 1,
                    'name'   => 'XXXX',
                    'search' => 'XXXX',
                    'group'  => 'Z'
                ],
                [
                    'value'  => 2,
                    'name'   => 'YYYY',
                    'search' => 'YYYY',
                    'group'  => 'Y'
                ],
            ])
            ->setMultiple()
            ->setGroups([
                'Z' => 'Z Name',
                'Y' => 'Y Name'
            ])
        );
```
![image](uploads/c6f6e283a57617784768bfc12996566c/image.png)

Opcje można definiować również przekazując tablicę $klucz => $wartość, gdzie $klucz to value opci a $wartosć to nazwa:

![image](uploads/69f0e38763c8d961b29a2131adfb73de/image.png)

W tym przypadku (klucz `value` nie jest ustawiony) opcje są konwertowane i klucz `search` przyjmuje taką samą wartość jak `name`

#### UWAGA <br>
Kiedy używamy metody `setOptions()` i przekazujemy tablicę z rozpisanymi jawnie kluczami `value`, `name` to opcje NIE są konwertowane i trzeba samemu zadbać o ustawienie klucza `search`


### Zaawansowane szukanie - callback szukajki

Komponent dropdown udostępnia metodę od definicji callbacka od szukania

```php setBuildSearchCallback(callable $callback) ```

gdzie jako parametr przekazujemy `name` opcji

Przykład: <br>
Podczas wyszukiwania chcemy żeby strzałki w nazwach opcji zostały pominięte:

```php
$this->builder->createField(Dropdown::class, 'resourcesIds', true)
                ->setBuildSearchCallback(function ($name) { 
                    return str_replace(" ➝ ", " ", $name); 
                });
```

