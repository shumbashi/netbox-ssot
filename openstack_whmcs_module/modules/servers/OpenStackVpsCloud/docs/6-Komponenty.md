### What are Components?
Components are the fundamental and sole building blocks of the user interface. Unlike previous versions of the framework, current components are fully dynamic elements rendered based on templates.

### Component Structure
```
- \components\MyComponent\MyComponent.php
- \components\MyComponent\assets\component.html
- \components\MyComponent\assets\component.js
```
1. The PHP file contains the logic for handling the component in PHP.
2. The `component.html` file contains the HTML structure.
3. The `component.js` file contains JavaScript logic and definitions for the elements managed by the component.

### Component Logic in PHP
A simple example of a PHP component definition is shown below, using the Badge component:
```php
class Badge extends AbstractComponent
{
    public function setText(string $content): self
    {
        $this->setSlot('text', $content);
        return $this;
    }

    public function setType(string $type)
    {
        $this->setSlot('type', $type);
        return $this;
    }
}
```
The `Badge` class inherits from `AbstractComponent`, which is required for all components (either directly or indirectly). The class includes two methods that simply call the `setSlot` method, which is responsible for passing data to the Vue component.

### Slots
Slots provide a mechanism for passing data from a PHP object to a component rendered in the browser. For example, calling `setSlot('type', 'XXX')` will pass the value `XXX` to the component as the `type` attribute. The `setSlot` method is the simplest way to transfer data.

### Advanced Slots
`in progress`

## Component Logic in JavaScript
### Basic Component
An example of the Badge component code:

```js
var component =
    {
        extends: BaseDataComponent,
        template: '#template-name#',
        data: function () {
            return {
                text_: '',
                type_: '',
            }
        },
        props: [
            'text',
            'type'
        ],
        created: function () {
        },
        updated: function () {
        },
        computed: {
            badgeType: function(){
                return 'lu-badge--'+this.type_;
            }
        }
    }
```
This is a basic Vue component definition with some modifications:
- `var component` is always present; its value is dynamically replaced by the framework.
- `extends: BaseDataComponent` is always present and inherits the base component logic.
- `data` defines variables used by the component and available in HTML. Each variable ends with `_`, as required by the framework. This format allows the framework to copy data from `props` to `data` variables.
- `props` defines the fields the component can operate on. Prop names must not end with `_`.
- `created` is a method called when the component is created; it is optional.
- `computed` contains values calculated from other properties.

### Value Copying Mechanism
Components use standard Vue features such as [props](https://v2.vuejs.org/v2/guide/components-props.html) and [data](https://v2.vuejs.org/v2/guide/computed.html). The value copying mechanism transfers values from props to data. Since props and data cannot have the same names, data variables have a `_` suffix. This mechanism is especially useful for components handling dynamic data (AJAX), as it provides a unified interface for data delivery. Regardless of the data source, values will always be available in `data`.

## HTML Structure
```html
<span class="lu-badge lu-value" :class="badgeType">{{text_}}</span>
```

### Dynamic Component Assumptions
- Components that fetch data via AJAX must implement `ModulesGarden\OpenStackVpsCloud\Core\UI\Interfaces\AjaxElementInterface`.
- Element configuration is performed in the `loadHtml` method.
- Data preparation is performed in the `loadData` method.
- The `loadData` method should set values for slots.
- Data returned by the component can be overridden using the `returnAjaxData` method. By default, only slot contents are returned.
```php
        return (new DataJsonResponse($this->toArray()['slots']));
```
- Dynamic data providers can be set as follows:
```php
        $this->setDataProvider(new class() extends SampleDataProvider {
            public function read()
            {
                die('ada2222222wd');
            }

            public function update()
            {

            }
        });
         $this->loadDataToForm();
```

### Traits
The following traits may be useful:
- `\ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\VisibilityTrait` allows defining behavior for when an element (mainly a button in a table) should be displayed. It offers two methods: `hideWhen` and `disableWhen`. The trait requires the implementation of the `VisibilityTrait` mixin.
- `\ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TitleTrait`
- `\ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ValueTrait`
- `\ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TranslatorTrait`
- `\ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TextTrait`
- `\ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ImageTrait`