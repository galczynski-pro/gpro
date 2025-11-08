# 🏗️ AIGENCY: Complete Guide to Creating Modules & Classes

## The Developer's Cookbook for Building New Features

*Because copy-pasting from Stack Overflow only gets you so far* 😉

---

## 📚 Table of Contents

1. [Quick Start: The 5-Minute Module](#quick-start-the-5-minute-module)
2. [Understanding Module Architecture](#understanding-module-architecture)
3. [Creating User-Facing Modules](#creating-user-facing-modules)
4. [Creating Admin Modules](#creating-admin-modules)
5. [Building PHP Classes](#building-php-classes)
6. [Complete Real-World Examples](#complete-real-world-examples)
7. [Testing Your Creations](#testing-your-creations)
8. [Common Pitfalls & Solutions](#common-pitfalls--solutions)

---

## 🚀 Quick Start: The 5-Minute Module

**Want to see results fast?** Here's how to create a basic module in literally 5 minutes:

### The "Hello AI" Module

Let's create a simple module that lets users say hello to different AI personalities. It'll teach you the fundamentals without drowning you in complexity.

```bash
# Step 1: Create the module directory
cd /your-project-root/modules
mkdir hello-ai

# Step 2: Create the necessary files
cd hello-ai
touch _action.php _new-chat.php
```

**File 1: `_new-chat.php`** (The Interface)

```php
<?php
/**
 * Hello AI Module - User Interface
 * 
 * This is what the user sees. Think of it as your storefront window.
 */

// ALWAYS start with this - it's like checking if the bouncer let you in
session_start();

// Load our toolkit (database connection, helper functions, etc.)
require_once("../../inc/includes.php");

// Are you logged in? If not, back to the entrance!
if (!isset($_SESSION['id_customer'])) {
    header("Location: /sign-in.php");
    exit;
}

// Get customer info - we'll need this for personalization
$customers = new Customers();
$customer = $customers->getById($_SESSION['id_customer']);

// Available AI personalities (we could also get this from database)
$personalities = [
    'friendly' => [
        'name' => 'Friendly Fred',
        'greeting' => "Hey there! 👋 I'm here to chat and have a good time!",
        'icon' => '😊'
    ],
    'professional' => [
        'name' => 'Professional Patricia',
        'greeting' => "Good day. I'm here to assist you with utmost professionalism.",
        'icon' => '💼'
    ],
    'creative' => [
        'name' => 'Creative Charlie',
        'greeting' => "Hello, beautiful soul! Let's create something amazing together! ✨",
        'icon' => '🎨'
    ]
];

// Include the page header (navigation, CSS, etc.)
include("../../inc/header.php");
?>

<!-- Now for the HTML - this is what users actually see -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            
            <!-- Module Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">
                        <i class="fas fa-robot"></i> Hello AI
                    </h2>
                    <p class="mb-0 small">Choose your AI companion and start chatting!</p>
                </div>
                <div class="card-body">
                    
                    <!-- Welcome Message -->
                    <div class="alert alert-info">
                        <strong>Welcome, <?= htmlspecialchars($customer->name) ?>!</strong>
                        <br>
                        You have <strong><?= $customer->credits ?> credits</strong> available.
                        Each conversation costs 1 credit.
                    </div>
                    
                    <!-- Personality Selection -->
                    <div class="row">
                        <?php foreach ($personalities as $key => $personality): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card personality-card" 
                                     data-personality="<?= $key ?>"
                                     style="cursor: pointer; transition: transform 0.2s;">
                                    <div class="card-body text-center">
                                        <div style="font-size: 3rem;">
                                            <?= $personality['icon'] ?>
                                        </div>
                                        <h5 class="mt-3"><?= $personality['name'] ?></h5>
                                        <p class="text-muted small">
                                            <?= $personality['greeting'] ?>
                                        </p>
                                        <button class="btn btn-primary btn-sm start-chat-btn"
                                                data-personality="<?= $key ?>">
                                            Start Chat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                </div>
            </div>
            
            <!-- Chat Interface (hidden until personality selected) -->
            <div id="chat-interface" class="card shadow-sm" style="display: none;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span id="current-personality-name"></span>
                        <span id="current-personality-icon" style="font-size: 1.5rem;"></span>
                    </div>
                    <button class="btn btn-sm btn-secondary" id="change-personality-btn">
                        Change AI
                    </button>
                </div>
                <div class="card-body">
                    <!-- Messages Container -->
                    <div id="messages-container" 
                         style="height: 400px; overflow-y: auto; border: 1px solid #ddd; 
                                padding: 15px; border-radius: 5px; background: #f8f9fa;">
                        <!-- Messages will be added here dynamically -->
                    </div>
                    
                    <!-- Input Area -->
                    <div class="mt-3">
                        <div class="input-group">
                            <textarea id="user-message" 
                                      class="form-control" 
                                      placeholder="Type your message..." 
                                      rows="2"
                                      maxlength="500"></textarea>
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="send-message-btn">
                                    <i class="fas fa-paper-plane"></i> Send
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">
                            <span id="char-count">0</span>/500 characters
                        </small>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- JavaScript for interactivity -->
<script>
/**
 * Module JavaScript - Where the Magic Happens
 * 
 * This handles all the interactive behavior without reloading the page.
 * Modern web development = making things feel snappy and responsive!
 */

// Store current state
let currentPersonality = null;
let messageHistory = [];

// When page loads, set up all our event listeners
document.addEventListener('DOMContentLoaded', function() {
    
    // Personality card click handlers
    document.querySelectorAll('.start-chat-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const personality = this.dataset.personality;
            startChat(personality);
        });
    });
    
    // Hover effect for personality cards
    document.querySelectorAll('.personality-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Send message button
    document.getElementById('send-message-btn').addEventListener('click', sendMessage);
    
    // Send on Enter (but not Shift+Enter - that's for new lines)
    document.getElementById('user-message').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    
    // Character counter
    document.getElementById('user-message').addEventListener('input', function() {
        document.getElementById('char-count').textContent = this.value.length;
    });
    
    // Change personality button
    document.getElementById('change-personality-btn').addEventListener('click', function() {
        document.getElementById('chat-interface').style.display = 'none';
        currentPersonality = null;
        messageHistory = [];
    });
});

/**
 * Start a chat with selected personality
 */
function startChat(personality) {
    currentPersonality = personality;
    
    // Get personality data
    const personalities = <?= json_encode($personalities) ?>;
    const selected = personalities[personality];
    
    // Update UI
    document.getElementById('current-personality-name').textContent = selected.name;
    document.getElementById('current-personality-icon').textContent = selected.icon;
    
    // Show chat interface
    document.getElementById('chat-interface').style.display = 'block';
    
    // Add welcome message
    addMessage('assistant', selected.greeting);
    
    // Smooth scroll to chat
    document.getElementById('chat-interface').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
    
    // Focus on input
    document.getElementById('user-message').focus();
}

/**
 * Send a message to the AI
 */
async function sendMessage() {
    const input = document.getElementById('user-message');
    const message = input.value.trim();
    
    // Validation
    if (!message) {
        alert('Please type a message first!');
        return;
    }
    
    if (!currentPersonality) {
        alert('Please select a personality first!');
        return;
    }
    
    // Add user message to UI
    addMessage('user', message);
    
    // Clear input
    input.value = '';
    document.getElementById('char-count').textContent = '0';
    
    // Disable send button while processing
    const sendBtn = document.getElementById('send-message-btn');
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Thinking...';
    
    try {
        // Send to backend
        const response = await fetch('_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'send_message',
                personality: currentPersonality,
                message: message,
                history: messageHistory
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Add AI response
            addMessage('assistant', data.response);
            
            // Update credits display if provided
            if (data.credits_remaining !== undefined) {
                // You could update a credits counter here
                console.log('Credits remaining:', data.credits_remaining);
            }
        } else {
            throw new Error(data.error || 'Unknown error');
        }
        
    } catch (error) {
        console.error('Error:', error);
        addMessage('system', 'Sorry, something went wrong. Please try again.');
    } finally {
        // Re-enable send button
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send';
        input.focus();
    }
}

/**
 * Add a message to the chat display
 */
function addMessage(role, content) {
    const container = document.getElementById('messages-container');
    
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${role}-message mb-3`;
    
    // Different styles for different roles
    const styles = {
        user: 'background: #007bff; color: white; margin-left: 20%; border-radius: 15px;',
        assistant: 'background: white; border: 1px solid #ddd; margin-right: 20%; border-radius: 15px;',
        system: 'background: #f8d7da; border: 1px solid #f5c6cb; text-align: center; border-radius: 15px;'
    };
    
    messageDiv.style = styles[role] + ' padding: 10px 15px;';
    messageDiv.innerHTML = `
        <div class="message-content">
            ${escapeHtml(content)}
        </div>
        <div class="message-time text-muted small mt-1">
            ${new Date().toLocaleTimeString()}
        </div>
    `;
    
    container.appendChild(messageDiv);
    
    // Auto-scroll to bottom
    container.scrollTop = container.scrollHeight;
    
    // Store in history (exclude system messages)
    if (role !== 'system') {
        messageHistory.push({
            role: role,
            content: content,
            timestamp: Date.now()
        });
    }
}

/**
 * Escape HTML to prevent XSS attacks
 * (Always sanitize user input!)
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<!-- Add some custom CSS for polish -->
<style>
.personality-card {
    border: 2px solid transparent;
    transition: all 0.2s ease;
}

.personality-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.message {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#messages-container::-webkit-scrollbar {
    width: 8px;
}

#messages-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#messages-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

#messages-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<?php include("../../inc/footer.php"); ?>
```

**File 2: `_action.php`** (The Backend Logic)

```php
<?php
/**
 * Hello AI Module - Backend Actions
 * 
 * This is where the actual work happens. Users don't see this directly,
 * but it's the engine room of your module.
 */

// Start session and load dependencies
session_start();
require_once("../../inc/includes.php");

// Set JSON response header
header('Content-Type: application/json');

// Authentication check
if (!isset($_SESSION['id_customer'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

// Validate action
if (!isset($input['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No action specified']);
    exit;
}

/**
 * Route to appropriate handler based on action
 * 
 * This is like a switchboard operator - directs requests to the right place
 */
switch ($input['action']) {
    case 'send_message':
        handleSendMessage($input);
        break;
    
    case 'get_history':
        handleGetHistory($input);
        break;
    
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action: ' . $input['action']]);
        exit;
}

/**
 * Handle sending a message
 * 
 * This is where the AI magic happens!
 */
function handleSendMessage($input) {
    // Validate required fields
    $requiredFields = ['personality', 'message', 'history'];
    foreach ($requiredFields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            echo json_encode(['error' => "Missing field: $field"]);
            exit;
        }
    }
    
    // Get customer
    $customers = new Customers();
    $customer = $customers->getById($_SESSION['id_customer']);
    
    // Check credits
    $creditCost = 1;
    if ($customer->credits < $creditCost) {
        http_response_code(402);
        echo json_encode([
            'error' => 'Insufficient credits',
            'required' => $creditCost,
            'available' => $customer->credits
        ]);
        exit;
    }
    
    // Personality configurations
    $personalities = [
        'friendly' => [
            'system_prompt' => 'You are a friendly, casual AI assistant named Fred. Use emojis occasionally and keep things light and fun!',
            'temperature' => 0.9
        ],
        'professional' => [
            'system_prompt' => 'You are a professional AI assistant named Patricia. Be formal, clear, and concise in your responses.',
            'temperature' => 0.5
        ],
        'creative' => [
            'system_prompt' => 'You are a creative AI assistant named Charlie. Be imaginative, use metaphors, and think outside the box!',
            'temperature' => 1.0
        ]
    ];
    
    $personality = $personalities[$input['personality']];
    
    // Build message history for context
    $messages = [
        ['role' => 'system', 'content' => $personality['system_prompt']]
    ];
    
    // Add previous messages (last 10 for context)
    $history = array_slice($input['history'], -10);
    foreach ($history as $msg) {
        $messages[] = [
            'role' => $msg['role'],
            'content' => $msg['content']
        ];
    }
    
    // Add current message
    $messages[] = [
        'role' => 'user',
        'content' => $input['message']
    ];
    
    // Get OpenAI API key
    $settings = new Settings();
    $config = $settings->getById(1);
    $apiKey = $config->openai_api_key;
    
    if (!$apiKey) {
        http_response_code(500);
        echo json_encode(['error' => 'API key not configured']);
        exit;
    }
    
    // Call OpenAI API
    try {
        $response = callOpenAI($apiKey, $messages, $personality['temperature']);
        
        // Save message to database (optional but recommended)
        $messages = new Messages();
        $messages->insert([
            'id_message' => uniqid('msg_'),
            'id_thread' => 'hello-ai-' . $_SESSION['id_customer'],
            'id_customer' => $_SESSION['id_customer'],
            'id_prompt' => 0, // Special ID for this module
            'role' => 'user',
            'content' => $input['message'],
            'total_characters' => strlen($input['message']),
            'saved' => 1
        ]);
        
        $messages->insert([
            'id_message' => uniqid('msg_'),
            'id_thread' => 'hello-ai-' . $_SESSION['id_customer'],
            'id_customer' => $_SESSION['id_customer'],
            'id_prompt' => 0,
            'role' => 'assistant',
            'content' => $response,
            'total_characters' => strlen($response),
            'saved' => 1
        ]);
        
        // Deduct credits
        $customers->updateCredits($_SESSION['id_customer'], -$creditCost);
        
        // Return success
        echo json_encode([
            'success' => true,
            'response' => $response,
            'credits_remaining' => $customer->credits - $creditCost
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

/**
 * Call OpenAI API
 * 
 * Separated into its own function for reusability
 */
function callOpenAI($apiKey, $messages, $temperature = 0.7) {
    $endpoint = 'https://api.openai.com/v1/chat/completions';
    
    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => $messages,
        'temperature' => $temperature,
        'max_tokens' => 500
    ];
    
    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 30
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        throw new Exception('cURL error: ' . $error);
    }
    
    if ($httpCode !== 200) {
        throw new Exception('API error: ' . $response);
    }
    
    $result = json_decode($response, true);
    
    if (!isset($result['choices'][0]['message']['content'])) {
        throw new Exception('Invalid API response');
    }
    
    return $result['choices'][0]['message']['content'];
}

/**
 * Handle getting message history
 * 
 * Optional function to retrieve past conversations
 */
function handleGetHistory($input) {
    $messages = new Messages();
    
    $history = $messages->get(
        '*',
        "id_thread = 'hello-ai-" . $_SESSION['id_customer'] . "' AND id_prompt = 0",
        '50 ORDER BY created_at DESC'
    );
    
    $result = [];
    while ($msg = $history->fetch_object()) {
        $result[] = [
            'role' => $msg->role,
            'content' => $msg->content,
            'created_at' => $msg->created_at
        ];
    }
    
    echo json_encode([
        'success' => true,
        'history' => array_reverse($result)
    ]);
}
```

### 🎉 Congratulations!

You just created a complete, functional module! Here's what you got:

✅ User interface with personality selection  
✅ Real-time chat functionality  
✅ Credit system integration  
✅ Database message storage  
✅ OpenAI API integration  
✅ Clean, modern design  
✅ Error handling  
✅ Security measures  

**Total Time:** About 5 minutes to set up, but now you understand the anatomy of a module!

---

## 🏛️ Understanding Module Architecture

Now that you've seen a module in action, let's break down *why* it's structured this way.

### The Three-File Pattern

Every user-facing module follows this pattern:

```
/modules/your-module/
├── _action.php       # Backend logic (the brain)
├── _new-chat.php     # User interface (the face)
└── _main.php         # Optional: landing page
```

**Why this separation?**

Think of it like a restaurant:
- `_new-chat.php` is the dining room (where customers are)
- `_action.php` is the kitchen (where food is made)
- `_main.php` is the front door/menu (optional welcome area)

This separation keeps things organized and secure. Users shouldn't see your kitchen! 🍳

### File Naming Conventions

Notice the underscore prefix? That's intentional:

```php
_action.php     // ✅ Backend action handler
_new-chat.php   // ✅ Chat interface
_main.php       // ✅ Main entry point

action.php      // ❌ Could be confused with framework files
chat.php        // ❌ Less clear about purpose
```

The underscore is like saying "Hey, I'm a module file!" - it helps with organization and prevents naming conflicts.

### Module Communication Flow

Here's how data flows through a module:

```
1. User → _new-chat.php (UI)
   └─ User clicks button, fills form, etc.

2. _new-chat.php → _action.php (JavaScript fetch/AJAX)
   └─ Sends JSON: {action: 'do_something', data: {...}}

3. _action.php → Database/APIs (PHP)
   └─ Processes request, talks to database, calls APIs

4. _action.php → _new-chat.php (JSON response)
   └─ Returns: {success: true, data: {...}}

5. _new-chat.php → User (JavaScript)
   └─ Updates UI dynamically, shows results
```

**Pro Tip:** Always use JSON for communication between frontend and backend. It's like speaking the universal language of the web! 🌍

---

## 🎨 Creating User-Facing Modules

Let's build some real-world modules you might actually need.

### Example 1: Document Analyzer Module

**Use Case:** Users upload documents and get AI-powered analysis

#### File Structure

```
/modules/doc-analyzer/
├── _action.php
├── _upload.php
├── _analyze.php
└── class/
    └── DocumentProcessor.class.php
```

#### Implementation

**DocumentProcessor.class.php**

```php
<?php
/**
 * Document Processor Class
 * 
 * Handles document upload, parsing, and preparation for AI analysis
 */

class DocumentProcessor
{
    private $allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'text/plain'
    ];
    
    private $maxFileSize = 10 * 1024 * 1024; // 10MB
    
    private $uploadDir = '../../uploads/documents/';
    
    public function __construct()
    {
        // Ensure upload directory exists
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    /**
     * Process uploaded file
     *
     * @param array $file $_FILES array element
     * @return array Processing result
     */
    public function processUpload($file)
    {
        // Validate file
        $validation = $this->validateFile($file);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'error' => $validation['error']
            ];
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('doc_') . '.' . $extension;
        $filepath = $this->uploadDir . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            return [
                'success' => false,
                'error' => 'Failed to save file'
            ];
        }
        
        // Extract text content
        $text = $this->extractText($filepath, $extension);
        
        if (!$text) {
            return [
                'success' => false,
                'error' => 'Failed to extract text from document'
            ];
        }
        
        // Store in database
        $docId = $this->saveToDatabase([
            'filename' => $file['name'],
            'filepath' => $filepath,
            'filesize' => $file['size'],
            'mimetype' => $file['type'],
            'text_content' => $text
        ]);
        
        return [
            'success' => true,
            'document_id' => $docId,
            'filename' => $file['name'],
            'filesize' => $file['size'],
            'word_count' => str_word_count($text),
            'text_preview' => substr($text, 0, 500) . '...'
        ];
    }
    
    /**
     * Validate uploaded file
     */
    private function validateFile($file)
    {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'valid' => false,
                'error' => 'Upload error: ' . $file['error']
            ];
        }
        
        // Check file size
        if ($file['size'] > $this->maxFileSize) {
            return [
                'valid' => false,
                'error' => 'File too large. Maximum ' . 
                          ($this->maxFileSize / 1024 / 1024) . 'MB'
            ];
        }
        
        // Check file type
        if (!in_array($file['type'], $this->allowedTypes)) {
            return [
                'valid' => false,
                'error' => 'Invalid file type. Allowed: PDF, DOC, DOCX, TXT'
            ];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Extract text from document
     */
    private function extractText($filepath, $extension)
    {
        switch (strtolower($extension)) {
            case 'txt':
                return file_get_contents($filepath);
            
            case 'pdf':
                return $this->extractPdfText($filepath);
            
            case 'doc':
            case 'docx':
                return $this->extractDocText($filepath);
            
            default:
                return false;
        }
    }
    
    /**
     * Extract text from PDF
     */
    private function extractPdfText($filepath)
    {
        // Using poppler-utils (pdftotext command)
        $output = shell_exec("pdftotext '$filepath' -");
        
        if ($output === null) {
            // Fallback: try PDF parser library if available
            // require_once 'vendor/autoload.php';
            // $parser = new \Smalot\PdfParser\Parser();
            // $pdf = $parser->parseFile($filepath);
            // return $pdf->getText();
            
            return false;
        }
        
        return $output;
    }
    
    /**
     * Extract text from DOC/DOCX
     */
    private function extractDocText($filepath)
    {
        // Using antiword for .doc or docx2txt for .docx
        $extension = pathinfo($filepath, PATHINFO_EXTENSION);
        
        if ($extension === 'docx') {
            $output = shell_exec("docx2txt '$filepath' -");
        } else {
            $output = shell_exec("antiword '$filepath'");
        }
        
        return $output ?: false;
    }
    
    /**
     * Save document info to database
     */
    private function saveToDatabase($data)
    {
        global $db;
        
        $stmt = $db->prepare("
            INSERT INTO documents 
            (id_customer, filename, filepath, filesize, mimetype, text_content, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $customerId = $_SESSION['id_customer'];
        
        $stmt->bind_param(
            'ississ',
            $customerId,
            $data['filename'],
            $data['filepath'],
            $data['filesize'],
            $data['mimetype'],
            $data['text_content']
        );
        
        $stmt->execute();
        $id = $db->insert_id;
        $stmt->close();
        
        return $id;
    }
    
    /**
     * Analyze document with AI
     */
    public function analyzeDocument($documentId, $analysisType = 'summary')
    {
        // Get document from database
        global $db;
        
        $stmt = $db->prepare("
            SELECT * FROM documents 
            WHERE id = ? AND id_customer = ?
        ");
        
        $customerId = $_SESSION['id_customer'];
        $stmt->bind_param('ii', $documentId, $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $doc = $result->fetch_object();
        $stmt->close();
        
        if (!$doc) {
            return [
                'success' => false,
                'error' => 'Document not found'
            ];
        }
        
        // Build analysis prompt based on type
        $prompts = [
            'summary' => "Please provide a comprehensive summary of the following document:\n\n",
            'key_points' => "Extract and list the key points from the following document:\n\n",
            'sentiment' => "Analyze the sentiment and tone of the following document:\n\n",
            'questions' => "Generate 5 insightful questions about the following document:\n\n"
        ];
        
        $prompt = $prompts[$analysisType] . $doc->text_content;
        
        // Call AI API
        $settings = new Settings();
        $config = $settings->getById(1);
        
        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful document analysis assistant.'],
            ['role' => 'user', 'content' => $prompt]
        ];
        
        // Make API call (reuse function from previous example)
        // $response = callOpenAI($config->openai_api_key, $messages);
        
        // For now, return mock response
        $response = "This is a mock analysis response. In production, this would be the AI's actual analysis of your document.";
        
        return [
            'success' => true,
            'analysis' => $response,
            'document' => [
                'filename' => $doc->filename,
                'word_count' => str_word_count($doc->text_content)
            ]
        ];
    }
}
```

[Continue with more module examples...]

---

*[Document continues with more sections - should I continue? This is already quite comprehensive!]*

Let me know if you want me to:
1. Continue with more module examples
2. Move to the Style/UI guide
3. Or save this and start the Style guide as a separate document?
