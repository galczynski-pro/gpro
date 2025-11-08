<?php
/**
 * AI Employee Interface
 * Personal AI-powered code generation for HTML5, CSS, JavaScript
 */

require_once(__DIR__ . '/../../inc/includes.php');

// Admin-only access
if (!isset($_SESSION['admin_id'])) {
    header("Location: /admin");
    exit;
}

$slug = $_GET['slug'] ?? 'html5-dev';

// AI Employee profiles
$employees = [
    'html5-dev' => [
        'name' => 'HTML5 Developer',
        'expert' => 'Senior HTML5 & Semantic Markup Specialist',
        'color' => '#e34c26',
        'icon' => 'fa-html5',
        'prompt' => 'You are a Senior HTML5 Developer. Create clean, semantic, accessible HTML5 code following W3C standards. Use modern HTML5 elements, ARIA attributes, and SEO best practices. Always validate markup and ensure cross-browser compatibility.',
        'placeholder' => 'Describe the HTML structure you need...',
        'examples' => [
            'Create a responsive landing page with hero section',
            'Build a semantic blog post template',
            'Design an accessible form with validation'
        ]
    ],
    'css-dev' => [
        'name' => 'CSS Developer',
        'expert' => 'Senior CSS3 & Design Systems Specialist',
        'color' => '#264de4',
        'icon' => 'fa-css3',
        'prompt' => 'You are a Senior CSS Developer. Create modern, responsive CSS using CSS3, Flexbox, Grid, and custom properties. Follow BEM methodology, use mobile-first approach, ensure cross-browser compatibility, and optimize performance. Include animations and transitions when appropriate.',
        'placeholder' => 'Describe the styling you need...',
        'examples' => [
            'Create a responsive navigation with mobile menu',
            'Design a card grid layout with hover effects',
            'Build a custom dark/light theme system'
        ]
    ],
    'js-dev' => [
        'name' => 'JavaScript Developer',
        'expert' => 'Senior JavaScript & Frontend Engineer',
        'color' => '#f7df1e',
        'icon' => 'fa-js',
        'prompt' => 'You are a Senior JavaScript Developer. Write clean, modern JavaScript (ES6+) following best practices. Use vanilla JS when possible, implement proper error handling, ensure performance, and write modular, reusable code. Include comments for complex logic.',
        'placeholder' => 'Describe the functionality you need...',
        'examples' => [
            'Create a dynamic form validator',
            'Build an image gallery with lazy loading',
            'Implement a search filter for cards'
        ]
    ],
    'fullstack-dev' => [
        'name' => 'Fullstack Developer',
        'expert' => 'Senior Fullstack Developer (HTML/CSS/JS/PHP)',
        'color' => '#00d9ff',
        'icon' => 'fa-code',
        'prompt' => 'You are a Senior Fullstack Developer. Create complete, production-ready solutions combining HTML5, CSS3, JavaScript, and PHP. Follow MVC patterns, implement proper security (SQL injection prevention, XSS protection), use prepared statements, and ensure responsive design. Provide complete, working code.',
        'placeholder' => 'Describe the complete feature you need...',
        'examples' => [
            'Create a CRUD interface for blog posts',
            'Build a file upload system with validation',
            'Design a dashboard with charts and statistics'
        ]
    ]
];

$employee = $employees[$slug] ?? $employees['html5-dev'];

define('META_TITLE', $employee['name'] . ' - AI Assistant');
define('META_DESCRIPTION', 'AI-powered ' . $employee['expert']);
require_once(__DIR__ . '/../../inc/header.php');
?>

<style>
.ai-employee-header {
    background: linear-gradient(135deg, <?php echo $employee['color']; ?>15, <?php echo $employee['color']; ?>05);
    border-left: 4px solid <?php echo $employee['color']; ?>;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.employee-icon {
    font-size: 3rem;
    color: <?php echo $employee['color']; ?>;
    margin-bottom: 1rem;
}

.chat-container {
    max-width: 1200px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.chat-messages {
    height: 500px;
    overflow-y: auto;
    padding: 2rem;
    border-bottom: 1px solid #e0e0e0;
}

.message {
    margin-bottom: 1.5rem;
    padding: 1rem;
    border-radius: 8px;
}

.message.user {
    background: #f0f7ff;
    margin-left: 2rem;
}

.message.assistant {
    background: #f8f9fa;
    margin-right: 2rem;
}

.message pre {
    background: #282c34;
    color: #abb2bf;
    padding: 1rem;
    border-radius: 6px;
    overflow-x: auto;
    margin: 1rem 0;
}

.message code {
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
}

.chat-input-area {
    padding: 1.5rem;
    background: #fafafa;
    border-radius: 0 0 12px 12px;
}

.quick-actions {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.quick-action-btn {
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s;
}

.quick-action-btn:hover {
    background: <?php echo $employee['color']; ?>;
    color: white;
    border-color: <?php echo $employee['color']; ?>;
}

.code-toolbar {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.code-action-btn {
    padding: 0.5rem 1rem;
    background: <?php echo $employee['color']; ?>;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
}

.code-action-btn:hover {
    opacity: 0.9;
}
</style>

<section class="section-spacing">
    <div class="container-fluid">
        <!-- AI Employee Header -->
        <div class="ai-employee-header">
            <i class="fab <?php echo $employee['icon']; ?> employee-icon"></i>
            <h1><?php echo $employee['name']; ?></h1>
            <p class="lead"><?php echo $employee['expert']; ?></p>
        </div>

        <!-- Employee Switcher -->
        <div class="mb-4">
            <div class="btn-group" role="group">
                <?php foreach ($employees as $key => $emp): ?>
                    <a href="/dev/ai-employee/<?php echo $key; ?>"
                       class="btn <?php echo $key === $slug ? 'btn-primary' : 'btn-outline-primary'; ?>">
                        <i class="fab <?php echo $emp['icon']; ?>"></i> <?php echo $emp['name']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Chat Interface -->
        <div class="chat-container">
            <div class="chat-messages" id="chatMessages">
                <div class="message assistant">
                    <strong><?php echo $employee['name']; ?>:</strong>
                    <p>Hello! I'm your personal <?php echo $employee['expert']; ?>. How can I help you today?</p>
                    <div class="mt-3">
                        <strong>Example requests:</strong>
                        <ul>
                            <?php foreach ($employee['examples'] as $example): ?>
                                <li><?php echo $example; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="chat-input-area">
                <div class="quick-actions">
                    <button class="quick-action-btn" onclick="insertQuickPrompt('Explain the code')">
                        <i class="bi bi-question-circle"></i> Explain
                    </button>
                    <button class="quick-action-btn" onclick="insertQuickPrompt('Optimize this code')">
                        <i class="bi bi-lightning"></i> Optimize
                    </button>
                    <button class="quick-action-btn" onclick="insertQuickPrompt('Add comments')">
                        <i class="bi bi-chat-left-text"></i> Comment
                    </button>
                    <button class="quick-action-btn" onclick="insertQuickPrompt('Fix bugs')">
                        <i class="bi bi-bug"></i> Debug
                    </button>
                    <button class="quick-action-btn" onclick="insertQuickPrompt('Make it responsive')">
                        <i class="bi bi-phone"></i> Responsive
                    </button>
                </div>

                <form id="chatForm">
                    <div class="input-group">
                        <textarea
                            class="form-control"
                            id="userPrompt"
                            rows="3"
                            placeholder="<?php echo $employee['placeholder']; ?>"
                            required
                        ></textarea>
                    </div>
                    <div class="mt-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Generate Code
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="clearChat()">
                            <i class="bi bi-trash"></i> Clear Chat
                        </button>
                        <button type="button" class="btn btn-success" onclick="saveToFile()">
                            <i class="bi bi-download"></i> Save as File
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<script>
const employeePrompt = <?php echo json_encode($employee['prompt']); ?>;
const employeeName = <?php echo json_encode($employee['name']); ?>;
let chatHistory = [];

document.getElementById('chatForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const userPrompt = document.getElementById('userPrompt').value.trim();
    if (!userPrompt) return;

    // Add user message to chat
    addMessage('user', userPrompt);

    // Clear input
    document.getElementById('userPrompt').value = '';

    // Show loading
    const loadingId = addMessage('assistant', '<i class="spinner-border spinner-border-sm"></i> Generating code...');

    try {
        const response = await fetch('/php/api-code.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                prompt: userPrompt,
                system_prompt: employeePrompt,
                history: chatHistory
            })
        });

        if (!response.ok) throw new Error('API request failed');

        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let fullResponse = '';

        // Remove loading message
        document.getElementById(loadingId).remove();
        const responseId = addMessage('assistant', '');

        while (true) {
            const {value, done} = await reader.read();
            if (done) break;

            const chunk = decoder.decode(value);
            const lines = chunk.split('\n');

            for (const line of lines) {
                if (line.startsWith('data: ')) {
                    try {
                        const data = JSON.parse(line.substring(6));
                        if (data.content) {
                            fullResponse += data.content;
                            updateMessage(responseId, fullResponse);
                        }
                    } catch (e) {
                        // Skip invalid JSON
                    }
                }
            }
        }

        // Add to history
        chatHistory.push(
            { role: 'user', content: userPrompt },
            { role: 'assistant', content: fullResponse }
        );

        // Add code actions if code was generated
        if (fullResponse.includes('```')) {
            addCodeActions(responseId);
        }

    } catch (error) {
        console.error('Error:', error);
        updateMessage(loadingId, '❌ Error generating code. Please try again.');
    }
});

function addMessage(role, content) {
    const messagesDiv = document.getElementById('chatMessages');
    const messageId = 'msg-' + Date.now();

    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${role}`;
    messageDiv.id = messageId;
    messageDiv.innerHTML = role === 'assistant'
        ? `<strong>${employeeName}:</strong><div class="content">${formatMessage(content)}</div>`
        : `<strong>You:</strong><div class="content">${formatMessage(content)}</div>`;

    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;

    return messageId;
}

function updateMessage(messageId, content) {
    const messageDiv = document.getElementById(messageId);
    if (messageDiv) {
        const contentDiv = messageDiv.querySelector('.content');
        contentDiv.innerHTML = formatMessage(content);
    }
}

function formatMessage(content) {
    // Convert markdown-style code blocks to HTML
    content = content.replace(/```(\w+)?\n([\s\S]*?)```/g, (match, lang, code) => {
        return `<pre><code class="language-${lang || 'text'}">${escapeHtml(code.trim())}</code></pre>`;
    });

    // Convert inline code
    content = content.replace(/`([^`]+)`/g, '<code>$1</code>');

    // Convert line breaks
    content = content.replace(/\n/g, '<br>');

    return content;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function addCodeActions(messageId) {
    const messageDiv = document.getElementById(messageId);
    const contentDiv = messageDiv.querySelector('.content');

    const toolbar = document.createElement('div');
    toolbar.className = 'code-toolbar';
    toolbar.innerHTML = `
        <button class="code-action-btn" onclick="copyCode(this)">
            <i class="bi bi-clipboard"></i> Copy Code
        </button>
        <button class="code-action-btn" onclick="deployViaFTP(this)">
            <i class="bi bi-cloud-upload"></i> Deploy via FTP
        </button>
        <button class="code-action-btn" onclick="saveLocally(this)">
            <i class="bi bi-save"></i> Save Locally
        </button>
    `;

    contentDiv.appendChild(toolbar);
}

function copyCode(btn) {
    const codeBlock = btn.closest('.content').querySelector('pre code');
    if (codeBlock) {
        navigator.clipboard.writeText(codeBlock.textContent);
        btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-clipboard"></i> Copy Code';
        }, 2000);
    }
}

function deployViaFTP(btn) {
    const codeBlock = btn.closest('.content').querySelector('pre code');
    if (codeBlock) {
        const code = codeBlock.textContent;
        window.open('/dev/ftp-manager?deploy=1&code=' + encodeURIComponent(code), '_blank');
    }
}

function saveLocally(btn) {
    const codeBlock = btn.closest('.content').querySelector('pre code');
    if (codeBlock) {
        const code = codeBlock.textContent;
        const blob = new Blob([code], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'generated-code.txt';
        a.click();
    }
}

function insertQuickPrompt(text) {
    const textarea = document.getElementById('userPrompt');
    textarea.value = text;
    textarea.focus();
}

function clearChat() {
    if (confirm('Clear chat history?')) {
        document.getElementById('chatMessages').innerHTML = '';
        chatHistory = [];
        location.reload();
    }
}

function saveToFile() {
    const messages = Array.from(document.querySelectorAll('.message'));
    let content = '';

    messages.forEach(msg => {
        const role = msg.classList.contains('user') ? 'USER' : employeeName.toUpperCase();
        const text = msg.textContent.trim();
        content += `\n\n=== ${role} ===\n${text}`;
    });

    const blob = new Blob([content], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `${employeeName.replace(/\s+/g, '-')}-chat-${Date.now()}.txt`;
    a.click();
}
</script>

<?php require_once(__DIR__ . '/../../inc/footer.php'); ?>
