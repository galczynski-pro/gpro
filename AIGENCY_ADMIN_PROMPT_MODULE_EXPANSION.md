# AIGENCY: Admin Prompt Module Expansion Guide

## Advanced AI Configuration System

This guide details the implementation of enhanced prompt configuration features including meta instructions, interaction patterns, and personality traits within the admin panel.

---

## Table of Contents

1. [Database Schema Extensions](#database-schema-extensions)
2. [PHP Class Modifications](#php-class-modifications)
3. [Admin Interface Implementation](#admin-interface-implementation)
4. [Configuration Templates](#configuration-templates)
5. [Integration Examples](#integration-examples)
6. [Testing Procedures](#testing-procedures)

---

## Database Schema Extensions

### Current Prompts Table Structure

The existing `prompts` table requires additional fields to support advanced AI configuration:

```sql
-- Add new columns to prompts table
ALTER TABLE prompts
    -- Meta Instructions (system-level guidance)
    ADD COLUMN meta_instructions TEXT DEFAULT NULL 
        COMMENT 'System-level instructions that guide AI behavior',
    
    -- Interaction Pattern Configuration
    ADD COLUMN interaction_pattern VARCHAR(50) DEFAULT 'conversational' 
        COMMENT 'Defines how AI interacts: conversational, structured, analytical, creative',
    ADD COLUMN interaction_settings JSON DEFAULT NULL 
        COMMENT 'JSON configuration for interaction behavior',
    
    -- Personality Configuration
    ADD COLUMN personality_traits JSON DEFAULT NULL 
        COMMENT 'JSON array of personality characteristics',
    ADD COLUMN tone_profile VARCHAR(50) DEFAULT 'balanced' 
        COMMENT 'Overall tone: professional, casual, friendly, formal, technical',
    
    -- Response Format Controls
    ADD COLUMN response_structure VARCHAR(50) DEFAULT 'flexible' 
        COMMENT 'Expected response format: flexible, structured, list, steps, essay',
    ADD COLUMN response_length VARCHAR(50) DEFAULT 'medium' 
        COMMENT 'Target response length: brief, medium, detailed, comprehensive',
    
    -- Advanced Behavior Settings
    ADD COLUMN thinking_style VARCHAR(50) DEFAULT 'balanced' 
        COMMENT 'Reasoning approach: analytical, creative, practical, balanced',
    ADD COLUMN context_handling VARCHAR(50) DEFAULT 'standard' 
        COMMENT 'How to handle conversation context: minimal, standard, extensive',
    
    -- Constraints and Guidelines
    ADD COLUMN content_restrictions TEXT DEFAULT NULL 
        COMMENT 'Specific content to avoid or limitations',
    ADD COLUMN quality_requirements TEXT DEFAULT NULL 
        COMMENT 'Quality standards and output requirements',
    
    -- Example Configurations
    ADD COLUMN example_inputs JSON DEFAULT NULL 
        COMMENT 'Sample inputs for testing',
    ADD COLUMN example_outputs JSON DEFAULT NULL 
        COMMENT 'Expected output examples',
    
    -- Metadata
    ADD COLUMN configuration_version INT DEFAULT 1 
        COMMENT 'Version tracking for configuration changes',
    ADD COLUMN last_tested_at TIMESTAMP NULL 
        COMMENT 'Last time configuration was tested',
    ADD COLUMN test_results JSON DEFAULT NULL 
        COMMENT 'Results from configuration testing';

-- Add indexes for frequently queried fields
ALTER TABLE prompts
    ADD INDEX idx_interaction_pattern (interaction_pattern),
    ADD INDEX idx_tone_profile (tone_profile),
    ADD INDEX idx_thinking_style (thinking_style);
```

### Supporting Reference Tables

Create lookup tables for standardized configuration options:

```sql
-- Interaction Patterns Reference Table
CREATE TABLE prompt_interaction_patterns (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pattern_name VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL,
    description TEXT,
    default_settings JSON,
    example_use_cases TEXT,
    status TINYINT(1) DEFAULT 1,
    item_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Personality Traits Reference Table
CREATE TABLE prompt_personality_traits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    trait_name VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL,
    description TEXT,
    trait_category VARCHAR(50),
    compatible_patterns VARCHAR(255),
    status TINYINT(1) DEFAULT 1,
    item_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tone Profiles Reference Table
CREATE TABLE prompt_tone_profiles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    profile_name VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL,
    description TEXT,
    characteristics JSON,
    sample_text TEXT,
    status TINYINT(1) DEFAULT 1,
    item_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default interaction patterns
INSERT INTO prompt_interaction_patterns 
(pattern_name, display_name, description, default_settings, example_use_cases, item_order) VALUES
('conversational', 'Conversational', 
 'Natural dialogue-style interaction with back-and-forth exchange',
 '{"turn_taking": true, "context_memory": "high", "formality": "medium"}',
 'Chat assistants, tutoring, customer support', 1),

('structured', 'Structured Response',
 'Organized output with clear sections and formatting',
 '{"use_headings": true, "use_lists": true, "step_numbering": true}',
 'Reports, documentation, instructional content', 2),

('analytical', 'Analytical',
 'Data-driven responses with reasoning and evidence',
 '{"show_reasoning": true, "cite_sources": true, "quantify": true}',
 'Research assistance, data analysis, problem solving', 3),

('creative', 'Creative',
 'Imaginative and original responses',
 '{"allow_metaphors": true, "encourage_originality": true, "flexibility": "high"}',
 'Content creation, brainstorming, storytelling', 4),

('instructional', 'Instructional',
 'Step-by-step teaching approach',
 '{"use_examples": true, "break_down_steps": true, "check_understanding": true}',
 'Tutorials, learning materials, how-to guides', 5),

('socratic', 'Socratic Method',
 'Question-based guidance to develop understanding',
 '{"ask_questions": true, "guide_discovery": true, "minimal_direct_answers": true}',
 'Educational contexts, critical thinking development', 6);

-- Insert default personality traits
INSERT INTO prompt_personality_traits 
(trait_name, display_name, description, trait_category, compatible_patterns, item_order) VALUES
('professional', 'Professional', 
 'Formal, competent, business-appropriate communication',
 'formality', 'structured,analytical,instructional', 1),

('friendly', 'Friendly',
 'Warm, approachable, personable interaction style',
 'warmth', 'conversational,instructional,creative', 2),

('concise', 'Concise',
 'Brief, direct responses without unnecessary elaboration',
 'verbosity', 'structured,analytical', 3),

('detailed', 'Detailed',
 'Comprehensive responses with thorough explanations',
 'verbosity', 'analytical,instructional', 4),

('encouraging', 'Encouraging',
 'Supportive, positive, motivational communication',
 'emotional_tone', 'instructional,conversational,socratic', 5),

('neutral', 'Neutral',
 'Objective, unbiased, fact-focused responses',
 'emotional_tone', 'analytical,structured', 6),

('patient', 'Patient',
 'Accommodating, understanding, willing to repeat or clarify',
 'interaction_style', 'instructional,socratic,conversational', 7),

('efficient', 'Efficient',
 'Direct, time-conscious, results-oriented',
 'interaction_style', 'structured,analytical', 8),

('curious', 'Curious',
 'Questioning, exploratory, interested in deeper understanding',
 'cognitive_style', 'socratic,analytical,creative', 9),

('methodical', 'Methodical',
 'Systematic, organized, step-by-step approach',
 'cognitive_style', 'structured,analytical,instructional', 10);

-- Insert default tone profiles
INSERT INTO prompt_tone_profiles 
(profile_name, display_name, description, characteristics, sample_text, item_order) VALUES
('professional', 'Professional',
 'Formal business communication appropriate for workplace contexts',
 '{"formality": "high", "vocabulary": "advanced", "structure": "organized"}',
 'I recommend reviewing the quarterly performance metrics to identify optimization opportunities.', 1),

('casual', 'Casual',
 'Relaxed, informal communication suitable for everyday interaction',
 '{"formality": "low", "vocabulary": "everyday", "structure": "flexible"}',
 'Let me help you figure this out - it is actually pretty straightforward once you get the hang of it.', 2),

('friendly', 'Friendly',
 'Warm and personable while maintaining clarity',
 '{"formality": "medium", "vocabulary": "accessible", "structure": "conversational"}',
 'Great question - I would be happy to explain how this works and help you get started.', 3),

('formal', 'Formal',
 'Academic or official communication with precise language',
 '{"formality": "very_high", "vocabulary": "technical", "structure": "rigid"}',
 'The implementation of this methodology requires adherence to established protocols.', 4),

('technical', 'Technical',
 'Specialized terminology for expert audiences',
 '{"formality": "medium", "vocabulary": "specialized", "structure": "precise"}',
 'The API endpoint accepts POST requests with JSON payloads containing authentication tokens.', 5),

('educational', 'Educational',
 'Clear explanations designed for learning contexts',
 '{"formality": "medium", "vocabulary": "explanatory", "structure": "pedagogical"}',
 'This concept works like a filing system - each piece of information has its own place.', 6);
```

---

## PHP Class Modifications

### Enhanced Prompts Class

Extend the existing `Prompts.class.php` with advanced configuration methods:

```php
<?php
/**
 * Enhanced Prompts Class
 * 
 * File: /admin/class/Prompts.class.php
 * 
 * Add these methods to the existing Prompts class
 */

class Prompts extends Action
{
    // ... existing methods ...
    
    /**
     * Get prompt with full configuration
     * 
     * Retrieves prompt including all advanced settings
     * 
     * @param int $id Prompt ID
     * @return object|null Prompt object with decoded JSON fields
     */
    public function getWithConfiguration($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                p.*,
                ip.display_name as interaction_pattern_name,
                ip.description as interaction_pattern_description,
                tp.display_name as tone_profile_name,
                tp.characteristics as tone_characteristics
            FROM {$this->table} p
            LEFT JOIN prompt_interaction_patterns ip 
                ON p.interaction_pattern = ip.pattern_name
            LEFT JOIN prompt_tone_profiles tp 
                ON p.tone_profile = tp.profile_name
            WHERE p.id = ?
        ");
        
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $prompt = $result->fetch_object();
        
        if (!$prompt) {
            return null;
        }
        
        // Decode JSON fields
        $jsonFields = [
            'interaction_settings',
            'personality_traits',
            'example_inputs',
            'example_outputs',
            'test_results',
            'tone_characteristics'
        ];
        
        foreach ($jsonFields as $field) {
            if (isset($prompt->$field)) {
                $prompt->$field = json_decode($prompt->$field, true);
            }
        }
        
        return $prompt;
    }
    
    /**
     * Update prompt configuration
     * 
     * @param int $id Prompt ID
     * @param array $config Configuration data
     * @return bool Success status
     */
    public function updateConfiguration($id, $config)
    {
        // Validate configuration data
        $validation = $this->validateConfiguration($config);
        if (!$validation['valid']) {
            throw new Exception($validation['error']);
        }
        
        // Prepare JSON fields
        $jsonFields = [
            'interaction_settings',
            'personality_traits',
            'example_inputs',
            'example_outputs'
        ];
        
        foreach ($jsonFields as $field) {
            if (isset($config[$field]) && is_array($config[$field])) {
                $config[$field] = json_encode($config[$field]);
            }
        }
        
        // Increment configuration version
        if (!isset($config['configuration_version'])) {
            $current = $this->getById($id);
            $config['configuration_version'] = ($current->configuration_version ?? 0) + 1;
        }
        
        // Build UPDATE query
        $updates = [];
        $types = '';
        $values = [];
        
        $allowedFields = [
            'meta_instructions',
            'interaction_pattern',
            'interaction_settings',
            'personality_traits',
            'tone_profile',
            'response_structure',
            'response_length',
            'thinking_style',
            'context_handling',
            'content_restrictions',
            'quality_requirements',
            'example_inputs',
            'example_outputs',
            'configuration_version'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($config[$field])) {
                $updates[] = "$field = ?";
                $types .= 's';
                $values[] = $config[$field];
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . " WHERE id = ?";
        $types .= 'i';
        $values[] = $id;
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
    
    /**
     * Validate configuration data
     * 
     * @param array $config Configuration to validate
     * @return array Validation result
     */
    public function validateConfiguration($config)
    {
        $errors = [];
        
        // Validate interaction pattern
        if (isset($config['interaction_pattern'])) {
            $validPatterns = $this->getValidInteractionPatterns();
            if (!in_array($config['interaction_pattern'], $validPatterns)) {
                $errors[] = "Invalid interaction pattern";
            }
        }
        
        // Validate tone profile
        if (isset($config['tone_profile'])) {
            $validTones = $this->getValidToneProfiles();
            if (!in_array($config['tone_profile'], $validTones)) {
                $errors[] = "Invalid tone profile";
            }
        }
        
        // Validate personality traits
        if (isset($config['personality_traits']) && is_array($config['personality_traits'])) {
            $validTraits = $this->getValidPersonalityTraits();
            foreach ($config['personality_traits'] as $trait) {
                if (!in_array($trait, $validTraits)) {
                    $errors[] = "Invalid personality trait: $trait";
                }
            }
        }
        
        // Validate meta instructions length
        if (isset($config['meta_instructions']) && 
            strlen($config['meta_instructions']) > 5000) {
            $errors[] = "Meta instructions exceed maximum length (5000 characters)";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Get valid interaction patterns
     * 
     * @return array List of valid pattern names
     */
    public function getValidInteractionPatterns()
    {
        $result = $this->db->query("
            SELECT pattern_name 
            FROM prompt_interaction_patterns 
            WHERE status = 1
            ORDER BY item_order
        ");
        
        $patterns = [];
        while ($row = $result->fetch_object()) {
            $patterns[] = $row->pattern_name;
        }
        
        return $patterns;
    }
    
    /**
     * Get valid tone profiles
     * 
     * @return array List of valid profile names
     */
    public function getValidToneProfiles()
    {
        $result = $this->db->query("
            SELECT profile_name 
            FROM prompt_tone_profiles 
            WHERE status = 1
            ORDER BY item_order
        ");
        
        $profiles = [];
        while ($row = $result->fetch_object()) {
            $profiles[] = $row->profile_name;
        }
        
        return $profiles;
    }
    
    /**
     * Get valid personality traits
     * 
     * @return array List of valid trait names
     */
    public function getValidPersonalityTraits()
    {
        $result = $this->db->query("
            SELECT trait_name 
            FROM prompt_personality_traits 
            WHERE status = 1
            ORDER BY trait_category, item_order
        ");
        
        $traits = [];
        while ($row = $result->fetch_object()) {
            $traits[] = $row->trait_name;
        }
        
        return $traits;
    }
    
    /**
     * Build system prompt from configuration
     * 
     * Combines base prompt with meta instructions and configuration
     * 
     * @param int $id Prompt ID
     * @return string Complete system prompt
     */
    public function buildSystemPrompt($id)
    {
        $prompt = $this->getWithConfiguration($id);
        
        if (!$prompt) {
            throw new Exception("Prompt not found");
        }
        
        $systemPrompt = $prompt->prompt;
        
        // Add meta instructions
        if (!empty($prompt->meta_instructions)) {
            $systemPrompt .= "\n\n## Meta Instructions\n" . $prompt->meta_instructions;
        }
        
        // Add interaction pattern guidance
        if ($prompt->interaction_pattern && $prompt->interaction_pattern !== 'conversational') {
            $systemPrompt .= "\n\n## Interaction Pattern\n";
            $systemPrompt .= "Follow the '{$prompt->interaction_pattern}' interaction pattern.\n";
            
            if (!empty($prompt->interaction_settings)) {
                $systemPrompt .= "Configuration: " . json_encode($prompt->interaction_settings) . "\n";
            }
        }
        
        // Add personality traits
        if (!empty($prompt->personality_traits) && is_array($prompt->personality_traits)) {
            $systemPrompt .= "\n\n## Personality Traits\n";
            $systemPrompt .= "Embody these characteristics: " . 
                           implode(', ', $prompt->personality_traits) . "\n";
        }
        
        // Add tone guidance
        if ($prompt->tone_profile && $prompt->tone_profile !== 'balanced') {
            $systemPrompt .= "\n\n## Tone\n";
            $systemPrompt .= "Use a {$prompt->tone_profile} tone in all responses.\n";
        }
        
        // Add response structure requirements
        if ($prompt->response_structure && $prompt->response_structure !== 'flexible') {
            $systemPrompt .= "\n\n## Response Format\n";
            $systemPrompt .= "Structure responses in a {$prompt->response_structure} format.\n";
        }
        
        // Add response length guidance
        if ($prompt->response_length && $prompt->response_length !== 'medium') {
            $systemPrompt .= "Target response length: {$prompt->response_length}.\n";
        }
        
        // Add thinking style guidance
        if ($prompt->thinking_style && $prompt->thinking_style !== 'balanced') {
            $systemPrompt .= "\n\n## Thinking Style\n";
            $systemPrompt .= "Apply {$prompt->thinking_style} reasoning to all responses.\n";
        }
        
        // Add content restrictions
        if (!empty($prompt->content_restrictions)) {
            $systemPrompt .= "\n\n## Content Restrictions\n";
            $systemPrompt .= $prompt->content_restrictions . "\n";
        }
        
        // Add quality requirements
        if (!empty($prompt->quality_requirements)) {
            $systemPrompt .= "\n\n## Quality Standards\n";
            $systemPrompt .= $prompt->quality_requirements . "\n";
        }
        
        return $systemPrompt;
    }
    
    /**
     * Test prompt configuration
     * 
     * Validates configuration with sample inputs
     * 
     * @param int $id Prompt ID
     * @param array $testInputs Optional test inputs
     * @return array Test results
     */
    public function testConfiguration($id, $testInputs = null)
    {
        $prompt = $this->getWithConfiguration($id);
        
        if (!$prompt) {
            return ['success' => false, 'error' => 'Prompt not found'];
        }
        
        // Use provided inputs or stored examples
        $inputs = $testInputs ?? $prompt->example_inputs ?? [];
        
        if (empty($inputs)) {
            return [
                'success' => false, 
                'error' => 'No test inputs provided'
            ];
        }
        
        $systemPrompt = $this->buildSystemPrompt($id);
        $results = [];
        
        foreach ($inputs as $input) {
            // This would integrate with your actual AI API
            // For now, we validate the configuration structure
            $results[] = [
                'input' => $input,
                'system_prompt_length' => strlen($systemPrompt),
                'configuration_valid' => true,
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
        
        // Store test results
        $this->db->query("
            UPDATE {$this->table} 
            SET last_tested_at = NOW(),
                test_results = '" . json_encode($results) . "'
            WHERE id = " . (int)$id
        );
        
        return [
            'success' => true,
            'results' => $results,
            'system_prompt_preview' => substr($systemPrompt, 0, 500) . '...'
        ];
    }
}
```

### Configuration Manager Class

Create a dedicated class for managing configuration options:

```php
<?php
/**
 * PromptConfigurationManager Class
 * 
 * File: /admin/class/PromptConfigurationManager.class.php
 * 
 * Manages interaction patterns, personality traits, and tone profiles
 */

class PromptConfigurationManager
{
    private $db;
    
    public function __construct()
    {
        $this->db = Db::getInstance();
    }
    
    /**
     * Get all interaction patterns
     * 
     * @return array List of patterns with details
     */
    public function getInteractionPatterns()
    {
        $result = $this->db->query("
            SELECT * FROM prompt_interaction_patterns
            WHERE status = 1
            ORDER BY item_order ASC
        ");
        
        $patterns = [];
        while ($row = $result->fetch_object()) {
            $row->default_settings = json_decode($row->default_settings, true);
            $patterns[] = $row;
        }
        
        return $patterns;
    }
    
    /**
     * Get personality traits grouped by category
     * 
     * @return array Traits organized by category
     */
    public function getPersonalityTraitsByCategory()
    {
        $result = $this->db->query("
            SELECT * FROM prompt_personality_traits
            WHERE status = 1
            ORDER BY trait_category, item_order ASC
        ");
        
        $traits = [];
        while ($row = $result->fetch_object()) {
            $category = $row->trait_category ?? 'general';
            if (!isset($traits[$category])) {
                $traits[$category] = [];
            }
            $traits[$category][] = $row;
        }
        
        return $traits;
    }
    
    /**
     * Get all tone profiles
     * 
     * @return array List of tone profiles
     */
    public function getToneProfiles()
    {
        $result = $this->db->query("
            SELECT * FROM prompt_tone_profiles
            WHERE status = 1
            ORDER BY item_order ASC
        ");
        
        $profiles = [];
        while ($row = $result->fetch_object()) {
            $row->characteristics = json_decode($row->characteristics, true);
            $profiles[] = $row;
        }
        
        return $profiles;
    }
    
    /**
     * Get compatible traits for an interaction pattern
     * 
     * @param string $pattern Pattern name
     * @return array Compatible traits
     */
    public function getCompatibleTraits($pattern)
    {
        $result = $this->db->query("
            SELECT * FROM prompt_personality_traits
            WHERE status = 1
            AND (
                compatible_patterns LIKE '%{$pattern}%'
                OR compatible_patterns IS NULL
            )
            ORDER BY trait_category, item_order ASC
        ");
        
        $traits = [];
        while ($row = $result->fetch_object()) {
            $traits[] = $row;
        }
        
        return $traits;
    }
    
    /**
     * Get configuration preset
     * 
     * Returns recommended configuration based on use case
     * 
     * @param string $useCase Use case identifier
     * @return array Configuration preset
     */
    public function getConfigurationPreset($useCase)
    {
        $presets = [
            'customer_support' => [
                'interaction_pattern' => 'conversational',
                'personality_traits' => ['friendly', 'patient', 'professional'],
                'tone_profile' => 'friendly',
                'response_structure' => 'flexible',
                'response_length' => 'medium',
                'thinking_style' => 'practical'
            ],
            'technical_documentation' => [
                'interaction_pattern' => 'structured',
                'personality_traits' => ['methodical', 'detailed', 'neutral'],
                'tone_profile' => 'technical',
                'response_structure' => 'structured',
                'response_length' => 'detailed',
                'thinking_style' => 'analytical'
            ],
            'creative_writing' => [
                'interaction_pattern' => 'creative',
                'personality_traits' => ['curious', 'encouraging', 'detailed'],
                'tone_profile' => 'casual',
                'response_structure' => 'flexible',
                'response_length' => 'comprehensive',
                'thinking_style' => 'creative'
            ],
            'educational_tutoring' => [
                'interaction_pattern' => 'socratic',
                'personality_traits' => ['patient', 'encouraging', 'methodical'],
                'tone_profile' => 'educational',
                'response_structure' => 'structured',
                'response_length' => 'detailed',
                'thinking_style' => 'balanced'
            ],
            'data_analysis' => [
                'interaction_pattern' => 'analytical',
                'personality_traits' => ['methodical', 'detailed', 'neutral'],
                'tone_profile' => 'professional',
                'response_structure' => 'structured',
                'response_length' => 'comprehensive',
                'thinking_style' => 'analytical'
            ]
        ];
        
        return $presets[$useCase] ?? null;
    }
}
```

---

## Admin Interface Implementation

### Enhanced Prompt Edit Page

Create the admin interface for configuring advanced prompt settings:

```php
<?php
/**
 * Enhanced Prompt Configuration Page
 * 
 * File: /admin/modules/prompts/edit-configuration.php
 * 
 * Advanced configuration interface for prompt settings
 */

require_once("../../inc/includes.php");
require_once("../../inc/restrict.php");

$prompts = new Prompts();
$configManager = new PromptConfigurationManager();

// Get prompt ID
$promptId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$promptId) {
    header("Location: index.php");
    exit;
}

// Load prompt data
$prompt = $prompts->getWithConfiguration($promptId);

if (!$prompt) {
    header("Location: index.php?error=not_found");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $config = [
            'meta_instructions' => $_POST['meta_instructions'] ?? '',
            'interaction_pattern' => $_POST['interaction_pattern'] ?? 'conversational',
            'interaction_settings' => json_decode($_POST['interaction_settings'] ?? '{}', true),
            'personality_traits' => $_POST['personality_traits'] ?? [],
            'tone_profile' => $_POST['tone_profile'] ?? 'balanced',
            'response_structure' => $_POST['response_structure'] ?? 'flexible',
            'response_length' => $_POST['response_length'] ?? 'medium',
            'thinking_style' => $_POST['thinking_style'] ?? 'balanced',
            'context_handling' => $_POST['context_handling'] ?? 'standard',
            'content_restrictions' => $_POST['content_restrictions'] ?? '',
            'quality_requirements' => $_POST['quality_requirements'] ?? '',
            'example_inputs' => array_filter($_POST['example_inputs'] ?? []),
            'example_outputs' => array_filter($_POST['example_outputs'] ?? [])
        ];
        
        if ($prompts->updateConfiguration($promptId, $config)) {
            $successMessage = "Configuration updated successfully";
            $prompt = $prompts->getWithConfiguration($promptId);
        } else {
            $errorMessage = "Failed to update configuration";
        }
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}

// Load configuration options
$interactionPatterns = $configManager->getInteractionPatterns();
$personalityTraits = $configManager->getPersonalityTraitsByCategory();
$toneProfiles = $configManager->getToneProfiles();

include("../../inc/header.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>
                        <i class="fas fa-cog"></i>
                        Advanced Configuration: <?= htmlspecialchars($prompt->name) ?>
                    </h2>
                    <p class="text-muted">
                        Configure interaction patterns, personality traits, and meta instructions
                    </p>
                </div>
                <div>
                    <a href="edit.php?id=<?= $promptId ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Basic Settings
                    </a>
                </div>
            </div>
            
            <!-- Messages -->
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="configuration-form">
                
                <!-- Configuration Presets -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-magic"></i>
                            Quick Start Presets
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Select a preset to automatically configure settings for common use cases.
                            You can customize individual settings after applying a preset.
                        </p>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary" 
                                    onclick="applyPreset('customer_support')">
                                <i class="fas fa-headset"></i> Customer Support
                            </button>
                            <button type="button" class="btn btn-outline-primary" 
                                    onclick="applyPreset('technical_documentation')">
                                <i class="fas fa-book"></i> Technical Docs
                            </button>
                            <button type="button" class="btn btn-outline-primary" 
                                    onclick="applyPreset('creative_writing')">
                                <i class="fas fa-pen"></i> Creative Writing
                            </button>
                            <button type="button" class="btn btn-outline-primary" 
                                    onclick="applyPreset('educational_tutoring')">
                                <i class="fas fa-graduation-cap"></i> Education
                            </button>
                            <button type="button" class="btn btn-outline-primary" 
                                    onclick="applyPreset('data_analysis')">
                                <i class="fas fa-chart-line"></i> Data Analysis
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Meta Instructions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-file-alt"></i>
                            Meta Instructions
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            System-level instructions that provide context and constraints for the AI.
                            These instructions are added to the base prompt and guide overall behavior.
                        </p>
                        <div class="form-group">
                            <label for="meta_instructions">
                                Meta Instructions
                                <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea class="form-control" 
                                      id="meta_instructions" 
                                      name="meta_instructions" 
                                      rows="6"
                                      maxlength="5000"
                                      placeholder="Example: Always maintain educational context. Responses should be suitable for children aged 5-11 in UK Key Stage 1 & 2. Use clear, simple language and provide examples relevant to primary school learning."><?= htmlspecialchars($prompt->meta_instructions ?? '') ?></textarea>
                            <small class="form-text text-muted">
                                <span id="meta_char_count">0</span>/5000 characters
                            </small>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong>Usage guidance:</strong>
                            <ul class="mb-0">
                                <li>Define the target audience and context</li>
                                <li>Specify language requirements or restrictions</li>
                                <li>Set boundaries for content appropriateness</li>
                                <li>Establish any domain-specific guidelines</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Interaction Pattern -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-comments"></i>
                            Interaction Pattern
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Defines how the AI structures its interaction with users.
                        </p>
                        
                        <div class="row">
                            <?php foreach ($interactionPatterns as $pattern): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card interaction-pattern-card h-100 
                                                <?= $prompt->interaction_pattern === $pattern->pattern_name ? 'border-primary' : '' ?>">
                                        <div class="card-body">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" 
                                                       class="custom-control-input" 
                                                       id="pattern_<?= $pattern->id ?>" 
                                                       name="interaction_pattern" 
                                                       value="<?= htmlspecialchars($pattern->pattern_name) ?>"
                                                       <?= $prompt->interaction_pattern === $pattern->pattern_name ? 'checked' : '' ?>>
                                                <label class="custom-control-label" 
                                                       for="pattern_<?= $pattern->id ?>">
                                                    <strong><?= htmlspecialchars($pattern->display_name) ?></strong>
                                                </label>
                                            </div>
                                            <p class="text-muted small mt-2 mb-2">
                                                <?= htmlspecialchars($pattern->description) ?>
                                            </p>
                                            <div class="small">
                                                <strong>Best for:</strong>
                                                <br>
                                                <?= htmlspecialchars($pattern->example_use_cases) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Personality Traits -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-user"></i>
                            Personality Traits
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Select characteristics that define the AI's communication style and approach.
                            Multiple traits can be selected to create a nuanced personality.
                        </p>
                        
                        <?php foreach ($personalityTraits as $category => $traits): ?>
                            <div class="mb-4">
                                <h5 class="text-capitalize"><?= htmlspecialchars($category) ?></h5>
                                <div class="row">
                                    <?php foreach ($traits as $trait): ?>
                                        <div class="col-md-6 col-lg-3 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="trait_<?= $trait->id ?>" 
                                                       name="personality_traits[]" 
                                                       value="<?= htmlspecialchars($trait->trait_name) ?>"
                                                       <?= in_array($trait->trait_name, $prompt->personality_traits ?? []) ? 'checked' : '' ?>>
                                                <label class="custom-control-label" 
                                                       for="trait_<?= $trait->id ?>"
                                                       data-toggle="tooltip"
                                                       title="<?= htmlspecialchars($trait->description) ?>">
                                                    <?= htmlspecialchars($trait->display_name) ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="alert alert-info">
                            <strong>Selection guidance:</strong> Choose 2-4 traits that work well together.
                            Conflicting traits (e.g., 'concise' and 'detailed') may produce inconsistent behavior.
                        </div>
                    </div>
                </div>
                
                <!-- Tone Profile -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-volume-up"></i>
                            Tone Profile
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Defines the overall communication style and language level.
                        </p>
                        
                        <div class="row">
                            <?php foreach ($toneProfiles as $profile): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card tone-profile-card h-100
                                                <?= $prompt->tone_profile === $profile->profile_name ? 'border-primary' : '' ?>">
                                        <div class="card-body">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" 
                                                       class="custom-control-input" 
                                                       id="tone_<?= $profile->id ?>" 
                                                       name="tone_profile" 
                                                       value="<?= htmlspecialchars($profile->profile_name) ?>"
                                                       <?= $prompt->tone_profile === $profile->profile_name ? 'checked' : '' ?>>
                                                <label class="custom-control-label" 
                                                       for="tone_<?= $profile->id ?>">
                                                    <strong><?= htmlspecialchars($profile->display_name) ?></strong>
                                                </label>
                                            </div>
                                            <p class="text-muted small mt-2 mb-2">
                                                <?= htmlspecialchars($profile->description) ?>
                                            </p>
                                            <?php if (!empty($profile->sample_text)): ?>
                                                <div class="small bg-light p-2 rounded">
                                                    <strong>Example:</strong>
                                                    <br>
                                                    <em><?= htmlspecialchars($profile->sample_text) ?></em>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Response Configuration -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-list-check"></i>
                            Response Configuration
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Response Structure -->
                            <div class="col-md-6 mb-3">
                                <label for="response_structure">Response Structure</label>
                                <select class="form-control" id="response_structure" name="response_structure">
                                    <option value="flexible" <?= $prompt->response_structure === 'flexible' ? 'selected' : '' ?>>
                                        Flexible (adapt to context)
                                    </option>
                                    <option value="structured" <?= $prompt->response_structure === 'structured' ? 'selected' : '' ?>>
                                        Structured (organized sections)
                                    </option>
                                    <option value="list" <?= $prompt->response_structure === 'list' ? 'selected' : '' ?>>
                                        List (bullet points/numbered)
                                    </option>
                                    <option value="steps" <?= $prompt->response_structure === 'steps' ? 'selected' : '' ?>>
                                        Steps (sequential instructions)
                                    </option>
                                    <option value="essay" <?= $prompt->response_structure === 'essay' ? 'selected' : '' ?>>
                                        Essay (paragraphs and flow)
                                    </option>
                                </select>
                            </div>
                            
                            <!-- Response Length -->
                            <div class="col-md-6 mb-3">
                                <label for="response_length">Target Response Length</label>
                                <select class="form-control" id="response_length" name="response_length">
                                    <option value="brief" <?= $prompt->response_length === 'brief' ? 'selected' : '' ?>>
                                        Brief (1-2 sentences)
                                    </option>
                                    <option value="medium" <?= $prompt->response_length === 'medium' ? 'selected' : '' ?>>
                                        Medium (2-3 paragraphs)
                                    </option>
                                    <option value="detailed" <?= $prompt->response_length === 'detailed' ? 'selected' : '' ?>>
                                        Detailed (4-6 paragraphs)
                                    </option>
                                    <option value="comprehensive" <?= $prompt->response_length === 'comprehensive' ? 'selected' : '' ?>>
                                        Comprehensive (extensive)
                                    </option>
                                </select>
                            </div>
                            
                            <!-- Thinking Style -->
                            <div class="col-md-6 mb-3">
                                <label for="thinking_style">Thinking Style</label>
                                <select class="form-control" id="thinking_style" name="thinking_style">
                                    <option value="analytical" <?= $prompt->thinking_style === 'analytical' ? 'selected' : '' ?>>
                                        Analytical (data-driven, logical)
                                    </option>
                                    <option value="creative" <?= $prompt->thinking_style === 'creative' ? 'selected' : '' ?>>
                                        Creative (imaginative, original)
                                    </option>
                                    <option value="practical" <?= $prompt->thinking_style === 'practical' ? 'selected' : '' ?>>
                                        Practical (action-oriented)
                                    </option>
                                    <option value="balanced" <?= $prompt->thinking_style === 'balanced' ? 'selected' : '' ?>>
                                        Balanced (mixed approach)
                                    </option>
                                </select>
                            </div>
                            
                            <!-- Context Handling -->
                            <div class="col-md-6 mb-3">
                                <label for="context_handling">Context Memory</label>
                                <select class="form-control" id="context_handling" name="context_handling">
                                    <option value="minimal" <?= $prompt->context_handling === 'minimal' ? 'selected' : '' ?>>
                                        Minimal (current message only)
                                    </option>
                                    <option value="standard" <?= $prompt->context_handling === 'standard' ? 'selected' : '' ?>>
                                        Standard (last 5-10 messages)
                                    </option>
                                    <option value="extensive" <?= $prompt->context_handling === 'extensive' ? 'selected' : '' ?>>
                                        Extensive (full conversation)
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Content Guidelines -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-shield-alt"></i>
                            Content Guidelines
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="content_restrictions">Content Restrictions</label>
                                <textarea class="form-control" 
                                          id="content_restrictions" 
                                          name="content_restrictions" 
                                          rows="4"
                                          placeholder="Specify topics, language, or content types to avoid"><?= htmlspecialchars($prompt->content_restrictions ?? '') ?></textarea>
                                <small class="form-text text-muted">
                                    Define boundaries for appropriate content
                                </small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="quality_requirements">Quality Requirements</label>
                                <textarea class="form-control" 
                                          id="quality_requirements" 
                                          name="quality_requirements" 
                                          rows="4"
                                          placeholder="Specify accuracy, verification, or quality standards"><?= htmlspecialchars($prompt->quality_requirements ?? '') ?></textarea>
                                <small class="form-text text-muted">
                                    Set standards for response quality
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Test Examples -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-vial"></i>
                            Test Examples
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Provide sample inputs and expected outputs for testing the configuration.
                        </p>
                        
                        <div id="test-examples">
                            <?php 
                            $exampleInputs = $prompt->example_inputs ?? [''];
                            $exampleOutputs = $prompt->example_outputs ?? [''];
                            $maxExamples = max(count($exampleInputs), count($exampleOutputs), 1);
                            
                            for ($i = 0; $i < $maxExamples; $i++):
                            ?>
                                <div class="test-example-row mb-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label>Sample Input <?= $i + 1 ?></label>
                                            <textarea class="form-control" 
                                                      name="example_inputs[]" 
                                                      rows="2"
                                                      placeholder="Enter a sample user message"><?= htmlspecialchars($exampleInputs[$i] ?? '') ?></textarea>
                                        </div>
                                        <div class="col-md-5">
                                            <label>Expected Output <?= $i + 1 ?></label>
                                            <textarea class="form-control" 
                                                      name="example_outputs[]" 
                                                      rows="2"
                                                      placeholder="Describe the expected response"><?= htmlspecialchars($exampleOutputs[$i] ?? '') ?></textarea>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <?php if ($i > 0): ?>
                                                <button type="button" class="btn btn-danger btn-sm remove-example">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="add-example">
                            <i class="fas fa-plus"></i> Add Example
                        </button>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i>
                                    Save Configuration
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg" 
                                        onclick="testConfiguration()">
                                    <i class="fas fa-flask"></i>
                                    Test Configuration
                                </button>
                            </div>
                            <div>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>
            
        </div>
    </div>
</div>

<!-- Configuration Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">System Prompt Preview</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <pre id="system-prompt-preview" class="bg-light p-3 rounded"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter for meta instructions
document.getElementById('meta_instructions').addEventListener('input', function() {
    document.getElementById('meta_char_count').textContent = this.value.length;
});

// Initial count
document.getElementById('meta_char_count').textContent = 
    document.getElementById('meta_instructions').value.length;

// Add test example
document.getElementById('add-example').addEventListener('click', function() {
    const container = document.getElementById('test-examples');
    const count = container.querySelectorAll('.test-example-row').length + 1;
    
    const row = document.createElement('div');
    row.className = 'test-example-row mb-3';
    row.innerHTML = `
        <div class="row">
            <div class="col-md-5">
                <label>Sample Input ${count}</label>
                <textarea class="form-control" 
                          name="example_inputs[]" 
                          rows="2"
                          placeholder="Enter a sample user message"></textarea>
            </div>
            <div class="col-md-5">
                <label>Expected Output ${count}</label>
                <textarea class="form-control" 
                          name="example_outputs[]" 
                          rows="2"
                          placeholder="Describe the expected response"></textarea>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-example">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(row);
});

// Remove test example
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-example') || 
        e.target.closest('.remove-example')) {
        const row = e.target.closest('.test-example-row');
        row.remove();
    }
});

// Apply configuration preset
function applyPreset(presetName) {
    if (!confirm('This will overwrite current settings. Continue?')) {
        return;
    }
    
    fetch('ajax-get-preset.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({preset: presetName})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Apply preset values to form
            document.querySelector('[name="interaction_pattern"][value="' + data.config.interaction_pattern + '"]').checked = true;
            document.querySelector('[name="tone_profile"][value="' + data.config.tone_profile + '"]').checked = true;
            document.getElementById('response_structure').value = data.config.response_structure;
            document.getElementById('response_length').value = data.config.response_length;
            document.getElementById('thinking_style').value = data.config.thinking_style;
            
            // Clear and set personality traits
            document.querySelectorAll('[name="personality_traits[]"]').forEach(cb => cb.checked = false);
            data.config.personality_traits.forEach(trait => {
                const checkbox = document.querySelector('[name="personality_traits[]"][value="' + trait + '"]');
                if (checkbox) checkbox.checked = true;
            });
            
            alert('Preset applied successfully. Review and save changes.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to apply preset');
    });
}

// Test configuration
function testConfiguration() {
    const button = event.target;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
    
    fetch('ajax-test-configuration.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            prompt_id: <?= $promptId ?>
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('system-prompt-preview').textContent = 
                data.system_prompt_preview;
            $('#previewModal').modal('show');
        } else {
            alert('Test failed: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Test failed');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-flask"></i> Test Configuration';
    });
}

// Enable tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

// Visual feedback for selection
document.querySelectorAll('.interaction-pattern-card, .tone-profile-card').forEach(card => {
    const radio = card.querySelector('input[type="radio"]');
    radio.addEventListener('change', function() {
        document.querySelectorAll('.' + card.className.split(' ')[0]).forEach(c => {
            c.classList.remove('border-primary');
        });
        if (this.checked) {
            card.classList.add('border-primary');
        }
    });
});
</script>

<style>
.interaction-pattern-card,
.tone-profile-card {
    transition: all 0.2s ease;
    cursor: pointer;
}

.interaction-pattern-card:hover,
.tone-profile-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.interaction-pattern-card.border-primary,
.tone-profile-card.border-primary {
    border-width: 2px !important;
}

#system-prompt-preview {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    max-height: 500px;
    overflow-y: auto;
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>

<?php include("../../inc/footer.php"); ?>
```

### AJAX Helper Files

Create supporting AJAX endpoints:

```php
<?php
/**
 * Get Configuration Preset
 * 
 * File: /admin/modules/prompts/ajax-get-preset.php
 */

require_once("../../inc/includes.php");
require_once("../../inc/restrict.php");

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$presetName = $input['preset'] ?? '';

if (!$presetName) {
    echo json_encode(['success' => false, 'error' => 'No preset specified']);
    exit;
}

$configManager = new PromptConfigurationManager();
$config = $configManager->getConfigurationPreset($presetName);

if ($config) {
    echo json_encode([
        'success' => true,
        'config' => $config
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Preset not found'
    ]);
}
```

```php
<?php
/**
 * Test Configuration
 * 
 * File: /admin/modules/prompts/ajax-test-configuration.php
 */

require_once("../../inc/includes.php");
require_once("../../inc/restrict.php");

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$promptId = $input['prompt_id'] ?? 0;

if (!$promptId) {
    echo json_encode(['success' => false, 'error' => 'No prompt ID provided']);
    exit;
}

try {
    $prompts = new Prompts();
    $result = $prompts->testConfiguration($promptId);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'system_prompt_preview' => $result['system_prompt_preview'],
            'results' => $result['results']
        ]);
    } else {
        echo json_encode($result);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
```

---

## Configuration Templates

### Educational Content Template

Configuration designed for primary school educational content:

```php
$educationalConfig = [
    'meta_instructions' => 'All responses must be suitable for children aged 5-11 in UK Key Stage 1 & 2. Use clear, simple language. Avoid complex terminology unless explained. Maintain an encouraging and supportive tone. Ensure content is age-appropriate and educationally sound.',
    
    'interaction_pattern' => 'instructional',
    
    'personality_traits' => ['patient', 'encouraging', 'friendly', 'methodical'],
    
    'tone_profile' => 'educational',
    
    'response_structure' => 'structured',
    
    'response_length' => 'medium',
    
    'thinking_style' => 'balanced',
    
    'context_handling' => 'standard',
    
    'content_restrictions' => 'No references to violence, inappropriate content, or complex political/social issues. Avoid terminology that requires advanced reading level.',
    
    'quality_requirements' => 'Information must be factually accurate. Examples should be relatable to primary school children. Explanations should build understanding step by step.'
];
```

### Technical Documentation Template

Configuration for generating technical documentation:

```php
$technicalConfig = [
    'meta_instructions' => 'Generate clear, accurate technical documentation. Use precise terminology. Include code examples where relevant. Structure information logically with clear headings and sections.',
    
    'interaction_pattern' => 'structured',
    
    'personality_traits' => ['methodical', 'detailed', 'neutral', 'professional'],
    
    'tone_profile' => 'technical',
    
    'response_structure' => 'structured',
    
    'response_length' => 'detailed',
    
    'thinking_style' => 'analytical',
    
    'context_handling' => 'minimal',
    
    'content_restrictions' => 'Maintain technical accuracy. Avoid speculation without clear indication.',
    
    'quality_requirements' => 'Code examples must be syntactically correct. Technical terms must be used appropriately. Include relevant warnings about potential issues.'
];
```

---

## Integration Examples

### Using Configuration in API Calls

How to apply the configuration when making AI API requests:

```php
<?php
/**
 * Example: Making API call with configuration
 * 
 * File: /php/api.php (modified section)
 */

// Get prompt with configuration
$prompts = new Prompts();
$prompt = $prompts->getWithConfiguration($promptId);

// Build complete system prompt
$systemPrompt = $prompts->buildSystemPrompt($promptId);

// Get AI parameters with configuration adjustments
$aiConfig = [
    'model' => $prompt->API_MODEL,
    'max_tokens' => $prompt->max_tokens,
    'temperature' => $prompt->temperature,
    'frequency_penalty' => $prompt->frequency_penalty,
    'presence_penalty' => $prompt->presence_penalty
];

// Adjust parameters based on thinking style
switch ($prompt->thinking_style) {
    case 'creative':
        $aiConfig['temperature'] = min(1.0, $aiConfig['temperature'] + 0.2);
        break;
    case 'analytical':
        $aiConfig['temperature'] = max(0.3, $aiConfig['temperature'] - 0.2);
        break;
}

// Adjust max_tokens based on response length
$lengthMultipliers = [
    'brief' => 0.5,
    'medium' => 1.0,
    'detailed' => 1.5,
    'comprehensive' => 2.0
];

if (isset($lengthMultipliers[$prompt->response_length])) {
    $aiConfig['max_tokens'] = (int)($aiConfig['max_tokens'] * 
                                     $lengthMultipliers[$prompt->response_length]);
}

// Make API call with enhanced configuration
$messages = [
    ['role' => 'system', 'content' => $systemPrompt],
    ['role' => 'user', 'content' => $userMessage]
];

$response = callOpenAI($apiKey, $messages, $aiConfig);
```

---

## Testing Procedures

### Configuration Testing Checklist

1. **Basic Validation**
   - All required fields are populated
   - JSON fields are valid JSON
   - Foreign key references exist
   - Character limits are respected

2. **Interaction Pattern Testing**
   - Test with sample inputs for each pattern
   - Verify response structure matches pattern
   - Check context handling behavior

3. **Personality Trait Verification**
   - Confirm traits are reflected in responses
   - Test trait combinations
   - Verify no conflicts between selected traits

4. **Tone Profile Assessment**
   - Evaluate language level appropriateness
   - Check consistency across responses
   - Verify sample text matches profile

5. **Response Configuration**
   - Test structure enforcement
   - Verify length targets are met
   - Confirm thinking style is evident

### Automated Testing Script

```php
<?php
/**
 * Configuration Testing Script
 * 
 * Run from command line: php test-configuration.php [prompt_id]
 */

require_once(__DIR__ . '/admin/inc/includes.php');

$promptId = $argv[1] ?? null;

if (!$promptId) {
    echo "Usage: php test-configuration.php [prompt_id]\n";
    exit(1);
}

$prompts = new Prompts();

echo "Testing configuration for prompt ID: $promptId\n";
echo str_repeat('=', 60) . "\n\n";

// Load configuration
$prompt = $prompts->getWithConfiguration($promptId);

if (!$prompt) {
    echo "Error: Prompt not found\n";
    exit(1);
}

// Test 1: Configuration validity
echo "Test 1: Configuration Validation\n";
$validation = $prompts->validateConfiguration((array)$prompt);
if ($validation['valid']) {
    echo "✓ Configuration is valid\n";
} else {
    echo "✗ Configuration errors:\n";
    foreach ($validation['errors'] as $error) {
        echo "  - $error\n";
    }
}
echo "\n";

// Test 2: System prompt generation
echo "Test 2: System Prompt Generation\n";
try {
    $systemPrompt = $prompts->buildSystemPrompt($promptId);
    echo "✓ System prompt generated successfully\n";
    echo "  Length: " . strlen($systemPrompt) . " characters\n";
    echo "  Preview:\n";
    echo "  " . substr($systemPrompt, 0, 200) . "...\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 3: Configuration testing
echo "Test 3: Example Input Testing\n";
$testResult = $prompts->testConfiguration($promptId);
if ($testResult['success']) {
    echo "✓ Configuration test passed\n";
    echo "  Tested " . count($testResult['results']) . " examples\n";
} else {
    echo "✗ Test failed: " . $testResult['error'] . "\n";
}
echo "\n";

echo "Testing complete\n";
```

---

## Summary

This expansion provides:

1. **Database structure** for storing advanced AI configuration
2. **PHP classes** for managing and applying configurations
3. **Admin interface** for intuitive configuration management
4. **Configuration templates** for common use cases
5. **Integration examples** showing how to use configurations
6. **Testing procedures** to ensure configurations work correctly

The system enables administrators to create sophisticated AI interactions without requiring technical knowledge of prompt engineering, while maintaining consistency and quality across the platform.
