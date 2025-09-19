<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PreviewController extends Controller
{
    use AuthorizesRequests;

    public function show(Project $project)
    {
        // For demo purposes, allow public access
        // In production, you might want to add authorization back
        // $this->authorize('view', $project);

        // Check if we have a cached Flutter web build
        $buildPath = $this->getFlutterWebBuildPath($project);

        if (!File::exists($buildPath)) {
            // Generate Flutter web build if it doesn't exist
            try {
                $this->generateFlutterWebBuild($project);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate preview: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Return the preview HTML page
        return view('user.projects.preview', compact('project'));
    }

    public function iframe(Project $project)
    {
        // Serve the actual Flutter web app in an iframe
        $buildPath = $this->getFlutterWebBuildPath($project);
        $indexPath = $buildPath . '/index.html';

        if (!File::exists($indexPath)) {
            return response('Preview not available. Please try again.', 404);
        }

        // Read and modify the Flutter web index.html to inject our schema
        $content = File::get($indexPath);

        // Inject the schema as a global variable
        $schema = json_encode($project->toSchemaArray());
        $injection = "<script>window.flutterSchema = {$schema};</script>";

        // Insert before closing head tag
        $content = str_replace('</head>', $injection . '</head>', $content);

        return response($content)->header('Content-Type', 'text/html');
    }

    public function assets(Project $project, Request $request)
    {
        // Serve Flutter web assets
        $buildPath = $this->getFlutterWebBuildPath($project);
        $assetPath = $request->path();

        // Remove the preview route prefix
        $assetPath = str_replace("preview/{$project->id}/", '', $assetPath);

        $fullPath = $buildPath . '/' . $assetPath;

        if (!File::exists($fullPath) || strpos(realpath($fullPath), realpath($buildPath)) !== 0) {
            return response('Asset not found', 404);
        }

        $mimeType = $this->getMimeType($fullPath);

        return response(File::get($fullPath))
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=3600');
    }

    private function getFlutterWebBuildPath(Project $project): string
    {
        return storage_path("app/flutter_builds/project_{$project->id}");
    }

    private function generateFlutterWebBuild(Project $project): void
    {
        $buildPath = $this->getFlutterWebBuildPath($project);

        // Create build directory
        if (!File::exists($buildPath)) {
            File::makeDirectory($buildPath, 0755, true);
        }

        // Create a simplified Flutter web app
        $this->createFlutterWebApp($project, $buildPath);
    }

    private function createFlutterWebApp(Project $project, string $buildPath): void
    {
        // Create index.html
        $indexHtml = $this->generateIndexHtml($project);
        File::put($buildPath . '/index.html', $indexHtml);

        // Create main.dart.js (simplified JavaScript version)
        $mainJs = $this->generateMainJs($project);
        File::put($buildPath . '/main.dart.js', $mainJs);

        // Create basic CSS
        $css = $this->generateCss();
        File::put($buildPath . '/styles.css', $css);
    }

    private function generateIndexHtml(Project $project): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($project->name) . ' - Preview</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="app-container">
        <div class="phone-frame">
            <div class="phone-header">
                <div class="status-bar">
                    <span class="time">9:41</span>
                    <div class="indicators">
                        <span class="signal">📶</span>
                        <span class="wifi">📶</span>
                        <span class="battery">🔋</span>
                    </div>
                </div>
                <div class="app-bar">
                    <h1 id="page-title">Loading...</h1>
                </div>
            </div>
            <div class="phone-content" id="phone-content">
                <div class="loading">Loading app...</div>
            </div>
        </div>
    </div>
    <script src="main.dart.js"></script>
</body>
</html>';
    }

    private function generateMainJs(Project $project): string
    {
        $schema = json_encode($project->toSchemaArray());

        return "
// Flutter Web Preview - JavaScript Version
const schema = {$schema};
let currentPageIndex = 0;

document.addEventListener('DOMContentLoaded', function() {
    renderApp();
});

function renderApp() {
    if (!schema.pages || schema.pages.length === 0) {
        document.getElementById('phone-content').innerHTML = '<div class=\"empty-state\">No pages found</div>';
        return;
    }

    renderPage(schema.pages[currentPageIndex]);
}

function renderPage(page) {
    document.getElementById('page-title').textContent = page.name || 'Untitled';

    const content = document.getElementById('phone-content');
    content.innerHTML = '';

    if (page.widgets && page.widgets.length > 0) {
        page.widgets.forEach(widget => {
            const element = renderWidget(widget);
            if (element) {
                content.appendChild(element);
            }
        });
    } else {
        content.innerHTML = '<div class=\"empty-state\">No widgets on this page</div>';
    }
}

function renderWidget(widget) {
    const container = document.createElement('div');
    container.className = 'widget-container';

    switch (widget.type) {
        case 'Text':
            const text = document.createElement('p');
            text.textContent = widget.value || 'Welcome to your app';
            text.style.color = widget.color || '#1F2937';
            text.style.fontSize = (widget.fontSize || 18) + 'px';
            text.style.fontWeight = widget.fontWeight || '600';
            text.style.textAlign = widget.textAlign || 'left';
            text.style.marginBottom = '8px';
            text.style.lineHeight = '1.5';
            container.appendChild(text);
            break;

        case 'Button':
            const button = document.createElement('button');
            button.textContent = widget.label || 'Get Started';
            button.className = 'widget-button';
            button.style.backgroundColor = widget.color || '#3B82F6';
            button.style.color = widget.textColor || '#FFFFFF';
            button.style.borderRadius = (widget.borderRadius || 8) + 'px';
            button.style.padding = widget.size === 'large' ? '16px 24px' : widget.size === 'small' ? '8px 16px' : '12px 20px';
            button.style.fontSize = widget.size === 'large' ? '16px' : widget.size === 'small' ? '12px' : '14px';
            button.style.fontWeight = '600';
            button.style.border = 'none';
            button.style.cursor = 'pointer';
            button.style.marginBottom = '8px';
            button.onclick = () => handleButtonAction(widget.action);
            container.appendChild(button);
            break;

        case 'Image':
            const imgContainer = document.createElement('div');
            imgContainer.style.marginBottom = '16px';

            const img = document.createElement('img');
            img.src = widget.url || 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=300&h=200&fit=crop';
            img.alt = widget.alt || 'Beautiful image';
            img.style.width = (widget.width || 300) + 'px';
            img.style.height = (widget.height || 200) + 'px';
            img.style.objectFit = 'cover';
            img.style.borderRadius = (widget.borderRadius || 8) + 'px';
            img.style.display = 'block';
            img.style.maxWidth = '100%';
            img.onerror = () => {
                img.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2Y5ZmFmYiIvPjx0ZXh0IHg9IjE1MCIgeT0iMTAwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM5Y2EzYWYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5JbWFnZSBOb3QgRm91bmQ8L3RleHQ+PC9zdmc+';
            };
            imgContainer.appendChild(img);
            container.appendChild(imgContainer);
            break;

        case 'Input':
            const inputContainer = document.createElement('div');
            inputContainer.style.marginBottom = '16px';

            if (widget.label) {
                const label = document.createElement('label');
                label.textContent = widget.label;
                label.style.display = 'block';
                label.style.fontSize = '14px';
                label.style.fontWeight = '500';
                label.style.color = '#374151';
                label.style.marginBottom = '4px';
                inputContainer.appendChild(label);
            }

            const input = document.createElement('input');
            input.type = widget.type || 'email';
            input.placeholder = widget.placeholder || 'Enter your email';
            input.className = 'widget-input';
            input.style.width = '100%';
            input.style.padding = '12px 16px';
            input.style.border = '1px solid #d1d5db';
            input.style.borderRadius = (widget.borderRadius || 8) + 'px';
            input.style.fontSize = '14px';
            input.style.boxSizing = 'border-box';
            input.style.transition = 'border-color 0.2s';
            input.onfocus = () => input.style.borderColor = '#3b82f6';
            input.onblur = () => input.style.borderColor = '#d1d5db';

            inputContainer.appendChild(input);

            if (widget.required) {
                const required = document.createElement('span');
                required.textContent = ' *';
                required.style.color = '#ef4444';
                inputContainer.querySelector('label')?.appendChild(required);
            }

            container.appendChild(inputContainer);
            break;

        case 'Container':
            const containerDiv = document.createElement('div');
            containerDiv.className = 'widget-container-inner';
            containerDiv.style.padding = (widget.padding || 20) + 'px';
            containerDiv.style.backgroundColor = widget.backgroundColor || '#F9FAFB';
            containerDiv.style.borderRadius = (widget.borderRadius || 12) + 'px';
            containerDiv.style.marginBottom = '16px';
            containerDiv.style.display = 'flex';
            containerDiv.style.flexDirection = widget.direction === 'row' ? 'row' : 'column';
            containerDiv.style.gap = (widget.spacing || 12) + 'px';

            if (widget.children && widget.children.length > 0) {
                widget.children.forEach(child => {
                    const childElement = renderWidget(child);
                    if (childElement) {
                        containerDiv.appendChild(childElement);
                    }
                });
            }
            container.appendChild(containerDiv);
            break;

        case 'Card':
            const card = document.createElement('div');
            card.className = 'widget-card';
            card.style.backgroundColor = widget.backgroundColor || '#FFFFFF';
            card.style.borderRadius = (widget.borderRadius || 12) + 'px';
            card.style.padding = (widget.padding || 16) + 'px';
            card.style.marginBottom = '16px';
            card.style.border = '1px solid #e5e7eb';
            if (widget.shadow) card.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';

            const cardTitle = document.createElement('h3');
            cardTitle.textContent = widget.title || 'Card Title';
            cardTitle.style.fontWeight = '600';
            cardTitle.style.marginBottom = '4px';
            card.appendChild(cardTitle);

            if (widget.subtitle) {
                const cardSubtitle = document.createElement('p');
                cardSubtitle.textContent = widget.subtitle;
                cardSubtitle.style.color = '#6b7280';
                cardSubtitle.style.fontSize = '14px';
                card.appendChild(cardSubtitle);
            }

            container.appendChild(card);
            break;

        case 'ProfileHeader':
            const profileHeader = document.createElement('div');
            profileHeader.className = 'widget-profile-header';
            profileHeader.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            profileHeader.style.padding = '20px';
            profileHeader.style.borderRadius = '12px';
            profileHeader.style.color = widget.textColor || '#FFFFFF';
            profileHeader.style.marginBottom = '16px';

            const profileContent = document.createElement('div');
            profileContent.style.display = 'flex';
            profileContent.style.alignItems = 'center';
            profileContent.style.gap = '12px';

            const avatar = document.createElement('img');
            avatar.src = widget.avatar || 'https://via.placeholder.com/50x50';
            avatar.style.width = '50px';
            avatar.style.height = '50px';
            avatar.style.borderRadius = '50%';
            avatar.style.border = '2px solid rgba(255,255,255,0.3)';
            profileContent.appendChild(avatar);

            const profileName = document.createElement('h2');
            profileName.textContent = widget.name || 'John Doe';
            profileName.style.fontWeight = '600';
            profileName.style.fontSize = '18px';
            profileContent.appendChild(profileName);

            profileHeader.appendChild(profileContent);
            container.appendChild(profileHeader);
            break;

        case 'BalanceCard':
            const balanceCard = document.createElement('div');
            balanceCard.className = 'widget-balance-card';
            balanceCard.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            balanceCard.style.padding = '20px';
            balanceCard.style.borderRadius = '12px';
            balanceCard.style.color = widget.textColor || '#FFFFFF';
            balanceCard.style.marginBottom = '16px';

            const balanceTitle = document.createElement('p');
            balanceTitle.textContent = widget.title || 'Total Balance';
            balanceTitle.style.opacity = '0.8';
            balanceTitle.style.fontSize = '14px';
            balanceTitle.style.marginBottom = '8px';
            balanceCard.appendChild(balanceTitle);

            const balanceAmount = document.createElement('h1');
            balanceAmount.textContent = widget.amount || '$1,234.56';
            balanceAmount.style.fontSize = '32px';
            balanceAmount.style.fontWeight = 'bold';
            balanceCard.appendChild(balanceAmount);

            container.appendChild(balanceCard);
            break;

        case 'CryptoItem':
            const cryptoItem = document.createElement('div');
            cryptoItem.className = 'widget-crypto-item';
            cryptoItem.style.backgroundColor = '#FFFFFF';
            cryptoItem.style.padding = '16px';
            cryptoItem.style.borderRadius = '12px';
            cryptoItem.style.border = '1px solid #e5e7eb';
            cryptoItem.style.marginBottom = '8px';
            cryptoItem.style.display = 'flex';
            cryptoItem.style.justifyContent = 'space-between';
            cryptoItem.style.alignItems = 'center';

            const cryptoLeft = document.createElement('div');
            cryptoLeft.style.display = 'flex';
            cryptoLeft.style.alignItems = 'center';
            cryptoLeft.style.gap = '12px';

            const cryptoIcon = document.createElement('div');
            cryptoIcon.style.width = '40px';
            cryptoIcon.style.height = '40px';
            cryptoIcon.style.backgroundColor = '#f59e0b';
            cryptoIcon.style.borderRadius = '50%';
            cryptoIcon.style.display = 'flex';
            cryptoIcon.style.alignItems = 'center';
            cryptoIcon.style.justifyContent = 'center';
            cryptoIcon.style.color = '#FFFFFF';
            cryptoIcon.style.fontWeight = 'bold';
            cryptoIcon.textContent = '₿';
            cryptoLeft.appendChild(cryptoIcon);

            const cryptoInfo = document.createElement('div');
            const cryptoName = document.createElement('h4');
            cryptoName.textContent = widget.name || 'Bitcoin';
            cryptoName.style.fontWeight = '600';
            cryptoName.style.marginBottom = '2px';
            cryptoInfo.appendChild(cryptoName);

            const cryptoAmount = document.createElement('p');
            cryptoAmount.textContent = (widget.amount || '0.1234') + ' ' + (widget.symbol || 'BTC');
            cryptoAmount.style.color = '#6b7280';
            cryptoAmount.style.fontSize = '14px';
            cryptoInfo.appendChild(cryptoAmount);

            cryptoLeft.appendChild(cryptoInfo);
            cryptoItem.appendChild(cryptoLeft);

            const cryptoRight = document.createElement('div');
            cryptoRight.style.textAlign = 'right';

            const cryptoValue = document.createElement('p');
            cryptoValue.textContent = widget.value || '$5,678.90';
            cryptoValue.style.fontWeight = '600';
            cryptoValue.style.marginBottom = '2px';
            cryptoRight.appendChild(cryptoValue);

            const cryptoChange = document.createElement('p');
            cryptoChange.textContent = widget.change || '+2.15%';
            cryptoChange.style.color = widget.changeColor || '#10b981';
            cryptoChange.style.fontSize = '14px';
            cryptoRight.appendChild(cryptoChange);

            cryptoItem.appendChild(cryptoRight);
            container.appendChild(cryptoItem);
            break;

        case 'ActionButton':
            const actionBtn = document.createElement('div');
            actionBtn.className = 'widget-action-button';
            actionBtn.style.backgroundColor = widget.backgroundColor || '#FFFFFF';
            actionBtn.style.padding = '12px';
            actionBtn.style.borderRadius = '12px';
            actionBtn.style.border = '1px solid #e5e7eb';
            actionBtn.style.marginBottom = '8px';
            actionBtn.style.display = 'flex';
            actionBtn.style.alignItems = 'center';
            actionBtn.style.gap = '12px';
            actionBtn.style.cursor = 'pointer';

            const actionIcon = document.createElement('div');
            actionIcon.style.width = '48px';
            actionIcon.style.height = '48px';
            actionIcon.style.backgroundColor = '#f3f4f6';
            actionIcon.style.borderRadius = '50%';
            actionIcon.style.display = 'flex';
            actionIcon.style.alignItems = 'center';
            actionIcon.style.justifyContent = 'center';
            actionIcon.style.color = widget.iconColor || '#6366f1';
            actionIcon.innerHTML = '+';
            actionIcon.style.fontSize = '20px';
            actionIcon.style.fontWeight = 'bold';
            actionBtn.appendChild(actionIcon);

            const actionLabel = document.createElement('span');
            actionLabel.textContent = widget.label || 'Add';
            actionLabel.style.fontWeight = '600';
            actionLabel.style.color = widget.textColor || '#374151';
            actionBtn.appendChild(actionLabel);

            container.appendChild(actionBtn);
            break;

        case 'TransactionItem':
            const transactionItem = document.createElement('div');
            transactionItem.className = 'widget-transaction-item';
            transactionItem.style.backgroundColor = '#FFFFFF';
            transactionItem.style.padding = '12px';
            transactionItem.style.borderRadius = '8px';
            transactionItem.style.border = '1px solid #e5e7eb';
            transactionItem.style.marginBottom = '8px';
            transactionItem.style.display = 'flex';
            transactionItem.style.justifyContent = 'space-between';
            transactionItem.style.alignItems = 'center';

            const transactionLeft = document.createElement('div');
            transactionLeft.style.display = 'flex';
            transactionLeft.style.alignItems = 'center';
            transactionLeft.style.gap = '12px';

            const transactionIcon = document.createElement('div');
            transactionIcon.style.width = '32px';
            transactionIcon.style.height = '32px';
            transactionIcon.style.backgroundColor = '#f3f4f6';
            transactionIcon.style.borderRadius = '50%';
            transactionIcon.style.display = 'flex';
            transactionIcon.style.alignItems = 'center';
            transactionIcon.style.justifyContent = 'center';
            transactionIcon.innerHTML = '↔';
            transactionLeft.appendChild(transactionIcon);

            const transactionInfo = document.createElement('div');
            const transactionTitle = document.createElement('p');
            transactionTitle.textContent = widget.title || 'Transaction';
            transactionTitle.style.fontWeight = '600';
            transactionTitle.style.fontSize = '14px';
            transactionTitle.style.marginBottom = '2px';
            transactionInfo.appendChild(transactionTitle);

            const transactionSubtitle = document.createElement('p');
            transactionSubtitle.textContent = widget.subtitle || '2023-07-25';
            transactionSubtitle.style.color = '#6b7280';
            transactionSubtitle.style.fontSize = '12px';
            transactionInfo.appendChild(transactionSubtitle);

            transactionLeft.appendChild(transactionInfo);
            transactionItem.appendChild(transactionLeft);

            const transactionAmount = document.createElement('p');
            transactionAmount.textContent = widget.amount || '+$100.00';
            transactionAmount.style.color = widget.amountColor || '#10b981';
            transactionAmount.style.fontWeight = '600';
            transactionAmount.style.fontSize = '14px';
            transactionItem.appendChild(transactionAmount);

            container.appendChild(transactionItem);
            break;

        case 'TabBar':
            const tabBar = document.createElement('div');
            tabBar.className = 'widget-tab-bar';
            tabBar.style.backgroundColor = widget.backgroundColor || '#FFFFFF';
            tabBar.style.borderTop = '1px solid #e5e7eb';
            tabBar.style.padding = '8px';
            tabBar.style.display = 'flex';
            tabBar.style.justifyContent = 'space-around';
            tabBar.style.position = 'fixed';
            tabBar.style.bottom = '0';
            tabBar.style.left = '0';
            tabBar.style.right = '0';

            const tabs = widget.tabs || ['Dashboard', 'Cards', 'Accounts', 'Settings'];
            const activeTab = widget.activeTab || 0;

            tabs.forEach((tab, index) => {
                const tabItem = document.createElement('div');
                tabItem.style.textAlign = 'center';
                tabItem.style.padding = '8px';
                tabItem.style.color = index === activeTab ? (widget.activeColor || '#6366f1') : (widget.inactiveColor || '#9ca3af');
                tabItem.style.fontSize = '12px';
                tabItem.style.fontWeight = index === activeTab ? '600' : 'normal';
                tabItem.textContent = tab;
                tabBar.appendChild(tabItem);
            });

            container.appendChild(tabBar);
            break;

        default:
            container.innerHTML = '<div class=\"unsupported-widget\">Unsupported widget: ' + widget.type + '</div>';
    }

    return container;
}

function handleButtonAction(action) {
    if (!action) return;

    if (action.startsWith('goTo:')) {
        const targetPageId = action.split(':')[1];
        const targetPage = schema.pages.find(p => p.id === targetPageId);

        if (targetPage) {
            const targetIndex = schema.pages.indexOf(targetPage);
            if (targetIndex !== -1) {
                currentPageIndex = targetIndex;
                renderPage(targetPage);
            }
        }
    }
}

// Navigation functions
function goToPage(index) {
    if (index >= 0 && index < schema.pages.length) {
        currentPageIndex = index;
        renderPage(schema.pages[index]);
    }
}

function nextPage() {
    goToPage(currentPageIndex + 1);
}

function prevPage() {
    goToPage(currentPageIndex - 1);
}
";
    }

    private function generateCss(): string
    {
        return '
body {
    margin: 0;
    padding: 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

#app-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

.phone-frame {
    width: 375px;
    height: 667px;
    background: #000;
    border-radius: 25px;
    padding: 10px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.phone-header {
    background: #fff;
    border-radius: 15px 15px 0 0;
}

.status-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 20px;
    font-size: 14px;
    font-weight: 600;
    background: #f8f9fa;
    border-radius: 15px 15px 0 0;
}

.time {
    color: #000;
}

.indicators {
    display: flex;
    gap: 4px;
    font-size: 12px;
}

.app-bar {
    padding: 16px 20px;
    border-bottom: 1px solid #e9ecef;
    background: #fff;
}

.app-bar h1 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #212529;
    text-align: center;
}

.phone-content {
    height: calc(667px - 120px);
    background: #fff;
    overflow-y: auto;
    padding: 16px;
}

.widget-container {
    margin-bottom: 16px;
}

.widget-button {
    width: 100%;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.2s;
}

.widget-button:hover {
    opacity: 0.8;
}

.widget-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.2s;
    box-sizing: border-box;
}

.widget-input:focus {
    outline: none;
    border-color: #007AFF;
}

.widget-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #495057;
}

.widget-container-inner {
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.loading, .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
    font-size: 16px;
}

.unsupported-widget {
    padding: 12px;
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 4px;
    color: #856404;
    text-align: center;
}

/* Responsive design for smaller screens */
@media (max-width: 480px) {
    #app-container {
        padding: 10px;
    }

    .phone-frame {
        width: 100%;
        max-width: 375px;
        height: 100vh;
        max-height: 667px;
        border-radius: 0;
    }

    .phone-header .status-bar,
    .phone-header .app-bar {
        border-radius: 0;
    }
}
';
    }

    private function getMimeType(string $path): string
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        $mimeTypes = [
            'html' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}