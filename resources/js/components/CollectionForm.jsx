import React, { useState, useEffect } from 'react';

const CollectionForm = ({ collection, onSubmit, onCancel, csrfToken }) => {
    const [formData, setFormData] = useState({
        name: '',
        description: '',
        icon: 'database',
        is_active: true
    });
    const [errors, setErrors] = useState({});
    const [submitting, setSubmitting] = useState(false);

    const isEditing = !!collection;

    useEffect(() => {
        if (collection) {
            setFormData({
                name: collection.name || '',
                description: collection.description || '',
                icon: collection.icon || 'database',
                is_active: collection.is_active !== undefined ? collection.is_active : true
            });
        }
    }, [collection]);

    const handleInputChange = (e) => {
        const { name, value, type, checked } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: type === 'checkbox' ? checked : value
        }));

        // Clear error for this field
        if (errors[name]) {
            setErrors(prev => ({
                ...prev,
                [name]: null
            }));
        }
    };

    const validateForm = () => {
        const newErrors = {};

        if (!formData.name.trim()) {
            newErrors.name = 'Collection name is required';
        } else if (formData.name.length > 255) {
            newErrors.name = 'Collection name must be less than 255 characters';
        }

        if (formData.description && formData.description.length > 500) {
            newErrors.description = 'Description must be less than 500 characters';
        }

        if (formData.icon && formData.icon.length > 50) {
            newErrors.icon = 'Icon name must be less than 50 characters';
        }

        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        setSubmitting(true);
        try {
            await onSubmit(formData);
        } catch (error) {
            if (error.errors) {
                setErrors(error.errors);
            } else {
                setErrors({ general: error.message || 'An error occurred' });
            }
        } finally {
            setSubmitting(false);
        }
    };

    return (
        <div className="space-y-6">
            {/* Header */}
            <div className="bg-white shadow sm:rounded-lg">
                <div className="px-4 py-5 sm:p-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-900">
                                {isEditing ? 'Edit Collection' : 'Create New Collection'}
                            </h2>
                            <p className="mt-1 text-sm text-gray-600">
                                {isEditing
                                    ? 'Update the details of your data collection.'
                                    : 'Create a new data collection for dynamic content in your mobile app.'
                                }
                            </p>
                        </div>
                        <button
                            onClick={onCancel}
                            className="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back
                        </button>
                    </div>
                </div>
            </div>

            {/* Form */}
            <div className="bg-white shadow sm:rounded-lg">
                <form onSubmit={handleSubmit} className="p-6 space-y-6">
                    {errors.general && (
                        <div className="bg-red-50 border border-red-200 rounded-md p-4">
                            <div className="flex">
                                <svg className="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                </svg>
                                <div className="ml-3">
                                    <h3 className="text-sm font-medium text-red-800">{errors.general}</h3>
                                </div>
                            </div>
                        </div>
                    )}

                    <div>
                        <label htmlFor="name" className="block text-sm font-medium text-gray-700">
                            Collection Name *
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value={formData.name}
                            onChange={handleInputChange}
                            className={`mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ${
                                errors.name ? 'border-red-300' : ''
                            }`}
                            placeholder="e.g., Blog Posts, Products, Users"
                            required
                        />
                        {errors.name && (
                            <p className="mt-1 text-sm text-red-600">{errors.name}</p>
                        )}
                        <p className="mt-1 text-sm text-gray-500">
                            A descriptive name for your data collection. This will be used in the API endpoints.
                        </p>
                    </div>

                    <div>
                        <label htmlFor="description" className="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            value={formData.description}
                            onChange={handleInputChange}
                            className={`mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ${
                                errors.description ? 'border-red-300' : ''
                            }`}
                            placeholder="Describe what this collection will store..."
                        />
                        {errors.description && (
                            <p className="mt-1 text-sm text-red-600">{errors.description}</p>
                        )}
                        <p className="mt-1 text-sm text-gray-500">
                            Optional. Describe the purpose of this data collection.
                        </p>
                    </div>

                    <div>
                        <label htmlFor="icon" className="block text-sm font-medium text-gray-700">
                            Icon (FontAwesome)
                        </label>
                        <div className="mt-1 relative">
                            <input
                                type="text"
                                name="icon"
                                id="icon"
                                value={formData.icon}
                                onChange={handleInputChange}
                                className={`block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ${
                                    errors.icon ? 'border-red-300' : ''
                                }`}
                                placeholder="database"
                            />
                            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i className={`fas fa-${formData.icon || 'database'} text-gray-400`}></i>
                            </div>
                        </div>
                        {errors.icon && (
                            <p className="mt-1 text-sm text-red-600">{errors.icon}</p>
                        )}
                        <p className="mt-1 text-sm text-gray-500">
                            FontAwesome icon name (without 'fa-' prefix). Examples: database, table, list, users, shopping-cart
                        </p>
                    </div>

                    {isEditing && (
                        <div className="flex items-center">
                            <input
                                id="is_active"
                                name="is_active"
                                type="checkbox"
                                checked={formData.is_active}
                                onChange={handleInputChange}
                                className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            />
                            <label htmlFor="is_active" className="ml-2 block text-sm text-gray-900">
                                Active
                            </label>
                            <p className="ml-2 text-sm text-gray-500">
                                Inactive collections are hidden from the API.
                            </p>
                        </div>
                    )}

                    <div className="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div className="flex">
                            <div className="flex-shrink-0">
                                <svg className="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd"></path>
                                </svg>
                            </div>
                            <div className="ml-3">
                                <h3 className="text-sm font-medium text-blue-800">
                                    {isEditing ? 'Collection Information' : 'What happens next?'}
                                </h3>
                                <div className="mt-2 text-sm text-blue-700">
                                    {isEditing ? (
                                        <div>
                                            <p>Collection details:</p>
                                            <ul className="list-disc list-inside mt-1 space-y-1">
                                                <li>Slug: <code className="bg-blue-100 px-1 rounded">{collection.slug}</code></li>
                                                <li>{collection.fields_count || 0} fields defined</li>
                                                <li>{collection.records_count || 0} records stored</li>
                                                {collection.is_system && (
                                                    <li className="text-yellow-700">⚠️ This is a system collection</li>
                                                )}
                                            </ul>
                                        </div>
                                    ) : (
                                        <div>
                                            <p>After creating the collection, you'll be able to:</p>
                                            <ul className="list-disc list-inside mt-1 space-y-1">
                                                <li>Add custom fields with different data types</li>
                                                <li>Configure field validation and requirements</li>
                                                <li>Use the collection with UI components</li>
                                                <li>Access data through auto-generated APIs</li>
                                            </ul>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="flex justify-end space-x-3">
                        <button
                            type="button"
                            onClick={onCancel}
                            className="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            disabled={submitting}
                            className="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {submitting ? (
                                <>
                                    <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {isEditing ? 'Updating...' : 'Creating...'}
                                </>
                            ) : (
                                isEditing ? 'Update Collection' : 'Create Collection'
                            )}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default CollectionForm;