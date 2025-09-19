import React, { useState, useEffect } from 'react';
import CollectionList from './CollectionList';
import CollectionForm from './CollectionForm';
import CollectionDetail from './CollectionDetail';

const DataCollectionApp = ({ projectId, projectName, csrfToken }) => {
    const [currentView, setCurrentView] = useState('list');
    const [collections, setCollections] = useState([]);
    const [selectedCollection, setSelectedCollection] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // Fetch collections from the API
    const fetchCollections = async () => {
        try {
            setLoading(true);
            const response = await fetch(`/user/projects/${projectId}/data-collections`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch collections');
            }

            const data = await response.json();
            setCollections(data.collections || []);
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchCollections();
    }, [projectId]);

    const handleCreateCollection = async (collectionData) => {
        try {
            const response = await fetch(`/user/projects/${projectId}/data-collections`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(collectionData)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to create collection');
            }

            await fetchCollections();
            setCurrentView('list');
        } catch (err) {
            setError(err.message);
        }
    };

    const handleEditCollection = (collection) => {
        setSelectedCollection(collection);
        setCurrentView('edit');
    };

    const handleUpdateCollection = async (collectionData) => {
        try {
            const response = await fetch(`/user/projects/${projectId}/data-collections/${selectedCollection.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(collectionData)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to update collection');
            }

            await fetchCollections();
            setCurrentView('list');
            setSelectedCollection(null);
        } catch (err) {
            setError(err.message);
        }
    };

    const handleDeleteCollection = async (collectionId) => {
        if (!confirm('Are you sure you want to delete this collection? This will also delete all associated records.')) {
            return;
        }

        try {
            const response = await fetch(`/user/projects/${projectId}/data-collections/${collectionId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to delete collection');
            }

            await fetchCollections();
        } catch (err) {
            setError(err.message);
        }
    };

    const handleViewCollection = (collection) => {
        setSelectedCollection(collection);
        setCurrentView('detail');
    };

    const renderContent = () => {
        if (loading) {
            return (
                <div className="flex items-center justify-center h-64">
                    <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                </div>
            );
        }

        if (error) {
            return (
                <div className="bg-red-50 border border-red-200 rounded-md p-4">
                    <div className="flex">
                        <svg className="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                        </svg>
                        <div className="ml-3">
                            <h3 className="text-sm font-medium text-red-800">
                                Error loading data collections
                            </h3>
                            <div className="mt-2 text-sm text-red-700">
                                <p>{error}</p>
                            </div>
                            <div className="mt-4">
                                <button
                                    onClick={() => {
                                        setError(null);
                                        fetchCollections();
                                    }}
                                    className="bg-red-100 px-3 py-2 rounded-md text-sm font-medium text-red-800 hover:bg-red-200"
                                >
                                    Try Again
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            );
        }

        switch (currentView) {
            case 'create':
                return (
                    <CollectionForm
                        onSubmit={handleCreateCollection}
                        onCancel={() => setCurrentView('list')}
                        csrfToken={csrfToken}
                    />
                );
            case 'edit':
                return (
                    <CollectionForm
                        collection={selectedCollection}
                        onSubmit={handleUpdateCollection}
                        onCancel={() => {
                            setCurrentView('list');
                            setSelectedCollection(null);
                        }}
                        csrfToken={csrfToken}
                    />
                );
            case 'detail':
                return (
                    <CollectionDetail
                        collection={selectedCollection}
                        projectId={projectId}
                        onBack={() => setCurrentView('list')}
                        onEdit={(collection) => handleEditCollection(collection)}
                        onDelete={handleDeleteCollection}
                        csrfToken={csrfToken}
                    />
                );
            default:
                return (
                    <CollectionList
                        collections={collections}
                        projectName={projectName}
                        onCreateNew={() => setCurrentView('create')}
                        onEdit={handleEditCollection}
                        onDelete={handleDeleteCollection}
                        onView={handleViewCollection}
                    />
                );
        }
    };

    return (
        <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {renderContent()}
        </div>
    );
};

export default DataCollectionApp;