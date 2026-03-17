## Podstawowa konfiguracja 
1. W pliku `app/Config/packages.yml` dodaj `Logs: true`

## Sposób użycia
Należy wykorzystać fasadę `\ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger` która udostępnia takie metody jak:
- `emergency`
- `alert`
- `critical`
- `error`
- `warning`
- `notice`
- `info`
- `debug`

Wszystkie metody przyjmują taki sam zestaw parametrów `emergency($message, array $data = [])` gdzie pierwszy parametr to tekst a drugi to tablica
zawierające elementy które zostaną użyte do podmiany wartościu w `message`. Dodatkowe dane z `data` są dostępne do wglądu z poziomu paczki.

### Przesyłanie zmiennych
```php
Logger::info(':user updated profile', [
    'user'  => 'John Doe'
]);
```
Zostanie wyświetlone jako `John Doe updated profiler`;

### Linkowanie do innych elementów

Domyślny formatter 
`\ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Formatters\RelatedItem()`
 tworzy linki do wybranych itemków. <br> Żeby to działało należy jako **data** przekazać nazwę oraz ID itemu. Np.:
````php
Logger::info("Some Message", ['invoice'=>67]);
````
![image](uploads/2fe6d306942afff8d5c335035404d27a/image.png)
Dostępne typy:
````php
    const TYPE_CLIENT  = 'client';
    const TYPE_SERVICE = 'service';
    const TYPE_ADDON   = 'addon';
    const TYPE_DOMAIN  = 'domain';
    const TYPE_INVOICE = 'invoice';
    const TYPE_TICKET  = 'ticket';
    const TYPE_ORDER   = 'order';
````


## Dodatkowe ustawienia
Przykładowy config 
```php
return [
    'related_item' => [
        'show'      => true,
        'formatter' => new \ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Formatters\RelatedItem()
    ],
    'delete_logs'  => [
        'enabled' => false
    ],
    'auto_prune'   => [
        'enabled' => false
    ]
];
```
### Related item
Czyli info czego dotyczy dany log. <br>
Dostępne ustawienia:
1. Ukrywanie kolumny z related items _'show' => false_ <br>
2. Definiowanie własnego formattera _'formatter' => klasa_formattera_
```php
return [
    'related_item' => [
        'show'      => false,
        'formatter' => new \ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Formatters\RelatedItem()
    ]
];
```

#### Deleting logs
Dostępne ustawienia:
1. Wyłączenie możliwości usuwania logów - _'enabled' => false_
```php
return [
    'delete_logs' => [
        'enabled' => false
    ]
];
```

### Auto Prune
Dostępne ustawienia:
1. Wyłączenie systemu Auto Prune - _'enabled' => false_
```php
return [
    'auto_prune' => [
        'enabled' => false
    ]
];
```


## Zalecenia
Tekst przekazywany do logów powinien być tekstem pośrednim, który dopiero później zostanie przetłumaczony. Przykładowo
```php
Logger::info('user_update_profile', [
    'user'  => 'John Doe'
]);
```
i w plikach językowych 
```php
$_LANG['logs']['user_update_profile'] = ':user updated profile';
```