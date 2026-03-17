### Wymagania systemowe
Paczka RequirementsChecker pozwala na weryfikację czy WHMCS klient spełnia określone wymagagania

### Uruchomienie paczki 
1. W pliku `app/Config/packages.yml` dodaj `RequirementsChecker: true`
2. Utwórz plik z regułami w `app/Config/packages/requirementsChecker.php`. Przykładowa zawartość może wyglądać tak:
```php
<?php

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\ClassExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\DirectoryExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\ExtensionExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\FileExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\FunctionExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\IsReadable;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\IsWritable;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\MethodExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\PhpMinVersion;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\WhmcsMinVersion;

return [
    new PhpMinVersion('8.2.0'),
    new WhmcsMinVersion('7.9.0'),
    new MethodExists('SimpleXMLElement', 'attributes'),
    new DirectoryExists('/var/www'),
    new FileExists('/var/www/html/index.php'),
    new FunctionExists('exec'),
    new ClassExists('SimpleXMLElement'),
    new ExtensionExists('mysqli'),
    new IsWritable('/var/www'),
    new 

```


### Dostępne reguły
Aktualna lista reguł jest dostępna w katalogu `packages/RequirementsChecker/Checkers`. Znajdziesz tam między innymi:
- `ClassExists` - sprawdza czy klasa istnieje
- `DirectoryExists` - sprawdza czy katalog istnienie
- `ExtensionExists` sprawdza czy extensions w PHP istniejem np `xml`
- `FileExists` - sprawdza czy plik istnieje
- `FunctionExists` - sprawdza czy funkcja w PHP istnieje
- `IsReadable` - sprawdza czy katalog lub plik jest odczytywalny
- `IsWritable` - sprawdza czy katalog lub plik jest zapisywalny
- `MethodExists` - sprawdza czy istnieje metoda w podanej klasie
- `PhpMinVersion` - sprawdza czy PHP jest w wersji wyższej nie określona
- `WhmcsMinVersion` - sprawdza czy WHMCS jest w wersji wyższej niż określona