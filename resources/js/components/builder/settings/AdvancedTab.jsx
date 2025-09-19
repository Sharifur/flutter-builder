import React, { useState, useEffect } from 'react';

const AdvancedTab = ({ widget, projectId, onUpdateWidget, csrfToken }) => {
    const [config, setConfig] = useState(widget?.config || {});
    const [hasChanges, setHasChanges] = useState(false);
    const [dataCollections, setDataCollections] = useState([]);
    const [selectedCollection, setSelectedCollection] = useState(null);
    const [collectionFields, setCollectionFields] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        setConfig(widget?.config || {});
        setHasChanges(false);
        fetchDataCollections();
    }, [widget]);

    useEffect(() => {
        if (config.dataCollectionId) {
            fetchCollectionFields(config.dataCollectionId);
        }
    }, [config.dataCollectionId]);

    const fetchDataCollections = async () => {
        try {
            const response = await fetch(`/user/api/projects/${projectId}/data-collections`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                }
            });

            if (response.ok) {
                const data = await response.json();
                setDataCollections(data.collections || []);
            }
        } catch (error) {
            console.error('Failed to fetch data collections:', error);
        } finally {
            setLoading(false);
        }
    };

    const fetchCollectionFields = async (collectionId) => {
        try {
            const response = await fetch(`/user/api/data-collections/${collectionId}/fields`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                }
            });

            if (response.ok) {
                const data = await response.json();
                setCollectionFields(data.fields || []);
                setSelectedCollection(dataCollections.find(c => c.id === parseInt(collectionId)));
            }
        } catch (error) {
            console.error('Failed to fetch collection fields:', error);
            setCollectionFields([]);
        }
    };

    const handleChange = (field, value) => {
        const newConfig = { ...config, [field]: value };
        setConfig(newConfig);
        setHasChanges(true);

        if (field === 'dataCollectionId') {
            // Clear field mappings when collection changes
            const fieldsToReset = ['titleField', 'valueField', 'imageField', 'descriptionField', 'emailField', 'nameField'];
            fieldsToReset.forEach(f => {
                if (newConfig[f]) {
                    delete newConfig[f];
                }
            });
            setConfig(newConfig);
        }
    };

    const handleSave = async () => {
        if (hasChanges && onUpdateWidget) {
            await onUpdateWidget(widget.id, { ...widget, config });
            setHasChanges(false);
        }
    };

    const FieldMapper = ({ label, field, placeholder = "Select field..." }) => (
        <div className="space-y-2">
            <label className="block text-sm font-medium text-gray-700">{label}</label>
            <select
                value={config[field] || ''}
                onChange={(e) => handleChange(field, e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                disabled={!selectedCollection}
            >
                <option value="">{placeholder}</option>
                {collectionFields.map((f) => (
                    <option key={f.id} value={f.name}>
                        {f.label || f.name} ({f.type})
                    </option>
                ))}
            </select>
        </div>
    );

    const renderAdvancedFields = () => {
        if (!widget) return null;

        const supportsDataBinding = [
            'Text', 'Button', 'Image', 'Card', 'StatCard',
            'BalanceCard', 'ProfileHeader', 'LoginForm', 'RegisterForm', 'UnifiedForm'
        ].includes(widget.type);

        if (!supportsDataBinding) {
            return (
                <div className="text-center py-8">
                    <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 className="mt-2 text-sm font-medium text-gray-900">No Data Binding</h3>
                    <p className="mt-1 text-sm text-gray-500">
                        {widget.type} widgets don't support data collection binding.
                    </p>
                </div>
            );
        }

        return (
            <div className="space-y-6">
                {/* Data Collection Selection */}
                <div className="space-y-2">
                    <label className="block text-sm font-medium text-gray-700">Data Collection</label>
                    <select
                        value={config.dataCollectionId || ''}
                        onChange={(e) => handleChange('dataCollectionId', e.target.value)}
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">Select data collection...</option>
                        {dataCollections.map((collection) => (
                            <option key={collection.id} value={collection.id}>
                                {collection.name} ({collection.fields_count || 0} fields)
                            </option>
                        ))}
                    </select>
                    {config.dataCollectionId && selectedCollection && (
                        <p className="text-xs text-gray-500">
                            Collection: {selectedCollection.name} | Fields: {collectionFields.length}
                        </p>
                    )}
                </div>

                {/* Dynamic Behavior Settings */}
                {config.dataCollectionId && (
                    <>
                        <div className="border-t pt-4">
                            <h4 className="text-sm font-medium text-gray-900 mb-3">Dynamic Behavior</h4>

                            <div className="space-y-4">
                                <div className="flex items-center">
                                    <input
                                        type="checkbox"
                                        id="enableDataBinding"
                                        checked={config.enableDataBinding || false}
                                        onChange={(e) => handleChange('enableDataBinding', e.target.checked)}
                                        className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    />
                                    <label htmlFor="enableDataBinding" className="ml-2 block text-sm text-gray-900">
                                        Enable data binding
                                    </label>
                                </div>

                                <div className="flex items-center">
                                    <input
                                        type="checkbox"
                                        id="autoRefresh"
                                        checked={config.autoRefresh || false}
                                        onChange={(e) => handleChange('autoRefresh', e.target.checked)}
                                        className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    />
                                    <label htmlFor="autoRefresh" className="ml-2 block text-sm text-gray-900">
                                        Auto-refresh data
                                    </label>
                                </div>

                                {config.autoRefresh && (
                                    <div className="ml-6 space-y-2">
                                        <label className="block text-sm font-medium text-gray-700">Refresh Interval (seconds)</label>
                                        <input
                                            type="number"
                                            min="5"
                                            max="3600"
                                            value={config.refreshInterval || 30}
                                            onChange={(e) => handleChange('refreshInterval', parseInt(e.target.value))}
                                            className="w-32 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        />
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Field Mapping */}
                        <div className="border-t pt-4">
                            <h4 className="text-sm font-medium text-gray-900 mb-3">Field Mapping</h4>

                            {widget.type === 'Text' && (
                                <FieldMapper
                                    label="Content Field"
                                    field="contentField"
                                    placeholder="Select field for text content..."
                                />
                            )}

                            {widget.type === 'Button' && (
                                <FieldMapper
                                    label="Label Field"
                                    field="labelField"
                                    placeholder="Select field for button label..."
                                />
                            )}

                            {widget.type === 'Image' && (
                                <div className="space-y-4">
                                    <FieldMapper
                                        label="Image URL Field"
                                        field="imageField"
                                        placeholder="Select field for image URL..."
                                    />
                                    <FieldMapper
                                        label="Alt Text Field"
                                        field="altField"
                                        placeholder="Select field for alt text..."
                                    />
                                </div>
                            )}

                            {widget.type === 'Card' && (
                                <div className="space-y-4">
                                    <FieldMapper
                                        label="Title Field"
                                        field="titleField"
                                        placeholder="Select field for card title..."
                                    />
                                    <FieldMapper
                                        label="Subtitle Field"
                                        field="subtitleField"
                                        placeholder="Select field for card subtitle..."
                                    />
                                </div>
                            )}

                            {widget.type === 'StatCard' && (
                                <div className="space-y-4">
                                    <FieldMapper
                                        label="Title Field"
                                        field="titleField"
                                        placeholder="Select field for stat title..."
                                    />
                                    <FieldMapper
                                        label="Value Field"
                                        field="valueField"
                                        placeholder="Select field for stat value..."
                                    />
                                    <FieldMapper
                                        label="Change Field"
                                        field="changeField"
                                        placeholder="Select field for change percentage..."
                                    />
                                </div>
                            )}

                            {widget.type === 'BalanceCard' && (
                                <div className="space-y-4">
                                    <FieldMapper
                                        label="Title Field"
                                        field="titleField"
                                        placeholder="Select field for balance title..."
                                    />
                                    <FieldMapper
                                        label="Amount Field"
                                        field="amountField"
                                        placeholder="Select field for balance amount..."
                                    />
                                    <FieldMapper
                                        label="Currency Field"
                                        field="currencyField"
                                        placeholder="Select field for currency..."
                                    />
                                </div>
                            )}

                            {widget.type === 'ProfileHeader' && (
                                <div className="space-y-4">
                                    <FieldMapper
                                        label="Name Field"
                                        field="nameField"
                                        placeholder="Select field for user name..."
                                    />
                                    <FieldMapper
                                        label="Email Field"
                                        field="emailField"
                                        placeholder="Select field for user email..."
                                    />
                                    <FieldMapper
                                        label="Avatar Field"
                                        field="avatarField"
                                        placeholder="Select field for avatar URL..."
                                    />
                                </div>
                            )}

                            {(widget.type === 'LoginForm' || widget.type === 'RegisterForm' || widget.type === 'UnifiedForm') && (
                                <div className="space-y-4">
                                    <FieldMapper
                                        label="Title Field"
                                        field="titleField"
                                        placeholder="Select field for form title..."
                                    />
                                    <FieldMapper
                                        label="Subtitle Field"
                                        field="subtitleField"
                                        placeholder="Select field for form subtitle..."
                                    />

                                    <div className="border-t pt-4">
                                        <h5 className="text-xs font-medium text-gray-700 mb-2">Form Submission</h5>
                                        <div className="flex items-center mb-2">
                                            <input
                                                type="checkbox"
                                                id="saveToCollection"
                                                checked={config.saveToCollection || false}
                                                onChange={(e) => handleChange('saveToCollection', e.target.checked)}
                                                className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                            />
                                            <label htmlFor="saveToCollection" className="ml-2 block text-sm text-gray-900">
                                                Save form submissions to this collection
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Data Display Options */}
                        <div className="border-t pt-4">
                            <h4 className="text-sm font-medium text-gray-900 mb-3">Display Options</h4>

                            <div className="space-y-4">
                                <div className="space-y-2">
                                    <label className="block text-sm font-medium text-gray-700">Data Display Mode</label>
                                    <select
                                        value={config.displayMode || 'single'}
                                        onChange={(e) => handleChange('displayMode', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                        <option value="single">Single Record</option>
                                        <option value="first">First Record</option>
                                        <option value="last">Last Record</option>
                                        <option value="random">Random Record</option>
                                    </select>
                                </div>

                                {config.displayMode === 'single' && (
                                    <div className="space-y-2">
                                        <label className="block text-sm font-medium text-gray-700">Record ID</label>
                                        <input
                                            type="number"
                                            min="1"
                                            value={config.recordId || ''}
                                            onChange={(e) => handleChange('recordId', e.target.value)}
                                            placeholder="Enter record ID..."
                                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        />
                                    </div>
                                )}

                                <div className="space-y-2">
                                    <label className="block text-sm font-medium text-gray-700">Fallback Behavior</label>
                                    <select
                                        value={config.fallbackBehavior || 'default'}
                                        onChange={(e) => handleChange('fallbackBehavior', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                        <option value="default">Use default values</option>
                                        <option value="hide">Hide widget</option>
                                        <option value="placeholder">Show placeholder</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </>
                )}
            </div>
        );
    };

    if (loading) {
        return (
            <div className="p-6">
                <div className="animate-pulse space-y-4">
                    <div className="h-4 bg-gray-200 rounded w-1/2"></div>
                    <div className="h-10 bg-gray-200 rounded"></div>
                    <div className="h-4 bg-gray-200 rounded w-1/3"></div>
                    <div className="h-10 bg-gray-200 rounded"></div>
                </div>
            </div>
        );
    }

    return (
        <div className="p-6">
            <div className="space-y-6">
                {renderAdvancedFields()}

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

export default AdvancedTab;