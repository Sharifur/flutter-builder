# Dynamic UI Component System

## Overview

The Dynamic UI Component System is the core of the Flutter Builder, providing a flexible, extensible framework for creating, configuring, and rendering UI widgets. It supports drag-and-drop functionality, real-time property editing, and seamless data integration.

## Component Architecture

### Component Structure

Each UI component consists of:
- **Type Definition**: Unique identifier and metadata
- **Configuration Schema**: Configurable properties
- **Rendering Template**: Blade template for display
- **Property Panel**: Dynamic form for editing
- **Data Bindings**: Integration with data collections

### Component Lifecycle

```
1. Registration → 2. Selection → 3. Instantiation → 4. Configuration → 5. Rendering
```

## Component Registration

### Basic Component Registration

```javascript
// Component palette registration
const componentCategories = {
    'Basic': ['Text', 'Button', 'Image', 'Input', 'Container'],
    'Layout': ['Card', 'TabBar', 'NavigationTabs'],
    'Data': ['StatCard', 'ChartCard', 'ProductCard'],
    'Financial': ['BalanceCard', 'CryptoItem', 'TransactionItem'],
    'Authentication': ['LoginForm', 'RegisterForm', 'UnifiedForm']
};
```

### Component Metadata

```javascript
// Component definitions with icons and descriptions
const componentMetadata = {
    'Text': {
        icon: 'text-icon',
        description: 'Display text content',
        category: 'Basic',
        tags: ['content', 'typography']
    },
    'Button': {
        icon: 'button-icon',
        description: 'Interactive button element',
        category: 'Basic',
        tags: ['interactive', 'action']
    }
};
```

## Configuration System

### Default Configurations

```javascript
function getDefaultConfig(type) {
    const configs = {
        'Text': {
            value: 'Welcome to your app',
            color: '#1F2937',
            fontSize: 18,
            fontWeight: '600',
            textAlign: 'left'
        },
        'Button': {
            label: 'Get Started',
            color: '#3B82F6',
            textColor: '#FFFFFF',
            action: null,
            borderRadius: 8,
            size: 'medium'
        },
        'UnifiedForm': {
            title: 'Dynamic Form',
            subtitle: 'Fill out the form below',
            buttonText: 'Submit Form',
            buttonColor: '#4F46E5',
            dataCollection: null,
            formFields: [
                {
                    type: 'text',
                    name: 'sample_field',
                    label: 'Sample Field',
                    placeholder: 'Enter sample data',
                    required: false,
                    mappedField: null,
                    showUserData: false
                }
            ],
            enableUserDataDisplay: false,
            userRelationField: null,
            requiresAuthentication: false
        }
    };
    return configs[type] || {};
}
```

### Property Types

| Type | Description | UI Element | Validation |
|------|-------------|------------|------------|
| `text` | Text input | `<input type="text">` | maxLength, pattern |
| `color` | Color picker | `<input type="color">` | hex format |
| `number` | Numeric input | `<input type="number">` | min, max, step |
| `boolean` | Checkbox | `<input type="checkbox">` | boolean |
| `select` | Dropdown | `<select>` | enum values |
| `array` | Dynamic list | Custom array editor | array validation |
| `json` | JSON object | Textarea + validation | JSON schema |

## Rendering System

### Template Engine

Components are rendered using Blade templates with conditional logic:

```php
@if($widget->type === 'Text')
    <!-- Text Widget -->
    <div class="widget-text"
         style="color: {{ $widget->config['color'] }};
                font-size: {{ $widget->config['fontSize'] }}px;
                font-weight: {{ $widget->config['fontWeight'] }};
                text-align: {{ $widget->config['textAlign'] }};">
        {{ $widget->config['value'] }}
    </div>

@elseif($widget->type === 'Button')
    <!-- Button Widget -->
    <button class="widget-button {{ $widget->config['size'] }}"
            style="background-color: {{ $widget->config['color'] }};
                   color: {{ $widget->config['textColor'] }};
                   border-radius: {{ $widget->config['borderRadius'] }}px;"
            onclick="handleButtonAction('{{ $widget->config['action'] }}')">
        {{ $widget->config['label'] }}
    </button>

@elseif($widget->type === 'UnifiedForm')
    <!-- Unified Form Widget -->
    <div class="unified-form-widget">
        <div class="form-header">
            <h2>{{ $widget->config['title'] }}</h2>
            <p>{{ $widget->config['subtitle'] }}</p>
        </div>

        <form class="dynamic-form">
            @if($widget->config['formFields'])
                @foreach($widget->config['formFields'] as $index => $field)
                    <div class="form-field">
                        <label>{{ $field['label'] }}</label>

                        @if($field['type'] === 'textarea')
                            <textarea name="{{ $field['name'] }}"
                                      placeholder="{{ $field['placeholder'] }}"></textarea>
                        @elseif($field['type'] === 'select')
                            <select name="{{ $field['name'] }}">
                                @foreach($field['options'] as $option)
                                    <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="{{ $field['type'] }}"
                                   name="{{ $field['name'] }}"
                                   placeholder="{{ $field['placeholder'] }}">
                        @endif

                        @if($field['mappedField'])
                            <div class="mapping-indicator">
                                🔗 Mapped to: {{ $widget->config['dataCollection'] }}.{{ $field['mappedField'] }}
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif

            <button type="submit"
                    style="background-color: {{ $widget->config['buttonColor'] }}">
                {{ $widget->config['buttonText'] }}
            </button>
        </form>
    </div>
@endif
```

### Dynamic Property Rendering

```javascript
function renderDynamicForm(widget, fieldDefinitions, config) {
    let formFields = '<div class="space-y-3">';

    fieldDefinitions.forEach(field => {
        const value = config[field.key] || field.default || '';

        switch (field.type) {
            case 'text':
                formFields += `
                    <div>
                        <label class="form-label">${field.label}</label>
                        <input type="text" name="${field.key}" value="${value}"
                               placeholder="${field.placeholder || ''}"
                               class="form-input">
                    </div>
                `;
                break;

            case 'color':
                formFields += `
                    <div>
                        <label class="form-label">${field.label}</label>
                        <input type="color" name="${field.key}" value="${value}"
                               class="form-color-input">
                    </div>
                `;
                break;

            case 'array':
                const arrayValue = Array.isArray(value) ? value : [];
                formFields += `
                    <div>
                        <label class="form-label">${field.label}</label>
                        <div class="array-editor" data-field="${field.key}">
                            ${arrayValue.map((item, index) => `
                                <div class="array-item">
                                    <input type="text" value="${item}" data-index="${index}">
                                    <button type="button" class="remove-item">Remove</button>
                                </div>
                            `).join('')}
                        </div>
                        <button type="button" class="add-array-item" data-field="${field.key}">
                            Add Item
                        </button>
                    </div>
                `;
                break;
        }
    });

    formFields += '</div>';
    return formFields;
}
```

## Data Integration

### Collection Binding

```javascript
// Data collection mapping for forms
const dataMapping = {
    'LoginForm': [
        { label: 'Email', configKey: 'emailField', expectedType: 'email' },
        { label: 'Password', configKey: 'passwordField', expectedType: 'password' }
    ],
    'RegisterForm': [
        { label: 'Name', configKey: 'nameField', expectedType: 'text' },
        { label: 'Email', configKey: 'emailField', expectedType: 'email' },
        { label: 'Phone', configKey: 'phoneField', expectedType: 'text' },
        { label: 'Password', configKey: 'passwordField', expectedType: 'password' }
    ],
    'UnifiedForm': [] // Dynamic fields handled separately
};

// Field compatibility checking
function isFieldCompatible(collectionFieldType, expectedType) {
    const compatibility = {
        'email': ['email', 'text'],
        'password': ['password', 'text'],
        'text': ['text', 'textarea', 'email', 'url'],
        'number': ['number', 'decimal']
    };

    const compatibleTypes = compatibility[expectedType] || [expectedType];
    return compatibleTypes.includes(collectionFieldType);
}
```

### Real-time Data Display

```javascript
// Dynamic data widgets with user context
const dataDisplayConfig = {
    type: 'BalanceCard',
    config: {
        dataCollection: 'wallets',
        displayField: 'balance',
        userRelationField: 'user_id',
        enableUserDataDisplay: true,
        formatters: {
            type: 'currency',
            currency: 'USD',
            decimals: 2
        },
        refreshInterval: 30000
    }
};

// Data fetching and binding
async function bindWidgetData(widget) {
    if (widget.config.dataCollection && widget.config.enableUserDataDisplay) {
        const userData = await fetchUserData(
            widget.config.dataCollection,
            widget.config.userRelationField
        );

        updateWidgetDisplay(widget, userData);
    }
}
```

## Property Panel System

### Dynamic Property Generation

```javascript
function generatePropertyPanel(widget) {
    const propertiesPanel = document.getElementById('properties-panel');

    // Load component field definitions
    fetch(`/user/api/ui-components?type=${widget.type}`)
        .then(response => response.json())
        .then(result => {
            if (result.success && result.components.length > 0) {
                const component = result.components[0];
                renderDynamicForm(widget, component.field_definitions, widget.config);
            } else {
                renderFallbackForm(widget);
            }
        });
}

function renderDataCollectionMapping(widget, collections) {
    if (widget.type === 'LoginForm' || widget.type === 'RegisterForm' || widget.type === 'UnifiedForm') {
        const mappingHtml = `
            <div class="data-mapping-section">
                <h5>Data Collection Mapping</h5>
                <select name="dataCollection" onchange="updateFieldMappings('${widget.type}')">
                    <option value="">No mapping</option>
                    ${collections.map(collection => `
                        <option value="${collection.slug}"
                                ${widget.config.dataCollection === collection.slug ? 'selected' : ''}>
                            ${collection.name}
                        </option>
                    `).join('')}
                </select>

                <div id="field-mappings">
                    ${generateFieldMappings(widget.type, widget.config)}
                </div>
            </div>
        `;

        return mappingHtml;
    }
    return '';
}
```

### Form Validation

```javascript
function validateWidgetConfig(widget, config) {
    const errors = [];

    // Type-specific validation
    switch (widget.type) {
        case 'Text':
            if (!config.value || config.value.trim() === '') {
                errors.push('Text value is required');
            }
            break;

        case 'Button':
            if (!config.label || config.label.trim() === '') {
                errors.push('Button label is required');
            }
            break;

        case 'UnifiedForm':
            if (!config.formFields || config.formFields.length === 0) {
                errors.push('At least one form field is required');
            }

            config.formFields?.forEach((field, index) => {
                if (!field.name || !field.label) {
                    errors.push(`Form field ${index + 1} requires name and label`);
                }
            });
            break;
    }

    return errors;
}
```

## Event System

### Widget Events

```javascript
// Widget interaction events
const widgetEvents = {
    'widget:created': (widget) => {
        console.log('Widget created:', widget.type);
        trackEvent('widget_created', { type: widget.type });
    },

    'widget:updated': (widget, changes) => {
        console.log('Widget updated:', widget.id, changes);
        saveWidgetConfig(widget.id, widget.config);
    },

    'widget:deleted': (widgetId) => {
        console.log('Widget deleted:', widgetId);
        removeWidgetFromCanvas(widgetId);
    },

    'data:mapped': (widget, mapping) => {
        console.log('Data mapped to widget:', widget.id, mapping);
        refreshWidgetData(widget);
    }
};

// Event dispatcher
function dispatchWidgetEvent(eventName, ...args) {
    if (widgetEvents[eventName]) {
        widgetEvents[eventName](...args);
    }
}
```

### Drag and Drop Events

```javascript
// Initialize drag and drop
function initializeDragAndDrop() {
    // Component palette drag
    document.querySelectorAll('.drag-component').forEach(component => {
        component.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', component.dataset.component);
            e.dataTransfer.effectAllowed = 'copy';
        });
    });

    // Canvas drop
    const canvas = document.getElementById('canvas-area');
    canvas.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
    });

    canvas.addEventListener('drop', (e) => {
        e.preventDefault();
        const componentType = e.dataTransfer.getData('text/plain');
        const dropPosition = calculateDropPosition(e);

        createWidget(componentType, dropPosition);
    });
}

// Widget reordering
function initializeWidgetSorting() {
    new Sortable(document.getElementById('canvas-area'), {
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function(evt) {
            updateWidgetOrder(evt.oldIndex, evt.newIndex);
        }
    });
}
```

## Performance Optimization

### Efficient Rendering

```javascript
// Virtual scrolling for large component lists
class VirtualComponentList {
    constructor(container, items, itemHeight) {
        this.container = container;
        this.items = items;
        this.itemHeight = itemHeight;
        this.visibleStart = 0;
        this.visibleEnd = 0;

        this.render();
    }

    render() {
        const containerHeight = this.container.clientHeight;
        const scrollTop = this.container.scrollTop;

        this.visibleStart = Math.floor(scrollTop / this.itemHeight);
        this.visibleEnd = Math.min(
            this.visibleStart + Math.ceil(containerHeight / this.itemHeight) + 1,
            this.items.length
        );

        this.renderVisibleItems();
    }

    renderVisibleItems() {
        const fragment = document.createDocumentFragment();

        for (let i = this.visibleStart; i < this.visibleEnd; i++) {
            const item = this.createItemElement(this.items[i], i);
            fragment.appendChild(item);
        }

        this.container.innerHTML = '';
        this.container.appendChild(fragment);
    }
}

// Debounced property updates
const debouncedPropertyUpdate = debounce((widgetId, property, value) => {
    updateWidgetProperty(widgetId, property, value);
}, 300);

// Lazy loading of widget properties
const lazyLoadProperties = async (widgetId) => {
    if (!propertyCache[widgetId]) {
        propertyCache[widgetId] = await fetchWidgetProperties(widgetId);
    }
    return propertyCache[widgetId];
};
```

### Memory Management

```javascript
// Component cleanup
class WidgetManager {
    constructor() {
        this.widgets = new Map();
        this.observers = new Map();
    }

    addWidget(widget) {
        this.widgets.set(widget.id, widget);

        // Set up property observer
        const observer = new PropertyObserver(widget, (changes) => {
            this.handleWidgetChanges(widget.id, changes);
        });

        this.observers.set(widget.id, observer);
    }

    removeWidget(widgetId) {
        // Clean up observers
        const observer = this.observers.get(widgetId);
        if (observer) {
            observer.disconnect();
            this.observers.delete(widgetId);
        }

        // Remove widget
        this.widgets.delete(widgetId);
    }

    cleanup() {
        this.observers.forEach(observer => observer.disconnect());
        this.observers.clear();
        this.widgets.clear();
    }
}
```

## Testing

### Component Testing

```javascript
// Unit tests for component system
describe('Component System', () => {
    test('should create widget with default config', () => {
        const widget = createWidget('Button');
        expect(widget.type).toBe('Button');
        expect(widget.config.label).toBe('Get Started');
    });

    test('should validate widget configuration', () => {
        const config = { label: '' };
        const errors = validateWidgetConfig({ type: 'Button' }, config);
        expect(errors).toContain('Button label is required');
    });

    test('should map data collection fields correctly', () => {
        const mapping = generateFieldMapping('LoginForm', mockCollection);
        expect(mapping).toHaveLength(2);
        expect(mapping[0].label).toBe('Email');
    });
});

// Integration tests
describe('Widget Integration', () => {
    test('should save and restore widget state', async () => {
        const widget = createWidget('Text', { value: 'Test' });
        await saveWidget(widget);

        const restored = await loadWidget(widget.id);
        expect(restored.config.value).toBe('Test');
    });
});
```

## Best Practices

### Component Development
1. **Keep components atomic** - Each component should have a single responsibility
2. **Use consistent naming** - Follow established naming conventions
3. **Validate configurations** - Always validate user inputs
4. **Handle edge cases** - Consider empty states, errors, and loading states
5. **Optimize performance** - Use debouncing, lazy loading, and efficient rendering

### Configuration Design
1. **Provide sensible defaults** - Components should work out of the box
2. **Use clear property names** - Make configurations self-documenting
3. **Group related properties** - Organize properties logically
4. **Support data binding** - Enable dynamic data integration where appropriate

### Template Guidelines
1. **Use semantic HTML** - Choose appropriate HTML elements
2. **Apply consistent styling** - Follow design system guidelines
3. **Handle responsive design** - Ensure components work on all screen sizes
4. **Implement accessibility** - Add ARIA labels and keyboard navigation

This system provides a robust foundation for creating extensible, performant UI components with sophisticated data integration capabilities.