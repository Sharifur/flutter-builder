import React, { useState, useEffect } from 'react';

const CollectionDetail = ({ collection, projectId, onBack, onEdit, onDelete, csrfToken }) => {
    const [fields, setFields] = useState([]);
    const [records, setRecords] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [showAddField, setShowAddField] = useState(false);
    const [newField, setNewField] = useState({
        name: '',
        label: '',
        type: 'text',
        default_value: '',
        is_required: false,
        is_unique: false,
        is_searchable: true
    });

    const fieldTypes = {
        'text': 'Text',
        'number': 'Number',
        'email': 'Email',
        'url': 'URL',
        'boolean': 'Boolean',
        'date': 'Date',
        'datetime': 'Date & Time',
        'textarea': 'Long Text',
        'select': 'Select',
        'json': 'JSON',
        'file': 'File'
    };

    const fetchCollectionData = async () => {
        try {
            setLoading(true);
            const response = await fetch(`/user/projects/${projectId}/data-collections/${collection.id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch collection data');
            }

            const data = await response.json();
            setFields(data.fields || []);
            setRecords(data.records || []);
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchCollectionData();
    }, [collection.id, projectId]);

    const handleAddField = async (e) => {
        e.preventDefault();
        try {
            const response = await fetch(`/user/projects/${projectId}/data-collections/${collection.id}/fields`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(newField)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to add field');
            }

            await fetchCollectionData();
            setShowAddField(false);
            setNewField({
                name: '',
                label: '',
                type: 'text',
                default_value: '',
                is_required: false,
                is_unique: false,
                is_searchable: true
            });
        } catch (err) {
            setError(err.message);
        }
    };

    const handleDeleteField = async (fieldId) => {
        if (!confirm('Are you sure you want to delete this field?')) {
            return;
        }

        try {
            const response = await fetch(`/user/projects/${projectId}/data-collections/${collection.id}/fields/${fieldId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to delete field');
            }

            await fetchCollectionData();
        } catch (err) {
            setError(err.message);
        }
    };

    if (loading) {
        return (
            <div className="flex items-center justify-center h-64">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
            </div>
        );
    }

    return (
        <div className="space-y-6">
            {/* Header */}
            <div className="bg-white shadow sm:rounded-lg">
                <div className="px-4 py-5 sm:p-6">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center">
                            <div className="flex-shrink-0">
                                <div className="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                                    {collection.icon ? (
                                        <i className={`fas fa-${collection.icon} text-green-600 text-xl`}></i>
                                    ) : (
                                        <svg className="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    )}
                                </div>
                            </div>
                            <div className="ml-4">
                                <div className="flex items-center">
                                    <h2 className="text-2xl font-bold text-gray-900">{collection.name}</h2>
                                    {collection.is_system && (
                                        <span className="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            System Collection
                                        </span>
                                    )}
                                    {!collection.is_active && (
                                        <span className="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    )}
                                </div>
                                {collection.description && (
                                    <p className="text-gray-600 mt-1">{collection.description}</p>
                                )}
                                <div className="flex items-center space-x-4 text-sm text-gray-500 mt-2">
                                    <span>{fields.length} fields</span>
                                    <span>{records.length} records</span>
                                    <span>Slug: {collection.slug}</span>
                                </div>
                            </div>
                        </div>
                        <div className="flex space-x-3">
                            <button
                                onClick={onBack}
                                className="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back
                            </button>
                            {!collection.is_system && (
                                <button
                                    onClick={() => onEdit(collection)}
                                    className="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {error && (
                <div className="bg-red-50 border border-red-200 rounded-md p-4">
                    <div className="flex">
                        <svg className="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                        </svg>
                        <div className="ml-3">
                            <h3 className="text-sm font-medium text-red-800">Error</h3>
                            <div className="mt-2 text-sm text-red-700">
                                <p>{error}</p>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* Fields Management */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {/* Fields List */}
                <div className="lg:col-span-2">
                    <div className="bg-white shadow sm:rounded-lg">
                        <div className="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 className="text-lg leading-6 font-medium text-gray-900">Collection Fields</h3>
                            <p className="mt-1 max-w-2xl text-sm text-gray-500">Manage the structure of your data collection.</p>
                        </div>

                        {fields.length > 0 ? (
                            <ul className="divide-y divide-gray-200">
                                {fields.map((field) => (
                                    <li key={field.id} className="px-4 py-4">
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center">
                                                <div className="flex-shrink-0">
                                                    <div className="h-8 w-8 rounded bg-gray-100 flex items-center justify-center">
                                                        <span className="text-xs font-medium text-gray-600">
                                                            {field.type === 'text' ? 'T' :
                                                             field.type === 'number' ? '#' :
                                                             field.type === 'boolean' ? '✓' :
                                                             field.type === 'date' ? '📅' :
                                                             field.type === 'email' ? '@' : '?'}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div className="ml-4">
                                                    <div className="flex items-center space-x-2">
                                                        <h4 className="text-sm font-medium text-gray-900">{field.label}</h4>
                                                        <span className="text-xs text-gray-500 font-mono">{field.name}</span>
                                                        {field.is_required && (
                                                            <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Required</span>
                                                        )}
                                                        {field.is_unique && (
                                                            <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Unique</span>
                                                        )}
                                                        {!field.is_active && (
                                                            <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                                        )}
                                                    </div>
                                                    <div className="flex items-center space-x-4 text-xs text-gray-500 mt-1">
                                                        <span>Type: {fieldTypes[field.type] || field.type}</span>
                                                        {field.default_value && (
                                                            <span>Default: {field.default_value}</span>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="flex items-center space-x-2">
                                                <button
                                                    onClick={() => handleDeleteField(field.id)}
                                                    className="text-red-600 hover:text-red-900 text-sm font-medium"
                                                >
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <div className="px-4 py-12 text-center">
                                <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 className="mt-2 text-sm font-medium text-gray-900">No fields</h3>
                                <p className="mt-1 text-sm text-gray-500">Get started by adding your first field to this collection.</p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Add Field Form */}
                <div className="lg:col-span-1">
                    <div className="bg-white shadow sm:rounded-lg">
                        <div className="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 className="text-lg leading-6 font-medium text-gray-900">Add Field</h3>
                            <p className="mt-1 max-w-2xl text-sm text-gray-500">Add a new field to this collection.</p>
                        </div>

                        <div className="px-4 py-5">
                            {!showAddField ? (
                                <button
                                    onClick={() => setShowAddField(true)}
                                    className="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Add New Field
                                </button>
                            ) : (
                                <form onSubmit={handleAddField} className="space-y-4">
                                    <div>
                                        <label htmlFor="field_name" className="block text-sm font-medium text-gray-700">Field Name</label>
                                        <input
                                            type="text"
                                            id="field_name"
                                            value={newField.name}
                                            onChange={(e) => setNewField(prev => ({ ...prev, name: e.target.value }))}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            placeholder="field_name"
                                            required
                                        />
                                        <p className="mt-1 text-xs text-gray-500">Must be unique, lowercase, no spaces.</p>
                                    </div>

                                    <div>
                                        <label htmlFor="field_label" className="block text-sm font-medium text-gray-700">Field Label</label>
                                        <input
                                            type="text"
                                            id="field_label"
                                            value={newField.label}
                                            onChange={(e) => setNewField(prev => ({ ...prev, label: e.target.value }))}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            placeholder="Display Name"
                                            required
                                        />
                                    </div>

                                    <div>
                                        <label htmlFor="field_type" className="block text-sm font-medium text-gray-700">Field Type</label>
                                        <select
                                            id="field_type"
                                            value={newField.type}
                                            onChange={(e) => setNewField(prev => ({ ...prev, type: e.target.value }))}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        >
                                            {Object.entries(fieldTypes).map(([value, label]) => (
                                                <option key={value} value={value}>{label}</option>
                                            ))}
                                        </select>
                                    </div>

                                    <div>
                                        <label htmlFor="default_value" className="block text-sm font-medium text-gray-700">Default Value</label>
                                        <input
                                            type="text"
                                            id="default_value"
                                            value={newField.default_value}
                                            onChange={(e) => setNewField(prev => ({ ...prev, default_value: e.target.value }))}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            placeholder="Optional"
                                        />
                                    </div>

                                    <div className="space-y-3">
                                        <div className="flex items-center">
                                            <input
                                                id="is_required"
                                                type="checkbox"
                                                checked={newField.is_required}
                                                onChange={(e) => setNewField(prev => ({ ...prev, is_required: e.target.checked }))}
                                                className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                            />
                                            <label htmlFor="is_required" className="ml-2 block text-sm text-gray-900">Required field</label>
                                        </div>
                                        <div className="flex items-center">
                                            <input
                                                id="is_unique"
                                                type="checkbox"
                                                checked={newField.is_unique}
                                                onChange={(e) => setNewField(prev => ({ ...prev, is_unique: e.target.checked }))}
                                                className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                            />
                                            <label htmlFor="is_unique" className="ml-2 block text-sm text-gray-900">Unique values only</label>
                                        </div>
                                        <div className="flex items-center">
                                            <input
                                                id="is_searchable"
                                                type="checkbox"
                                                checked={newField.is_searchable}
                                                onChange={(e) => setNewField(prev => ({ ...prev, is_searchable: e.target.checked }))}
                                                className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                            />
                                            <label htmlFor="is_searchable" className="ml-2 block text-sm text-gray-900">Searchable</label>
                                        </div>
                                    </div>

                                    <div className="flex space-x-3">
                                        <button
                                            type="button"
                                            onClick={() => setShowAddField(false)}
                                            className="flex-1 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            className="flex-1 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        >
                                            Add Field
                                        </button>
                                    </div>
                                </form>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CollectionDetail;