import React, { useState, useEffect } from 'react';

const StyleTab = ({ widget, onUpdateWidget }) => {
    const [config, setConfig] = useState(widget?.config || {});
    const [hasChanges, setHasChanges] = useState(false);

    useEffect(() => {
        setConfig(widget?.config || {});
        setHasChanges(false);
    }, [widget]);

    const handleChange = (field, value) => {
        const newConfig = { ...config, [field]: value };
        setConfig(newConfig);
        setHasChanges(true);
    };

    const handleSave = () => {
        if (hasChanges && onUpdateWidget) {
            onUpdateWidget(widget.id, { ...widget, config });
            setHasChanges(false);
        }
    };

    const ColorPicker = ({ label, value, onChange, defaultColor = '#000000' }) => (
        <div className="space-y-2">
            <label className="block text-sm font-medium text-gray-700">{label}</label>
            <div className="flex items-center space-x-3">
                <input
                    type="color"
                    value={value || defaultColor}
                    onChange={(e) => onChange(e.target.value)}
                    className="w-12 h-10 border border-gray-300 rounded-md cursor-pointer"
                />
                <input
                    type="text"
                    value={value || defaultColor}
                    onChange={(e) => onChange(e.target.value)}
                    placeholder={defaultColor}
                    className="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
            </div>
        </div>
    );

    const renderStyleFields = () => {
        switch (widget?.type) {
            case 'Text':
                return (
                    <div className="space-y-6">
                        <ColorPicker
                            label="Text Color"
                            value={config.color}
                            onChange={(value) => handleChange('color', value)}
                            defaultColor="#000000"
                        />

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Font Size</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="range"
                                    min="8"
                                    max="48"
                                    value={config.fontSize || 16}
                                    onChange={(e) => handleChange('fontSize', parseInt(e.target.value))}
                                    className="flex-1"
                                />
                                <span className="text-sm text-gray-600 w-12">{config.fontSize || 16}px</span>
                            </div>
                        </div>

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Font Weight</label>
                            <select
                                value={config.fontWeight || 'normal'}
                                onChange={(e) => handleChange('fontWeight', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="normal">Normal</option>
                                <option value="bold">Bold</option>
                                <option value="lighter">Light</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="300">300</option>
                                <option value="400">400</option>
                                <option value="500">500</option>
                                <option value="600">600</option>
                                <option value="700">700</option>
                                <option value="800">800</option>
                                <option value="900">900</option>
                            </select>
                        </div>

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Text Alignment</label>
                            <div className="flex space-x-2">
                                {['left', 'center', 'right', 'justify'].map((align) => (
                                    <button
                                        key={align}
                                        onClick={() => handleChange('textAlign', align)}
                                        className={`flex-1 px-3 py-2 text-sm border rounded-md transition-colors ${
                                            config.textAlign === align
                                                ? 'bg-indigo-100 border-indigo-300 text-indigo-700'
                                                : 'border-gray-300 hover:bg-gray-50'
                                        }`}
                                    >
                                        {align.charAt(0).toUpperCase() + align.slice(1)}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>
                );

            case 'Button':
                return (
                    <div className="space-y-6">
                        <ColorPicker
                            label="Background Color"
                            value={config.color}
                            onChange={(value) => handleChange('color', value)}
                            defaultColor="#3B82F6"
                        />

                        <ColorPicker
                            label="Text Color"
                            value={config.textColor}
                            onChange={(value) => handleChange('textColor', value)}
                            defaultColor="#FFFFFF"
                        />

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Button Size</label>
                            <select
                                value={config.size || 'medium'}
                                onChange={(e) => handleChange('size', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="small">Small</option>
                                <option value="medium">Medium</option>
                                <option value="large">Large</option>
                            </select>
                        </div>

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Border Radius</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="range"
                                    min="0"
                                    max="24"
                                    value={config.borderRadius || 8}
                                    onChange={(e) => handleChange('borderRadius', parseInt(e.target.value))}
                                    className="flex-1"
                                />
                                <span className="text-sm text-gray-600 w-12">{config.borderRadius || 8}px</span>
                            </div>
                        </div>
                    </div>
                );

            case 'Image':
                return (
                    <div className="space-y-6">
                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Image Height</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="range"
                                    min="50"
                                    max="400"
                                    value={config.height || 120}
                                    onChange={(e) => handleChange('height', parseInt(e.target.value))}
                                    className="flex-1"
                                />
                                <span className="text-sm text-gray-600 w-12">{config.height || 120}px</span>
                            </div>
                        </div>

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Object Fit</label>
                            <select
                                value={config.objectFit || 'cover'}
                                onChange={(e) => handleChange('objectFit', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="cover">Cover</option>
                                <option value="contain">Contain</option>
                                <option value="fill">Fill</option>
                                <option value="scale-down">Scale Down</option>
                                <option value="none">None</option>
                            </select>
                        </div>

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Border Radius</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="range"
                                    min="0"
                                    max="24"
                                    value={config.borderRadius || 8}
                                    onChange={(e) => handleChange('borderRadius', parseInt(e.target.value))}
                                    className="flex-1"
                                />
                                <span className="text-sm text-gray-600 w-12">{config.borderRadius || 8}px</span>
                            </div>
                        </div>
                    </div>
                );

            case 'Container':
                return (
                    <div className="space-y-6">
                        <ColorPicker
                            label="Background Color"
                            value={config.backgroundColor}
                            onChange={(value) => handleChange('backgroundColor', value)}
                            defaultColor="#FFFFFF"
                        />

                        <ColorPicker
                            label="Border Color"
                            value={config.borderColor}
                            onChange={(value) => handleChange('borderColor', value)}
                            defaultColor="#D1D5DB"
                        />

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Border Width</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="range"
                                    min="0"
                                    max="8"
                                    value={config.borderWidth || 2}
                                    onChange={(e) => handleChange('borderWidth', parseInt(e.target.value))}
                                    className="flex-1"
                                />
                                <span className="text-sm text-gray-600 w-12">{config.borderWidth || 2}px</span>
                            </div>
                        </div>

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Border Radius</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="range"
                                    min="0"
                                    max="24"
                                    value={config.borderRadius || 8}
                                    onChange={(e) => handleChange('borderRadius', parseInt(e.target.value))}
                                    className="flex-1"
                                />
                                <span className="text-sm text-gray-600 w-12">{config.borderRadius || 8}px</span>
                            </div>
                        </div>

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Padding</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="range"
                                    min="0"
                                    max="32"
                                    value={config.padding || 16}
                                    onChange={(e) => handleChange('padding', parseInt(e.target.value))}
                                    className="flex-1"
                                />
                                <span className="text-sm text-gray-600 w-12">{config.padding || 16}px</span>
                            </div>
                        </div>
                    </div>
                );

            case 'Card':
                return (
                    <div className="space-y-6">
                        <ColorPicker
                            label="Background Color"
                            value={config.backgroundColor}
                            onChange={(value) => handleChange('backgroundColor', value)}
                            defaultColor="#FFFFFF"
                        />

                        <ColorPicker
                            label="Border Color"
                            value={config.borderColor}
                            onChange={(value) => handleChange('borderColor', value)}
                            defaultColor="#E5E7EB"
                        />

                        <ColorPicker
                            label="Title Color"
                            value={config.titleColor}
                            onChange={(value) => handleChange('titleColor', value)}
                            defaultColor="#111827"
                        />

                        <ColorPicker
                            label="Subtitle Color"
                            value={config.subtitleColor}
                            onChange={(value) => handleChange('subtitleColor', value)}
                            defaultColor="#6B7280"
                        />

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Shadow</label>
                            <select
                                value={config.shadow || 'sm'}
                                onChange={(e) => handleChange('shadow', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="none">None</option>
                                <option value="sm">Small</option>
                                <option value="md">Medium</option>
                                <option value="lg">Large</option>
                                <option value="xl">Extra Large</option>
                            </select>
                        </div>
                    </div>
                );

            case 'BalanceCard':
                return (
                    <div className="space-y-6">
                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Gradient Start Color</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="color"
                                    value={config.gradientStart || '#6366F1'}
                                    onChange={(e) => handleChange('gradientStart', e.target.value)}
                                    className="w-12 h-10 border border-gray-300 rounded-md cursor-pointer"
                                />
                                <input
                                    type="text"
                                    value={config.gradientStart || '#6366F1'}
                                    onChange={(e) => handleChange('gradientStart', e.target.value)}
                                    className="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>
                        </div>

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Gradient End Color</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="color"
                                    value={config.gradientEnd || '#8B5CF6'}
                                    onChange={(e) => handleChange('gradientEnd', e.target.value)}
                                    className="w-12 h-10 border border-gray-300 rounded-md cursor-pointer"
                                />
                                <input
                                    type="text"
                                    value={config.gradientEnd || '#8B5CF6'}
                                    onChange={(e) => handleChange('gradientEnd', e.target.value)}
                                    className="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>
                        </div>

                        <ColorPicker
                            label="Text Color"
                            value={config.textColor}
                            onChange={(value) => handleChange('textColor', value)}
                            defaultColor="#FFFFFF"
                        />
                    </div>
                );

            case 'ProfileHeader':
                return (
                    <div className="space-y-6">
                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Gradient Start Color</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="color"
                                    value={config.gradientStart || '#6366F1'}
                                    onChange={(e) => handleChange('gradientStart', e.target.value)}
                                    className="w-12 h-10 border border-gray-300 rounded-md cursor-pointer"
                                />
                                <input
                                    type="text"
                                    value={config.gradientStart || '#6366F1'}
                                    onChange={(e) => handleChange('gradientStart', e.target.value)}
                                    className="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>
                        </div>

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Gradient End Color</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="color"
                                    value={config.gradientEnd || '#8B5CF6'}
                                    onChange={(e) => handleChange('gradientEnd', e.target.value)}
                                    className="w-12 h-10 border border-gray-300 rounded-md cursor-pointer"
                                />
                                <input
                                    type="text"
                                    value={config.gradientEnd || '#8B5CF6'}
                                    onChange={(e) => handleChange('gradientEnd', e.target.value)}
                                    className="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>
                        </div>

                        <ColorPicker
                            label="Text Color"
                            value={config.textColor}
                            onChange={(value) => handleChange('textColor', value)}
                            defaultColor="#FFFFFF"
                        />

                        <ColorPicker
                            label="Avatar Background"
                            value={config.avatarBg}
                            onChange={(value) => handleChange('avatarBg', value)}
                            defaultColor="#FFFFFF"
                        />
                    </div>
                );

            case 'StatCard':
                return (
                    <div className="space-y-6">
                        <ColorPicker
                            label="Background Color"
                            value={config.backgroundColor}
                            onChange={(value) => handleChange('backgroundColor', value)}
                            defaultColor="#FFFFFF"
                        />

                        <ColorPicker
                            label="Icon Background"
                            value={config.iconBg}
                            onChange={(value) => handleChange('iconBg', value)}
                            defaultColor="#EEF2FF"
                        />

                        <ColorPicker
                            label="Icon Color"
                            value={config.iconColor}
                            onChange={(value) => handleChange('iconColor', value)}
                            defaultColor="#6366F1"
                        />

                        <ColorPicker
                            label="Title Color"
                            value={config.titleColor}
                            onChange={(value) => handleChange('titleColor', value)}
                            defaultColor="#6B7280"
                        />

                        <ColorPicker
                            label="Value Color"
                            value={config.valueColor}
                            onChange={(value) => handleChange('valueColor', value)}
                            defaultColor="#111827"
                        />

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Change Type Color</label>
                            <div className="flex space-x-4">
                                <div className="flex items-center space-x-2">
                                    <input
                                        type="color"
                                        value={config.increaseColor || '#10B981'}
                                        onChange={(e) => handleChange('increaseColor', e.target.value)}
                                        className="w-8 h-8 border border-gray-300 rounded cursor-pointer"
                                    />
                                    <span className="text-sm text-gray-600">Increase</span>
                                </div>
                                <div className="flex items-center space-x-2">
                                    <input
                                        type="color"
                                        value={config.decreaseColor || '#EF4444'}
                                        onChange={(e) => handleChange('decreaseColor', e.target.value)}
                                        className="w-8 h-8 border border-gray-300 rounded cursor-pointer"
                                    />
                                    <span className="text-sm text-gray-600">Decrease</span>
                                </div>
                            </div>
                        </div>
                    </div>
                );

            case 'Input':
                return (
                    <div className="space-y-6">
                        <ColorPicker
                            label="Border Color"
                            value={config.borderColor}
                            onChange={(value) => handleChange('borderColor', value)}
                            defaultColor="#D1D5DB"
                        />

                        <ColorPicker
                            label="Focus Border Color"
                            value={config.focusBorderColor}
                            onChange={(value) => handleChange('focusBorderColor', value)}
                            defaultColor="#3B82F6"
                        />

                        <ColorPicker
                            label="Background Color"
                            value={config.backgroundColor}
                            onChange={(value) => handleChange('backgroundColor', value)}
                            defaultColor="#FFFFFF"
                        />

                        <ColorPicker
                            label="Text Color"
                            value={config.textColor}
                            onChange={(value) => handleChange('textColor', value)}
                            defaultColor="#111827"
                        />

                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Border Radius</label>
                            <div className="flex items-center space-x-3">
                                <input
                                    type="range"
                                    min="0"
                                    max="16"
                                    value={config.borderRadius || 6}
                                    onChange={(e) => handleChange('borderRadius', parseInt(e.target.value))}
                                    className="flex-1"
                                />
                                <span className="text-sm text-gray-600 w-12">{config.borderRadius || 6}px</span>
                            </div>
                        </div>
                    </div>
                );

            default:
                return (
                    <div className="text-center py-8">
                        <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 3v18M17 8l4-4M21 12l-4 4M17 16l4-4"></path>
                        </svg>
                        <h3 className="mt-2 text-sm font-medium text-gray-900">Style Options</h3>
                        <p className="mt-1 text-sm text-gray-500">
                            Style customization for {widget?.type} is not available yet.
                        </p>
                    </div>
                );
        }
    };

    return (
        <div className="p-6">
            <div className="space-y-6">
                {renderStyleFields()}

                {hasChanges && (
                    <div className="pt-4 border-t border-gray-200">
                        <button
                            onClick={handleSave}
                            className="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                        >
                            Save Changes
                        </button>
                    </div>
                )}
            </div>
        </div>
    );
};

export default StyleTab;