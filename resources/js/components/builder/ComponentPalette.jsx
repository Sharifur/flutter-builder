import React, { useState, useEffect } from 'react';

const ComponentPalette = ({ onAddWidget }) => {
    const [components, setComponents] = useState([]);
    const [categories, setCategories] = useState([]);
    const [selectedCategory, setSelectedCategory] = useState('all');
    const [loading, setLoading] = useState(true);

    // Fetch available components
    useEffect(() => {
        const fetchComponents = async () => {
            try {
                const [componentsRes, categoriesRes] = await Promise.all([
                    fetch('/user/api/ui-components'),
                    fetch('/user/api/ui-components/categories')
                ]);

                const componentsData = await componentsRes.json();
                const categoriesData = await categoriesRes.json();

                setComponents(componentsData.components || []);
                setCategories([
                    { id: 'all', name: 'All Components', icon: 'grid' },
                    ...categoriesData.categories || []
                ]);
            } catch (err) {
                console.error('Failed to fetch components:', err);
            } finally {
                setLoading(false);
            }
        };

        fetchComponents();
    }, []);

    const filteredComponents = selectedCategory === 'all'
        ? components
        : components.filter(c => c.category === selectedCategory);

    const getComponentIcon = (componentType) => {
        const icons = {
            // Basic Components
            'Text': (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
            ),
            'Button': (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                </svg>
            ),
            'Image': (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            ),
            'Input': (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            ),
            'Container': (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            ),

            // Layout Components
            'Card': (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            ),
            'TabBar': (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            ),

            // Forms
            'LoginForm': (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            ),
            'RegisterForm': (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            ),
            'UnifiedForm': (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            ),

            // Default
            default: (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                </svg>
            )
        };

        return icons[componentType] || icons.default;
    };

    const getComponentDescription = (componentType) => {
        const descriptions = {
            'Text': 'Display text content',
            'Button': 'Clickable button',
            'Image': 'Display images',
            'Input': 'Text input field',
            'Container': 'Layout container',
            'Card': 'Content card',
            'TabBar': 'Tab navigation',
            'LoginForm': 'User login form',
            'RegisterForm': 'User registration',
            'UnifiedForm': 'Dynamic form',
            'StatCard': 'Statistics display',
            'BalanceCard': 'Balance display',
            'ProfileHeader': 'Profile section',
            'CryptoItem': 'Crypto currency item',
            'ActionButton': 'Action button',
            'TransactionItem': 'Transaction display',
            'ProductCard': 'Product showcase',
            'ChartCard': 'Chart display',
            'ExpenseItem': 'Expense category',
            'BalanceChart': 'Balance chart',
            'NavigationTabs': 'Horizontal navigation',
            'StockChart': 'Stock chart',
            'OrderItem': 'Order list item',
            'SearchHeader': 'Search with title',
            'HeaderCard': 'Header section'
        };

        return descriptions[componentType] || 'UI Component';
    };

    if (loading) {
        return (
            <div className="w-80 bg-white border-r border-gray-200 p-4">
                <div className="animate-pulse">
                    <div className="h-6 bg-gray-200 rounded mb-4"></div>
                    <div className="space-y-3">
                        {[...Array(8)].map((_, i) => (
                            <div key={i} className="h-12 bg-gray-200 rounded"></div>
                        ))}
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="w-80 bg-white border-r border-gray-200 flex flex-col">
            {/* Header */}
            <div className="p-4 border-b border-gray-200">
                <h2 className="text-lg font-medium text-gray-900">Components</h2>
                <p className="text-sm text-gray-500 mt-1">Drag components to the canvas</p>
            </div>

            {/* Categories */}
            <div className="p-4 border-b border-gray-200">
                <div className="space-y-1">
                    {categories.map((category) => (
                        <button
                            key={category.id}
                            onClick={() => setSelectedCategory(category.id)}
                            className={`w-full text-left px-3 py-2 rounded-md text-sm transition-colors ${
                                selectedCategory === category.id
                                    ? 'bg-indigo-100 text-indigo-700'
                                    : 'text-gray-600 hover:bg-gray-100'
                            }`}
                        >
                            {category.name}
                        </button>
                    ))}
                </div>
            </div>

            {/* Components List */}
            <div className="flex-1 overflow-y-auto p-4">
                <div className="space-y-2">
                    {filteredComponents.map((component) => (
                        <div
                            key={component.component_type}
                            className="drag-component cursor-grab active:cursor-grabbing bg-white p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:shadow-md hover:scale-105 transition-all duration-200"
                            draggable
                            onDragStart={(e) => {
                                e.dataTransfer.setData('componentType', component.component_type);
                            }}
                            onClick={() => onAddWidget(component.component_type)}
                            title={`Click or drag to add ${component.component_type}`}
                        >
                            <div className="flex items-center">
                                <div className="flex-shrink-0 text-gray-500">
                                    {getComponentIcon(component.component_type)}
                                </div>
                                <div className="ml-3">
                                    <p className="text-sm font-medium text-gray-900">
                                        {component.component_type}
                                    </p>
                                    <p className="text-xs text-gray-500">
                                        {getComponentDescription(component.component_type)}
                                    </p>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {filteredComponents.length === 0 && (
                    <div className="text-center py-8">
                        <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h3 className="mt-2 text-sm font-medium text-gray-900">No components</h3>
                        <p className="mt-1 text-sm text-gray-500">No components available in this category.</p>
                    </div>
                )}
            </div>
        </div>
    );
};

export default ComponentPalette;