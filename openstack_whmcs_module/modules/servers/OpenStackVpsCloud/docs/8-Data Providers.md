# Data Providers

Data Providers are a fundamental component of the OpenStackVpsCloud that handle data operations (CRUD: Create, Read, Update, Delete) for UI components. They serve as an intermediary layer between the user interface and data models, encapsulating business logic, validation, and data access patterns.

## Core Concepts

### Base Class: CrudProvider
All Data Providers extend the `CrudProvider` base class, which provides:
- **Form Data Access**: Access to user input through `$this->formData`
- **Data Container**: Storage for output data via `$this->data`
- **Available Values**: Supply dropdown/select options through `$this->availableValues`
- **Translation Support**: Built-in translation capabilities

### CRUD Operations
Data Providers implement one or more of these methods:
- `read()` - Load and prepare data for display
- `create()` - Handle creation of new records
- `update()` - Process updates to existing records
- `delete()` - Remove records from the system

## Common Patterns

### 1. Configuration Management
Handles reading and updating module settings with validation.

```php
class SettingsProvider extends CrudProvider
{
    public function read()
    {
        $this->data['enableFeature'] = ModuleSettings::get('MyModule.enableFeature');
        $this->data['maxItems'] = ModuleSettings::get('MyModule.maxItems');
        
        $this->availableValues['enableFeature'] = [
            '0' => 'Disabled',
            '1' => 'Enabled'
        ];
    }

    public function update()
    {
        Validator::validate($this->formData->toArray(), [
            'enableFeature' => ['required', Rule::in([0, 1])],
            'maxItems' => ['required', 'numeric', 'min:1', 'max:100']
        ]);

        ModuleSettings::save([
            'MyModule.enableFeature' => $this->formData->get('enableFeature'),
            'MyModule.maxItems' => $this->formData->get('maxItems')
        ]);
    }
}
```

### 2. Record Deletion
Handles single or bulk deletion operations with ID parsing.

```php
class DeleteRecordsProvider extends CrudProvider
{
    public function delete()
    {
        $ids = explode(',', $this->formData->get('id'));
        
        MyModel::whereIn('id', $ids)
            ->where('protected', 0) // Safety check
            ->delete();
    }
}
```

### 3. Full CRUD Operations
Complete resource management with all operations.

```php
class UserProvider extends CrudProvider
{
    public function read()
    {
        $user = User::find($this->formData->get('id'));
        
        if (!$user || !$user->exists) {
            return;
        }

        $this->data['id'] = $user->id;
        $this->data['name'] = $user->name;
        $this->data['email'] = $user->email;
        $this->data['status'] = $user->status;
        
        $this->availableValues['status'] = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'suspended' => 'Suspended'
        ];
    }

    public function create()
    {
        Validator::validate($this->formData->toArray(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);

        User::create([
            'name' => $this->formData->get('name'),
            'email' => $this->formData->get('email'),
            'status' => $this->formData->get('status')
        ]);
    }

    public function update()
    {
        $user = User::findOrFail($this->formData->get('id'));
        
        $user->update([
            'name' => $this->formData->get('name'),
            'email' => $this->formData->get('email'),
            'status' => $this->formData->get('status')
        ]);
    }

    public function delete()
    {
        User::destroy($this->formData->get('id'));
    }
}
```

### 4. Mass Operations
Handle bulk operations with special logic and safety checks.

```php
class MassUpdateProvider extends CrudProvider
{
    public function update()
    {
        $ids = explode(',', $this->formData->get('id'));
        $updates = $this->formData->toArray();
        unset($updates['id']);

        // Remove empty values to avoid overwriting with blanks
        foreach ($updates as $key => $value) {
            if (empty($value) || $value === 'NO_CHANGE') {
                unset($updates[$key]);
            }
        }

        if (empty($updates)) {
            return;
        }

        MyModel::whereIn('id', $ids)->update($updates);
    }

    public function delete()
    {
        $ids = explode(',', $this->formData->get('id'));
        
        // Check for protected records
        if (MyModel::whereIn('id', $ids)->where('protected', 1)->exists()) {
            return (new Response())->setError('Cannot delete protected items');
        }

        MyModel::whereIn('id', $ids)->each(function ($item) {
            $item->delete(); // Use model deletion for proper event handling
        });
    }
}
```

### 5. Complex Data Loading
Advanced providers with multiple data sources and relationships.

```php
class ComplexDataProvider extends CrudProvider
{
    public function read()
    {
        $item = Item::with('configurations', 'groups')->find($this->formData->get('id'));
        
        if (!$item) {
            return;
        }

        // Decode HTML entities for display
        $data = $item->toArray();
        foreach ($data as $key => &$value) {
            if (is_string($value)) {
                $value = html_entity_decode($value);
            }
        }

        $this->data = new DataContainer($data);
        
        // Load available values from multiple sources
        $this->availableValues['categories'] = Category::pluck('name', 'id')->toArray();
        $this->availableValues['statuses'] = Status::getAvailable();
        $this->availableValues['admins'] = Admin::select(['id', 'firstname', 'lastname'])
            ->get()
            ->mapWithKeys(fn($admin) => [$admin->id => "#{$admin->id} {$admin->firstname} {$admin->lastname}"])
            ->toArray();
    }
}
```

## Advanced Features

### Custom Actions
Data Providers can implement custom methods beyond CRUD operations.

```php
class CustomActionProvider extends CrudProvider
{
    const ACTION_DUPLICATE = 'duplicate';
    const ACTION_REFRESH = 'refresh';

    public function duplicate()
    {
        Validator::validate($this->formData->toArray(), [
            'name' => 'required',
            'source_id' => 'required|exists:items,id'
        ]);

        $sourceItem = Item::findOrFail($this->formData->get('source_id'));
        $newItem = $sourceItem->replicate();
        $newItem->name = $this->formData->get('name');
        $newItem->save();

        // Duplicate related records
        foreach ($sourceItem->configurations as $config) {
            $newConfig = $config->replicate();
            $newConfig->item_id = $newItem->id;
            $newConfig->save();
        }
    }

    public function refresh()
    {
        $mode = $this->formData->get('mode', 'update');
        
        switch ($mode) {
            case 'full_refresh':
                $this->deleteAllItems();
                $this->loadItemsFromSource();
                break;
            case 'update_existing':
                $this->updateExistingItems();
                break;
        }
    }
}
```

### Response Handling
Providers can return custom responses for error handling or special cases.

```php
public function create()
{
    if (MyModel::where('unique_field', $this->formData->get('unique_field'))->exists()) {
        return (new Response())->setError('Record with this value already exists');
    }

    // Proceed with creation...
}
```

## Best Practices

### Validation
- Always validate input data in `create()` and `update()` methods
- Use Laravel validation rules and custom Rule classes
- Validate required fields and data types
- Check for unique constraints and business rules

### Data Access
- Prefer Eloquent models over raw database queries
- Use `\WHMCS\Database\Capsule` (aliased as `DB`) when models are unavailable
- Implement proper error handling for missing records
- Use early returns to avoid nested conditions

### Security
- Validate user permissions before operations
- Sanitize input data appropriately
- Check for protected records before deletion
- Implement proper access controls

### Performance
- Use eager loading for related models when needed
- Implement pagination for large datasets
- Cache frequently accessed data when appropriate
- Optimize database queries

### Code Organization
- Keep providers focused on single responsibilities
- Extract complex logic into separate service classes
- Use dependency injection for external services
- Follow consistent naming conventions

## Integration with UI Components

Data Providers are automatically called by UI components based on user actions:
- Forms call appropriate CRUD methods based on form submission
- DataTables use providers for data loading and mass operations  
- Modal dialogs invoke providers for create/update operations
- Delete buttons trigger delete methods

The framework handles the routing between UI components and Data Providers automatically based on naming conventions and configuration.

## Error Handling

```php
public function update()
{
    try {
        Validator::validate($this->formData->toArray(), $this->getRules());
        
        $model = MyModel::findOrFail($this->formData->get('id'));
        $model->update($this->formData->toArray());
        
    } catch (ValidationException $e) {
        return (new Response())->setError($e->getMessage());
    } catch (ModelNotFoundException $e) {
        return (new Response())->setError('Record not found');
    }
}

private function getRules(): array
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email'
    ];
}
```

## Summary

Data Providers are essential for maintaining clean separation of concerns in WHMCS modules. They encapsulate all data-related logic, provide consistent interfaces for UI components, and ensure proper validation and error handling. By following the established patterns and best practices, developers can create maintainable and robust data handling systems within the OpenStackVpsCloud.
