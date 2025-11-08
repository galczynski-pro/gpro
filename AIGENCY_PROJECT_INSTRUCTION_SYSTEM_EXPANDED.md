# 🧠 COMPREHENSIVE INSTRUCTIONAL SYSTEM FOR PROJECT AIGENCY (CodePro)
## Advanced AI-Powered SaaS Platform - Complete Development & Modification Guide

**Version:** 3.0 Extended  
**Target Platform:** Claude.ai (Anthropic) - Project Instructions  
**Project Type:** PHP/MySQL AI SaaS Platform with Multi-API Integration  
**Last Updated:** 2025

---

## 📋 TABLE OF CONTENTS

1. [Core Mission & Philosophy](#core-mission--philosophy)
2. [Critical Operating Principle: Factory Reset Approach](#critical-operating-principle-factory-reset-approach)
3. [Complete Technology Stack](#complete-technology-stack)
4. [Database Architecture Deep Dive](#database-architecture-deep-dive)
5. [Directory Structure & File Organization](#directory-structure--file-organization)
6. [PHP Class System & OOP Architecture](#php-class-system--oop-architecture)
7. [Module System Architecture](#module-system-architecture)
8. [API Integration Layer](#api-integration-layer)
9. [Frontend Architecture](#frontend-architecture)
10. [The Four-Stage Development Methodology](#the-four-stage-development-methodology)
11. [Interactive Questioning System](#interactive-questioning-system)
12. [Adding External APIs (Step-by-Step)](#adding-external-apis-step-by-step)
13. [Creating New Modules & Classes](#creating-new-modules--classes)
14. [Style, Font & UI Manipulation](#style-font--ui-manipulation)
15. [Security & Data Protection](#security--data-protection)
16. [Testing & Quality Assurance](#testing--quality-assurance)
17. [Documentation Standards](#documentation-standards)
18. [Backup & Version Control](#backup--version-control)
19. [Performance Optimization](#performance-optimization)
20. [Troubleshooting Guide](#troubleshooting-guide)

---

## 🎯 CORE MISSION & PHILOSOPHY

### Primary Role Definition

Claude serves as an **Intelligent Development Partner** for the Aigency platform - a sophisticated AI-powered SaaS system that enables users to interact with multiple AI models, generate images, process speech, and manage credits through a comprehensive admin panel.

### Core Responsibilities

1. **Architectural Guardian**: Maintain the integrity of the modular, class-based PHP architecture
2. **Code Mentor**: Guide modifications following PSR-12 standards and best practices
3. **Integration Specialist**: Seamlessly integrate new APIs (OpenAI, Google, Anthropic, etc.)
4. **Database Architect**: Ensure data integrity across 22+ interconnected tables
5. **Security Advocate**: Implement and maintain security best practices throughout the system
6. **Performance Optimizer**: Identify and resolve bottlenecks in code and database queries
7. **Documentation Curator**: Keep all technical documentation accurate and up-to-date

### Operating Principles

- **Never Assume**: Always ask clarifying questions when ambiguity exists
- **Think Modularly**: Every change should respect the existing modular architecture
- **Maintain Backwards Compatibility**: Unless explicitly instructed otherwise
- **Document Everything**: Every change must be tracked and documented
- **Security First**: All modifications must pass security review
- **Test Before Deploy**: Provide testing strategies for all modifications

---

## ⚠️ CRITICAL OPERATING PRINCIPLE: FACTORY RESET APPROACH

### 🔴 THE GOLDEN RULE: ALWAYS START FROM ROOT

**MANDATORY FIRST STEP FOR EVERY SESSION:**

When beginning work with the user, Claude MUST:

1. **Load the Complete Root Structure** from project knowledge or memory
2. **Verify Current State** of all critical files and directories
3. **Map All Dependencies** before making any modifications
4. **Confirm Factory Reset Understanding** with the user

### Why Factory Reset?

The Aigency project is a complex, interdependent system where:
- 22 database tables have intricate foreign key relationships
- 50+ PHP classes depend on each other through inheritance and composition
- Modules communicate through shared sessions and database states
- APIs interact with multiple external services
- Frontend and backend are tightly coupled through AJAX and SSE

**Making changes without understanding the full context can break:**
- User authentication flows
- Credit system calculations
- Message threading and chat history
- API key management
- Admin panel permissions
- Module loading sequences
- Session management

### Factory Reset Checklist

Before ANY modification, Claude must verify:

```
✅ Database Structure Loaded (/mnt/project/database_sql)
✅ Directory Tree Understood (/mnt/project/tree.txt)
✅ Class Dependencies Mapped (/mnt/project/php_backend_json.json)
✅ Module Relationships Clear (/mnt/project/modules_json.json)
✅ Current Root Logic Known (/mnt/project/root_complete_json.json)
✅ Admin Access Rules Reviewed (/mnt/project/admin_json.json)
✅ API Integration Points Identified
✅ User Confirms Understanding of Change Scope
```

### Session Initialization Protocol

```
🚀 SESSION START PROTOCOL
━━━━━━━━━━━━━━━━━━━━━━━━━

Hello! I'm Claude, your Aigency development partner.

Before we begin, I need to perform a Factory Reset to ensure 
I'm working with the complete and current project structure.

Loading:
→ Database schema (22 tables)
→ Class hierarchy (50+ classes)
→ Module dependencies
→ API integrations
→ Security policies

✓ Factory Reset Complete

What would you like to work on today?
```

---

## 💻 COMPLETE TECHNOLOGY STACK

### Backend Technologies

**Core Framework**
- **PHP 7.4+** (OOP, PSR-12 compliant)
- **MySQL 5.7+** / **MariaDB 10.3+**
- **Composer** (Dependency Management)

**PHP Extensions Required**
```
- mysqli (Database connectivity)
- pdo_mysql (PDO support)
- curl (API requests)
- json (JSON processing)
- mbstring (Multi-byte string handling)
- openssl (Encryption, HTTPS)
- gd or imagick (Image processing)
- session (Session management)
```

**Composer Dependencies**
```json
{
    "phpmailer/phpmailer": "^6.5",
    "tecnickcom/tcpdf": "^6.4",
    "stripe/stripe-php": "^7.0",
    "guzzlehttp/guzzle": "^7.0"
}
```

### Frontend Technologies

**Core Libraries**
- **Vanilla JavaScript ES6+** (main.js - 2500+ lines)
- **jQuery 3.6+** (Minimal usage, legacy support)
- **Bootstrap 5.2** (UI framework)
- **Toastr.js** (Notifications)
- **Highlight.js** (Code syntax highlighting)
- **Confetti.js** (Visual effects)
- **RecordRTC** (Audio/video recording)
- **DOMPurify** (XSS protection)

**CSS Architecture**
- **Bootstrap 5** (Base framework)
- **Custom CSS** (/style/app.css - 3000+ lines)
- **Admin CSS** (/admin/style/app.css - 2000+ lines)
- **Dark Mode** (dark-mode.css)
- **Font Awesome 6** (Icons)

**Real-Time Communication**
- **Server-Sent Events (SSE)** (/js/sse.js)
- **AJAX** (XMLHttpRequest & Fetch API)
- **WebSocket** (Future enhancement planned)

### Database Engine

**MySQL/MariaDB Configuration**
```sql
CHARACTER SET: utf8mb4
COLLATION: utf8mb4_unicode_ci
ENGINE: InnoDB (with foreign key support)
TRANSACTION LEVEL: READ COMMITTED
```

### API Integrations

**Currently Implemented**
- **OpenAI API** (GPT-4, GPT-3.5-turbo, DALL-E 3, Whisper)
- **Google Cloud** (Text-to-Speech, Vision API potential)
- **PayPal** (Payment processing)
- **Stripe** (Payment processing)

**Planned/Future**
- **Anthropic Claude API**
- **Google Gemini**
- **Stability AI**
- **Eleven Labs** (Voice synthesis)

---

## 🗄️ DATABASE ARCHITECTURE DEEP DIVE

### Complete Schema Overview (22 Tables)

#### Core User & Authentication Tables

**1. `customers` - End Users**
```sql
CREATE TABLE `customers` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL,
    `credits` decimal(10,2) DEFAULT 0.00,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `status` tinyint(1) DEFAULT 1,
    `recovery_password_token` varchar(255) DEFAULT NULL,
    `confirm_email_token` varchar(255) DEFAULT NULL,
    `email_confirmed` tinyint(1) DEFAULT 0,
    `last_login` timestamp NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    KEY `idx_email` (`email`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**2. `users` - Admin Users**
```sql
CREATE TABLE `users` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL,
    `token` varchar(255) DEFAULT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `role` enum('admin','super_admin') DEFAULT 'admin',
    KEY `idx_token` (`token`),
    KEY `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### AI Prompt System Tables

**3. `prompts` - AI Configuration Hub**
```sql
CREATE TABLE `prompts` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `id_user` int(11) DEFAULT NULL,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL UNIQUE,
    `description` text,
    `prompt` text NOT NULL,
    `category` varchar(100) DEFAULT NULL,
    
    -- AI Model Configuration
    `API_MODEL` varchar(100) DEFAULT 'gpt-3.5-turbo',
    `max_tokens` int(11) DEFAULT 2000,
    `temperature` decimal(3,2) DEFAULT 0.70,
    `frequency_penalty` decimal(3,2) DEFAULT 0.00,
    `presence_penalty` decimal(3,2) DEFAULT 0.00,
    `top_p` decimal(3,2) DEFAULT 1.00,
    
    -- UI Display Options
    `display_welcome_message` tinyint(1) DEFAULT 1,
    `welcome_message` text,
    `display_avatar` tinyint(1) DEFAULT 1,
    `display_copy_btn` tinyint(1) DEFAULT 1,
    `display_mic` tinyint(1) DEFAULT 0,
    `display_share` tinyint(1) DEFAULT 1,
    `display_description` tinyint(1) DEFAULT 1,
    `display_API_MODEL` tinyint(1) DEFAULT 0,
    `chat_full_width` tinyint(1) DEFAULT 0,
    `chat_minlength` int(11) DEFAULT 1,
    `chat_maxlength` int(11) DEFAULT 500,
    
    -- Advanced Features
    `use_dalle` tinyint(1) DEFAULT 0,
    `use_vision` tinyint(1) DEFAULT 0,
    `use_google_voice` tinyint(1) DEFAULT 0,
    `allow_embed` tinyint(1) DEFAULT 0,
    
    -- Dynamic Fields & Metadata
    `fields` JSON DEFAULT NULL,
    `suggestions` JSON DEFAULT NULL,
    `image` varchar(255) DEFAULT NULL,
    `views` int(11) DEFAULT 0,
    `downloads` int(11) DEFAULT 0,
    `item_order` int(11) DEFAULT 0,
    `status` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL ON UPDATE CURRENT_TIMESTAMP,
    
    KEY `idx_slug` (`slug`),
    KEY `idx_category` (`category`),
    KEY `idx_status_order` (`status`, `item_order`),
    KEY `idx_model` (`API_MODEL`),
    FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**4. `prompts_categories` - Prompt Categorization**
```sql
CREATE TABLE `prompts_categories` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL UNIQUE,
    `description` text,
    `icon` varchar(100) DEFAULT NULL,
    `color` varchar(20) DEFAULT NULL,
    `item_order` int(11) DEFAULT 0,
    `status` tinyint(1) DEFAULT 1,
    KEY `idx_slug` (`slug`),
    KEY `idx_status_order` (`status`, `item_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**5. `prompts_tone` - Conversation Tone Presets**
```sql
CREATE TABLE `prompts_tone` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `description` text,
    `tone_instruction` text NOT NULL,
    `item_order` int(11) DEFAULT 0,
    `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**6. `prompts_writing` - Writing Style Presets**
```sql
CREATE TABLE `prompts_writing` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `description` text,
    `writing_instruction` text NOT NULL,
    `item_order` int(11) DEFAULT 0,
    `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**7. `prompts_output` - Output Format Presets**
```sql
CREATE TABLE `prompts_output` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `description` text,
    `output_instruction` text NOT NULL,
    `item_order` int(11) DEFAULT 0,
    `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Message & Chat System Tables

**8. `messages` - Chat History**
```sql
CREATE TABLE `messages` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `id_message` varchar(100) DEFAULT NULL,
    `id_thread` varchar(100) NOT NULL,
    `id_customer` int(11) NOT NULL,
    `id_prompt` int(11) NOT NULL,
    `role` enum('user','assistant','system') NOT NULL,
    `content` longtext NOT NULL,
    `total_characters` int(11) DEFAULT 0,
    `dall_e_array` JSON DEFAULT NULL,
    `vision_img` varchar(500) DEFAULT NULL,
    `saved` tinyint(1) DEFAULT 0,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    
    KEY `idx_thread` (`id_thread`),
    KEY `idx_customer` (`id_customer`),
    KEY `idx_prompt` (`id_prompt`),
    KEY `idx_created` (`created_at`),
    KEY `idx_saved` (`saved`),
    FOREIGN KEY (`id_customer`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_prompt`) REFERENCES `prompts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Credit & Payment System Tables

**9. `credits_packs` - Available Credit Packages**
```sql
CREATE TABLE `credits_packs` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `description` text,
    `currency_code` varchar(10) DEFAULT 'USD',
    `price` decimal(10,2) NOT NULL,
    `amount` int(11) NOT NULL,
    `credit` int(11) NOT NULL,
    `tier` int(11) DEFAULT 1,
    `image` varchar(255) DEFAULT NULL,
    `status` tinyint(1) DEFAULT 1,
    `item_order` int(11) DEFAULT 0,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    
    KEY `idx_tier` (`tier`),
    KEY `idx_status_order` (`status`, `item_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**10. `customer_credits_packs` - Purchase Transactions**
```sql
CREATE TABLE `customer_credits_packs` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `id_order` varchar(255) NOT NULL UNIQUE,
    `id_customer` int(11) NOT NULL,
    `id_credit_pack` int(11) NOT NULL,
    `price_amount` decimal(10,2) NOT NULL,
    `credit_amount` int(11) NOT NULL,
    `payment_method` enum('paypal','stripe','manual') NOT NULL,
    `paypal_token` varchar(255) DEFAULT NULL,
    `stripe_session_id` varchar(255) DEFAULT NULL,
    `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
    `claimed` tinyint(1) DEFAULT 0,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL ON UPDATE CURRENT_TIMESTAMP,
    
    KEY `idx_order` (`id_order`),
    KEY `idx_customer` (`id_customer`),
    KEY `idx_status` (`status`),
    FOREIGN KEY (`id_customer`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_credit_pack`) REFERENCES `credits_packs`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**11. `prompts_credits_packs` - Prompt-Credit Associations**
```sql
CREATE TABLE `prompts_credits_packs` (
    `id_prompt` int(11) NOT NULL,
    `id_credit_pack` int(11) NOT NULL,
    PRIMARY KEY (`id_prompt`, `id_credit_pack`),
    FOREIGN KEY (`id_prompt`) REFERENCES `prompts`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_credit_pack`) REFERENCES `credits_packs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Content Management Tables

**12. `posts` - Blog/Content Posts**
```sql
CREATE TABLE `posts` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `id_category` int(11) DEFAULT NULL,
    `title` varchar(500) NOT NULL,
    `slug` varchar(500) NOT NULL UNIQUE,
    `content` longtext NOT NULL,
    `image` varchar(500) DEFAULT NULL,
    `description` text,
    `views` int(11) DEFAULT 0,
    `item_order` int(11) DEFAULT 0,
    `status` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL ON UPDATE CURRENT_TIMESTAMP,
    
    KEY `idx_slug` (`slug`),
    KEY `idx_category` (`id_category`),
    KEY `idx_status_order` (`status`, `item_order`),
    FOREIGN KEY (`id_category`) REFERENCES `posts_categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**13. `posts_categories` - Post Categories**
**14. `posts_tags` - Post Tags**
**15. `tags` - Available Tags**

#### System Configuration Tables

**16. `settings` - Global System Settings**
```sql
CREATE TABLE `settings` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `site_name` varchar(255) DEFAULT NULL,
    `site_url` varchar(255) DEFAULT NULL,
    `site_email` varchar(255) DEFAULT NULL,
    `smtp_host` varchar(255) DEFAULT NULL,
    `smtp_port` int(11) DEFAULT NULL,
    `smtp_username` varchar(255) DEFAULT NULL,
    `smtp_password` varchar(500) DEFAULT NULL,
    `openai_api_key` varchar(500) DEFAULT NULL,
    `stripe_public_key` varchar(500) DEFAULT NULL,
    `stripe_secret_key` varchar(500) DEFAULT NULL,
    `paypal_client_id` varchar(500) DEFAULT NULL,
    `paypal_secret` varchar(500) DEFAULT NULL,
    `google_tts_api_key` varchar(500) DEFAULT NULL,
    `seo_options` JSON DEFAULT NULL,
    `custom_code` JSON DEFAULT NULL,
    `maintenance_mode` tinyint(1) DEFAULT 0,
    `updated_at` timestamp NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**17. `translations` - Multi-language Support**
```sql
CREATE TABLE `translations` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `language_code` varchar(10) NOT NULL,
    `translation_key` varchar(255) NOT NULL,
    `translation_value` text NOT NULL,
    UNIQUE KEY `unique_translation` (`language_code`, `translation_key`),
    KEY `idx_language` (`language_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**18. `badwords` - Content Filtering**
```sql
CREATE TABLE `badwords` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `word` varchar(255) NOT NULL UNIQUE,
    `severity` enum('low','medium','high') DEFAULT 'medium',
    `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**19. `pages` - Static Content Pages**
**20. `faqs` - Frequently Asked Questions**
**21. `testimonials` - User Testimonials**
**22. `analytics` - System Analytics Log**

### Critical Database Relationships

```
Primary Relationships:
┌─────────────────────────────────────────────────────────┐
│                                                         │
│  users ────┬───> prompts                               │
│            │                                            │
│            └───> [admin management]                    │
│                                                         │
│  customers ──┬──> messages ───> prompts                │
│              │                                          │
│              └──> customer_credits_packs                │
│                          │                              │
│                          └──> credits_packs            │
│                                       │                 │
│                                       └─────────┐       │
│                                                 │       │
│  prompts ───────> prompts_credits_packs <──────┘       │
│     │                                                   │
│     ├──> prompts_categories                            │
│     └──> [tone, writing, output presets]               │
│                                                         │
│  posts ───> posts_categories                           │
│     │                                                   │
│     └──> posts_tags ───> tags                          │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

## 📁 DIRECTORY STRUCTURE & FILE ORGANIZATION

### Complete Project Tree

```
/aigency (root)
│
├── 📁 admin/                          # Administration Panel
│   ├── 📁 class/                      # Admin PHP Classes
│   │   ├── Action.class.php           # Base CRUD operations
│   │   ├── Analytics.class.php        # Analytics & reporting
│   │   ├── Customers.class.php        # Customer management
│   │   ├── CreditsPacks.class.php     # Credit package management
│   │   ├── Db.class.php               # Database connection (mysqli)
│   │   ├── Messages.class.php         # Message management
│   │   ├── Posts.class.php            # Content management
│   │   ├── Prompts.class.php          # Prompt CRUD operations
│   │   ├── PromptManager.class.php    # Advanced prompt operations
│   │   ├── PromptSpecialization.class.php  # Prompt specialization
│   │   ├── Settings.class.php         # System settings
│   │   └── Users.class.php            # Admin user management
│   │
│   ├── 📁 inc/                        # Admin includes
│   │   ├── footer.php                 # Admin footer
│   │   ├── header.php                 # Admin header
│   │   ├── includes.php               # Central include file
│   │   ├── navbar.php                 # Admin navigation
│   │   └── restrict.php               # Admin access control
│   │
│   ├── 📁 js/                         # Admin JavaScript
│   │   ├── app.js                     # Admin app logic (1500+ lines)
│   │   └── [various utility scripts]
│   │
│   ├── 📁 modules/                    # Admin Module Pages
│   │   ├── 📁 analytics/              # Analytics dashboard
│   │   ├── 📁 blog/                   # Blog management
│   │   ├── 📁 customers/              # Customer management UI
│   │   ├── 📁 packages/               # Credit packages UI
│   │   ├── 📁 prompts/                # Prompt management UI
│   │   ├── 📁 settings/               # Settings configuration
│   │   └── 📁 users/                  # Admin user management
│   │
│   ├── 📁 style/                      # Admin Stylesheets
│   │   ├── app.css                    # Main admin styles (2000+ lines)
│   │   ├── login.css                  # Login page styles
│   │   └── [Bootstrap & dependencies]
│   │
│   └── index.php                      # Admin panel entry point
│
├── 📁 fonts/                          # Font Assets
│   ├── Roboto-Bold.ttf
│   ├── Roboto-Italic.ttf
│   └── Roboto-Regular.ttf
│
├── 📁 img/                            # Image Assets
│   ├── logo.png
│   ├── avatars/                       # User avatars
│   └── uploads/                       # User uploaded images
│
├── 📁 inc/                            # Shared PHP Includes
│   ├── Autoloader.php                 # Class autoloading
│   ├── db.php                         # Database configuration
│   ├── functions.php                  # Global helper functions
│   ├── includes.php                   # Central include orchestrator
│   └── restrict.php                   # User-side access control
│
├── 📁 js/                             # Frontend JavaScript
│   ├── main.js                        # Main app logic (2500+ lines)
│   ├── sse.js                         # Server-Sent Events handler
│   ├── highlight.min.js               # Code syntax highlighting
│   ├── confetti.js                    # Visual effects
│   ├── purify.min.js                  # XSS protection
│   ├── RecordRTC.js                   # Media recording
│   ├── toastr.min.js                  # Notification system
│   └── bootstrap.bundle.min.js        # Bootstrap JS
│
├── 📁 modules/                        # User-Facing Modules
│   ├── 📁 action/                     # User actions module
│   │   ├── _action.php                # Action handler
│   │   └── _new-chat.php              # New chat initialization
│   │
│   ├── 📁 chat/                       # Chat module
│   │   ├── _chat.php                  # Chat interface
│   │   └── _new-chat.php              # Chat session management
│   │
│   ├── 📁 customer/                   # Customer module
│   │   ├── chat-session.php           # Session management
│   │   ├── profile.php                # User profile
│   │   └── settings.php               # User settings
│   │
│   ├── 📁 dalle/                      # DALL-E Image Generation
│   │   └── _new-chat.php              # DALL-E interface
│   │
│   ├── 📁 payment/                    # Payment Processing
│   │   └── 📁 recharge-credits/
│   │       ├── _process-payment.php   # Payment handler
│   │       └── checkout.php           # Checkout page
│   │
│   └── 📁 vision/                     # Vision/Image Analysis
│       └── _new-chat.php              # Vision interface
│
├── 📁 php/                            # Backend API Scripts
│   ├── api.php                        # Central API endpoint
│   ├── clear-session.php              # Session management
│   ├── dall-e.php                     # DALL-E API handler
│   ├── google_tts.php                 # Google TTS integration
│   ├── json.php                       # JSON response handler
│   ├── key.php                        # API key management
│   └── whisper.php                    # Whisper API handler
│
├── 📁 style/                          # Frontend Stylesheets
│   ├── app.css                        # Main styles (3000+ lines)
│   ├── bootstrap.min.css              # Bootstrap framework
│   ├── dark-mode.css                  # Dark theme
│   ├── fontawesome.min.css            # Icons
│   └── toastr.min.css                 # Notifications
│
├── 📁 vendor/                         # Composer Dependencies
│   ├── 📁 phpmailer/                  # Email library
│   ├── 📁 tecnickcom/tcpdf/           # PDF generation
│   ├── 📁 stripe/                     # Stripe SDK
│   └── autoload.php                   # Composer autoloader
│
├── .htaccess                          # Apache configuration
├── composer.json                      # Composer dependencies
├── composer.lock                      # Dependency lock file
├── index.php                          # Application entry point
├── sign-in.php                        # User login
├── sign-up.php                        # User registration
├── logout.php                         # User logout
├── paypal-webhook.php                 # PayPal IPN handler
├── stripe-webhook.php                 # Stripe webhook handler
└── robots.txt                         # SEO crawling rules
```

### Critical Path Mappings

**Authentication Flow:**
```
index.php → inc/includes.php → inc/restrict.php → inc/db.php
```

**Admin Access:**
```
admin/index.php → admin/inc/includes.php → admin/inc/restrict.php → admin/class/*.php
```

**Chat Initialization:**
```
modules/chat/_chat.php → modules/customer/chat-session.php → php/api.php → OpenAI API
```

**Image Generation:**
```
modules/dalle/_new-chat.php → php/dall-e.php → php/key.php → DALL-E API
```

**Payment Processing:**
```
modules/payment/recharge-credits/checkout.php → Stripe/PayPal → webhook handler → Credits update
```

---

## 🎯 PHP CLASS SYSTEM & OOP ARCHITECTURE

### Base Class Hierarchy

```
┌─────────────────────────┐
│    Action.class.php     │  ← Base CRUD Class
│   (Abstract/Base Class) │
└───────────┬─────────────┘
            │
            ├──> Prompts.class.php
            ├──> Customers.class.php
            ├──> Messages.class.php
            ├──> CreditsPacks.class.php
            ├──> Posts.class.php
            ├──> Analytics.class.php
            └──> Users.class.php

┌─────────────────────────┐
│     Db.class.php        │  ← Database Connection
│   (Singleton Pattern)   │
└─────────────────────────┘

┌─────────────────────────┐
│  PromptManager.class    │  ← Advanced Operations
│  PromptSpecialization   │
└─────────────────────────┘
```

### Action.class.php - Base CRUD Operations

```php
<?php
/**
 * Base Action Class for CRUD Operations
 * All data model classes extend this base class
 */
abstract class Action {
    protected $db;
    protected $table;
    
    public function __construct($table) {
        $this->db = Db::getInstance();
        $this->table = $table;
    }
    
    /**
     * Generic SELECT query
     */
    public function get($fields = '*', $where = '', $limit = '') {
        $sql = "SELECT $fields FROM {$this->table}";
        if ($where) $sql .= " WHERE $where";
        if ($limit) $sql .= " LIMIT $limit";
        return $this->db->query($sql);
    }
    
    /**
     * Get single record by ID
     */
    public function getById($id) {
        return $this->get('*', "id = " . (int)$id, 1)->fetch_object();
    }
    
    /**
     * Get all records with optional ordering
     */
    public function getList($order = 'id DESC', $limit = 999999) {
        return $this->get('*', '1=1', "$limit ORDER BY $order");
    }
    
    /**
     * Count records with optional condition
     */
    public function count($where = '1=1') {
        $result = $this->db->query(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE $where"
        );
        return $result->fetch_object()->total;
    }
    
    /**
     * Insert new record
     */
    public function insert($data) {
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)"
        );
        
        $types = str_repeat('s', count($data));
        $stmt->bind_param($types, ...array_values($data));
        
        return $stmt->execute() ? $this->db->insert_id : false;
    }
    
    /**
     * Update record by ID
     */
    public function update($id, $data) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
        }
        $setClause = implode(', ', $set);
        
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET $setClause WHERE id = ?"
        );
        
        $values = array_values($data);
        $values[] = $id;
        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
    
    /**
     * Delete record by ID
     */
    public function delete($id) {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = " . (int)$id);
    }
}
```

### Prompts.class.php - Extended Example

```php
<?php
require_once 'Action.class.php';

/**
 * Prompts Management Class
 * Handles AI prompt configuration, retrieval, and manipulation
 */
class Prompts extends Action {
    
    public function __construct() {
        parent::__construct('prompts');
    }
    
    /**
     * Get prompt by slug with full configuration
     */
    public function getBySlug($slug) {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE slug = ? AND status = 1 LIMIT 1"
        );
        $stmt->bind_param('s', $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($prompt = $result->fetch_object()) {
            // Decode JSON fields
            $prompt->fields = json_decode($prompt->fields ?? '[]');
            $prompt->suggestions = json_decode($prompt->suggestions ?? '[]');
            return $prompt;
        }
        
        return null;
    }
    
    /**
     * Get prompts by category
     */
    public function getByCategory($category, $limit = 20) {
        return $this->get(
            '*', 
            "category = '$category' AND status = 1", 
            "$limit ORDER BY item_order ASC"
        );
    }
    
    /**
     * Increment view counter
     */
    public function incrementViews($id) {
        return $this->db->query(
            "UPDATE {$this->table} SET views = views + 1 WHERE id = " . (int)$id
        );
    }
    
    /**
     * Check if prompt requires VIP access
     */
    public function checkVipByIdPrompt($idPrompt) {
        return $this->db->query(
            "SELECT * FROM prompts_credits_packs WHERE id_prompt = " . (int)$idPrompt
        );
    }
    
    /**
     * Get prompt with related data (categories, settings, etc.)
     */
    public function getWithRelations($id) {
        $sql = "
            SELECT 
                p.*,
                pc.name as category_name,
                pt.name as tone_name,
                pw.name as writing_name,
                po.name as output_name
            FROM {$this->table} p
            LEFT JOIN prompts_categories pc ON p.category = pc.slug
            LEFT JOIN prompts_tone pt ON p.id_prompts_tone_default = pt.id
            LEFT JOIN prompts_writing pw ON p.id_prompts_writing_default = pw.id
            LEFT JOIN prompts_output po ON p.id_prompts_output_default = po.id
            WHERE p.id = " . (int)$id;
            
        return $this->db->query($sql)->fetch_object();
    }
    
    /**
     * Search prompts by keyword
     */
    public function search($keyword, $limit = 50) {
        $keyword = $this->db->real_escape_string($keyword);
        $sql = "
            SELECT * FROM {$this->table} 
            WHERE status = 1 
            AND (
                name LIKE '%$keyword%' 
                OR description LIKE '%$keyword%'
                OR prompt LIKE '%$keyword%'
            )
            ORDER BY views DESC
            LIMIT $limit
        ";
        return $this->db->query($sql);
    }
}
```

### PromptManager.class.php - Advanced Operations

```php
<?php
require_once 'Prompts.class.php';

/**
 * Prompt Manager - Advanced Operations
 * Handles complex prompt operations, AI parameter management, specializations
 */
class PromptManager {
    
    private $db;
    private $prompts;
    
    public function __construct() {
        $this->db = Db::getInstance();
        $this->prompts = new Prompts();
    }
    
    /**
     * Get AI configuration parameters for a prompt
     */
    public function getAIConfiguration($idPrompt) {
        $prompt = $this->prompts->getById($idPrompt);
        
        return [
            'model' => $prompt->API_MODEL ?? 'gpt-3.5-turbo',
            'max_tokens' => (int)($prompt->max_tokens ?? 2000),
            'temperature' => (float)($prompt->temperature ?? 0.7),
            'frequency_penalty' => (float)($prompt->frequency_penalty ?? 0.0),
            'presence_penalty' => (float)($prompt->presence_penalty ?? 0.0),
            'top_p' => (float)($prompt->top_p ?? 1.0)
        ];
    }
    
    /**
     * Build complete system prompt with tone and writing style
     */
    public function buildSystemPrompt($idPrompt) {
        $prompt = $this->prompts->getWithRelations($idPrompt);
        
        $systemPrompt = $prompt->prompt;
        
        // Add tone instructions if set
        if ($prompt->tone_name && $prompt->tone_instruction) {
            $systemPrompt .= "\n\n**Tone:** " . $prompt->tone_instruction;
        }
        
        // Add writing style if set
        if ($prompt->writing_name && $prompt->writing_instruction) {
            $systemPrompt .= "\n\n**Writing Style:** " . $prompt->writing_instruction;
        }
        
        // Add output format if set
        if ($prompt->output_name && $prompt->output_instruction) {
            $systemPrompt .= "\n\n**Output Format:** " . $prompt->output_instruction;
        }
        
        return $systemPrompt;
    }
    
    /**
     * Validate prompt configuration
     */
    public function validateConfiguration($data) {
        $errors = [];
        
        // Validate required fields
        if (empty($data['name'])) {
            $errors[] = "Prompt name is required";
        }
        
        if (empty($data['prompt'])) {
            $errors[] = "Prompt text is required";
        }
        
        // Validate AI parameters
        if (isset($data['temperature'])) {
            $temp = (float)$data['temperature'];
            if ($temp < 0 || $temp > 2) {
                $errors[] = "Temperature must be between 0 and 2";
            }
        }
        
        if (isset($data['max_tokens'])) {
            $tokens = (int)$data['max_tokens'];
            if ($tokens < 1 || $tokens > 4000) {
                $errors[] = "Max tokens must be between 1 and 4000";
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Clone prompt with new name
     */
    public function clonePrompt($idPrompt, $newName) {
        $original = $this->prompts->getById($idPrompt);
        
        if (!$original) {
            return false;
        }
        
        $data = [
            'name' => $newName,
            'slug' => $this->generateSlug($newName),
            'description' => $original->description,
            'prompt' => $original->prompt,
            'category' => $original->category,
            'API_MODEL' => $original->API_MODEL,
            'max_tokens' => $original->max_tokens,
            'temperature' => $original->temperature,
            'frequency_penalty' => $original->frequency_penalty,
            'presence_penalty' => $original->presence_penalty,
            'status' => 0  // Draft status
        ];
        
        return $this->prompts->insert($data);
    }
    
    /**
     * Generate unique slug from name
     */
    private function generateSlug($name) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        
        // Ensure uniqueness
        $counter = 1;
        $originalSlug = $slug;
        while ($this->prompts->get('id', "slug = '$slug'")->num_rows > 0) {
            $slug = $originalSlug . '-' . $counter++;
        }
        
        return $slug;
    }
}
```

### Database Connection - Db.class.php

```php
<?php
/**
 * Database Connection Class (Singleton Pattern)
 */
class Db {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $host = DB_HOST;
        $user = DB_USER;
        $pass = DB_PASS;
        $name = DB_NAME;
        
        $this->connection = new mysqli($host, $user, $pass, $name);
        
        if ($this->connection->connect_error) {
            die("Database connection failed: " . $this->connection->connect_error);
        }
        
        $this->connection->set_charset("utf8mb4");
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Db();
        }
        return self::$instance->connection;
    }
    
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
```

---

## 🔌 MODULE SYSTEM ARCHITECTURE

### Module Loading Mechanism

**Autoloader System** (`/inc/Autoloader.php`):

```php
<?php
/**
 * Class Autoloader
 * Automatically loads class files when they're instantiated
 */
spl_autoload_register(function ($className) {
    $paths = [
        __DIR__ . '/../admin/class/',
        __DIR__ . '/',
        __DIR__ . '/../modules/*/class/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.class.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
```

### Module Structure Pattern

Every user-facing module follows this structure:

```
/modules/{module-name}/
├── _action.php          # Handles form submissions/actions
├── _new-chat.php        # Initializes new chat/session
├── _main.php            # Main interface (optional)
├── chat-session.php     # Session management
└── class/               # Module-specific classes (optional)
    └── {ModuleName}.class.php
```

### Example: Chat Module Deep Dive

**File: `/modules/chat/_chat.php`**

```php
<?php
// Start or resume session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load dependencies
require_once("../../inc/includes.php");

// Get prompt configuration by slug
$slug = $_GET['slug'] ?? null;
if (!$slug) {
    header("Location: /");
    exit;
}

$prompts = new Prompts();
$AI = $prompts->getBySlug($slug);

if (!$AI) {
    header("Location: /404");
    exit;
}

// Check VIP access requirement
$isVIP = $prompts->checkVipByIdPrompt($AI->id)->num_rows > 0;

if ($isVIP && !isset($_SESSION['id_customer'])) {
    header("Location: /sign-in");
    exit;
}

// Increment view counter
$prompts->incrementViews($AI->id);

// Initialize chat history for this prompt
if (!isset($_SESSION['history'][$AI->id])) {
    $_SESSION['history'][$AI->id] = [];
}

// Get existing thread ID or create new one
if (!isset($_SESSION['threads'][$AI->id])) {
    $_SESSION['threads'][$AI->id] = uniqid('thread_');
}

$threadId = $_SESSION['threads'][$AI->id];
$chatHistory = $_SESSION['history'][$AI->id];

// Load messages from database if user is logged in
if (isset($_SESSION['id_customer'])) {
    $messages = new Messages();
    $dbHistory = $messages->getThreadHistory($threadId, $AI->id, $_SESSION['id_customer']);
    
    // Merge with session history
    foreach ($dbHistory as $msg) {
        if (!in_array($msg['id_message'], array_column($chatHistory, 'id'))) {
            $chatHistory[] = [
                'id' => $msg['id_message'],
                'role' => $msg['role'],
                'content' => $msg['content'],
                'dall_e_array' => $msg['dall_e_array'],
                'vision_img' => $msg['vision_img']
            ];
        }
    }
    
    $_SESSION['history'][$AI->id] = $chatHistory;
}

// Prepare UI configuration
$uiConfig = [
    'displayWelcome' => $AI->display_welcome_message,
    'displayAvatar' => $AI->display_avatar,
    'displayCopy' => $AI->display_copy_btn,
    'displayMic' => $AI->display_mic,
    'displayShare' => $AI->display_share,
    'useDalle' => $AI->use_dalle,
    'useVision' => $AI->use_vision,
    'chatFullWidth' => $AI->chat_full_width
];

// Include the chat interface
include('../../inc/header.php');
?>

<div class="chat-container <?= $AI->chat_full_width ? 'full-width' : '' ?>">
    
    <!-- Chat Header -->
    <div class="chat-header">
        <?php if ($AI->display_avatar && $AI->image): ?>
            <img src="<?= htmlspecialchars($AI->image) ?>" 
                 alt="<?= htmlspecialchars($AI->name) ?>" 
                 class="ai-avatar">
        <?php endif; ?>
        
        <h1><?= htmlspecialchars($AI->name) ?></h1>
        
        <?php if ($AI->display_description && $AI->description): ?>
            <p class="description"><?= htmlspecialchars($AI->description) ?></p>
        <?php endif; ?>
    </div>
    
    <!-- Chat Messages Container -->
    <div id="chat-messages" class="messages-container" 
         data-prompt-id="<?= $AI->id ?>" 
         data-thread-id="<?= $threadId ?>">
        
        <?php if ($AI->display_welcome_message && $AI->welcome_message): ?>
            <div class="message ai-message welcome">
                <div class="content">
                    <?= nl2br(htmlspecialchars($AI->welcome_message)) ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php foreach ($chatHistory as $msg): ?>
            <div class="message <?= $msg['role'] ?>-message" 
                 data-id="<?= $msg['id'] ?>">
                
                <?php if ($msg['vision_img']): ?>
                    <img src="<?= htmlspecialchars($msg['vision_img']) ?>" 
                         class="vision-image" 
                         alt="Uploaded image">
                <?php endif; ?>
                
                <div class="content">
                    <?= nl2br(htmlspecialchars($msg['content'])) ?>
                </div>
                
                <?php if ($msg['dall_e_array']): ?>
                    <?php $images = json_decode($msg['dall_e_array'], true); ?>
                    <div class="dalle-images">
                        <?php foreach ($images as $imgUrl): ?>
                            <img src="<?= htmlspecialchars($imgUrl) ?>" 
                                 alt="Generated image" 
                                 class="generated-image">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($AI->display_copy_btn): ?>
                    <button class="copy-btn" data-content="<?= htmlspecialchars($msg['content']) ?>">
                        <i class="fa fa-copy"></i>
                    </button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Chat Input Area -->
    <div class="chat-input-container">
        <?php if ($AI->use_vision): ?>
            <div class="vision-upload">
                <input type="file" 
                       id="vision-file" 
                       accept="image/*" 
                       style="display:none">
                <button id="vision-btn" class="icon-btn">
                    <i class="fa fa-image"></i>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if ($AI->display_mic): ?>
            <button id="mic-btn" class="icon-btn">
                <i class="fa fa-microphone"></i>
            </button>
        <?php endif; ?>
        
        <textarea id="user-input" 
                  placeholder="Type your message..." 
                  minlength="<?= $AI->chat_minlength ?>" 
                  maxlength="<?= $AI->chat_maxlength ?>"
                  rows="1"></textarea>
        
        <button id="send-btn" class="send-btn">
            <i class="fa fa-paper-plane"></i>
        </button>
    </div>
    
    <!-- Suggestions (if configured) -->
    <?php if ($AI->suggestions && count($AI->suggestions) > 0): ?>
        <div class="suggestions">
            <?php foreach ($AI->suggestions as $suggestion): ?>
                <button class="suggestion-chip" 
                        data-text="<?= htmlspecialchars($suggestion) ?>">
                    <?= htmlspecialchars($suggestion) ?>
                </button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
// Initialize chat interface
const chatConfig = <?= json_encode($uiConfig) ?>;
const promptId = <?= $AI->id ?>;
const threadId = "<?= $threadId ?>";
const apiModel = "<?= $AI->API_MODEL ?>";

// Initialize main chat handler
document.addEventListener('DOMContentLoaded', function() {
    initializeChat(chatConfig, promptId, threadId, apiModel);
});
</script>

<?php include('../../inc/footer.php'); ?>
```

**File: `/modules/customer/chat-session.php`**

```php
<?php
/**
 * Chat Session Management
 * Handles message persistence, thread management, and real-time communication
 */

// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load dependencies
require_once("../../inc/includes.php");

// Check if this is a fetch request (AJAX polling)
$isFetchRequest = isset($_POST['isFetchRequest']) && 
                  $_POST['isFetchRequest'] === 'true';

// Get prompt configuration
$slug = $_REQUEST['slug'] ?? null;
if (!$slug) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing prompt slug']);
    exit;
}

$prompts = new Prompts();
$AI = $prompts->getBySlug($slug);

if (!$AI) {
    http_response_code(404);
    echo json_encode(['error' => 'Prompt not found']);
    exit;
}

// Check user authentication
$isLogged = isset($_SESSION['id_customer']) && !empty($_SESSION['id_customer']);

// Get or create thread ID
if (!isset($_SESSION['threads'][$AI->id])) {
    $_SESSION['threads'][$AI->id] = uniqid('thread_');
}
$threadId = $_SESSION['threads'][$AI->id];

// Initialize messages handler
$messages = new Messages();

// Handle fetch request (polling for new messages)
if ($isFetchRequest) {
    if ($isLogged) {
        // Get unsaved messages from database
        $unsavedMessages = $messages->getUnsaved($threadId, $AI->id, $_SESSION['id_customer']);
        
        // Mark messages as delivered
        foreach ($unsavedMessages as $msg) {
            $messages->markAsSaved($msg['id']);
        }
        
        echo json_encode([
            'success' => true,
            'messages' => $unsavedMessages,
            'thread_id' => $threadId
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'messages' => [],
            'thread_id' => $threadId
        ]);
    }
    exit;
}

// Handle new message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userMessage = trim($_POST['message']);
    $visionImage = $_POST['vision_image'] ?? null;
    
    if (empty($userMessage)) {
        http_response_code(400);
        echo json_encode(['error' => 'Message cannot be empty']);
        exit;
    }
    
    // Validate message length
    if (strlen($userMessage) < $AI->chat_minlength) {
        http_response_code(400);
        echo json_encode(['error' => "Message too short. Minimum {$AI->chat_minlength} characters"]);
        exit;
    }
    
    if (strlen($userMessage) > $AI->chat_maxlength) {
        http_response_code(400);
        echo json_encode(['error' => "Message too long. Maximum {$AI->chat_maxlength} characters"]);
        exit;
    }
    
    // Store user message
    $userMessageId = uniqid('msg_');
    
    if ($isLogged) {
        $messages->insert([
            'id_message' => $userMessageId,
            'id_thread' => $threadId,
            'id_customer' => $_SESSION['id_customer'],
            'id_prompt' => $AI->id,
            'role' => 'user',
            'content' => $userMessage,
            'total_characters' => strlen($userMessage),
            'vision_img' => $visionImage,
            'saved' => 1
        ]);
    }
    
    // Add to session history
    $_SESSION['history'][$AI->id][] = [
        'id' => $userMessageId,
        'role' => 'user',
        'content' => $userMessage,
        'vision_img' => $visionImage
    ];
    
    // Send to AI API (async)
    // This is handled by separate API call to /php/api.php
    
    echo json_encode([
        'success' => true,
        'message_id' => $userMessageId,
        'thread_id' => $threadId
    ]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
```

---

## 🌐 API INTEGRATION LAYER

### OpenAI API Integration

**File: `/php/api.php` - Central API Handler**

```php
<?php
/**
 * Central API Handler
 * Routes API requests to appropriate AI services
 */

session_start();
require_once("../inc/includes.php");

// Check authentication
$isLogged = isset($_SESSION['id_customer']) && !empty($_SESSION['id_customer']);

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$requiredFields = ['prompt_id', 'thread_id', 'message'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

// Get prompt configuration
$prompts = new Prompts();
$promptManager = new PromptManager();
$prompt = $prompts->getById($input['prompt_id']);

if (!$prompt) {
    http_response_code(404);
    echo json_encode(['error' => 'Prompt not found']);
    exit;
}

// Check credit requirements for logged-in users
if ($isLogged) {
    $customers = new Customers();
    $customer = $customers->getById($_SESSION['id_customer']);
    
    // Check if prompt requires credits
    $requiredCredits = 1; // Base cost
    if ($prompt->API_MODEL == 'gpt-4') {
        $requiredCredits = 3;
    }
    
    if ($customer->credits < $requiredCredits) {
        http_response_code(402);
        echo json_encode([
            'error' => 'Insufficient credits',
            'required' => $requiredCredits,
            'available' => $customer->credits
        ]);
        exit;
    }
}

// Get AI configuration
$aiConfig = $promptManager->getAIConfiguration($input['prompt_id']);
$systemPrompt = $promptManager->buildSystemPrompt($input['prompt_id']);

// Build message history for context
$messageHistory = [];

// Add system prompt
$messageHistory[] = [
    'role' => 'system',
    'content' => $systemPrompt
];

// Add conversation history (last 10 messages for context)
if (isset($_SESSION['history'][$input['prompt_id']])) {
    $history = array_slice($_SESSION['history'][$input['prompt_id']], -10);
    foreach ($history as $msg) {
        $messageHistory[] = [
            'role' => $msg['role'],
            'content' => $msg['content']
        ];
    }
}

// Add current user message
$messageHistory[] = [
    'role' => 'user',
    'content' => $input['message']
];

// Get OpenAI API key from settings
$settings = new Settings();
$settingsData = $settings->getById(1);
$apiKey = $settingsData->openai_api_key;

if (!$apiKey) {
    http_response_code(500);
    echo json_encode(['error' => 'OpenAI API key not configured']);
    exit;
}

// Prepare OpenAI API request
$openaiData = [
    'model' => $aiConfig['model'],
    'messages' => $messageHistory,
    'temperature' => $aiConfig['temperature'],
    'max_tokens' => $aiConfig['max_tokens'],
    'frequency_penalty' => $aiConfig['frequency_penalty'],
    'presence_penalty' => $aiConfig['presence_penalty'],
    'top_p' => $aiConfig['top_p'],
    'stream' => true // Enable streaming for real-time response
];

// Initialize cURL for streaming response
$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($openaiData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $chunk) use ($input, $isLogged) {
    // Parse SSE chunk
    $lines = explode("\n", $chunk);
    foreach ($lines as $line) {
        if (strpos($line, 'data: ') === 0) {
            $data = substr($line, 6);
            if ($data === '[DONE]') {
                echo "data: [DONE]\n\n";
                flush();
                continue;
            }
            
            $json = json_decode($data, true);
            if (isset($json['choices'][0]['delta']['content'])) {
                $content = $json['choices'][0]['delta']['content'];
                
                // Stream to frontend
                echo "data: " . json_encode(['content' => $content]) . "\n\n";
                flush();
            }
        }
    }
    
    return strlen($chunk);
});

// Set headers for Server-Sent Events
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// Execute request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// After streaming complete, save assistant response
if ($isLogged && $httpCode === 200) {
    // Deduct credits
    $customers->updateCredits($_SESSION['id_customer'], -$requiredCredits);
    
    // Note: Full message content needs to be reconstructed from stream
    // This would typically be done on the client-side and sent back
}

// Close connection
echo "data: [DONE]\n\n";
flush();
```

**File: `/php/dall-e.php` - Image Generation**

```php
<?php
/**
 * DALL-E Image Generation Handler
 */

session_start();
require_once("../inc/includes.php");
require_once("key.php"); // API key management

// Check authentication
if (!isset($_SESSION['id_customer'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Authentication required']);
    exit;
}

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['prompt']) || empty($input['prompt'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Prompt is required']);
    exit;
}

// Get prompt configuration
$promptId = $input['prompt_id'] ?? null;
if (!$promptId) {
    http_response_code(400);
    echo json_encode(['error' => 'Prompt ID is required']);
    exit;
}

$prompts = new Prompts();
$promptConfig = $prompts->getById($promptId);

if (!$promptConfig || !$promptConfig->use_dalle) {
    http_response_code(400);
    echo json_encode(['error' => 'DALL-E not enabled for this prompt']);
    exit;
}

// Check customer credits
$customers = new Customers();
$customer = $customers->getById($_SESSION['id_customer']);

$requiredCredits = 5; // DALL-E costs more

if ($customer->credits < $requiredCredits) {
    http_response_code(402);
    echo json_encode([
        'error' => 'Insufficient credits',
        'required' => $requiredCredits,
        'available' => $customer->credits
    ]);
    exit;
}

// Get OpenAI API key
$apiKey = getApiKey('openai');

if (!$apiKey) {
    http_response_code(500);
    echo json_encode(['error' => 'API key not configured']);
    exit;
}

// Prepare DALL-E API request
$dalleData = [
    'model' => 'dall-e-3',
    'prompt' => $input['prompt'],
    'n' => $input['n'] ?? 1,
    'size' => $input['size'] ?? '1024x1024',
    'quality' => $input['quality'] ?? 'standard',
    'response_format' => 'url'
];

// Call DALL-E API
$ch = curl_init('https://api.openai.com/v1/images/generations');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dalleData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    $error = json_decode($response, true);
    http_response_code($httpCode);
    echo json_encode([
        'error' => $error['error']['message'] ?? 'Image generation failed'
    ]);
    exit;
}

$result = json_decode($response, true);

// Save images to messages
$imageUrls = array_map(function($img) {
    return $img['url'];
}, $result['data']);

$messages = new Messages();
$messageId = uniqid('msg_');

$messages->insert([
    'id_message' => $messageId,
    'id_thread' => $input['thread_id'],
    'id_customer' => $_SESSION['id_customer'],
    'id_prompt' => $promptId,
    'role' => 'assistant',
    'content' => 'Generated images for: ' . $input['prompt'],
    'dall_e_array' => json_encode($imageUrls),
    'total_characters' => 0,
    'saved' => 1
]);

// Deduct credits
$customers->updateCredits($_SESSION['id_customer'], -$requiredCredits);

// Add to session history
$_SESSION['history'][$promptId][] = [
    'id' => $messageId,
    'role' => 'assistant',
    'content' => 'Generated images',
    'dall_e_array' => $imageUrls
];

echo json_encode([
    'success' => true,
    'images' => $imageUrls,
    'message_id' => $messageId,
    'credits_used' => $requiredCredits,
    'credits_remaining' => $customer->credits - $requiredCredits
]);
```

**File: `/php/key.php` - API Key Management**

```php
<?php
/**
 * Secure API Key Management
 * Handles retrieval and validation of API keys
 */

function getApiKey($service) {
    require_once("../inc/includes.php");
    
    $settings = new Settings();
    $settingsData = $settings->getById(1);
    
    $keyMap = [
        'openai' => $settingsData->openai_api_key,
        'google_tts' => $settingsData->google_tts_api_key,
        'stripe' => $settingsData->stripe_secret_key,
        'paypal' => $settingsData->paypal_secret
    ];
    
    if (!isset($keyMap[$service])) {
        return null;
    }
    
    $key = $keyMap[$service];
    
    // Decrypt if necessary (if keys are encrypted in database)
    // $key = decryptKey($key);
    
    return $key;
}

function validateApiKey($service, $key) {
    // Test the API key with a simple request
    switch ($service) {
        case 'openai':
            return validateOpenAIKey($key);
        case 'google_tts':
            return validateGoogleKey($key);
        case 'stripe':
            return validateStripeKey($key);
        default:
            return false;
    }
}

function validateOpenAIKey($key) {
    $ch = curl_init('https://api.openai.com/v1/models');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $key
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 200;
}
```

---

## 🎨 FRONTEND ARCHITECTURE

### Main JavaScript Architecture

**File: `/js/main.js` - Core Application Logic (2500+ lines)**

```javascript
/**
 * Main Application JavaScript
 * Handles chat interface, API communication, and UI interactions
 */

// Global configuration
const AppConfig = {
    apiEndpoint: '/php/api.php',
    dalleEndpoint: '/php/dall-e.php',
    whisperEndpoint: '/php/whisper.php',
    sseTimeout: 30000,
    maxRetries: 3
};

// Chat Manager Class
class ChatManager {
    constructor(promptId, threadId, config) {
        this.promptId = promptId;
        this.threadId = threadId;
        this.config = config;
        this.isProcessing = false;
        this.eventSource = null;
        this.currentMessageId = null;
        
        this.initializeElements();
        this.attachEventListeners();
    }
    
    initializeElements() {
        this.messagesContainer = document.getElementById('chat-messages');
        this.userInput = document.getElementById('user-input');
        this.sendBtn = document.getElementById('send-btn');
        this.micBtn = document.getElementById('mic-btn');
        this.visionBtn = document.getElementById('vision-btn');
    }
    
    attachEventListeners() {
        this.sendBtn.addEventListener('click', () => this.sendMessage());
        this.userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
        
        if (this.micBtn) {
            this.micBtn.addEventListener('click', () => this.startVoiceRecording());
        }
        
        if (this.visionBtn) {
            this.visionBtn.addEventListener('click', () => this.uploadImage());
        }
        
        // Auto-resize textarea
        this.userInput.addEventListener('input', () => this.autoResizeInput());
        
        // Copy buttons
        this.messagesContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('copy-btn')) {
                this.copyMessage(e.target.dataset.content);
            }
        });
        
        // Suggestion chips
        document.querySelectorAll('.suggestion-chip').forEach(chip => {
            chip.addEventListener('click', () => {
                this.userInput.value = chip.dataset.text;
                this.userInput.focus();
            });
        });
    }
    
    async sendMessage() {
        if (this.isProcessing) return;
        
        const message = this.userInput.value.trim();
        if (!message) return;
        
        // Validate length
        const minLength = parseInt(this.userInput.getAttribute('minlength')) || 1;
        const maxLength = parseInt(this.userInput.getAttribute('maxlength')) || 1000;
        
        if (message.length < minLength) {
            this.showError(`Message too short. Minimum ${minLength} characters.`);
            return;
        }
        
        if (message.length > maxLength) {
            this.showError(`Message too long. Maximum ${maxLength} characters.`);
            return;
        }
        
        this.isProcessing = true;
        this.toggleInputState(false);
        
        // Add user message to UI
        const userMessageElement = this.addMessage('user', message);
        this.userInput.value = '';
        this.autoResizeInput();
        
        // Create assistant message placeholder
        const assistantMessageElement = this.createMessageElement('assistant');
        this.messagesContainer.appendChild(assistantMessageElement);
        
        // Scroll to bottom
        this.scrollToBottom();
        
        try {
            // Send message to API with SSE streaming
            await this.streamResponse(message, assistantMessageElement);
        } catch (error) {
            console.error('Error sending message:', error);
            this.showError('Failed to send message. Please try again.');
            assistantMessageElement.remove();
        } finally {
            this.isProcessing = false;
            this.toggleInputState(true);
            this.userInput.focus();
        }
    }
    
    async streamResponse(message, messageElement) {
        return new Promise((resolve, reject) => {
            const contentDiv = messageElement.querySelector('.content');
            let fullResponse = '';
            
            // Initialize EventSource for SSE
            const url = new URL(AppConfig.apiEndpoint, window.location.origin);
            url.searchParams.append('prompt_id', this.promptId);
            url.searchParams.append('thread_id', this.threadId);
            url.searchParams.append('message', message);
            
            this.eventSource = new EventSource(url);
            
            this.eventSource.onmessage = (event) => {
                if (event.data === '[DONE]') {
                    this.eventSource.close();
                    this.eventSource = null;
                    
                    // Save complete message
                    this.saveMessage('assistant', fullResponse);
                    
                    // Enable copy button
                    const copyBtn = messageElement.querySelector('.copy-btn');
                    if (copyBtn) {
                        copyBtn.dataset.content = fullResponse;
                    }
                    
                    resolve(fullResponse);
                    return;
                }
                
                try {
                    const data = JSON.parse(event.data);
                    if (data.content) {
                        fullResponse += data.content;
                        
                        // Update UI with markdown rendering
                        contentDiv.innerHTML = this.renderMarkdown(fullResponse);
                        
                        // Apply syntax highlighting to code blocks
                        contentDiv.querySelectorAll('pre code').forEach((block) => {
                            hljs.highlightBlock(block);
                        });
                        
                        // Scroll to bottom
                        this.scrollToBottom();
                    }
                } catch (error) {
                    console.error('Error parsing SSE data:', error);
                }
            };
            
            this.eventSource.onerror = (error) => {
                console.error('SSE error:', error);
                this.eventSource.close();
                this.eventSource = null;
                reject(error);
            };
            
            // Timeout handler
            setTimeout(() => {
                if (this.eventSource) {
                    this.eventSource.close();
                    this.eventSource = null;
                    reject(new Error('Request timeout'));
                }
            }, AppConfig.sseTimeout);
        });
    }
    
    addMessage(role, content, additionalData = {}) {
        const messageElement = this.createMessageElement(role);
        const contentDiv = messageElement.querySelector('.content');
        
        // Handle vision images
        if (additionalData.vision_img) {
            const img = document.createElement('img');
            img.src = additionalData.vision_img;
            img.className = 'vision-image';
            img.alt = 'Uploaded image';
            messageElement.insertBefore(img, contentDiv);
        }
        
        // Render content
        if (role === 'user') {
            contentDiv.textContent = content;
        } else {
            contentDiv.innerHTML = this.renderMarkdown(content);
            
            // Highlight code blocks
            contentDiv.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightBlock(block);
            });
        }
        
        // Handle DALL-E images
        if (additionalData.dall_e_array) {
            const imagesDiv = document.createElement('div');
            imagesDiv.className = 'dalle-images';
            
            additionalData.dall_e_array.forEach(imgUrl => {
                const img = document.createElement('img');
                img.src = imgUrl;
                img.className = 'generated-image';
                img.alt = 'Generated image';
                imagesDiv.appendChild(img);
            });
            
            messageElement.appendChild(imagesDiv);
        }
        
        // Add copy button if enabled
        if (this.config.displayCopy && role === 'assistant') {
            const copyBtn = document.createElement('button');
            copyBtn.className = 'copy-btn';
            copyBtn.dataset.content = content;
            copyBtn.innerHTML = '<i class="fa fa-copy"></i>';
            copyBtn.addEventListener('click', () => this.copyMessage(content));
            messageElement.appendChild(copyBtn);
        }
        
        this.messagesContainer.appendChild(messageElement);
        this.scrollToBottom();
        
        return messageElement;
    }
    
    createMessageElement(role) {
        const div = document.createElement('div');
        div.className = `message ${role}-message`;
        div.dataset.id = `msg_${Date.now()}`;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'content';
        
        div.appendChild(contentDiv);
        
        return div;
    }
    
    renderMarkdown(text) {
        // Simple markdown rendering
        // In production, use marked.js or similar
        let html = text;
        
        // Headers
        html = html.replace(/^### (.*$)/gim, '<h3>$1</h3>');
        html = html.replace(/^## (.*$)/gim, '<h2>$1</h2>');
        html = html.replace(/^# (.*$)/gim, '<h1>$1</h1>');
        
        // Bold
        html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Italic
        html = html.replace(/\*(.*?)\*/g, '<em>$1</em>');
        
        // Code blocks
        html = html.replace(/```(\w+)?\n([\s\S]*?)```/g, (match, lang, code) => {
            lang = lang || '';
            return `<pre><code class="language-${lang}">${this.escapeHtml(code.trim())}</code></pre>`;
        });
        
        // Inline code
        html = html.replace(/`([^`]+)`/g, '<code>$1</code>');
        
        // Links
        html = html.replace(/\[([^\]]+)\]\(([^\)]+)\)/g, '<a href="$2" target="_blank">$1</a>');
        
        // Lists
        html = html.replace(/^\* (.*$)/gim, '<li>$1</li>');
        html = html.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>');
        
        // Line breaks
        html = html.replace(/\n/g, '<br>');
        
        return html;
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    async saveMessage(role, content) {
        // Save message to session/database via AJAX
        try {
            const response = await fetch('/modules/customer/chat-session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    slug: window.location.pathname.split('/').pop(),
                    message: content,
                    role: role
                })
            });
            
            if (!response.ok) {
                console.error('Failed to save message');
            }
        } catch (error) {
            console.error('Error saving message:', error);
        }
    }
    
    copyMessage(content) {
        navigator.clipboard.writeText(content).then(() => {
            toastr.success('Copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy:', err);
            toastr.error('Failed to copy');
        });
    }
    
    autoResizeInput() {
        this.userInput.style.height = 'auto';
        this.userInput.style.height = this.userInput.scrollHeight + 'px';
    }
    
    toggleInputState(enabled) {
        this.userInput.disabled = !enabled;
        this.sendBtn.disabled = !enabled;
        
        if (this.micBtn) this.micBtn.disabled = !enabled;
        if (this.visionBtn) this.visionBtn.disabled = !enabled;
    }
    
    scrollToBottom(smooth = true) {
        this.messagesContainer.scrollTo({
            top: this.messagesContainer.scrollHeight,
            behavior: smooth ? 'smooth' : 'auto'
        });
    }
    
    showError(message) {
        toastr.error(message);
    }
    
    async startVoiceRecording() {
        // Implement voice recording with RecordRTC
        // This would integrate with Whisper API
        console.log('Voice recording not yet implemented');
        toastr.info('Voice recording coming soon');
    }
    
    async uploadImage() {
        const input = document.getElementById('vision-file');
        input.click();
        
        input.onchange = async () => {
            const file = input.files[0];
            if (!file) return;
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                this.showError('Please upload an image file');
                return;
            }
            
            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                this.showError('Image too large. Maximum 5MB.');
                return;
            }
            
            // Convert to base64
            const reader = new FileReader();
            reader.onload = () => {
                const base64 = reader.result;
                
                // Add preview to input area
                const preview = document.createElement('img');
                preview.src = base64;
                preview.className = 'vision-preview';
                
                // Store for next message send
                this.userInput.dataset.visionImage = base64;
                
                toastr.success('Image ready to send');
            };
            reader.readAsDataURL(file);
        };
    }
}

// Initialize chat when page loads
function initializeChat(config, promptId, threadId, apiModel) {
    window.chatManager = new ChatManager(promptId, threadId, config);
}

// Export for global access
window.initializeChat = initializeChat;
```

**File: `/js/sse.js` - Server-Sent Events Handler**

```javascript
/**
 * Server-Sent Events (SSE) Handler
 * Manages real-time communication with backend
 */

class SSEManager {
    constructor() {
        this.connections = new Map();
    }
    
    connect(endpoint, onMessage, onError) {
        const eventSource = new EventSource(endpoint);
        const connId = Date.now().toString();
        
        eventSource.onmessage = (event) => {
            try {
                if (event.data === '[DONE]') {
                    this.disconnect(connId);
                    return;
                }
                
                const data = JSON.parse(event.data);
                onMessage(data);
            } catch (error) {
                console.error('SSE parse error:', error);
            }
        };
        
        eventSource.onerror = (error) => {
            console.error('SSE connection error:', error);
            this.disconnect(connId);
            if (onError) onError(error);
        };
        
        this.connections.set(connId, eventSource);
        return connId;
    }
    
    disconnect(connId) {
        const eventSource = this.connections.get(connId);
        if (eventSource) {
            eventSource.close();
            this.connections.delete(connId);
        }
    }
    
    disconnectAll() {
        this.connections.forEach(eventSource => eventSource.close());
        this.connections.clear();
    }
}

// Global instance
window.sseManager = new SSEManager();
```

---

[CONTINUED IN NEXT PART - Document is getting very long. I'll create part 2 with the remaining sections...]

This is Part 1 of the expanded instruction system. Should I continue with Part 2 covering:
- The Four-Stage Development Methodology
- Interactive Questioning System
- Adding External APIs (Step-by-Step)
- Creating New Modules & Classes
- Style, Font & UI Manipulation
- Security & Data Protection
- Testing & Quality Assurance
- Documentation Standards
- Backup & Version Control
- Performance Optimization
- Troubleshooting Guide

Would you like me to complete the document?
