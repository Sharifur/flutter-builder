import React, { useState, useEffect } from 'react';
import ComponentPalette from './builder/ComponentPalette';
import CanvasArea from './builder/CanvasArea';
import SettingsPanel from './builder/SettingsPanel';

const BuilderApp = (props = {}) => {
    // Get config from props or window object
    const config = props.projectId ? props : window.builderConfig || {};
    const { projectId, projectName, selectedPageId, csrfToken } = config;
    const [project, setProject] = useState(null);
    const [pages, setPages] = useState([]);
    const [currentPage, setCurrentPage] = useState(null);
    const [widgets, setWidgets] = useState([]);
    const [selectedWidget, setSelectedWidget] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // Fetch project data
    const fetchProjectData = async () => {
        try {
            setLoading(true);
            const response = await fetch(`/user/projects/${projectId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch project data');
            }

            const data = await response.json();
            setProject(data.project);
            setPages(data.project.app_pages || []);

            // Set current page
            const targetPage = selectedPageId
                ? data.project.app_pages.find(p => p.id == selectedPageId)
                : data.project.app_pages[0];

            if (targetPage) {
                setCurrentPage(targetPage);
                setWidgets(targetPage.widgets || []);
            }
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchProjectData();
    }, [projectId, selectedPageId]);

    // Switch pages
    const handlePageSwitch = async (pageId) => {
        const page = pages.find(p => p.id === pageId);
        if (page) {
            setCurrentPage(page);
            setWidgets(page.widgets || []);
            setSelectedWidget(null);

            // Update URL
            const url = new URL(window.location);
            url.searchParams.set('page', pageId);
            window.history.pushState({}, '', url);
        }
    };

    // Add widget
    const handleAddWidget = async (widgetType, insertIndex = null) => {
        try {
            const response = await fetch(`/user/pages/${currentPage.id}/widgets`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    type: widgetType,
                    order: insertIndex ?? widgets.length
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to add widget');
            }

            const data = await response.json();
            if (data.success) {
                // Refresh widgets
                await fetchProjectData();
            }
        } catch (err) {
            alert(err.message);
        }
    };

    // Update widget
    const handleUpdateWidget = async (widgetId, config) => {
        try {
            const response = await fetch(`/user/widgets/${widgetId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ config })
            });

            if (!response.ok) {
                throw new Error('Failed to update widget');
            }

            const data = await response.json();
            if (data.success) {
                // Update local state
                setWidgets(prev => prev.map(w =>
                    w.id === widgetId ? { ...w, config } : w
                ));
            }
        } catch (err) {
            alert(err.message);
        }
    };

    // Delete widget
    const handleDeleteWidget = async (widgetId) => {
        if (!confirm('Are you sure you want to delete this widget?')) {
            return;
        }

        try {
            const response = await fetch(`/user/widgets/${widgetId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) {
                throw new Error('Failed to delete widget');
            }

            // Remove from local state
            setWidgets(prev => prev.filter(w => w.id !== widgetId));
            setSelectedWidget(null);
        } catch (err) {
            alert(err.message);
        }
    };

    // Reorder widgets
    const handleReorderWidgets = async (reorderedWidgets) => {
        try {
            const response = await fetch(`/user/pages/${currentPage.id}/widgets/reorder`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    widgets: reorderedWidgets.map((widget, index) => ({
                        id: widget.id,
                        order: index
                    }))
                })
            });

            if (!response.ok) {
                throw new Error('Failed to reorder widgets');
            }

            setWidgets(reorderedWidgets);
        } catch (err) {
            alert(err.message);
        }
    };

    if (loading) {
        return (
            <div className="flex items-center justify-center h-screen">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="flex items-center justify-center h-screen">
                <div className="bg-red-50 border border-red-200 rounded-md p-4 max-w-md">
                    <div className="flex">
                        <svg className="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                        </svg>
                        <div className="ml-3">
                            <h3 className="text-sm font-medium text-red-800">
                                Error loading builder
                            </h3>
                            <div className="mt-2 text-sm text-red-700">
                                <p>{error}</p>
                            </div>
                            <div className="mt-4">
                                <button
                                    onClick={() => {
                                        setError(null);
                                        fetchProjectData();
                                    }}
                                    className="bg-red-100 px-3 py-2 rounded-md text-sm font-medium text-red-800 hover:bg-red-200"
                                >
                                    Try Again
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="h-screen flex flex-col bg-gray-50">
            {/* Header */}
            <header className="bg-white border-b border-gray-200 px-6 py-4">
                <div className="flex items-center justify-between">
                    <div className="flex items-center space-x-4">
                        <h1 className="text-xl font-semibold text-gray-900">
                            {project?.name} Builder
                        </h1>

                        {/* Page Tabs */}
                        <div className="flex space-x-1">
                            {pages.map((page) => (
                                <button
                                    key={page.id}
                                    onClick={() => handlePageSwitch(page.id)}
                                    className={`px-3 py-2 rounded-md text-sm font-medium transition-colors ${
                                        currentPage?.id === page.id
                                            ? 'bg-indigo-100 text-indigo-700'
                                            : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'
                                    }`}
                                >
                                    {page.name}
                                </button>
                            ))}
                        </div>
                    </div>

                    <div className="flex items-center space-x-4">
                        <button className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Preview
                        </button>
                        <button className="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                            Save
                        </button>
                    </div>
                </div>
            </header>

            {/* Main Content */}
            <div className="flex-1 flex">
                {/* Component Palette */}
                <ComponentPalette onAddWidget={handleAddWidget} />

                {/* Canvas Area */}
                <CanvasArea
                    widgets={widgets}
                    selectedWidget={selectedWidget}
                    onSelectWidget={setSelectedWidget}
                    onUpdateWidget={handleUpdateWidget}
                    onDeleteWidget={handleDeleteWidget}
                    onReorderWidgets={handleReorderWidgets}
                />

                {/* Settings Panel */}
                <SettingsPanel
                    selectedWidget={selectedWidget}
                    projectId={projectId}
                    onUpdateWidget={handleUpdateWidget}
                    onClosePanel={() => setSelectedWidget(null)}
                    csrfToken={csrfToken}
                />
            </div>
        </div>
    );
};

export default BuilderApp;