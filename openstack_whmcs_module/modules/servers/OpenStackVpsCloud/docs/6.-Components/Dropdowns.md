# Dropdowns

### Option Definition

A dropdown option has 4 keys: `value`, `name`, `search`, `group`

`search` - this is the value by which the option is searched

Example:

```php
$this->builder->addField((new Dropdown())
            ->setName('dropdownName')
            ->setOptions([
                [
                    'value'  => 1,
                    'name'   => 'XXXX',
                    'search' => 'XXXX',
                    'group'  => 'Z'
                ],
                [
                    'value'  => 2,
                    'name'   => 'YYYY',
                    'search' => 'YYYY',
                    'group'  => 'Y'
                ],
            ])
            ->setMultiple()
            ->setGroups([
                'Z' => 'Z Name',
                'Y' => 'Y Name'
            ])
        );
```
![image](uploads/c6f6e283a57617784768bfc12996566c/image.png)

Options can also be defined by passing a $key => $value array, where $key is the option's value and $value is the name:

![image](uploads/69f0e38763c8d961b29a2131adfb73de/image.png)

In this case (when the `value` key is not set), options are converted and the `search` key takes the same value as `name`

#### NOTE <br>
When we use the `setOptions()` method and pass an array with explicitly defined `value`, `name` keys, the options are NOT converted and you need to take care of setting the `search` key yourself

### Advanced Search - Search Callback

The dropdown component provides a method for defining a search callback

```php setBuildSearchCallback(callable $callback) ```

where we pass the option's `name` as a parameter

Example: <br>
During search, we want arrows in option names to be ignored:

```php
$this->builder->createField(Dropdown::class, 'resourcesIds', true)
                ->setBuildSearchCallback(function ($name) { 
                    return str_replace(" ➝ ", " ", $name); 
                });
```
