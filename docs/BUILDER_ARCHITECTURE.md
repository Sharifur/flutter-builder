# Flutter Builder - Project & Builder Architecture

## Overview

The Flutter Builder is a visual, drag-and-drop interface for creating mobile app layouts using a PHP backend with dynamic UI components. It features a sophisticated widget system, data collection integration, and real-time preview capabilities.

## System Architecture

### High-Level Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │    │   Database      │
│   (Blade+JS)    │◄──►│   (Laravel)     │◄──►│   (SQLite)      │
│                 │    │                 │    │                 │
│ ┌─────────────┐ │    │ ┌─────────────┐ │    │ ┌─────────────┐ │
│ │  Builder    │ │    │ │ Controllers │ │    │ │ Projects    │ │
│ │  Interface  │ │    │ │             │ │    │ │ Pages       │ │
│ │             │ │    │ │ Models      │ │    │ │ Widgets     │ │
│ │ Component   │ │    │ │             │ │    │ │ Collections │ │
│ │ Palette     │ │    │ │ Services    │ │    │ │ Users       │ │
│ │             │ │    │ │             │ │    │ │             │ │
│ │ Canvas      │ │    │ │ Middleware  │ │    │ │             │ │
│ │             │ │    │ │             │ │    │ │             │ │
│ │ Properties  │ │    │ │ Requests    │ │    │ │             │ │
│ └─────────────┘ │    │ └─────────────┘ │    │ └─────────────┘ │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Core Components

1. **Projects**: Top-level containers for apps
2. **Pages**: Individual screens within projects
3. **Widgets**: UI components on pages
4. **Data Collections**: Dynamic database structures
5. **Builder Interface**: Visual editor

## Project Structure

### Database Schema

```sql
-- Core entities
projects (id, name, user_id, settings, created_at, updated_at)
app_pages (id, project_id, name, slug, created_at, updated_at)
widgets (id, page_id, type, config, order, created_at, updated_at)

-- Data collections
data_collections (id, project_id, name, slug, description, is_active)
collection_fields (id, collection_id, name, type, validation_rules, ...)
collection_records (id, collection_id, created_by, published_at, ...)
collection_data (id, record_id, field_id, field_value, field_type)

-- User management
users (id, name, email, password, created_at, updated_at)
```

### Model Relationships

```php
// Project relationships
Project::class
├── hasMany(AppPage::class)
├── hasMany(DataCollection::class)
└── belongsTo(User::class)

// Page relationships
AppPage::class
├── hasMany(Widget::class)
└── belongsTo(Project::class)

// Widget relationships
Widget::class
└── belongsTo(AppPage::class)

// Data Collection relationships
DataCollection::class
├── hasMany(CollectionField::class)
├── hasMany(CollectionRecord::class)
└── belongsTo(Project::class)
```

## Builder Interface

### Layout Structure

```html
<!-- Main Builder Layout -->
<div class="builder-layout">
    <!-- Header with project navigation -->
    <header class="builder-header">
        <project-selector />
        <page-tabs />
        <preview-controls />
    </header>

    <!-- Main content area -->
    <main class="builder-main">
        <!-- Left sidebar: Component palette -->
        <aside class="component-palette">
            <component-categories />
            <draggable-components />
        </aside>

        <!-- Center: Canvas area -->
        <section class="canvas-area">
            <page-canvas>
                <sortable-widget-list />
            </page-canvas>
        </section>

        <!-- Right sidebar: Properties panel -->
        <aside class="properties-panel">
            <widget-properties />
            <data-mapping />
        </aside>
    </main>
</div>
```

### Component Categories

1. **Basic Components**
   - Text, Button, Image, Input, Container

2. **Layout Components**
   - Card, TabBar, NavigationTabs

3. **Data Display**
   - StatCard, ChartCard, ProductCard

4. **Financial Components**
   - BalanceCard, CryptoItem, TransactionItem

5. **Authentication Components**
   - LoginForm, RegisterForm, UnifiedForm

6. **Interactive Components**
   - SearchHeader, ActionButton, ExpenseItem

## Widget System

### Widget Lifecycle

```javascript
// 1. Component Selection
dragComponent(type) →
// 2. Canvas Drop
dropOnCanvas(position) →
// 3. Widget Creation
createWidget(type, config) →
// 4. Database Storage
saveToDatabase() →
// 5. UI Rendering
renderWidget()
```

### Widget Configuration

Each widget has a standardized configuration structure:

```javascript
const widgetConfig = {
    // Basic properties
    type: 'ButtonWidget',

    // Visual properties
    label: 'Click Me',
    color: '#3B82F6',
    size: 'medium',

    // Behavior properties
    action: 'navigate',
    target: '/dashboard',

    // Data binding (if applicable)
    dataCollection: 'users',
    dataField: 'name',
    showUserData: true,

    // Layout properties
    margin: 10,
    padding: 15,
    alignment: 'center'
};
```

### Dynamic Widget Rendering

```php
// Server-side rendering
@if($widget->type === 'Button')
    <button class="widget-button"
            style="background: {{ $widget->config['color'] }}">
        {{ $widget->config['label'] }}
    </button>
@elseif($widget->type === 'Text')
    <p class="widget-text"
       style="font-size: {{ $widget->config['fontSize'] }}px">
        {{ $widget->config['value'] }}
    </p>
@endif
```

## Data Integration

### Form-to-Collection Mapping

```javascript
// Mapping configuration
const formMapping = {
    dataCollection: 'user_profiles',
    fields: {
        'name_input': 'full_name',
        'email_input': 'email_address',
        'phone_input': 'phone_number'
    },
    userRelation: 'user_id',
    autoPopulate: true
};

// Runtime data binding
function bindFormData(widget, mapping) {
    if (mapping.autoPopulate) {
        const userData = fetchUserData(mapping.dataCollection);
        populateFormFields(widget, userData, mapping.fields);
    }
}
```

### Real-time Data Display

```javascript
// Dynamic data widgets
const dataWidget = {
    type: 'BalanceCard',
    config: {
        dataCollection: 'wallets',
        dataField: 'balance',
        userField: 'user_id',
        refreshInterval: 30000, // 30 seconds
        formatters: {
            currency: 'USD',
            decimals: 2
        }
    }
};
```

## API Architecture

### RESTful Endpoints

```php
// Project management
GET    /user/projects                     # List projects
POST   /user/projects                     # Create project
GET    /user/projects/{project}           # Show project
PUT    /user/projects/{project}           # Update project
DELETE /user/projects/{project}           # Delete project

// Page management
GET    /user/projects/{project}/pages     # List pages
POST   /user/projects/{project}/pages     # Create page
GET    /user/projects/{project}/pages/{page} # Show page
PUT    /user/projects/{project}/pages/{page} # Update page
DELETE /user/projects/{project}/pages/{page} # Delete page

// Widget management
POST   /user/pages/{page}/widgets         # Create widget
PUT    /user/widgets/{widget}             # Update widget
DELETE /user/widgets/{widget}             # Delete widget
POST   /user/pages/{page}/widgets/reorder # Reorder widgets

// Builder interface
GET    /user/projects/{project}/builder   # Builder interface
GET    /user/projects/{project}/preview   # Preview interface
```

### Data Collection APIs

```php
// Collection management
GET    /user/projects/{project}/data-collections-mapping
GET    /user/projects/{project}/data-collections/{collection}/user-data
GET    /user/projects/{project}/related-collections

// Auto-generated collection APIs
GET    /api/projects/{project}/collections/{collection}
POST   /api/projects/{project}/collections/{collection}
PUT    /api/projects/{project}/collections/{collection}/{record}
DELETE /api/projects/{project}/collections/{collection}/{record}
```

## Security Model

### Authentication & Authorization

```php
// Multi-guard authentication
Route::middleware(['user'])->group(function () {
    // User routes
});

Route::middleware(['admin'])->group(function () {
    // Admin routes
});

// Project ownership verification
private function ensureUserOwnsProject(Project $project)
{
    if (auth()->user()->id !== $project->user_id) {
        abort(403, 'Unauthorized access to this project.');
    }
}
```

### Data Access Control

```php
// Scope queries to user's projects
$projects = auth()->user()->projects()
    ->with('appPages.widgets')
    ->get();

// Protect collection data
$records = $collection->records()
    ->where('created_by', auth()->id())
    ->get();
```

## Performance Optimizations

### Database Optimizations

```php
// Eager loading relationships
$projects = Project::with([
    'appPages.widgets',
    'dataCollections.allFields'
])->where('user_id', auth()->id())->get();

// Efficient widget queries
$widgets = Widget::where('page_id', $pageId)
    ->orderBy('order')
    ->get();
```

### Frontend Optimizations

```javascript
// Lazy load widget properties
const loadWidgetProperties = debounce(async (widgetId) => {
    const properties = await fetch(`/api/widgets/${widgetId}/properties`);
    return properties.json();
}, 300);

// Efficient drag and drop
const optimizedSortable = new Sortable(canvas, {
    animation: 150,
    ghostClass: 'sortable-ghost',
    chosenClass: 'sortable-chosen',
    onEnd: debounce(saveWidgetOrder, 500)
});
```

### Caching Strategy

```php
// Cache widget configurations
Cache::remember("widget_config_{$widgetId}", 3600, function () use ($widget) {
    return $widget->fresh(['page', 'page.project']);
});

// Cache data collection schemas
Cache::remember("collection_fields_{$collectionId}", 1800, function () use ($collection) {
    return $collection->allFields()->active()->get();
});
```

## Error Handling

### Frontend Error Handling

```javascript
// Global error handler
window.addEventListener('unhandledrejection', (event) => {
    console.error('Unhandled promise rejection:', event.reason);
    showNotification('An error occurred. Please try again.', 'error');
});

// API error handling
async function apiCall(url, options = {}) {
    try {
        const response = await fetch(url, {
            ...options,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                ...options.headers
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        return await response.json();
    } catch (error) {
        console.error('API call failed:', error);
        throw error;
    }
}
```

### Backend Error Handling

```php
// Custom exception handling
class BuilderException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $this->getMessage(),
                'code' => $this->getCode()
            ], 400);
        }

        return back()->withErrors(['error' => $this->getMessage()]);
    }
}

// Validation error handling
public function store(Request $request)
{
    try {
        $validated = $request->validate($rules);
        // Process...
    } catch (ValidationException $e) {
        return response()->json([
            'errors' => $e->errors()
        ], 422);
    }
}
```

## Extensibility

### Adding New Widget Types

1. **Define Widget Configuration**
```javascript
// Add to getDefaultConfig()
'CustomWidget': {
    title: 'Custom Title',
    customProperty: 'default value'
}
```

2. **Add Blade Template**
```php
@elseif($widget->type === 'CustomWidget')
<!-- Custom Widget Template -->
<div class="custom-widget">
    {{ $widget->config['title'] }}
</div>
```

3. **Add to Component Palette**
```php
// Add to component list
@foreach(['Text', 'Button', 'CustomWidget'] as $component)
```

### Extending Data Collections

```php
// Custom field types
public const FIELD_TYPES = [
    // ... existing types
    'custom_type' => 'Custom Type',
    'geo_location' => 'Geographic Location',
    'rich_text' => 'Rich Text Editor'
];

// Custom validation rules
public function getCustomValidation($type)
{
    return match($type) {
        'geo_location' => 'regex:/^-?\d+\.?\d*,-?\d+\.?\d*$/',
        'rich_text' => 'string|max:10000',
        default => ''
    };
}
```

This architecture provides a solid foundation for a scalable, maintainable Flutter app builder with dynamic data capabilities.