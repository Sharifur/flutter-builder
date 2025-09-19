import React from 'react';

const Widget = ({ widget }) => {
    const { type, config = {} } = widget;

    const renderWidget = () => {
        switch (type) {
            case 'Text':
                return (
                    <div
                        className="p-2"
                        style={{
                            color: config.color || '#000000',
                            fontSize: `${config.fontSize || 16}px`,
                            fontWeight: config.fontWeight || 'normal'
                        }}
                    >
                        {config.value || 'Sample text'}
                    </div>
                );

            case 'Button':
                return (
                    <button
                        className="w-full py-3 px-4 rounded-lg font-medium text-sm transition-colors"
                        style={{
                            backgroundColor: config.color || '#3B82F6',
                            color: config.textColor || '#FFFFFF'
                        }}
                    >
                        {config.label || 'Button'}
                    </button>
                );

            case 'Image':
                return (
                    <div className="relative overflow-hidden rounded-lg border border-gray-200">
                        <img
                            src={config.url || 'https://via.placeholder.com/300x200'}
                            alt={config.alt || 'Image'}
                            className="w-full object-cover"
                            style={{ height: `${config.height || 120}px` }}
                            onError={(e) => {
                                e.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2Y5ZmFmYiIvPjx0ZXh0IHg9IjE1MCIgeT0iMTAwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM5Y2EzYWYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5JbWFnZSBOb3QgRm91bmQ8L3RleHQ+PC9zdmc+';
                            }}
                        />
                    </div>
                );

            case 'Input':
                return (
                    <div className="space-y-1">
                        {config.label && (
                            <label className="block text-sm font-medium text-gray-700">
                                {config.label}
                            </label>
                        )}
                        <input
                            type={config.type || 'text'}
                            placeholder={config.placeholder || 'Enter text here'}
                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            disabled
                        />
                        {config.required && (
                            <p className="text-xs text-gray-400">Required field</p>
                        )}
                    </div>
                );

            case 'Container':
                return (
                    <div
                        className="border-2 border-dashed border-gray-300 rounded-lg p-4"
                        style={{ backgroundColor: config.backgroundColor || '#FFFFFF' }}
                    >
                        <div className="flex items-center space-x-2 mb-2">
                            <svg className="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span className="text-xs text-gray-600">Container Widget</span>
                        </div>
                    </div>
                );

            case 'Card':
                return (
                    <div className="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                        <h4 className="font-medium">{config.title || 'Card Title'}</h4>
                        <p className="text-sm text-gray-500 mt-1">{config.subtitle || 'Card subtitle'}</p>
                    </div>
                );

            case 'LoginForm':
            case 'RegisterForm':
            case 'UnifiedForm':
                return (
                    <div className="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                        <div className="text-center mb-6">
                            <h2 className="text-xl font-bold text-gray-900 mb-2">
                                {config.title || (type === 'LoginForm' ? 'Welcome Back' : type === 'RegisterForm' ? 'Create Account' : 'Dynamic Form')}
                            </h2>
                            <p className="text-gray-600">
                                {config.subtitle || (type === 'LoginForm' ? 'Sign in to your account' : type === 'RegisterForm' ? 'Sign up for a new account' : 'Fill out the form below')}
                            </p>
                        </div>

                        <div className="space-y-4">
                            {type !== 'LoginForm' && (
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <input type="text" className="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter your name" disabled />
                                </div>
                            )}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" className="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter your email" disabled />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" className="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter your password" disabled />
                            </div>
                            {type === 'LoginForm' && (
                                <div className="flex items-center">
                                    <input type="checkbox" className="h-4 w-4 text-indigo-600" disabled />
                                    <label className="ml-2 block text-sm text-gray-900">Remember me</label>
                                </div>
                            )}
                            <button className="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                                {type === 'LoginForm' ? 'Sign In' : type === 'RegisterForm' ? 'Sign Up' : 'Submit'}
                            </button>
                        </div>
                    </div>
                );

            case 'StatCard':
                return (
                    <div className="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                        <div className="flex items-center space-x-2 mb-2">
                            <div className="w-6 h-6 bg-indigo-100 rounded flex items-center justify-center">
                                <svg className="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span className="text-sm text-gray-500">{config.title || 'Total Customer'}</span>
                        </div>
                        <h3 className="text-2xl font-bold text-gray-900">{config.value || '32,502'}</h3>
                        <p className={`text-sm mt-1 ${(config.changeType || 'increase') === 'decrease' ? 'text-red-600' : 'text-green-600'}`}>
                            {(config.changeType || 'increase') === 'decrease' ? '↓' : '↑'} {config.change || '2.1%'}
                            <span className="text-gray-500 ml-1">vs last month</span>
                        </p>
                    </div>
                );

            case 'BalanceCard':
                return (
                    <div className="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-4 text-white">
                        <div className="flex items-center justify-between mb-2">
                            <span className="text-white/80 text-sm">{config.title || 'Current Balance'}</span>
                            <svg className="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h2 className="text-2xl font-bold">{config.amount || '$42,450.75'}</h2>
                        <p className="text-white/60 text-sm">{config.currency || 'USD'}</p>
                    </div>
                );

            case 'ProfileHeader':
                return (
                    <div className="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg text-white p-4">
                        <div className="flex items-center space-x-4">
                            <div className="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                                <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 className="text-lg font-semibold">{config.name || 'John Doe'}</h3>
                                <p className="text-white/80 text-sm">{config.email || 'john@example.com'}</p>
                            </div>
                        </div>
                    </div>
                );

            case 'TabBar':
                return (
                    <div className="bg-white rounded-lg border border-gray-200">
                        <div className="flex p-1">
                            {(config.tabs || ['Dashboard', 'Cards', 'Accounts', 'Settings']).map((tab, index) => (
                                <div
                                    key={index}
                                    className={`flex-1 text-center py-2 px-3 text-sm rounded-md ${
                                        index === 0 ? 'bg-indigo-100 text-indigo-600 font-medium' : 'text-gray-500'
                                    }`}
                                >
                                    {tab}
                                </div>
                            ))}
                        </div>
                    </div>
                );

            case 'ActionButton':
                return (
                    <div className="flex items-center space-x-3 p-3 bg-white rounded-lg border border-gray-200">
                        <div className="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg className="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <span className="font-medium">{config.label || 'Add'}</span>
                    </div>
                );

            default:
                return (
                    <div className="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                        <svg className="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                        <p className="text-sm text-gray-600">{type}</p>
                        <p className="text-xs text-gray-400">Widget type not implemented</p>
                    </div>
                );
        }
    };

    return (
        <div className="widget-component">
            {renderWidget()}
        </div>
    );
};

export default Widget;