import React from 'react';
import Widget from './Widget';

const CanvasArea = ({
    widgets,
    selectedWidget,
    onSelectWidget,
    onUpdateWidget,
    onDeleteWidget,
    onReorderWidgets
}) => {
    const handleDragOver = (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
    };

    const handleDrop = (e) => {
        e.preventDefault();
        const componentType = e.dataTransfer.getData('componentType');
        if (componentType && onAddWidget) {
            onAddWidget(componentType);
        }
    };

    const handleWidgetDragStart = (e, widget) => {
        e.dataTransfer.setData('widgetId', widget.id.toString());
        e.dataTransfer.effectAllowed = 'move';
    };

    const handleWidgetDragOver = (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    };

    const handleWidgetDrop = (e, targetIndex) => {
        e.preventDefault();
        const draggedWidgetId = parseInt(e.dataTransfer.getData('widgetId'));

        if (!draggedWidgetId) return;

        const draggedWidget = widgets.find(w => w.id === draggedWidgetId);
        if (!draggedWidget) return;

        const currentIndex = widgets.indexOf(draggedWidget);
        if (currentIndex === targetIndex) return;

        // Reorder widgets
        const newWidgets = [...widgets];
        newWidgets.splice(currentIndex, 1);
        newWidgets.splice(targetIndex, 0, draggedWidget);

        onReorderWidgets(newWidgets);
    };

    return (
        <div className="flex-1 flex flex-col bg-gray-100">
            {/* Canvas Header */}
            <div className="bg-white border-b border-gray-200 px-6 py-3">
                <div className="flex items-center justify-center">
                    <div className="flex items-center space-x-2">
                        <div className="w-3 h-3 bg-red-400 rounded-full"></div>
                        <div className="w-3 h-3 bg-yellow-400 rounded-full"></div>
                        <div className="w-3 h-3 bg-green-400 rounded-full"></div>
                    </div>
                    <div className="ml-4 text-sm font-medium text-gray-600">Mobile Preview</div>
                </div>
            </div>

            {/* Canvas */}
            <div className="flex-1 p-6 overflow-auto">
                <div className="max-w-sm mx-auto">
                    {/* Phone Frame */}
                    <div className="bg-gray-900 rounded-[2rem] p-2 shadow-2xl">
                        <div className="bg-white rounded-[1.5rem] h-[600px] overflow-hidden">
                            {/* Status Bar */}
                            <div className="bg-gray-50 px-4 py-2 flex items-center justify-between text-xs text-gray-600">
                                <span>9:41</span>
                                <div className="flex items-center space-x-1">
                                    <div className="w-4 h-2 border border-gray-400 rounded-sm">
                                        <div className="w-3 h-1 bg-gray-600 rounded-sm"></div>
                                    </div>
                                </div>
                            </div>

                            {/* Drop Zone */}
                            <div
                                className="p-4 min-h-96 h-full overflow-y-auto"
                                onDragOver={handleDragOver}
                                onDrop={handleDrop}
                            >
                                {widgets.length > 0 ? (
                                    <div className="space-y-3">
                                        {widgets.map((widget, index) => (
                                            <div
                                                key={widget.id}
                                                draggable
                                                onDragStart={(e) => handleWidgetDragStart(e, widget)}
                                                onDragOver={handleWidgetDragOver}
                                                onDrop={(e) => handleWidgetDrop(e, index)}
                                                className={`relative group transition-all duration-200 ${
                                                    selectedWidget?.id === widget.id
                                                        ? 'ring-2 ring-indigo-500 ring-offset-2'
                                                        : ''
                                                }`}
                                                onClick={() => onSelectWidget(widget)}
                                            >
                                                {/* Widget Controls */}
                                                <div className="absolute top-0 right-0 opacity-0 group-hover:opacity-100 transition-opacity z-10 flex space-x-1 p-1">
                                                    <button
                                                        onClick={(e) => {
                                                            e.stopPropagation();
                                                            onSelectWidget(widget);
                                                        }}
                                                        className="bg-indigo-600 text-white p-1 rounded text-xs hover:bg-indigo-700"
                                                        title="Settings"
                                                    >
                                                        <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                    </button>
                                                    <button
                                                        onClick={(e) => {
                                                            e.stopPropagation();
                                                            onDeleteWidget(widget.id);
                                                        }}
                                                        className="bg-red-600 text-white p-1 rounded text-xs hover:bg-red-700"
                                                        title="Delete"
                                                    >
                                                        <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                {/* Widget Component */}
                                                <Widget
                                                    widget={widget}
                                                    onUpdate={onUpdateWidget}
                                                />
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <div className="flex items-center justify-center h-full text-gray-400">
                                        <div className="text-center">
                                            <svg className="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <p className="text-sm">Drop components here</p>
                                            <p className="text-xs mt-1">Drag components from the palette to start building</p>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CanvasArea;