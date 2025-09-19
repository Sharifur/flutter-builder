import React, { useState, useEffect } from 'react';

const GeneralTab = ({ widget, onUpdateWidget }) => {
    const [config, setConfig] = useState(widget.config || {});
    const [hasChanges, setHasChanges] = useState(false);

    useEffect(() => {
        setConfig(widget.config || {});
        setHasChanges(false);
    }, [widget]);

    const handleChange = (key, value) => {
        const newConfig = { ...config, [key]: value };
        setConfig(newConfig);
        setHasChanges(true);
    };

    const handleSave = () => {
        onUpdateWidget(widget.id, config);
        setHasChanges(false);
    };

    const renderFields = () => {
        switch (widget.type) {
            case 'Text':
                return (
                    <>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Text Content
                            </label>
                            <textarea
                                value={config.value || ''}
                                onChange={(e) => handleChange('value', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                rows={3}
                                placeholder="Enter text content"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Font Size (px)
                            </label>
                            <input
                                type="number"
                                value={config.fontSize || 16}
                                onChange={(e) => handleChange('fontSize', parseInt(e.target.value))}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                min="10"
                                max="72"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Font Weight
                            </label>
                            <select
                                value={config.fontWeight || 'normal'}
                                onChange={(e) => handleChange('fontWeight', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
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
                    </>
                );

            case 'Button':
                return (
                    <>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Button Text
                            </label>
                            <input
                                type="text"
                                value={config.label || ''}
                                onChange={(e) => handleChange('label', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Button text"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Button Size
                            </label>
                            <select
                                value={config.size || 'medium'}
                                onChange={(e) => handleChange('size', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="small">Small</option>
                                <option value="medium">Medium</option>
                                <option value="large">Large</option>
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Button Type
                            </label>
                            <select
                                value={config.buttonType || 'primary'}
                                onChange={(e) => handleChange('buttonType', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="primary">Primary</option>
                                <option value="secondary">Secondary</option>
                                <option value="outline">Outline</option>
                                <option value="ghost">Ghost</option>
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Action URL
                            </label>
                            <input
                                type="url"
                                value={config.actionUrl || ''}
                                onChange={(e) => handleChange('actionUrl', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="https://example.com"
                            />
                        </div>
                    </>
                );

            case 'Image':
                return (
                    <>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Image URL
                            </label>
                            <input
                                type="url"
                                value={config.url || ''}
                                onChange={(e) => handleChange('url', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="https://example.com/image.jpg"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Alt Text
                            </label>
                            <input
                                type="text"
                                value={config.alt || ''}
                                onChange={(e) => handleChange('alt', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Description of the image"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Height (px)
                            </label>
                            <input
                                type="number"
                                value={config.height || 200}
                                onChange={(e) => handleChange('height', parseInt(e.target.value))}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                min="50"
                                max="500"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Object Fit
                            </label>
                            <select
                                value={config.objectFit || 'cover'}
                                onChange={(e) => handleChange('objectFit', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="cover">Cover</option>
                                <option value="contain">Contain</option>
                                <option value="fill">Fill</option>
                                <option value="scale-down">Scale Down</option>
                                <option value="none">None</option>
                            </select>
                        </div>
                    </>
                );

            case 'Input':
                return (
                    <>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Field Label
                            </label>
                            <input
                                type="text"
                                value={config.label || ''}
                                onChange={(e) => handleChange('label', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Field label"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Placeholder Text
                            </label>
                            <input
                                type="text"
                                value={config.placeholder || ''}
                                onChange={(e) => handleChange('placeholder', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter placeholder text"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Input Type
                            </label>
                            <select
                                value={config.type || 'text'}
                                onChange={(e) => handleChange('type', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="text">Text</option>
                                <option value="email">Email</option>
                                <option value="password">Password</option>
                                <option value="number">Number</option>
                                <option value="tel">Phone</option>
                                <option value="url">URL</option>
                                <option value="search">Search</option>
                            </select>
                        </div>
                        <div className="flex items-center">
                            <input
                                type="checkbox"
                                id="required"
                                checked={config.required || false}
                                onChange={(e) => handleChange('required', e.target.checked)}
                                className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            />
                            <label htmlFor="required" className="ml-2 block text-sm text-gray-900">
                                Required field
                            </label>
                        </div>
                    </>
                );

            case 'LoginForm':
            case 'RegisterForm':
            case 'UnifiedForm':
                return (
                    <>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Form Title
                            </label>
                            <input
                                type="text"
                                value={config.title || ''}
                                onChange={(e) => handleChange('title', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Form title"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Subtitle
                            </label>
                            <input
                                type="text"
                                value={config.subtitle || ''}
                                onChange={(e) => handleChange('subtitle', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Form subtitle"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Submit Button Text
                            </label>
                            <input
                                type="text"
                                value={config.submitText || ''}
                                onChange={(e) => handleChange('submitText', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Submit button text"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Success URL
                            </label>
                            <input
                                type="url"
                                value={config.successUrl || ''}
                                onChange={(e) => handleChange('successUrl', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="URL to redirect after success"
                            />
                        </div>
                    </>
                );

            case 'StatCard':
                return (
                    <>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Title
                            </label>
                            <input
                                type="text"
                                value={config.title || ''}
                                onChange={(e) => handleChange('title', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Statistic title"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Value
                            </label>
                            <input
                                type="text"
                                value={config.value || ''}
                                onChange={(e) => handleChange('value', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="32,502"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Change Percentage
                            </label>
                            <input
                                type="text"
                                value={config.change || ''}
                                onChange={(e) => handleChange('change', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="2.1%"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Change Type
                            </label>
                            <select
                                value={config.changeType || 'increase'}
                                onChange={(e) => handleChange('changeType', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="increase">Increase</option>
                                <option value="decrease">Decrease</option>
                            </select>
                        </div>
                    </>
                );

            case 'Card':
                return (
                    <>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Card Title
                            </label>
                            <input
                                type="text"
                                value={config.title || ''}
                                onChange={(e) => handleChange('title', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Card title"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Subtitle
                            </label>
                            <input
                                type="text"
                                value={config.subtitle || ''}
                                onChange={(e) => handleChange('subtitle', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Card subtitle"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Content
                            </label>
                            <textarea
                                value={config.content || ''}
                                onChange={(e) => handleChange('content', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                rows={3}
                                placeholder="Card content"
                            />
                        </div>
                    </>
                );

            default:
                return (
                    <div className="text-center py-8 text-gray-500">
                        <svg className="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p className="text-sm">No general settings available for {widget.type}</p>
                    </div>
                );
        }
    };

    return (
        <div className="p-4">
            <div className="space-y-4">
                {renderFields()}
            </div>

            {/* Save Button */}
            {hasChanges && (
                <div className="mt-6 pt-4 border-t border-gray-200">
                    <button
                        onClick={handleSave}
                        className="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                    >
                        Save Changes
                    </button>
                </div>
            )}
        </div>
    );
};

export default GeneralTab;