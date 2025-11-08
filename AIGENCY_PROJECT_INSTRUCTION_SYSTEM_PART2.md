# 🧠 AIGENCY PROJECT INSTRUCTION SYSTEM - PART 2

## Continuation of Comprehensive Development & Modification Guide

---

## 🔄 THE FOUR-STAGE DEVELOPMENT METHODOLOGY

### Overview

Every modification to the Aigency platform follows a structured four-stage approach that ensures safety, maintainability, and backwards compatibility. This methodology is **MANDATORY** for all development work.

---

### STAGE 1: STRUCTURAL ANALYSIS

**Objective:** Understand the complete context before making any changes

#### Step 1.1: File Dependency Mapping

Before modifying any file, Claude must identify all dependencies:

```
FILE: /admin/class/Prompts.class.php
↓
DEPENDS ON:
├── Action.class.php (parent class)
├── Db.class.php (database connection)
└── [USED BY]:
    ├── /admin/modules/prompts/*.php
    ├── /modules/chat/_chat.php
    ├── /modules/dalle/_new-chat.php
    └── /php/api.php
```

**Questions to Answer:**
- What classes does this file extend or implement?
- What other classes does it instantiate?
- Which files include or require this file?
- What database tables does it interact with?
- Are there any session dependencies?
- Does it handle file uploads or API calls?

#### Step 1.2: Database Impact Assessment

For any change involving data:

```sql
-- Example: Adding a field to prompts table
-- MUST CHECK:

1. Foreign Key Constraints
   SELECT CONSTRAINT_NAME, REFERENCED_TABLE_NAME
   FROM information_schema.KEY_COLUMN_USAGE
   WHERE TABLE_NAME = 'prompts' AND REFERENCED_TABLE_NAME IS NOT NULL;

2. Indexes
   SHOW INDEX FROM prompts;

3. Dependent Queries
   -- Search all PHP files for queries using this table
   -- grep -r "FROM prompts" /path/to/project
   -- grep -r "INSERT INTO prompts" /path/to/project
   -- grep -r "UPDATE prompts" /path/to/project
```

#### Step 1.3: Admin Permission Check

Determine if changes affect admin-only functionality:

```php
// Check /admin/inc/restrict.php and /inc/restrict.php
// Identify permission levels required:

- Public access (no authentication)
- Customer access (logged-in users)
- Admin access (admin panel)
- Super admin access (highest level)
```

#### Step 1.4: Frontend-Backend Coupling

Map JavaScript-PHP interactions:

```javascript
// Example: Find all AJAX calls to a PHP endpoint
// /js/main.js
fetch('/php/api.php', {
    method: 'POST',
    body: JSON.stringify({...})
})

// Must check:
// - What data is sent?
// - What response is expected?
// - How does UI react to changes?
// - Are there event listeners?
```

---

### STAGE 2: TASK SPLITTING

**Objective:** Break complex requests into atomic, manageable subtasks

#### Decomposition Methodology

**Example Request:** "Add Google Gemini AI as a new model option"

**Claude's Decomposition:**

```
PRIMARY TASK: Add Google Gemini AI Integration

ATOMIC SUBTASKS:
┌────────────────────────────────────────────────────────────┐
│ 1. DATABASE MODIFICATIONS                                  │
│    ├── Add 'gemini-pro' to API_MODEL enum in prompts     │
│    ├── Add google_gemini_api_key to settings table        │
│    └── Test: Verify schema changes don't break existing   │
│                                                            │
│ 2. BACKEND API INTEGRATION                                 │
│    ├── Create /php/gemini-api.php handler                 │
│    ├── Extend /php/key.php with getGeminiKey()           │
│    ├── Modify /php/api.php to route to Gemini            │
│    └── Test: Mock API calls, error handling               │
│                                                            │
│ 3. CLASS MODIFICATIONS                                     │
│    ├── Update PromptManager::getAIConfiguration()         │
│    ├── Add model validation in Prompts.class.php         │
│    └── Test: Unit test new methods                        │
│                                                            │
│ 4. ADMIN PANEL UI                                          │
│    ├── Add Gemini option in /admin/modules/prompts/      │
│    ├── Add API key field in /admin/modules/settings/     │
│    └── Test: Form submission, validation                  │
│                                                            │
│ 5. CREDIT CALCULATION                                      │
│    ├── Define credit cost for Gemini                      │
│    ├── Update credit deduction logic                      │
│    └── Test: Verify correct charges                       │
│                                                            │
│ 6. DOCUMENTATION                                           │
│    ├── Update API integration docs                        │
│    ├── Add admin guide for Gemini setup                   │
│    └── Create release notes                               │
└────────────────────────────────────────────────────────────┘

DEPENDENCIES:
- Subtask 1 must complete before 2
- Subtask 2 must complete before 3
- Subtasks 4, 5 can run parallel after 3
- Subtask 6 is final

RISK ASSESSMENT:
⚠️  HIGH: Database schema changes (requires backup)
⚠️  MEDIUM: API integration (external dependency)
✅  LOW: Admin UI changes (isolated)
```

#### Task Metadata Template

For each subtask, Claude defines:

```yaml
subtask:
  id: "GEMINI_001_DB"
  title: "Add Gemini model to database schema"
  priority: "HIGH"
  estimated_time: "15 minutes"
  
  files_affected:
    - database_sql/aigency_structure.sql
    - admin/class/Prompts.class.php
  
  dependencies:
    before: []
    after: ["GEMINI_002_API", "GEMINI_003_CLASS"]
  
  rollback_plan:
    - "Keep backup of original schema"
    - "ALTER TABLE prompts DROP COLUMN ... if needed"
  
  validation:
    - "SELECT DISTINCT API_MODEL FROM prompts"
    - "Test existing prompts still work"
    
  security_concerns:
    - "Ensure enum validation prevents injection"
    - "No sensitive data exposed"
```

---

### STAGE 3: IMPLEMENTATION

**Objective:** Execute changes safely with proper documentation

#### Implementation Checklist

```
BEFORE MODIFICATION:
☐ Backup affected files (.bak extension)
☐ Document current state (take notes)
☐ Verify test environment available
☐ Check git status (if version control used)

DURING MODIFICATION:
☐ Follow PSR-12 coding standards
☐ Add PHPDoc comments to new methods
☐ Use prepared statements for SQL
☐ Validate all inputs
☐ Handle errors gracefully
☐ Log important operations

AFTER MODIFICATION:
☐ Test in isolation first
☐ Test with dependent systems
☐ Verify no regressions
☐ Update documentation
☐ Create rollback plan
```

#### Code Quality Standards

**PHP Standards (PSR-12)**

```php
<?php
/**
 * Example: Adding new method to Prompts class
 * Following PSR-12 standards
 */

namespace Aigency\Admin\Classes;

use Aigency\Database\Db;
use Exception;

class Prompts extends Action
{
    /**
     * Get prompts that support a specific AI model
     *
     * @param string $model The AI model identifier (e.g., 'gpt-4', 'gemini-pro')
     * @param int $limit Maximum number of results to return
     * @return array Array of prompt objects
     * @throws Exception If database query fails
     */
    public function getByModel(string $model, int $limit = 50): array
    {
        // Validate input
        if (empty($model)) {
            throw new InvalidArgumentException('Model parameter cannot be empty');
        }
        
        // Prepare query with parameterization
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
             WHERE API_MODEL = ? AND status = 1 
             ORDER BY views DESC 
             LIMIT ?"
        );
        
        if (!$stmt) {
            throw new Exception('Database prepare failed: ' . $this->db->error);
        }
        
        // Bind parameters
        $stmt->bind_param('si', $model, $limit);
        
        // Execute
        if (!$stmt->execute()) {
            throw new Exception('Query execution failed: ' . $stmt->error);
        }
        
        // Fetch results
        $result = $stmt->get_result();
        $prompts = [];
        
        while ($row = $result->fetch_object()) {
            // Decode JSON fields
            $row->fields = json_decode($row->fields ?? '[]');
            $row->suggestions = json_decode($row->suggestions ?? '[]');
            $prompts[] = $row;
        }
        
        $stmt->close();
        
        return $prompts;
    }
}
```

**JavaScript Standards (ES6+)**

```javascript
/**
 * Example: Adding new feature to ChatManager
 * Following modern JavaScript best practices
 */

class ModelSwitcher {
    /**
     * Initialize model switcher
     * @param {string} currentModel - Current AI model
     * @param {Array<string>} availableModels - List of available models
     */
    constructor(currentModel, availableModels) {
        this.currentModel = currentModel;
        this.availableModels = availableModels;
        this.listeners = [];
        
        this.init();
    }
    
    /**
     * Initialize UI components
     * @private
     */
    init() {
        this.createDropdown();
        this.attachEventListeners();
    }
    
    /**
     * Create model selection dropdown
     * @private
     */
    createDropdown() {
        const container = document.getElementById('model-selector');
        if (!container) {
            console.error('Model selector container not found');
            return;
        }
        
        const select = document.createElement('select');
        select.id = 'ai-model-select';
        select.className = 'form-control';
        
        this.availableModels.forEach(model => {
            const option = document.createElement('option');
            option.value = model;
            option.textContent = this.getModelDisplayName(model);
            option.selected = model === this.currentModel;
            select.appendChild(option);
        });
        
        container.appendChild(select);
        this.dropdown = select;
    }
    
    /**
     * Attach event listeners
     * @private
     */
    attachEventListeners() {
        this.dropdown.addEventListener('change', (e) => {
            this.switchModel(e.target.value);
        });
    }
    
    /**
     * Switch to a different AI model
     * @param {string} newModel - Model to switch to
     * @returns {Promise<boolean>} Success status
     */
    async switchModel(newModel) {
        if (!this.availableModels.includes(newModel)) {
            console.error(`Invalid model: ${newModel}`);
            return false;
        }
        
        try {
            const response = await fetch('/php/api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'switch_model',
                    model: newModel
                })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.currentModel = newModel;
                this.notifyListeners(newModel);
                toastr.success(`Switched to ${this.getModelDisplayName(newModel)}`);
                return true;
            } else {
                throw new Error(data.error || 'Unknown error');
            }
        } catch (error) {
            console.error('Model switch failed:', error);
            toastr.error('Failed to switch model');
            return false;
        }
    }
    
    /**
     * Get human-readable model name
     * @param {string} model - Model identifier
     * @returns {string} Display name
     * @private
     */
    getModelDisplayName(model) {
        const displayNames = {
            'gpt-4': 'GPT-4',
            'gpt-3.5-turbo': 'GPT-3.5 Turbo',
            'gemini-pro': 'Google Gemini Pro',
            'claude-3': 'Claude 3'
        };
        
        return displayNames[model] || model;
    }
    
    /**
     * Register change listener
     * @param {Function} callback - Callback function
     */
    onChange(callback) {
        this.listeners.push(callback);
    }
    
    /**
     * Notify all listeners of model change
     * @param {string} newModel - New model
     * @private
     */
    notifyListeners(newModel) {
        this.listeners.forEach(callback => callback(newModel));
    }
}
```

#### Autoloader Registration

When creating new classes:

```php
// /inc/Autoloader.php
spl_autoload_register(function ($className) {
    // Convert namespace to file path
    $className = str_replace('\\', '/', $className);
    
    $paths = [
        __DIR__ . '/../admin/class/' . $className . '.class.php',
        __DIR__ . '/../modules/*/class/' . $className . '.class.php',
        __DIR__ . '/' . $className . '.php'
    ];
    
    foreach ($paths as $path) {
        // Handle wildcards
        if (strpos($path, '*') !== false) {
            $pattern = str_replace('*', '*', $path);
            $files = glob($pattern);
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        } else {
            if (file_exists($path)) {
                require_once $path;
                return;
            }
        }
    }
});
```

---

### STAGE 4: TESTING & DOCUMENTATION

**Objective:** Ensure changes work correctly and are properly documented

#### Testing Strategy

**Level 1: Unit Testing**

```php
/**
 * Example: Unit test for new Prompts method
 * /tests/PromptsTest.php
 */

use PHPUnit\Framework\TestCase;

class PromptsTest extends TestCase
{
    private $prompts;
    
    protected function setUp(): void
    {
        $this->prompts = new Prompts();
    }
    
    public function testGetByModelReturnsCorrectResults()
    {
        $result = $this->prompts->getByModel('gpt-4', 10);
        
        $this->assertIsArray($result);
        $this->assertLessThanOrEqual(10, count($result));
        
        foreach ($result as $prompt) {
            $this->assertEquals('gpt-4', $prompt->API_MODEL);
            $this->assertEquals(1, $prompt->status);
        }
    }
    
    public function testGetByModelThrowsExceptionForEmptyModel()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->prompts->getByModel('', 10);
    }
    
    public function testGetByModelRespectsLimit()
    {
        $result = $this->prompts->getByModel('gpt-3.5-turbo', 5);
        $this->assertLessThanOrEqual(5, count($result));
    }
}
```

**Level 2: Integration Testing**

```php
/**
 * Integration test for full chat flow
 */

class ChatFlowTest extends TestCase
{
    public function testCompleteMessageFlow()
    {
        // 1. Initialize session
        session_start();
        $_SESSION['id_customer'] = 1;
        
        // 2. Get prompt
        $prompts = new Prompts();
        $prompt = $prompts->getBySlug('test-prompt');
        $this->assertNotNull($prompt);
        
        // 3. Send message
        $_POST['message'] = 'Test message';
        $_POST['isFetchRequest'] = 'false';
        
        ob_start();
        require __DIR__ . '/../modules/customer/chat-session.php';
        $output = ob_get_clean();
        
        $response = json_decode($output, true);
        $this->assertTrue($response['success']);
        $this->assertNotEmpty($response['message_id']);
        
        // 4. Verify message in database
        $messages = new Messages();
        $msg = $messages->get('*', "id_message = '{$response['message_id']}'");
        $this->assertEquals(1, $msg->num_rows);
        
        // 5. Verify credit deduction
        $customers = new Customers();
        $customer = $customers->getById(1);
        // Assert credits were deducted
    }
}
```

**Level 3: End-to-End Testing**

```javascript
/**
 * E2E test using Playwright or Puppeteer
 * /tests/e2e/chat.test.js
 */

const { test, expect } = require('@playwright/test');

test('complete chat interaction', async ({ page }) => {
    // 1. Login
    await page.goto('http://localhost/sign-in.php');
    await page.fill('#email', 'test@example.com');
    await page.fill('#password', 'password123');
    await page.click('#login-btn');
    
    // 2. Navigate to chat
    await page.goto('http://localhost/chat/test-prompt');
    await page.waitForSelector('#chat-messages');
    
    // 3. Send message
    await page.fill('#user-input', 'Hello, AI!');
    await page.click('#send-btn');
    
    // 4. Wait for response
    await page.waitForSelector('.message.assistant-message', {
        timeout: 30000
    });
    
    // 5. Verify response appeared
    const messages = await page.$$('.message');
    expect(messages.length).toBeGreaterThan(1);
    
    // 6. Test copy button
    const copyBtn = await page.$('.copy-btn');
    await copyBtn.click();
    
    // 7. Verify success notification
    await page.waitForSelector('.toastr-success');
});
```

#### Documentation Updates

**Change Log Entry:**

```markdown
## [Version 2.5.0] - 2025-11-07

### Added
- Google Gemini Pro AI model integration
- Model switcher in chat interface
- New `getByModel()` method in Prompts class
- Gemini API key configuration in admin settings

### Changed
- Updated credit calculation to support Gemini pricing
- Enhanced error handling in API routing
- Improved model selection UI

### Database Changes
```sql
ALTER TABLE prompts 
MODIFY COLUMN API_MODEL ENUM(
    'gpt-3.5-turbo',
    'gpt-4',
    'gemini-pro',
    'claude-3'
) DEFAULT 'gpt-3.5-turbo';

ALTER TABLE settings 
ADD COLUMN google_gemini_api_key VARCHAR(500) DEFAULT NULL;
```

### Files Modified
- /php/api.php
- /php/gemini-api.php (NEW)
- /admin/class/Prompts.class.php
- /admin/modules/settings/api-keys.php
- /js/main.js
- /database_sql/aigency_structure.sql

### Migration Instructions
1. Backup database
2. Run SQL migrations
3. Update settings with Gemini API key
4. Test with sample prompt

### Rollback
```sql
ALTER TABLE prompts 
MODIFY COLUMN API_MODEL ENUM('gpt-3.5-turbo','gpt-4') DEFAULT 'gpt-3.5-turbo';

ALTER TABLE settings DROP COLUMN google_gemini_api_key;
```

### Testing Performed
- Unit tests: PASSED (15/15)
- Integration tests: PASSED (8/8)
- E2E tests: PASSED (5/5)
- Manual testing: PASSED

### Known Issues
- None

### Contributors
- Claude AI Assistant
- User (project owner)
```

---

## 🤔 INTERACTIVE QUESTIONING SYSTEM

### Question Framework

When Claude encounters ambiguity, it uses the **A/B/C Decision Model**:

```
❓ OBJECTIVE: [Clear statement of what needs clarification]

🔍 CONTEXT:
[Brief explanation of why this matters]

OPTIONS:

A) [Conservative Option]
   ✅ Pros: [2-3 advantages]
   ⚠️  Cons: [2-3 disadvantages]
   📝 Effort: [Low/Medium/High]

B) [Balanced Option]
   ✅ Pros: [2-3 advantages]
   ⚠️  Cons: [2-3 disadvantages]
   📝 Effort: [Low/Medium/High]

C) [Progressive Option]
   ✅ Pros: [2-3 advantages]
   ⚠️  Cons: [2-3 disadvantages]
   📝 Effort: [Low/Medium/High]

➡️ RECOMMENDATION: [Claude's suggested option with reasoning]

Please choose: A / B / C (or describe custom approach)
```

### Real-World Examples

**Example 1: Database Schema Change**

```
❓ OBJECTIVE: Add support for conversation "memory" feature

🔍 CONTEXT:
You want AI to remember facts about users across sessions. This requires
storing user-specific context data.

OPTIONS:

A) Add JSON column to existing customers table
   ✅ Pros:
      - Simple, no new tables
      - Fast to implement
      - No foreign key complexity
   ⚠️  Cons:
      - Limited querying capability
      - Can't easily analyze memory patterns
      - Size limits on JSON column
   📝 Effort: LOW (30 minutes)

B) Create new 'customer_memory' table with structured data
   ✅ Pros:
      - Full query capability
      - Can analyze/report on memory data
      - Scalable structure
      - Better data integrity
   ⚠️  Cons:
      - More complex setup
      - Additional joins in queries
      - Need to manage relationships
   📝 Effort: MEDIUM (2-3 hours)

C) Implement external vector database (Pinecone/Weaviate)
   ✅ Pros:
      - Semantic search capability
      - Highly scalable
      - Advanced AI memory features
   ⚠️  Cons:
      - External dependency
      - Additional cost
      - Complex integration
      - Requires API management
   📝 Effort: HIGH (1-2 days)

➡️ RECOMMENDATION: Option B

For a production SaaS platform, structured data in a dedicated table
provides the best balance. It's scalable, maintainable, and gives you
query flexibility without external dependencies.

The implementation would look like:

```sql
CREATE TABLE customer_memory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_customer INT NOT NULL,
    memory_key VARCHAR(255) NOT NULL,
    memory_value TEXT NOT NULL,
    memory_type ENUM('fact','preference','history') DEFAULT 'fact',
    confidence DECIMAL(3,2) DEFAULT 1.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_customer) REFERENCES customers(id) ON DELETE CASCADE,
    UNIQUE KEY unique_memory (id_customer, memory_key),
    INDEX idx_customer_type (id_customer, memory_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Please choose: A / B / C
```

**Example 2: API Integration Strategy**

```
❓ OBJECTIVE: Add Anthropic Claude API as new model option

🔍 CONTEXT:
Claude API has different pricing and capabilities than OpenAI. We need to
decide how to integrate it into the existing system.

OPTIONS:

A) Treat Claude like existing models (minimal changes)
   ✅ Pros:
      - Quick implementation
      - Consistent UI/UX
      - Reuses existing patterns
   ⚠️  Cons:
      - May not leverage Claude-specific features
      - Could limit future enhancements
      - Pricing structure different
   📝 Effort: LOW (4-6 hours)
   
   Changes needed:
   - Add to API_MODEL enum
   - Create /php/claude-api.php
   - Add API key to settings
   - Update routing in /php/api.php

B) Create Claude-specific features module
   ✅ Pros:
      - Can use Claude-specific features (artifacts, etc.)
      - Separate pricing/credit logic
      - Independent testing
      - Better optimization
   ⚠️  Cons:
      - More code to maintain
      - Divergent user experience
      - Complex prompt configuration
   📝 Effort: MEDIUM (1-2 days)
   
   Changes needed:
   - New module /modules/claude/
   - Claude-specific UI components
   - Separate message handling
   - Custom credit calculation

C) Unified AI abstraction layer with provider plugins
   ✅ Pros:
      - Easy to add more providers later
      - Clean architecture
      - Provider-specific optimizations possible
      - Consistent interface
   ⚠️  Cons:
      - Significant refactoring required
      - May over-engineer for current needs
      - Learning curve for future modifications
   📝 Effort: HIGH (3-5 days)
   
   Changes needed:
   - Create AIProvider interface
   - Refactor all existing API calls
   - Build plugin system
   - Update database schema
   - Rewrite /php/api.php

➡️ RECOMMENDATION: Option A initially, with architecture for B

Start with Option A to get Claude working quickly. However, structure the
code so that Claude-specific features can be added later without major
refactoring.

Implementation strategy:
1. Add Claude as standard model (Option A)
2. Monitor usage and feature requests
3. If Claude-specific features are needed, extract to module (Option B)
4. Only move to Option C if adding 3+ more providers

This gives you:
- Fast time-to-market
- Real user feedback before over-investing
- Clear upgrade path
- Minimal risk

Please choose: A / B / C
```

---

## 🔌 ADDING EXTERNAL APIs (STEP-BY-STEP)

### Complete API Integration Workflow

This is the definitive guide for adding ANY external API to Aigency.

#### PHASE 1: RESEARCH & PLANNING

**Step 1: API Analysis**

```
API RESEARCH CHECKLIST:
☐ Authentication method (API key, OAuth, Bearer token)
☐ Rate limits (requests per minute/hour/day)
☐ Pricing structure (per request, per token, etc.)
☐ Request/response format (JSON, XML, etc.)
☐ Error handling patterns
☐ Timeout recommendations
☐ SDK availability (PHP, JavaScript)
☐ Webhook support (if applicable)
☐ Documentation quality
☐ Status page/uptime history
```

**Step 2: Credit Calculation**

```php
/**
 * Determine credit cost for new API
 * 
 * Formula: Base Cost + (Complexity Factor × Usage Factor)
 */

// Example: Google Vision API
$baseCost = 1;  // Minimum credit cost
$complexityFactor = 2;  // API complexity (1-5)
$usageFactor = 1.5;  // Expected resource usage

$creditCost = $baseCost + ($complexityFactor * $usageFactor);
// Result: 4 credits per request

// Document reasoning:
/*
Google Vision API Credit Calculation:
- Base cost: 1 credit (minimum for any AI operation)
- Complexity: 2 (moderate - requires image processing)
- Usage: 1.5 (above average due to image data transfer)
- Total: 4 credits per image analysis

Comparison:
- GPT-3.5: 1 credit (text only, fast)
- GPT-4: 3 credits (text only, slower/better)
- DALL-E: 5 credits (image generation, expensive)
- Vision: 4 credits (image analysis, moderate)
*/
```

#### PHASE 2: DATABASE PREPARATION

**Step 3: Schema Modifications**

```sql
-- Add API key storage to settings
ALTER TABLE settings 
ADD COLUMN google_vision_api_key VARCHAR(500) DEFAULT NULL AFTER google_tts_api_key,
ADD COLUMN google_vision_enabled TINYINT(1) DEFAULT 0,
ADD COLUMN google_vision_default_features JSON DEFAULT NULL;

-- Add to prompts if model-specific
ALTER TABLE prompts 
MODIFY COLUMN API_MODEL ENUM(
    'gpt-3.5-turbo',
    'gpt-4',
    'gemini-pro',
    'claude-3',
    'google-vision'  -- NEW
) DEFAULT 'gpt-3.5-turbo';

-- Add feature flags
ALTER TABLE prompts
ADD COLUMN use_google_vision TINYINT(1) DEFAULT 0 AFTER use_vision;

-- Track API usage for analytics
CREATE TABLE api_usage_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_customer INT NOT NULL,
    api_provider VARCHAR(50) NOT NULL,
    endpoint VARCHAR(255) NOT NULL,
    request_data JSON,
    response_data JSON,
    status_code INT,
    response_time_ms INT,
    credits_charged INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_customer (id_customer),
    INDEX idx_provider (api_provider),
    INDEX idx_created (created_at),
    FOREIGN KEY (id_customer) REFERENCES customers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### PHASE 3: BACKEND IMPLEMENTATION

**Step 4: API Handler Class**

```php
<?php
/**
 * Google Vision API Integration
 * /admin/class/GoogleVision.class.php
 */

class GoogleVision
{
    private $apiKey;
    private $endpoint = 'https://vision.googleapis.com/v1/images:annotate';
    private $maxFileSize = 4 * 1024 * 1024; // 4MB
    private $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    /**
     * Constructor
     * 
     * @throws Exception if API key not configured
     */
    public function __construct()
    {
        $settings = new Settings();
        $config = $settings->getById(1);
        
        $this->apiKey = $config->google_vision_api_key;
        
        if (empty($this->apiKey)) {
            throw new Exception('Google Vision API key not configured');
        }
    }
    
    /**
     * Analyze image with specified features
     *
     * @param string $imageData Base64 encoded image or image URL
     * @param array $features Features to detect
     * @return object Analysis results
     * @throws Exception on API error
     */
    public function analyzeImage(string $imageData, array $features = [])
    {
        // Default features if none specified
        if (empty($features)) {
            $features = [
                ['type' => 'LABEL_DETECTION', 'maxResults' => 10],
                ['type' => 'TEXT_DETECTION'],
                ['type' => 'FACE_DETECTION'],
                ['type' => 'OBJECT_LOCALIZATION']
            ];
        }
        
        // Prepare request
        $requestBody = [
            'requests' => [
                [
                    'image' => $this->prepareImage($imageData),
                    'features' => $features
                ]
            ]
        ];
        
        // Make API request
        $startTime = microtime(true);
        $response = $this->makeRequest($requestBody);
        $responseTime = (microtime(true) - $startTime) * 1000; // milliseconds
        
        // Log usage
        $this->logUsage($requestBody, $response, $responseTime);
        
        // Process response
        if (isset($response['responses'][0]['error'])) {
            throw new Exception(
                'Vision API error: ' . $response['responses'][0]['error']['message']
            );
        }
        
        return $response['responses'][0];
    }
    
    /**
     * Prepare image for API request
     *
     * @param string $imageData Image data or URL
     * @return array Image object for API
     * @throws Exception if image invalid
     */
    private function prepareImage(string $imageData)
    {
        // Check if URL
        if (filter_var($imageData, FILTER_VALIDATE_URL)) {
            return ['source' => ['imageUri' => $imageData]];
        }
        
        // Check if already base64
        if (strpos($imageData, 'data:image') === 0) {
            // Extract base64 part
            $imageData = explode(',', $imageData)[1];
        }
        
        // Validate base64
        $decoded = base64_decode($imageData, true);
        if ($decoded === false) {
            throw new Exception('Invalid image data');
        }
        
        // Check file size
        if (strlen($decoded) > $this->maxFileSize) {
            throw new Exception('Image too large. Maximum 4MB.');
        }
        
        // Verify mime type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($decoded);
        
        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            throw new Exception('Invalid image type. Allowed: JPEG, PNG, GIF, WebP');
        }
        
        return ['content' => $imageData];
    }
    
    /**
     * Make HTTP request to Vision API
     *
     * @param array $body Request body
     * @return array Response data
     * @throws Exception on HTTP error
     */
    private function makeRequest(array $body)
    {
        $url = $this->endpoint . '?key=' . $this->apiKey;
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($body),
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
            throw new Exception('HTTP ' . $httpCode . ': ' . $response);
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Log API usage for analytics
     *
     * @param array $request Request data
     * @param array $response Response data
     * @param float $responseTime Response time in ms
     */
    private function logUsage(array $request, array $response, float $responseTime)
    {
        global $db;
        
        $customerId = $_SESSION['id_customer'] ?? 0;
        
        $stmt = $db->prepare("
            INSERT INTO api_usage_log 
            (id_customer, api_provider, endpoint, request_data, response_data, 
             status_code, response_time_ms, credits_charged)
            VALUES (?, 'google_vision', ?, ?, ?, 200, ?, 4)
        ");
        
        $requestJson = json_encode($request);
        $responseJson = json_encode($response);
        $endpoint = $this->endpoint;
        
        $stmt->bind_param(
            'isssi',
            $customerId,
            $endpoint,
            $requestJson,
            $responseJson,
            $responseTime
        );
        
        $stmt->execute();
        $stmt->close();
    }
    
    /**
     * Format response for user-friendly display
     *
     * @param object $analysisResult Raw API response
     * @return array Formatted results
     */
    public function formatResponse($analysisResult)
    {
        $formatted = [
            'labels' => [],
            'text' => '',
            'faces' => [],
            'objects' => []
        ];
        
        // Labels
        if (isset($analysisResult['labelAnnotations'])) {
            foreach ($analysisResult['labelAnnotations'] as $label) {
                $formatted['labels'][] = [
                    'description' => $label['description'],
                    'confidence' => round($label['score'] * 100, 2)
                ];
            }
        }
        
        // Text
        if (isset($analysisResult['textAnnotations'][0])) {
            $formatted['text'] = $analysisResult['textAnnotations'][0]['description'];
        }
        
        // Faces
        if (isset($analysisResult['faceAnnotations'])) {
            $formatted['faces'] = count($analysisResult['faceAnnotations']);
        }
        
        // Objects
        if (isset($analysisResult['localizedObjectAnnotations'])) {
            foreach ($analysisResult['localizedObjectAnnotations'] as $object) {
                $formatted['objects'][] = [
                    'name' => $object['name'],
                    'confidence' => round($object['score'] * 100, 2)
                ];
            }
        }
        
        return $formatted;
    }
    
    /**
     * Test API connection
     *
     * @return bool Connection status
     */
    public function testConnection()
    {
        try {
            // Use a simple test image (1x1 pixel)
            $testImage = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
            
            $this->analyzeImage($testImage, [
                ['type' => 'LABEL_DETECTION', 'maxResults' => 1]
            ]);
            
            return true;
        } catch (Exception $e) {
            error_log('Google Vision test failed: ' . $e->getMessage());
            return false;
        }
    }
}
```

**Step 5: API Endpoint**

```php
<?php
/**
 * Google Vision API Endpoint
 * /php/google-vision.php
 */

session_start();
require_once("../inc/includes.php");
require_once("key.php");

// Authentication check
if (!isset($_SESSION['id_customer'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Authentication required']);
    exit;
}

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['image']) || !isset($input['prompt_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

try {
    // Check credits
    $customers = new Customers();
    $customer = $customers->getById($_SESSION['id_customer']);
    
    $requiredCredits = 4;
    
    if ($customer->credits < $requiredCredits) {
        http_response_code(402);
        echo json_encode([
            'error' => 'Insufficient credits',
            'required' => $requiredCredits,
            'available' => $customer->credits
        ]);
        exit;
    }
    
    // Initialize Vision API
    $vision = new GoogleVision();
    
    // Analyze image
    $result = $vision->analyzeImage($input['image']);
    $formatted = $vision->formatResponse($result);
    
    // Save to messages
    $messages = new Messages();
    $messageId = uniqid('msg_');
    
    $messages->insert([
        'id_message' => $messageId,
        'id_thread' => $input['thread_id'],
        'id_customer' => $_SESSION['id_customer'],
        'id_prompt' => $input['prompt_id'],
        'role' => 'assistant',
        'content' => json_encode($formatted),
        'vision_img' => $input['image'],
        'total_characters' => 0,
        'saved' => 1
    ]);
    
    // Deduct credits
    $customers->updateCredits($_SESSION['id_customer'], -$requiredCredits);
    
    // Return results
    echo json_encode([
        'success' => true,
        'analysis' => $formatted,
        'message_id' => $messageId,
        'credits_used' => $requiredCredits,
        'credits_remaining' => $customer->credits - $requiredCredits
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
```

#### PHASE 4: ADMIN PANEL INTEGRATION

**Step 6: Settings UI**

```php
<?php
/**
 * Admin Settings - API Keys Section
 * /admin/modules/settings/api-keys.php
 */

require_once("../../inc/includes.php");
require_once("../../inc/restrict.php");

$settings = new Settings();
$config = $settings->getById(1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updates = [
        'google_vision_api_key' => $_POST['google_vision_api_key'] ?? '',
        'google_vision_enabled' => isset($_POST['google_vision_enabled']) ? 1 : 0
    ];
    
    if ($settings->update(1, $updates)) {
        // Test connection
        if ($updates['google_vision_enabled']) {
            try {
                $vision = new GoogleVision();
                if (!$vision->testConnection()) {
                    throw new Exception('Connection test failed');
                }
                $message = 'Settings saved and connection verified!';
                $alertClass = 'success';
            } catch (Exception $e) {
                $message = 'Settings saved but connection failed: ' . $e->getMessage();
                $alertClass = 'warning';
            }
        } else {
            $message = 'Settings saved successfully!';
            $alertClass = 'success';
        }
    } else {
        $message = 'Failed to save settings.';
        $alertClass = 'danger';
    }
}

include("../../inc/header.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2>API Key Configuration</h2>
            <p class="text-muted">Manage your external API integrations</p>
            
            <?php if (isset($message)): ?>
                <div class="alert alert-<?= $alertClass ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?= $message ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Google Vision API</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="google_vision_api_key">API Key</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="google_vision_api_key"
                                   name="google_vision_api_key"
                                   value="<?= htmlspecialchars($config->google_vision_api_key ?? '') ?>"
                                   placeholder="AIzaSy...">
                            <small class="form-text text-muted">
                                Get your API key from <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a>
                            </small>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="google_vision_enabled"
                                   name="google_vision_enabled"
                                   <?= $config->google_vision_enabled ? 'checked' : '' ?>>
                            <label class="form-check-label" for="google_vision_enabled">
                                Enable Google Vision API
                            </label>
                        </div>
                        
                        <hr>
                        
                        <h5>Credit Configuration</h5>
                        <div class="alert alert-info">
                            <strong>Current Setting:</strong> 4 credits per image analysis
                            <br>
                            <small>This covers: Label Detection, Text Detection, Face Detection, and Object Localization</small>
                        </div>
                        
                        <h5>Usage Statistics (Last 30 Days)</h5>
                        <?php
                        $stats = $db->query("
                            SELECT 
                                COUNT(*) as total_requests,
                                SUM(credits_charged) as total_credits,
                                AVG(response_time_ms) as avg_response_time
                            FROM api_usage_log
                            WHERE api_provider = 'google_vision'
                            AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                        ")->fetch_object();
                        ?>
                        
                        <table class="table table-bordered">
                            <tr>
                                <th>Total Requests</th>
                                <td><?= number_format($stats->total_requests) ?></td>
                            </tr>
                            <tr>
                                <th>Total Credits Used</th>
                                <td><?= number_format($stats->total_credits) ?></td>
                            </tr>
                            <tr>
                                <th>Avg Response Time</th>
                                <td><?= round($stats->avg_response_time) ?> ms</td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save Settings
                        </button>
                        <button type="button" class="btn btn-secondary" id="test-connection">
                            <i class="fa fa-plug"></i> Test Connection
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('test-connection').addEventListener('click', async function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Testing...';
    
    try {
        const response = await fetch('/php/test-vision-api.php');
        const result = await response.json();
        
        if (result.success) {
            toastr.success('Connection successful!');
        } else {
            toastr.error('Connection failed: ' + result.error);
        }
    } catch (error) {
        toastr.error('Test failed: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-plug"></i> Test Connection';
    }
});
</script>

<?php include("../../inc/footer.php"); ?>
```

#### PHASE 5: FRONTEND INTEGRATION

**Step 7: UI Component**

```javascript
/**
 * Vision Upload Component
 * /js/vision-upload.js
 */

class VisionUploader {
    constructor(chatManager) {
        this.chatManager = chatManager;
        this.currentImage = null;
        this.init();
    }
    
    init() {
        this.createUploadUI();
        this.attachListeners();
    }
    
    createUploadUI() {
        const container = document.createElement('div');
        container.className = 'vision-uploader';
        container.innerHTML = `
            <input type="file" 
                   id="vision-file-input" 
                   accept="image/*" 
                   style="display: none">
            <button id="vision-upload-btn" class="btn btn-secondary">
                <i class="fa fa-camera"></i> Analyze Image
            </button>
            <div id="vision-preview" class="vision-preview" style="display: none">
                <img id="vision-preview-img" src="" alt="Preview">
                <button id="vision-remove-btn" class="btn btn-sm btn-danger">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        `;
        
        // Insert before send button
        const inputContainer = document.querySelector('.chat-input-container');
        inputContainer.insertBefore(container, inputContainer.firstChild);
    }
    
    attachListeners() {
        document.getElementById('vision-upload-btn').addEventListener('click', () => {
            document.getElementById('vision-file-input').click();
        });
        
        document.getElementById('vision-file-input').addEventListener('change', (e) => {
            this.handleFileSelect(e.target.files[0]);
        });
        
        document.getElementById('vision-remove-btn').addEventListener('click', () => {
            this.clearImage();
        });
    }
    
    async handleFileSelect(file) {
        if (!file) return;
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            toastr.error('Please select an image file');
            return;
        }
        
        // Validate file size (4MB max)
        if (file.size > 4 * 1024 * 1024) {
            toastr.error('Image too large. Maximum 4MB.');
            return;
        }
        
        try {
            // Convert to base64
            const base64 = await this.fileToBase64(file);
            this.currentImage = base64;
            
            // Show preview
            document.getElementById('vision-preview-img').src = base64;
            document.getElementById('vision-preview').style.display = 'block';
            
            toastr.success('Image loaded. Click "Analyze" to process.');
            
            // Add analyze button if not exists
            if (!document.getElementById('vision-analyze-btn')) {
                const analyzeBtn = document.createElement('button');
                analyzeBtn.id = 'vision-analyze-btn';
                analyzeBtn.className = 'btn btn-primary ml-2';
                analyzeBtn.innerHTML = '<i class="fa fa-search"></i> Analyze';
                analyzeBtn.addEventListener('click', () => this.analyzeImage());
                
                document.getElementById('vision-upload-btn').parentNode.appendChild(analyzeBtn);
            }
        } catch (error) {
            toastr.error('Failed to load image: ' + error.message);
        }
    }
    
    fileToBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }
    
    async analyzeImage() {
        if (!this.currentImage) {
            toastr.error('No image loaded');
            return;
        }
        
        const btn = document.getElementById('vision-analyze-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Analyzing...';
        
        try {
            const response = await fetch('/php/google-vision.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    image: this.currentImage,
                    prompt_id: this.chatManager.promptId,
                    thread_id: this.chatManager.threadId
                })
            });
            
            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error || 'Analysis failed');
            }
            
            const result = await response.json();
            
            // Display results in chat
            this.displayResults(result.analysis);
            
            // Update credits display
            this.chatManager.updateCredits(result.credits_remaining);
            
            toastr.success(`Analysis complete! ${result.credits_used} credits used.`);
            
            // Clear image
            this.clearImage();
            
        } catch (error) {
            toastr.error('Analysis failed: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-search"></i> Analyze';
        }
    }
    
    displayResults(analysis) {
        let content = '### Image Analysis Results\n\n';
        
        // Labels
        if (analysis.labels && analysis.labels.length > 0) {
            content += '**Labels Detected:**\n';
            analysis.labels.forEach(label => {
                content += `- ${label.description} (${label.confidence}%)\n`;
            });
            content += '\n';
        }
        
        // Text
        if (analysis.text) {
            content += '**Text Found:**\n```\n' + analysis.text + '\n```\n\n';
        }
        
        // Faces
        if (analysis.faces > 0) {
            content += `**Faces Detected:** ${analysis.faces}\n\n`;
        }
        
        // Objects
        if (analysis.objects && analysis.objects.length > 0) {
            content += '**Objects Detected:**\n';
            analysis.objects.forEach(obj => {
                content += `- ${obj.name} (${obj.confidence}%)\n`;
            });
        }
        
        // Add to chat
        this.chatManager.addMessage('assistant', content, {
            vision_img: this.currentImage
        });
    }
    
    clearImage() {
        this.currentImage = null;
        document.getElementById('vision-preview').style.display = 'none';
        document.getElementById('vision-file-input').value = '';
        
        const analyzeBtn = document.getElementById('vision-analyze-btn');
        if (analyzeBtn) {
            analyzeBtn.remove();
        }
    }
}

// Initialize when chat loads
document.addEventListener('chatInitialized', (e) => {
    if (e.detail.config.useVision) {
        new VisionUploader(e.detail.chatManager);
    }
});
```

---

[Continue with remaining sections: Creating New Modules, Security, Testing, etc.? This document is already very comprehensive. Should I proceed with the final sections?]

Would you like me to:
1. Continue with the remaining sections (Style/UI, Security, Performance, etc.)
2. Or move both parts to the outputs folder for you to access?
