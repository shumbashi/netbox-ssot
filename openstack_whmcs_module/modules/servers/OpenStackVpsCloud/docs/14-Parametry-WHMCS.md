Framework pozwala na bezpośredni dostęp do parametrów jakie WHMCS przysyła do standardowych funkcji modułu. Jest to przydatne głównie w modułach serwerowych.

### Dostęp do parametrów
```php
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

Params::get('fieldname');
Params::get('configoptions.xxx', 'default');
```
Aby się dostać do parametrów należy wykorzystać fasadę `Params`.    
Można też zrobić to za pomocą polecania `make` używając tego kodu
```php
make(\ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Services\Params::class)->set('fieldname')
```
