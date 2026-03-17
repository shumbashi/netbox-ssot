### Aktywacja

1. Dodaj plik `AccessControl` do `composer.json` w sekcji `extra/packages`
2. Uruchom `composer update && composer run-script fw-install`
3. Włącz paczkę `AccessControl` w `app/config/pakages`

### Zdefiniuj zasoby

W pliku `app/config/acl.php` możesz zdefiniować:

* resources - czyli nazwa zasobu do którego chcemy ograniczyć dostęp, nazwa ta zostanie domyślnie dodana do bazy danych przy aktywacji modułu
* rules - nazwy reguły, która zostanie domyślne stworzona

### Definiowanie nazw zasobów

Zasoby powinny zawierać w sobie nazwę kontrolera, akcji oraz hierarchie komponentów. Przykładowo

* home.index - czyli jest to kontroler Home oraz metoda Index
* home.index.welcome - podobnie jak wyżej z tym że doszedł dodatkowe komponent o nazwie `welcome`
* home.index.welcome.close - dodatkowy element `close` który znajduje się w elemencie `welcome`

Zachowanie tej hierarchii pozwala na szybkie dostanie/zablokowanie dostępu do określonego element oraz jego elementów. Przykładowo

* `allow` home.index - jeżeli użytkownik posiada dostęp do tego elementu to posiada tak, że dostęp do jego elementów podrzędnych (o ile ich nie wykluczymy).
* `deny` home.index.welcome.close - blokuje tylko dostęp do elementu `close`

### Sprawdzanie uprawnień

##### Kontrolery

```php
\ModulesGarden\OpenStackVpsCloud\Packages\AccessControl\Support\Facades\ACL::requires('home.index');
```

Sprawdza i rzuca wyjątek jeżeli użytkownik nie ma uprawnień (nie należy łapać tego wyjątku).

##### Komponenty

W większości przypadków zawsze będzie jakiś nadrzędny komponent, który pokazuje się zawsze. Natomiast czy pokazać jego dzieci należy sprawdzić w następujący sposób.

```php
if(\ModulesGarden\OpenStackVpsCloud\Packages\AccessControl\Support\Facades\ACL::has('home.index.welcome.close'))
{
   $this->addElement(new Close());
}
```

Nie zawsze to rozwiązanie jest wystarczające. Czasem elementy są ładowane poprzez AJAX. W takim przypadku należy w danym elemencie sprawdzić czy może zostać on załadowany.

Sytuacja analogiczna do tej co mamy przy `AdminAreaInterface` oraz `AjaxComponent`

##### Providerzy

Częstą sytuacja będzie że użytkownik może odczytać dane ale nie może ich zmieniać. W tym przypadku używamy

```php
\ModulesGarden\OpenStackVpsCloud\Packages\AccessControl\Support\Facades\ACL::requires('home.index.component.close.update');
```

w każdej z metod danego providera.