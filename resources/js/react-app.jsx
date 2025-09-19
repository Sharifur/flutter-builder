import React from 'react';
import { createRoot } from 'react-dom/client';
import DataCollectionApp from './components/DataCollectionApp';

// Initialize React app when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('react-data-collections');
    if (container) {
        const root = createRoot(container);

        // Get project data from the container's data attributes
        const projectId = container.dataset.projectId;
        const projectName = container.dataset.projectName;
        const csrfToken = container.dataset.csrfToken;

        root.render(
            <DataCollectionApp
                projectId={projectId}
                projectName={projectName}
                csrfToken={csrfToken}
            />
        );
    }
});