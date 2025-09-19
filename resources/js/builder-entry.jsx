import React from 'react';
import { createRoot } from 'react-dom/client';
import BuilderApp from './components/BuilderApp';

// Auto-mount when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('builder-app');
    if (container && window.builderConfig) {
        const root = createRoot(container);
        root.render(<BuilderApp />);
    }
});