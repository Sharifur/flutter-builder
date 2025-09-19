import React, { useState, useEffect } from 'react';

const ComponentPalette = ({ onAddWidget }) => {
    const [components, setComponents] = useState([]);
    const [loading, setLoading] = useState(true);

    // Fetch available components
    useEffect(() => {
        const fetchComponents = async () => {
            try {
                const response = await fetch('/user/api/ui-components');
                const data = await response.json();
                setComponents(data.components || []);
            } catch (err) {
                console.error('Failed to fetch components:', err);
            } finally {
                setLoading(false);
            }
        };

        fetchComponents();
    }, []);

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

            {/* Components List */}
            <div className="flex-1 overflow-y-auto p-4">
                <div className="space-y-2">
                    {components.map((component) => (
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
                                    <div className="w-5 h-5 bg-gray-300 rounded"></div>
                                </div>
                                <div className="ml-3">
                                    <p className="text-sm font-medium text-gray-900">
                                        {component.component_type}
                                    </p>
                                    <p className="text-xs text-gray-500">
                                        UI Component
                                    </p>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {components.length === 0 && (
                    <div className="text-center py-8">
                        <h3 className="mt-2 text-sm font-medium text-gray-900">No components</h3>
                        <p className="mt-1 text-sm text-gray-500">No components available.</p>
                    </div>
                )}
            </div>
        </div>
    );
};

export default ComponentPalette;