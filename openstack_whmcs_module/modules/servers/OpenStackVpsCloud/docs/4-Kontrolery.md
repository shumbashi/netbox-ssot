### Controller Structure

- Client Area controllers are located in `app/Http/Client/`
- Admin Area controllers are located in `app/Http/Admin/`
- Server module functions are located in `app/Http/Action/`

### Default Controllers
Default controllers can be defined in the `app/Config/configuration.yml` file:
```yml
clientAreaController: Home
adminAreaController: Home
```

### Client Area and Admin Area in Addon Modules

```php
<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;

class Home extends AbstractController
{
    public function index()
    {
        return Helper\view()
            ->addElement(\ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\PreBlocks\Pages\Container::class);
    }
}
```


### Server Module Functions

Server module functions are defined in the `app/Http/Action/` directory. These functions are responsible for handling server-side logic and are invoked by WHMCS based on the module's configuration and actions. Each function should be implemented as a separate class, following the framework's conventions for structure and naming.

---
