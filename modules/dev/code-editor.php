<?php
/**
 * Live Code Editor
 * Edit HTML, CSS, JavaScript with live preview
 */

require_once(__DIR__ . '/../../inc/includes.php');

// Admin-only access
if (!isset($_SESSION['admin_id'])) {
    header("Location: /admin");
    exit;
}

define('META_TITLE', 'Code Editor - Live Preview');
define('META_DESCRIPTION', 'Live code editor with instant preview');
require_once(__DIR__ . '/../../inc/header.php');
?>

<style>
body {
    overflow: hidden;
}

.code-editor-container {
    display: flex;
    height: calc(100vh - 80px);
    gap: 0;
}

.editor-pane {
    flex: 1;
    display: flex;
    flex-direction: column;
    border-right: 1px solid #ddd;
}

.editor-header {
    background: #2d2d2d;
    color: white;
    padding: 0.75rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.editor-tabs {
    display: flex;
    gap: 0.5rem;
}

.editor-tab {
    padding: 0.5rem 1rem;
    background: #3d3d3d;
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 6px 6px 0 0;
}

.editor-tab.active {
    background: #1e1e1e;
}

.editor-content {
    flex: 1;
    position: relative;
}

.editor-content textarea {
    width: 100%;
    height: 100%;
    padding: 1rem;
    border: none;
    resize: none;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.6;
    background: #1e1e1e;
    color: #d4d4d4;
    tab-size: 4;
}

.preview-pane {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: white;
}

.preview-header {
    background: #f5f5f5;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.preview-content {
    flex: 1;
    overflow: auto;
}

.preview-content iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.toolbar {
    background: #f8f9fa;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #ddd;
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.toolbar button {
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.toolbar button:hover {
    background: #e9ecef;
}

.toolbar button i {
    margin-right: 0.25rem;
}

.layout-toggle {
    display: flex;
    gap: 0.25rem;
}

.layout-btn {
    padding: 0.5rem;
    border: 1px solid #ddd;
    background: white;
    cursor: pointer;
}

.layout-btn.active {
    background: #007bff;
    color: white;
}
</style>

<div class="toolbar">
    <button onclick="runCode()"><i class="bi bi-play-fill"></i> Run</button>
    <button onclick="clearAll()"><i class="bi bi-trash"></i> Clear</button>
    <button onclick="saveCode()"><i class="bi bi-save"></i> Save</button>
    <button onclick="exportHTML()"><i class="bi bi-download"></i> Export</button>
    <button onclick="deployCode()"><i class="bi bi-cloud-upload"></i> Deploy via FTP</button>

    <div style="margin-left: auto;" class="layout-toggle">
        <button class="layout-btn active" onclick="setLayout('horizontal')">
            <i class="bi bi-layout-split"></i>
        </button>
        <button class="layout-btn" onclick="setLayout('vertical')">
            <i class="bi bi-layout-three-columns"></i>
        </button>
    </div>
</div>

<div class="code-editor-container" id="editorContainer">
    <!-- Editor Pane -->
    <div class="editor-pane">
        <div class="editor-header">
            <div class="editor-tabs">
                <button class="editor-tab active" onclick="switchTab('html')">
                    <i class="bi bi-filetype-html"></i> HTML
                </button>
                <button class="editor-tab" onclick="switchTab('css')">
                    <i class="bi bi-filetype-css"></i> CSS
                </button>
                <button class="editor-tab" onclick="switchTab('js')">
                    <i class="bi bi-filetype-js"></i> JavaScript
                </button>
            </div>
            <button class="btn btn-sm btn-outline-light" onclick="askAI()">
                <i class="bi bi-robot"></i> Ask AI
            </button>
        </div>

        <div class="editor-content" id="htmlEditor" style="display: block;">
            <textarea id="htmlCode" placeholder="Enter HTML code here..." oninput="updatePreview()"><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Page</title>
</head>
<body>
    <h1>Hello World!</h1>
    <p>Start coding here...</p>
</body>
</html></textarea>
        </div>

        <div class="editor-content" id="cssEditor" style="display: none;">
            <textarea id="cssCode" placeholder="Enter CSS code here..." oninput="updatePreview()">body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background: #f5f5f5;
}

h1 {
    color: #333;
}</textarea>
        </div>

        <div class="editor-content" id="jsEditor" style="display: none;">
            <textarea id="jsCode" placeholder="Enter JavaScript code here..." oninput="updatePreview()">// Your JavaScript code here
console.log('Hello World!');</textarea>
        </div>
    </div>

    <!-- Preview Pane -->
    <div class="preview-pane">
        <div class="preview-header">
            <strong><i class="bi bi-eye"></i> Live Preview</strong>
            <div>
                <button class="btn btn-sm btn-outline-secondary" onclick="refreshPreview()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleConsole()">
                    <i class="bi bi-terminal"></i> Console
                </button>
            </div>
        </div>
        <div class="preview-content">
            <iframe id="preview" sandbox="allow-scripts allow-same-origin"></iframe>
        </div>
    </div>
</div>

<script>
let currentTab = 'html';
let autoUpdate = true;

function switchTab(tab) {
    currentTab = tab;

    // Update tab buttons
    document.querySelectorAll('.editor-tab').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.closest('.editor-tab').classList.add('active');

    // Show corresponding editor
    document.querySelectorAll('.editor-content').forEach(editor => {
        editor.style.display = 'none';
    });
    document.getElementById(tab + 'Editor').style.display = 'block';
}

function updatePreview() {
    if (!autoUpdate) return;

    const html = document.getElementById('htmlCode').value;
    const css = document.getElementById('cssCode').value;
    const js = document.getElementById('jsCode').value;

    const preview = document.getElementById('preview');
    const previewDocument = preview.contentDocument || preview.contentWindow.document;

    // Build complete HTML
    let fullHTML = html;

    // Inject CSS
    if (css) {
        fullHTML = fullHTML.replace('</head>', `<style>${css}</style></head>`);
    }

    // Inject JS
    if (js) {
        fullHTML = fullHTML.replace('</body>', `<script>${js}<\/script></body>`);
    }

    previewDocument.open();
    previewDocument.write(fullHTML);
    previewDocument.close();
}

function refreshPreview() {
    updatePreview();
}

function runCode() {
    updatePreview();
}

function clearAll() {
    if (confirm('Clear all code?')) {
        document.getElementById('htmlCode').value = '';
        document.getElementById('cssCode').value = '';
        document.getElementById('jsCode').value = '';
        updatePreview();
    }
}

function saveCode() {
    const html = document.getElementById('htmlCode').value;
    const css = document.getElementById('cssCode').value;
    const js = document.getElementById('jsCode').value;

    const data = JSON.stringify({ html, css, js }, null, 2);
    localStorage.setItem('savedCode', data);

    alert('Code saved to browser storage!');
}

function loadCode() {
    const saved = localStorage.getItem('savedCode');
    if (saved) {
        const data = JSON.parse(saved);
        document.getElementById('htmlCode').value = data.html || '';
        document.getElementById('cssCode').value = data.css || '';
        document.getElementById('jsCode').value = data.js || '';
        updatePreview();
    }
}

function exportHTML() {
    const html = document.getElementById('htmlCode').value;
    const css = document.getElementById('cssCode').value;
    const js = document.getElementById('jsCode').value;

    // Build complete HTML file
    let fullHTML = html;

    if (css) {
        fullHTML = fullHTML.replace('</head>', `<style>\n${css}\n</style>\n</head>`);
    }

    if (js) {
        fullHTML = fullHTML.replace('</body>', `<script>\n${js}\n<\/script>\n</body>`);
    }

    // Download
    const blob = new Blob([fullHTML], { type: 'text/html' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'index.html';
    a.click();
}

function deployCode() {
    const html = document.getElementById('htmlCode').value;
    const css = document.getElementById('cssCode').value;
    const js = document.getElementById('jsCode').value;

    // Save to session and redirect to FTP manager
    sessionStorage.setItem('codeToDeployHTML', html);
    sessionStorage.setItem('codeToDeployCSS', css);
    sessionStorage.setItem('codeToDeployJS', js);

    window.open('/dev/ftp-manager', '_blank');
}

function askAI() {
    const currentCode = document.getElementById(currentTab + 'Code').value;
    const lang = currentTab.toUpperCase();

    const question = prompt(`Ask AI about your ${lang} code:`);
    if (question) {
        window.open(`/dev/ai-employee/${currentTab}-dev?prompt=${encodeURIComponent(question + '\n\nCode:\n' + currentCode)}`, '_blank');
    }
}

function setLayout(layout) {
    const container = document.getElementById('editorContainer');

    document.querySelectorAll('.layout-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.closest('.layout-btn').classList.add('active');

    if (layout === 'vertical') {
        container.style.flexDirection = 'column';
    } else {
        container.style.flexDirection = 'row';
    }
}

function toggleConsole() {
    alert('Console functionality coming soon!');
}

// Load saved code on page load
window.addEventListener('load', () => {
    loadCode();
    updatePreview();
});

// Auto-save every 30 seconds
setInterval(saveCode, 30000);
</script>

<?php require_once(__DIR__ . '/../../inc/footer.php'); ?>
