## Installation
### Base directory structure
Before starting, create the base directory structure.    
 - For an addon module, use `/modules/addon/YOUR_MODULE_NAME`. 
 - For a provisioning module, use `/modules/servers/YOUR_MODULE_NAME`.

### Composer file creation
In the module directory `/modules/addon/YOUR_MODULE_NAME`, create a file named `composer.json` and insert the following content:
```json
{
   "repositories":[
      {
         "type":"vcs",
         "url":"git@git.mglocal:whmcs-products/module-framework.git"
      }
   ],
   "require":{
      "modulesgarden/whmcs-framework":"*"
   },
  "scripts": {
    "fw-install": [
      "\\ModulesGarden\\OpenStackVpsCloud\\Install\\Installer::run"
    ]
  },
  "autoload": {
    "psr-4": {
      "ModulesGarden\\YOUR_MODULE_NAME\\Core\\": "./core",
      "ModulesGarden\\YOUR_MODULE_NAME\\App\\": "./app",
      "ModulesGarden\\YOUR_MODULE_NAME\\Packages\\": "./packages",
      "ModulesGarden\\YOUR_MODULE_NAME\\Components\\": "./components",
      "ModulesGarden\\YOUR_MODULE_NAME\\Install\\": "./install"
    },
  }
}
```
Note: Do not modify the line `\\ModulesGarden\\OpenStackVpsCloud\\Install\\Installer::run`. Altering this line will cause the `fw-install` command to fail.

### Composer execution
1. Execute `composer install && composer dump-autoload`.
2. Execute `composer run-script fw-install`.

### Update procedure
1. Execute `composer update && composer dump-autoload`.
2. Execute `composer run-script fw-install`.

## Troubleshooting
For issues, contact @mariusz.
