Module configuration is located in the `app/config` directory. Each package can have its own configuration file, and custom configurations can also be created as needed. Defined configurations are accessible via the `Config` facade.

### configuration.yml

Contains the most basic settings.

```yml
version: '2.1.0'
systemName: 'Module Framework'
description: 'Modules Garden Module Framework.<br>For more info visit our <a href="https://www.docs.modulesgarden.com/CHANGE_ME" style="color: #4169E1;" target="_blank">Wiki</a>.'
author: '<a href="https://www.modulesgarden.com" target="_blank">ModulesGarden</a>'
clientAreaName: 'OpenStackVpsCloud'
clientAreaController: Home
adminAreaController: Home
moduleIcon: 'modulesgarden_base'
debug: false
```

- version - module version, overridden by the moduleVersion.php file if present
- systemName - module name as displayed in WHMCS
- description - module description as displayed in WHMCS
- clientAreaName - name displayed in the Client Area
- clientAreaController - default controller for the Client Area
- adminAreaController - default controller for the Admin Area
- moduleIcon - module icon, specifically the CSS class that must be added first
- debug - enables debugging in the module

### hooks.yml

Allows disabling specific hooks in the module.

### packages.yml

Enables or disables specific packages. Before adding a package, it must be included in `composer.json`.

### menu/admin.yml menu.client.yml

Menu definition
