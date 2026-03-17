### Basic Translation
The default language file is placed in `app/langs`, for example as `english.php`. The structure uses a one-dimensional associative array in the format `key => value`. Example:
```php
$_LANG['admin.home.forms.forms.form_with_tabs.text_description'] = 'Description';
$_LANG['packages.samples.admin.home.alerts.pages.container.Alert Danger with outline'] = 'Alert Danger with outline';
```

Dot notation is used extensively, as the system also supports multi-dimensional arrays, which are later flattened into a one-dimensional structure. For example:
```php
$_LANG['addonAA']['pagesLabels']['home']['PreBlocks'] = 'pagesLabels home PreBlocks';
$_LANG['addonAA']['pagesLabels']['home']['SimpleForm'] = 'pagesLabels home SimpleForm';
$_LANG['addonAA']['pagesLabels']['home']['DataTable'] = 'pagesLabels home DataTable';
$_LANG['addonAA']['pagesLabels']['home']['Modals'] = 'pagesLabels home Modals';
$_LANG['addonAA']['pagesLabels']['home']['Graphs'] = 'pagesLabels home Graphs';
```

This format is supported but not recommended. 
Note: The use of multi-dimensional arrays is discouraged, as even a minor error in array construction can result in a fatal PHP error.

### Language Overrides
Language strings can be overridden without risk of being overwritten during updates. To override, create a file such as `english.php` in the `overrides/langs/` directory and include only the translations to be changed relative to the default language file.

### Migration
Changing the format cannot be enforced for clients, nor is it necessary to maintain the old format. When migrating a module to a new framework version, use the new format for new keys and leave the old ones unchanged. Maintaining the old format for clients does not require continued use in new development. For this purpose, `fallback` files such as `english.fallback.php` are provided, containing conversions from one format to another.
```php
<?php
return [
    'addonAA.pagesLabels.label.samples' => 'label.samples'
];
```
The above declaration ensures that the value under `addonAA.pagesLabels.label.samples` is also available under `label.samples`.
