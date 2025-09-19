import React, { useState } from 'react';
import GeneralTab from './settings/GeneralTab';
import StyleTab from './settings/StyleTab';
import AdvancedTab from './settings/AdvancedTab';

const SettingsPanel = ({
    selectedWidget,
    projectId,
    onUpdateWidget,
    onClosePanel,
    csrfToken
}) => {
    const [activeTab, setActiveTab] = useState('general');

    if (!selectedWidget) {
        return (
            <div className="w-80 bg-white border-l border-gray-200">
                <div className="p-6 text-center text-gray-500">
                    <svg className="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 className="text-lg font-medium text-gray-900 mb-2">Widget Settings</h3>
                    <p className="text-sm">Select a widget to edit its properties</p>
                </div>
            </div>
        );
    }

    const tabs = [
        { id: 'general', name: 'General', icon: 'document' },
        { id: 'style', name: 'Style', icon: 'color-swatch' },
        { id: 'advanced', name: 'Advanced', icon: 'cog' }
    ];

    const getTabIcon = (icon) => {
        const icons = {
            document: (
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            ),
            'color-swatch': (
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 3v18M17 8l4-4M21 12l-4 4M17 16l4-4"></path>
                </svg>
            ),
            cog: (
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            )
        };
        return icons[icon] || icons.document;
    };

    const renderTabContent = () => {
        switch (activeTab) {
            case 'general':
                return (
                    <GeneralTab
                        widget={selectedWidget}
                        onUpdateWidget={onUpdateWidget}
                    />
                );
            case 'style':
                return (
                    <StyleTab
                        widget={selectedWidget}
                        onUpdateWidget={onUpdateWidget}
                    />
                );
            case 'advanced':
                return (
                    <AdvancedTab
                        widget={selectedWidget}
                        projectId={projectId}
                        onUpdateWidget={onUpdateWidget}
                        csrfToken={csrfToken}
                    />
                );
            default:
                return null;
        }
    };

    return (
        <div className="w-80 bg-white border-l border-gray-200 flex flex-col">
            {/* Header */}
            <div className="p-4 border-b border-gray-200">
                <div className="flex items-center justify-between">
                    <div>
                        <h3 className="text-lg font-medium text-gray-900">Widget Settings</h3>
                        <p className="text-sm text-gray-500 capitalize">{selectedWidget.type} Properties</p>
                    </div>
                    <button
                        onClick={onClosePanel}
                        className="text-gray-400 hover:text-gray-600"
                        title="Close panel"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {/* Tabs */}
            <div className="border-b border-gray-200">
                <nav className="flex space-x-0">
                    {tabs.map((tab) => (
                        <button
                            key={tab.id}
                            onClick={() => setActiveTab(tab.id)}
                            className={`flex-1 flex items-center justify-center space-x-2 px-3 py-3 text-sm font-medium border-b-2 transition-colors ${
                                activeTab === tab.id
                                    ? 'border-indigo-500 text-indigo-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                            }`}
                        >
                            {getTabIcon(tab.icon)}
                            <span className="hidden sm:inline">{tab.name}</span>
                        </button>
                    ))}
                </nav>
            </div>

            {/* Tab Content */}
            <div className="flex-1 overflow-y-auto">
                {renderTabContent()}
            </div>
        </div>
    );
};

export default SettingsPanel;