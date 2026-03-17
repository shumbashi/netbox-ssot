Poniżej znajdziesz listę fasad które znacznie uproszczą prace we frameworku oraz całym WHMCS 

### Translator 
Tłumaczenie langów jeżeli nie jesteśmy w komponencie i nie mamy traita Translator
```php
Translator::get('some.translation');
```

### Breadcrumbs
Pozwala na zarządzanie breadcrump:    
http://whmcs-products.internal.modulesgarden.com/module-framework/classes/ModulesGarden-OpenStackVpsCloud-Core-UI-Breadcrumbs-Breadcrumbs.html
```php
Breadcrumbs::add(new Item('XXXX'));
```

### Request
Dostęp do parametrów GET/POST/FILES/COOKIES    
http://whmcs-products.internal.modulesgarden.com/module-framework/classes/ModulesGarden-OpenStackVpsCloud-Core-Support-Facades-Request.html
```php
Request::get('sortBy');
```

### Config
Pozwala na dostęp do konfiguracji modułu która znajduje się w plikach.    
http://whmcs-products.internal.modulesgarden.com/module-framework/classes/ModulesGarden-OpenStackVpsCloud-Core-Support-Facades-Config.html   
```php
Config::get('configuration.debug');
```
`configuration` oznacza plik `configuration.yml`, `debug` jest to wartość z tego pliku

### Params
Zapewnia dostęp do parametrów jakie WHMCS przesyła do modułu:   
http://whmcs-products.internal.modulesgarden.com/module-framework/classes/ModulesGarden-OpenStackVpsCloud-Core-Support-Facades-Params.html
```php
Params::get('id');
```

### Session
Zapewnia dostęp do sesji. Pozwala odwoływać sie do klucz poprzez notacje kropkową.    
http://whmcs-products.internal.modulesgarden.com/module-framework/classes/ModulesGarden-OpenStackVpsCloud-Core-Support-Facades-Session.html
```php
Session::get('adminid');
```

### Menu
Pozwala na edycję menu   
http://whmcs-products.internal.modulesgarden.com/module-framework/classes/ModulesGarden-OpenStackVpsCloud-Core-Support-Facades-Menu.html
```php
$item = new Item($catName);
$item->setUrl(!empty($category['externalUrl']) ? $category['externalUrl'] : BuildUrl::getUrl($catName));
$item->setIcon($category['icon'] ?: '');
Menu::addItem($item);
```

### Str
Metody służące do operacji na stringach    
http://whmcs-products.internal.modulesgarden.com/module-framework/classes/ModulesGarden-OpenStackVpsCloud-Core-Support-Str.html
```php
Str::password(32);
```


### Cookie
Dostęp do zmiennej globalnej $_COOKIE   

```php
ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Cookie::set("testKey", "off", 60);

ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Cookie::get("testKey");

ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Cookie::delete("testKey");
```