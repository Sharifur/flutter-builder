# Data Collections Documentation

## Overview

Data Collections are dynamic database structures that allow users to create custom data models without writing code. They provide a flexible way to store, manage, and retrieve application data with built-in validation, relationships, and API endpoints.

## Core Concepts

### Data Collection Structure

Each Data Collection consists of:
- **Collection**: The main container (similar to a database table)
- **Fields**: Individual data columns with types and validation
- **Records**: Individual data entries
- **Data**: The actual field values for each record

### Database Architecture

```
data_collections (collections metadata)
├── collection_fields (field definitions)
├── collection_records (record instances)
└── collection_data (actual field values)
```

## Collection Management

### Creating Collections

```php
$collection = $project->dataCollections()->create([
    'name' => 'Users',
    'slug' => 'users',
    'description' => 'User profiles and information',
    'is_active' => true,
    'is_system' => false
]);
```

### Field Types

| Type | Description | Validation | Use Cases |
|------|-------------|------------|-----------|
| `text` | Short text | max:255 | Names, titles, short descriptions |
| `textarea` | Long text | - | Descriptions, comments, articles |
| `email` | Email address | email format | User emails, contact forms |
| `url` | Web URL | URL format | Website links, references |
| `number` | Integer | integer | Counts, IDs, quantities |
| `decimal` | Decimal number | numeric | Prices, measurements, percentages |
| `boolean` | True/false | boolean | Flags, settings, toggles |
| `date` | Date only | date format | Birth dates, deadlines |
| `datetime` | Date and time | datetime format | Timestamps, appointments |
| `time` | Time only | time format | Opening hours, schedules |
| `select` | Dropdown | predefined options | Categories, statuses |
| `multiselect` | Multiple choice | array of options | Tags, multiple categories |
| `checkbox` | Multiple checkboxes | array | Features, permissions |
| `radio` | Single choice | single option | Gender, priority levels |
| `file` | File upload | file validation | Documents, attachments |
| `image` | Image upload | image validation | Photos, avatars, galleries |
| `json` | JSON data | JSON format | Complex data structures |
| `relation` | Related records | foreign key | Links to other collections |
| `color` | Color picker | hex color | Theme colors, preferences |
| `password` | Password field | encrypted | User passwords |

### Field Configuration

```php
$field = $collection->allFields()->create([
    'name' => 'email',
    'label' => 'Email Address',
    'type' => 'email',
    'is_required' => true,
    'is_unique' => true,
    'is_searchable' => true,
    'validation_rules' => ['email', 'max:255'],
    'field_options' => [],
    'sort_order' => 1
]);
```

## Relationships

### Relation Types

1. **belongsTo**: One-to-one or many-to-one
2. **hasMany**: One-to-many
3. **manyToMany**: Many-to-many (through pivot)

### Setting Up Relations

```php
// User belongsTo Company
$userEmailField = $userCollection->allFields()->create([
    'name' => 'company_id',
    'type' => 'relation',
    'related_collection_id' => $companyCollection->id,
    'relation_type' => 'belongsTo',
    'foreign_key' => 'company_id',
    'local_key' => 'id'
]);

// Company hasMany Users
$relation_config = [
    'display_field' => 'name', // Which field to show in dropdowns
    'allow_multiple' => false,
    'cascade_delete' => false
];
```

## Data Management

### Creating Records

```php
$record = $collection->createRecord([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'age' => 30,
    'is_active' => true
], $user);
```

### Querying Data

```php
// Get all records
$records = $collection->records()->with(['data.field'])->get();

// Get user-specific records
$userRecords = $collection->records()
    ->where('created_by', $userId)
    ->latest()
    ->paginate(20);

// Search records
$searchResults = $collection->records()
    ->whereHas('data', function($query) use ($searchTerm) {
        $query->where('field_value', 'like', "%{$searchTerm}%");
    })
    ->get();
```

### Data Validation

Each field automatically generates validation rules:

```php
// Field validation is automatically applied
$rules = $field->getValidationRulesString();
// Returns: "required|email|unique:collection_data,field_value"
```

## API Endpoints

### Auto-Generated REST API

Each collection automatically gets REST endpoints:

```
GET    /api/projects/{project}/collections/{collection}           # List records
POST   /api/projects/{project}/collections/{collection}           # Create record
GET    /api/projects/{project}/collections/{collection}/{record}  # Show record
PUT    /api/projects/{project}/collections/{collection}/{record}  # Update record
DELETE /api/projects/{project}/collections/{collection}/{record}  # Delete record
```

### Custom Endpoints

```php
// Get user-specific data
GET /user/projects/{project}/data-collections/{collection}/user-data

// Get collections for mapping
GET /user/projects/{project}/data-collections-mapping

// Get related collections
GET /user/projects/{project}/related-collections
```

## Integration with Forms

### Form Field Mapping

Data collections can be mapped to form fields for automatic data binding:

```php
// LoginForm mapping
$loginConfig = [
    'dataCollection' => 'users',
    'emailField' => 'email',
    'passwordField' => 'password'
];

// UnifiedForm mapping
$formConfig = [
    'dataCollection' => 'contacts',
    'formFields' => [
        [
            'type' => 'text',
            'name' => 'full_name',
            'mappedField' => 'name',
            'showUserData' => true
        ],
        [
            'type' => 'email',
            'name' => 'email_address',
            'mappedField' => 'email',
            'showUserData' => false
        ]
    ]
];
```

### Dynamic Data Display

Forms can display existing user data:

```php
// Show user's wallet balance
$widget->config = [
    'dataCollection' => 'wallets',
    'displayField' => 'balance',
    'userRelationField' => 'user_id',
    'enableUserDataDisplay' => true
];
```

## Security & Permissions

### Project Isolation
- Collections belong to specific projects
- Users can only access their own project's collections
- Authorization is enforced at the controller level

### Field-Level Security
```php
private function ensureUserOwnsProject(Project $project)
{
    if (auth()->user()->id !== $project->user_id) {
        abort(403, 'Unauthorized access to this project.');
    }
}
```

### Data Access Control
```php
// Only show user's own records
$records = $collection->records()
    ->where('created_by', auth()->id())
    ->get();
```

## Advanced Features

### Dynamic Field Types

Fields can be added/removed without migrations:

```php
// Add new field type on the fly
$collection->allFields()->create([
    'name' => 'custom_data',
    'type' => 'json',
    'field_options' => [
        'schema' => [
            'properties' => [
                'latitude' => ['type' => 'number'],
                'longitude' => ['type' => 'number']
            ]
        ]
    ]
]);
```

### Polymorphic Relationships

Collections can reference multiple types:

```php
$field = $collection->allFields()->create([
    'name' => 'attachable',
    'type' => 'relation',
    'relation_config' => [
        'polymorphic' => true,
        'types' => ['users', 'projects', 'documents']
    ]
]);
```

### Computed Fields

Fields can have computed values:

```php
$field = $collection->allFields()->create([
    'name' => 'full_name',
    'type' => 'computed',
    'field_options' => [
        'formula' => 'CONCAT(first_name, " ", last_name)',
        'depends_on' => ['first_name', 'last_name']
    ]
]);
```

## Best Practices

### Naming Conventions
- Use lowercase, underscores for field names: `first_name`, `email_address`
- Use descriptive collection names: `user_profiles`, `product_categories`
- Avoid reserved words: `id`, `created_at`, `updated_at`

### Performance
- Add indexes to searchable fields
- Use appropriate field types for data
- Limit the number of fields per collection (< 50)
- Use pagination for large datasets

### Data Integrity
- Always set required fields appropriately
- Use unique constraints where needed
- Validate foreign keys in relations
- Implement soft deletes for important data

### UI/UX Guidelines
- Provide clear field labels and help text
- Group related fields logically
- Use appropriate input types for better UX
- Show validation errors clearly

## Troubleshooting

### Common Issues

1. **Field validation errors**
   - Check field type matches data
   - Verify required fields are provided
   - Ensure unique constraints aren't violated

2. **Relation issues**
   - Verify related collection exists
   - Check foreign key field names
   - Ensure proper relation type is set

3. **Performance problems**
   - Add eager loading for relations
   - Use appropriate pagination
   - Consider field indexing

### Debug Mode

Enable detailed logging:

```php
// In controller
Log::info('Creating record', [
    'collection' => $collection->name,
    'data' => $validatedData,
    'user' => auth()->id()
]);
```

## Examples

### Complete User Management System

```php
// 1. Create Users collection
$users = $project->dataCollections()->create([
    'name' => 'Users',
    'slug' => 'users'
]);

// 2. Add fields
$users->allFields()->createMany([
    [
        'name' => 'first_name',
        'type' => 'text',
        'label' => 'First Name',
        'is_required' => true
    ],
    [
        'name' => 'email',
        'type' => 'email',
        'label' => 'Email',
        'is_required' => true,
        'is_unique' => true
    ],
    [
        'name' => 'profile_image',
        'type' => 'image',
        'label' => 'Profile Photo'
    ]
]);

// 3. Create form mapping
$registrationForm = [
    'type' => 'UnifiedForm',
    'config' => [
        'dataCollection' => 'users',
        'formFields' => [
            [
                'type' => 'text',
                'name' => 'first_name',
                'mappedField' => 'first_name',
                'required' => true
            ],
            [
                'type' => 'email',
                'name' => 'email',
                'mappedField' => 'email',
                'required' => true
            ]
        ]
    ]
];
```

This documentation provides a complete guide to understanding and using the Data Collections system.