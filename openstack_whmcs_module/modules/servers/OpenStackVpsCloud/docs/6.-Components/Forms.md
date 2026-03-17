# Forms

Forms are not just containers for elements like input, textarea, or select, but also a certain way of displaying them.\
The form itself does not limit adding elements in any way, but therefore does not have methods that would easily allow building the appearance.\
For this purpose, you should use the `Builder`, which provides appropriate methods.

### Basic Form Structure

```php
<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\DataTable\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\DataTable\Providers\Provider;
use ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Home\DataTable\Providers\UserProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Form\FormBuilder;
use ModulesGarden\OpenStackVpsCloud\Components\FormGroup\FormGroupHalfWidth;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputEmail\FormInputEmail;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\SubmitButton;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Interfaces\AjaxElementInterface;

class UserCreate extends Form implements AjaxElementInterface
{
    protected $provider = UserProvider::class;
    protected $providerAction = 'create';

    public function loadHtml(): void
    {
        $builder = BuilderCreator::oneColumn($this);

        $builder->createField(FormInputText::class, 'firstname')
            ->required();
        $builder->createField(FormInputText::class, 'lastname')
            ->required();
        $builder->createField(FormInputText::class, 'address1')
            ->required();
        $builder->createField(FormInputText::class, 'city', false, new FormGroupHalfWidth())
            ->required();
        $builder->createField(FormInputText::class, 'state', false, new FormGroupHalfWidth())
            ->required();
        $builder->createField(FormInputEmail::class, 'email')
            ->required()
            ->email();
    }
}
```

1. Building a form should start by creating your own class that inherits from `ModulesGarden\\OpenStackVpsCloud\\Components\\Form\\Form`
2. Next, you need to define a `provider` that tells the form which provider to use, and `providerAction`, which tells what action should be called when the form is submitted.
3. The next step is preparing the `loadHtml` method, which contains creating the HTML structure. The most important stage is creating a builder that will allow you to quickly create fields in the form `$builder = BuilderCreator::oneColumn($this);`. Then using the builder, we add a new field.

### Structure Building

The generated HTML structure depends mainly on the builder used, but you can influence it through additional parameters in the `createField` or `addField` method. We distinguish several builders:

- `simple` - this is a builder that doesn't enforce structure too much. It will be useful when we want to build a more complicated appearance ourselves. Like Tabs in a form and other widgets.
- `oneColumn` - basic appearance for popups and simple forms. This is an element that assumes that most fields in the form will be on a separate line. If you need to set selected elements on one line, you should pass your own `FormGroup` type as parameters in `createField` or `addField`
   ![image](uploads/6af61849b015ade24e2e5c7b24f124cf/image.png)

- `twoColumns` - works similarly to `oneColumn` but assumes that two elements will be in one row
   ![image](uploads/3f55cffaa9aee95bacfe9c460dc0165f/image.png)

- `oneColumnHalfPage` - one column to half the width of the container

As mentioned, we have two basic methods that allow adding fields to the form:
- `createField` - creates an object based on the passed full name (including namespace) and sets its name, then adds it to the form
- `addField` - only adds an element to the form. Don't forget to set and define names in the self-created element!

### Building Custom Structure

For this purpose, you should use a `simple` type builder, which will not force a layout on us.

```php
        $builder = BuilderCreator::simple($this);
        $builder->setDefaultFormGroup(new FormGroupHalfWidth());

        $this->tabsContainer = new \ModulesGarden\OpenStackVpsCloud\Components\TabsContainer\TabsContainer();
        $this->addElement($this->tabsContainer);

    //Create Tab
        $tab = new Tab();
        $tab->setTitle($this->translate('first_tab'));
        $this->tabsContainer->addTab($tab);

        //Text
        $builder->createFieldInContainer($tab, FormInputText::class, 'text', true);
        //Password
        $builder->createFieldInContainer($tab, FormInputPassword::class, 'password');
        //Switcher
        $builder->createFieldInContainer($tab, Switcher::class, 'switcher');
        //Dropdown
        $dropdown = (new Dropdown())
            ->setName('dropdown')
            ->setOptions([
                '1' => 'First',
                '2' => 'Second'
            ]);
        $builder->addFieldInContainer($tab, $dropdown);
        //Textarea
        $builder->createFieldInContainer($tab, TextArea::class, 'textarea');
```

1. We create a new builder and define that by default elements will be built into `FormGroupHalfWidth`

```php
        $builder = BuilderCreator::simple($this);
        $builder->setDefaultFormGroup(new FormGroupHalfWidth());
```

2. We create a container for tabs, then add the container to the form directly

```php
        $this->tabsContainer = new \ModulesGarden\OpenStackVpsCloud\Components\TabsContainer\TabsContainer();
        $this->addElement($this->tabsContainer);
```

3. The next stage is creating a tab

```php
        $tab = new Tab();
        $tab->setTitle($this->translate('first_tab'));
        $this->tabsContainer->addTab($tab);
```

4. The last stage is creating form elements in a specific element (in this case it's `Tab` created above). To create a form element in a specific element, you should use the `createFieldInContainer` or `addFieldInContainer` method. Both work similarly to `addField` or `createField` with the difference that as the first parameter you should provide the element in which we want to add form fields.

### Data Validation
Forms allow defining validation for fields when adding them. Validation runs automatically when calling the action defined in the `$providerActionsToValidate` attribute. By default, `create` and `update` actions are validated.

The framework has several built-in default validation types, which are located in `\\ModulesGarden\\OpenStackVpsCloud\\Core\\Components\\Traits\\ValidatorRulesTrait`.\
This is not a complete list of available elements, you can find all others in Laravel documentation https://laravel.com/docs/5.4/validation#rule-numeric\
Additional validators can be set through the `setValidators` method:

```php
        $builder->createField(FormInputText::class, 'firstname')
            ->setValidators(['required']);
```
When using validators like **greaterThen**, **lowerThen**, and **between** for numeric fields, you must add the **numeric** or **integer** validator. <br>
Without this, the value from the field will be treated as a string and the validated value will be the length of that string. More: http://git.mglocal/whmcs-products/module-framework/-/issues/607

```php
$this->builder->createField(Number::class, 'enabled_from', false)
                    ->setMin(0)->setMax(99999)->setDefaultValue(0)->numeric()->greaterThen(0);
```

### Data Provider

Time to perform actual actions after submitting the form or providing data to the form.\
Each provider must implement `\\ModulesGarden\\OpenStackVpsCloud\\Core\\Contracts\\CrudProviderInterface` and preferably should simply inherit from `\\ModulesGarden\\OpenStackVpsCloud\\Core\\DataProviders\\AbstractCrudProvider`

```php
class UserProvider extends AbstractCrudProvider
{
    public function read()
    {
        parent::read();
    }

    public function create()
    {
        API::run('AddClient', [
            'firstname' => $this->formData['firstname'],
            'lastname'  => $this->formData['lastname'],
            'address1'  => $this->formData['address1'],
            'email'     => $this->formData['email'],
            'city'      => $this->formData['city'],
            'state'     => $this->formData['state'],
            'postcode'  => $this->formData['postcode']
        ]);
    }

    public function update()
    {
        Client::where('id', $this->formData['id'])
            ->update([
                'firstname' => $this->formData['firstname'],
                'lastname'  => $this->formData['lastname'],
                'address1'  => $this->formData['address1'],
                'email'     => $this->formData['email']
            ]);
    }

    public function delete()
    {
        throw new Exception('Delete option is disabled');
    }
}
```

When building your form, you don't have to implement all methods, you're not limited only to basic methods like `create`, `read`, `update`, `delete`. You can add your own, just don't forget to use them in forms :)\
In forms, the most important for you are:

- `$this->formData` is an object that contains data sent by the form, or data sent when loading the form. In the case of a form in `DataTable`, all values of the given row will be passed to the `read` method
- `$this->data` is data that you pass to the form. Data names must be the same as those you use in your form.

Built-in methods and their usage:
- `create` - creating data
- `read` - retrieving information, e.g.: getting user information. By default, copies values sent in GET/POST parameters to `$this->data`
- `update` - updating data
- `delete` - deleting data

### Data Provider and Modal Opening
Modals can be either opened or loaded. Using `modalOpen` means that the entire modal content (including the form) is generated.\
In such a case, you should block the default provider loading by overriding `$providerDefaultAction` in the form. For example:
```php
class DeleteLogForm extends Form
{
    protected string $provider = DeleteLogProvider::class;
    protected string $providerAction = CrudProvider::ACTION_DELETE;
    protected ?string $providerDefaultAction = null;

    public function loadHtml(): void
    {
        $this->builder->createField(HiddenField::class, 'id');
    }
}
```

### Error Handling
#### Custom Validator
##### Using Anonymous Function

```php
        Validator::validate($this->formData->toArray(), [
            'types' => [
                'sometimes',
                function(string $attribute, $value, \Closure $fail) {
                    $error = false;
                    if (is_array($value))
                    {
                        foreach ($value as $val)
                        {
                            if (in_array($val, LogTypes::getAvailable()))
                            {
                                $error = true;
                                break;
                            }
                        }
                    }
                    else
                    {
                        $error = true;
                    }

                    if ($error)
                    {
                        $fail(Translator::get('invalid_value', [
                            'field' => $attribute
                        ]));
                    }
                }]
        ]);
```
The same effect can be achieved using Laravel's library directly:
```php
        Validator::validate($this->formData->toArray(), [
            'types.*'               => [
                'sometimes',
                Rule::in(array_keys(LogTypes::getAvailable()))
            ],
            'auto_prune'            => ['sometimes', Rule::in('on', 'off')],
            'auto_prune_older_than' => ['required_if:auto_prune,on', 'numeric', 'min:1']
        ]);

```
More examples can be found here: https://laravel.com/docs/9.x/validation

Returning errors for a specific form field should be done through validators. Global errors can be returned by throwing an exception with the error content.
```php
\throw new \Exception('invalid input')
```
Sometimes there may be a need to create more complex validation than what forms offer. Then you can use the `validate` method.
```php
        \ModulesGarden\OpenStackVpsCloud\Core\validator()->validate(request()->getAll(), [
            'text'     => 'required',
            'dropdown' => 'required'
        ]);
```
which takes the data we want to validate as the first element. The second parameter accepts a set of rules, whose documentation can be found here: https://laravel.com/docs/5.4/validation#available-validation-rules

If you didn't find an appropriate validator and need to report your own error to a specific field, you can:
 - create your own validator `not implemented yet`
 - or throw an exception inheriting from `Illuminate\Validation\ValidationException`, which contains information about errors for specific form fields.

TODO: provide example

### Returning Custom Data
Each provider method that is used by the form can return its own data. For this purpose, you should return an object implementing `\ModulesGarden\OpenStackVpsCloud\Core\Contracts\ResponseInterface`, for example it can be an object of type `\ModulesGarden\OpenStackVpsCloud\Core\UI\ResponseTemplates\Response`.
```php
        return (new Response())
            ->setActions([
                Action::reloadParent(),
            ])
            ->setData([])
            ->setError('changesHasBeenSaved');
```
Example code can be found here: http://git.mglocal/modulesgardenlive/mgmoduleframework/blob/new-age/components/Form/Form.php#L132

### Custom Field Names and Values

In case of validators with dependent fields e.g. `The field is required when switcher is 1`, you can give your own field names and values. More here: http://git.mglocal/whmcs-products/module-framework/-/issues/883#note_392944

### Things You Should Not Do:
- don't fetch data directly in the form, data should be fetched in the provider
- don't create complex elements in the provider constructor
- don't establish API or database connections in the provider constructor
