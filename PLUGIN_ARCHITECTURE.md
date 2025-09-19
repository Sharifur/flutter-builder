# Flutter Builder Plugin Architecture Documentation

## Overview

The Flutter Builder has been enhanced with a comprehensive PHP-based plugin architecture that enables dynamic UI component development, field definition management, and API-driven frontend interactions. This system provides a scalable foundation for building sophisticated mobile app interfaces.

## System Architecture

### Core Components

#### 1. Plugin Directory Structure
```
plugins/Builder/
├── Contracts/
│   └── ComponentInterface.php      # Interface defining component contract
├── Components/
│   ├── BaseComponent.php          # Abstract base class for all components
│   ├── TextComponent.php          # Example text component implementation
│   └── ButtonComponent.php        # Example button component implementation
└── Fields/
    ├── TextField.php              # Text field definition
    ├── ColorField.php             # Color picker field definition
    ├── NumberField.php            # Number input field definition
    └── SelectField.php            # Dropdown select field definition
```

#### 2. Database Schema

##### UI Components Table (`ui_components`)
```sql
- id: Primary key
- name: Human-readable component name
- component_type: Unique identifier for the component
- category: Grouping category (basic, financial, etc.)
- description: Component description
- default_config: JSON default configuration
- field_definitions: JSON field schema for editing
- icon: Icon identifier for UI
- preview_image: Optional preview image path
- is_active: Boolean active status
- sort_order: Display order
- php_class: Full PHP class name for instantiation
- dependencies: JSON array of required dependencies
- timestamps: Created/updated timestamps
```

#### 3. Component Interface Contract

All components must implement `ComponentInterface`:

```php
interface ComponentInterface
{
    public function getName(): string;
    public function getType(): string;
    public function getCategory(): string;
    public function getDescription(): string;
    public function getIcon(): ?string;
    public function getDefaultConfig(): array;
    public function getFieldDefinitions(): array;
    public function render(array $config): array;
    public function validate(array $config): bool;
    public function getConfigSchema(): array;
}
```

## API Endpoints

### UI Components API (`/user/api/ui-components/`)

- **GET /** - List all active components (supports filtering by category and type)
- **GET /categories** - Get available component categories
- **GET /{component}** - Get specific component details
- **POST /{component}/render** - Render component with given configuration
- **POST /{component}/validate** - Validate component configuration

### Widget Management API

- **POST /pages/{page}/widgets** - Create new widget (now validates against UI components)
- **PUT /widgets/{widget}** - Update widget configuration
- **DELETE /widgets/{widget}** - Delete widget
- **GET /widgets/{widget}/edit** - Get widget for editing

## Component Development Guide

### Creating a New Component

1. **Create Component Class**
```php
<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;

class CustomComponent extends BaseComponent
{
    protected string $name = 'Custom Widget';
    protected string $type = 'Custom';
    protected string $category = 'basic';
    protected string $description = 'A custom widget component';
    protected ?string $icon = 'custom-icon';
    protected int $sortOrder = 10;

    protected array $defaultConfig = [
        'title' => 'Default Title',
        'color' => '#000000',
    ];

    public function getFieldDefinitions(): array
    {
        return [
            TextField::create('title', 'Title', 'Default Title', true),
            ColorField::create('color', 'Text Color', '#000000'),
        ];
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'CustomWidget',
            'data' => [
                'title' => $mergedConfig['title'],
                'style' => [
                    'color' => $mergedConfig['color'],
                ],
            ],
        ];
    }
}
```

2. **Register Component**
Add to `UiComponentSeeder`:
```php
$components = [
    // ... existing components
    CustomComponent::class,
];
```

3. **Run Seeder**
```bash
php artisan db:seed --class=UiComponentSeeder
```

### Field Types Available

#### TextField
```php
TextField::create(
    $key,           // Config key
    $label,         // Display label
    $defaultValue,  // Default value
    $required,      // Required flag
    $placeholder,   // Placeholder text
    $maxLength,     // Maximum length
    $helpText       // Help text
);
```

#### ColorField
```php
ColorField::create(
    $key,           // Config key
    $label,         // Display label
    $defaultValue,  // Default color (#000000)
    $required,      // Required flag
    $helpText       // Help text
);
```

#### NumberField
```php
NumberField::create(
    $key,           // Config key
    $label,         // Display label
    $defaultValue,  // Default number
    $required,      // Required flag
    $min,           // Minimum value
    $max,           // Maximum value
    $helpText       // Help text
);
```

#### SelectField
```php
SelectField::create(
    $key,           // Config key
    $label,         // Display label
    $options,       // Array of value => label pairs
    $defaultValue,  // Default selected value
    $required,      // Required flag
    $helpText       // Help text
);
```

## Frontend Integration

### Component Loading
The builder interface dynamically loads components from the API:

```javascript
// Load components on initialization
function loadComponents() {
    fetch('/user/api/ui-components/')
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                renderComponents(result.components);
            }
        });
}
```

### Dynamic Form Generation
Edit forms are generated based on component field definitions:

```javascript
// Generate form fields from component definitions
function renderDynamicForm(widget, fieldDefinitions, config) {
    fieldDefinitions.forEach(field => {
        switch (field.type) {
            case 'text':
                // Render text input
                break;
            case 'color':
                // Render color picker
                break;
            case 'number':
                // Render number input
                break;
            case 'select':
                // Render select dropdown
                break;
        }
    });
}
```

### Universal Widget Controls
All widgets include standardized controls:

- **Drag Handle**: For reordering widgets via drag-and-drop
- **Settings Icon**: Opens component-specific editing interface
- **Delete Icon**: Removes widget with confirmation

## Validation System

### Server-side Validation
Components include built-in validation:

```php
public function validate(array $config): bool
{
    $schema = $this->getConfigSchema();

    foreach ($schema as $field => $rules) {
        if ($rules['required'] && empty($config[$field])) {
            return false;
        }

        // Type validation, range checks, etc.
    }

    return true;
}
```

### Client-side Integration
Widget creation validates against available components:

```php
// WidgetController validates against active UI components
$availableTypes = UiComponent::active()->pluck('component_type')->toArray();

$request->validate([
    'type' => 'required|string|in:' . implode(',', $availableTypes),
    // ...
]);
```

## Configuration Management

### Default Configuration
Each component defines sensible defaults:

```php
protected array $defaultConfig = [
    'value' => 'Welcome to your app',
    'color' => '#1F2937',
    'fontSize' => 18,
    'fontWeight' => '600',
    'textAlign' => 'left',
];
```

### Runtime Configuration
Configuration is merged at render time:

```php
public function render(array $config): array
{
    $mergedConfig = array_merge($this->defaultConfig, $config);
    // Use merged configuration for rendering
}
```

## Error Handling

### Component Loading Errors
- Graceful degradation when components fail to load
- Retry mechanisms for API failures
- Fallback interfaces for editing

### Validation Errors
- Server-side validation prevents invalid widget creation
- Client-side feedback for configuration errors
- Type safety through component contracts

## Performance Considerations

### Caching Strategy
- Component definitions cached in database
- API responses optimized with proper indexing
- Frontend component palette loaded once per session

### Database Optimization
- Indexes on frequently queried fields (`component_type`, `category`, `is_active`)
- JSON field optimization for configuration storage
- Efficient relationship queries

## Security Features

### Access Control
- All API endpoints protected by user authentication
- Authorization checks for project ownership
- CSRF protection on all forms

### Input Validation
- Component configuration validated against schemas
- SQL injection prevention through Eloquent ORM
- XSS protection via proper output escaping

## Testing Strategy

### Component Testing
```php
public function test_component_validation()
{
    $component = new TextComponent();

    $validConfig = ['value' => 'Test', 'color' => '#000000'];
    $this->assertTrue($component->validate($validConfig));

    $invalidConfig = ['value' => '', 'color' => 'invalid'];
    $this->assertFalse($component->validate($invalidConfig));
}
```

### API Testing
```php
public function test_component_listing()
{
    $response = $this->get('/user/api/ui-components/');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'success',
                 'components' => [
                     '*' => ['id', 'name', 'type', 'category']
                 ]
             ]);
}
```

## Migration & Deployment

### Database Migration
```bash
php artisan migrate
php artisan db:seed --class=UiComponentSeeder
```

### Composer Autoloading
```bash
composer dump-autoload
```

### Asset Compilation
```bash
npm run build  # If using build system
```

## Troubleshooting

### Common Issues

1. **"Component class not found"**
   - Ensure composer autoload includes plugins directory
   - Verify class namespace matches directory structure

2. **"The selected type is invalid"**
   - Check component is active in database
   - Verify component type matches exactly

3. **"Field definitions not loading"**
   - Confirm component implements getFieldDefinitions()
   - Check API endpoint accessibility

### Debug Commands
```bash
# Check component registration
php artisan tinker
>>> App\Models\UiComponent::active()->get();

# Test component instantiation
>>> $component = new Plugins\Builder\Components\TextComponent();
>>> $component->getFieldDefinitions();
```

This architecture provides a robust, scalable foundation for component development while maintaining clean separation of concerns and enabling future extensibility.