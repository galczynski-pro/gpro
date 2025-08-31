# Praktyczne Rady do Manipulacji Skryptami - Katalog /inc i Layout

## 📂 Struktura /inc i jej rola

Katalog `/inc` jest centrum kontrolnym aplikacji - to tu znajdują się kluczowe moduły odpowiadające za inicjalizację, bezpieczeństwo i layout strony.

### Kluczowe pliki:

#### 1. **Autoloader.php** 
```php
// Automatyczne ładowanie klas
spl_autoload_register(function ($className) {
    $paths = [
        '../admin/class/' . $className . '.class.php',
        './class/' . $className . '.class.php'
    ];
    // ... logika ładowania
});
```

**💡 Rady praktyczne:**
- Zawsze dodawaj nowe klasy zgodnie z konwencją `NazwaKlasy.class.php`
- Jeśli tworzysz nowe moduły, dodaj ścieżkę w autoloaderze
- Testuj autoloader po każdym dodaniu nowej klasy

#### 2. **includes.php**
Centralny punkt wejścia dla wszystkich zależności:
```php
<?php
session_start();
require_once 'Autoloader.php';
require_once '../config/database.php';
require_once 'functions.php';
// ... inne include'y
?>
```

**💡 Rady praktyczne:**
- Kolejność ma znaczenie - najpierw sesja, potem autoloader
- Dodawaj nowe zależności na końcu, żeby nie zepsuć istniejących
- Używaj `require_once` zamiast `require` - unikniesz duplikatów

#### 3. **restrict.php**
Kontrola dostępu:
```php
<?php
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php');
    exit();
}
?>
```

**💡 Rady praktyczne:**
- Zawsze dodawaj `exit()` po `header()` - bez tego skrypt będzie dalej wykonywany
- Rozważ dodanie timeout sesji
- Loguj próby nieautoryzowanego dostępu

## 🎨 Header.php - Praktyczne wskazówki

### Struktura nagłówka
```html
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Domyślny tytuł' ?></title>
    
    <!-- CSS - ładuj w kolejności od ogólnych do specyficznych -->
    <link rel="stylesheet" href="/style/bootstrap.min.css">
    <link rel="stylesheet" href="/style/app.css">
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
```

### 💡 Rady manipulacji header.php:

#### **Dynamiczne ładowanie CSS**
```php
// W pliku strony (przed include header.php):
$additional_css = [
    '/style/dark-mode.css',
    '/style/chat-specific.css'
];
$page_title = "Chat AI";
include 'inc/header.php';
```

#### **SEO i Meta tagi**
```php
// Dodaj zmienne dla SEO
$meta_description = $meta_description ?? 'Domyślny opis';
$meta_keywords = $meta_keywords ?? 'ai, chat, generator';
$og_image = $og_image ?? '/img/default-og.jpg';
```

#### **Conditional Loading**
```php
// Ładuj CSS tylko tam gdzie potrzebne
<?php if ($current_page === 'chat'): ?>
    <link rel="stylesheet" href="/style/chat.css">
<?php endif; ?>
```

## 🦶 Footer.php - Optymalizacja i funkcjonalność

### Przykładowa struktura
```html
    <!-- JavaScript - na końcu dla szybszego ładowania -->
    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    
    <?php if (isset($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
            <script src="<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Inline scripts na końcu -->
    <script>
        $(document).ready(function() {
            // Inicjalizacja globalnych funkcji
            <?= $inline_js ?? '' ?>
        });
    </script>
</body>
</html>
```

### 💡 Rady manipulacji footer.php:

#### **Async/Defer dla wydajności**
```html
<script src="/js/non-critical.js" defer></script>
<script src="/js/analytics.js" async></script>
```

#### **Conditional Scripts**
```php
<?php if ($enable_chat): ?>
    <script src="/js/sse.js"></script>
    <script src="/js/RecordRTC.js"></script>
<?php endif; ?>
```

#### **Error Handling**
```javascript
window.onerror = function(msg, url, line) {
    console.error('JS Error: ', msg, ' at ', url, ':', line);
    // Opcjonalnie wyślij do systemu logowania
    return false;
};
```

## 🔧 Zaawansowane techniki manipulacji

### 1. **Warunkowe ładowanie modułów**
```php
// W includes.php
$modules = [
    'payment' => isset($_GET['payment']),
    'chat' => strpos($_SERVER['REQUEST_URI'], 'chat') !== false,
    'admin' => strpos($_SERVER['REQUEST_URI'], 'admin') !== false
];

foreach ($modules as $module => $load) {
    if ($load) {
        require_once "modules/{$module}.php";
    }
}
```

### 2. **Dynamiczny routing w header**
```php
// Automatyczne określenie aktywnej strony
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$page_classes = "page-{$current_page}";

// W body tag:
<body class="<?= $page_classes ?>">
```

### 3. **Asset versioning**
```php
function asset_url($path) {
    $version = filemtime($_SERVER['DOCUMENT_ROOT'] . $path);
    return $path . '?v=' . $version;
}

// Użycie:
<link rel="stylesheet" href="<?= asset_url('/style/app.css') ?>">
```

### 4. **Environment-based loading**
```php
// W header.php
if (defined('DEVELOPMENT') && DEVELOPMENT) {
    // Wersje dev - niezbędne do debugowania
    echo '<link rel="stylesheet" href="/style/debug.css">';
    echo '<script src="/js/debug.js"></script>';
} else {
    // Produkcja - minifikowane
    echo '<link rel="stylesheet" href="/style/app.min.css">';
    echo '<script src="/js/app.min.js"></script>';
}
```

### 5. **Security Headers**
```php
// W header.php po session_start()
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\';');
```

## 🚀 Performance Tips

### 1. **CSS/JS Concatenation**
```php
// Funkcja łącząca pliki CSS
function combine_css($files) {
    $combined = '';
    foreach ($files as $file) {
        $combined .= file_get_contents($_SERVER['DOCUMENT_ROOT'] . $file);
    }
    return $combined;
}
```

### 2. **Lazy Loading**
```javascript
// W footer.php
document.addEventListener('DOMContentLoaded', function() {
    // Ładuj cięższe skrypty dopiero po załadowaniu strony
    setTimeout(function() {
        const script = document.createElement('script');
        script.src = '/js/heavy-features.js';
        document.head.appendChild(script);
    }, 1000);
});
```

### 3. **Resource Hints**
```html
<!-- W header.php -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preload" href="/js/main.js" as="script">
<link rel="prefetch" href="/img/next-page-bg.jpg">
```

## 🛡️ Bezpieczeństwo w praktyce

### 1. **Input Sanitization**
```php
// W includes.php
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Użycie w header:
$page_title = sanitize_input($page_title ?? 'Default');
```

### 2. **CSRF Protection**
```php
// W header.php
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
echo '<meta name="csrf-token" content="' . $_SESSION['csrf_token'] . '">';
```

### 3. **Rate Limiting**
```php
// W restrict.php
function check_rate_limit($action, $limit = 10, $window = 60) {
    $key = $action . '_' . $_SERVER['REMOTE_ADDR'];
    $current_time = time();
    
    if (!isset($_SESSION['rate_limit'][$key])) {
        $_SESSION['rate_limit'][$key] = [];
    }
    
    // Usuń stare wpisy
    $_SESSION['rate_limit'][$key] = array_filter(
        $_SESSION['rate_limit'][$key],
        function($timestamp) use ($current_time, $window) {
            return $current_time - $timestamp < $window;
        }
    );
    
    if (count($_SESSION['rate_limit'][$key]) >= $limit) {
        http_response_code(429);
        die('Rate limit exceeded');
    }
    
    $_SESSION['rate_limit'][$key][] = $current_time;
}
```

## 📊 Debugging i Monitoring

### 1. **Debug Mode w header.php**
```php
<?php if (defined('DEBUG') && DEBUG): ?>
    <div id="debug-panel" style="position: fixed; top: 0; right: 0; background: #000; color: #0f0; padding: 10px; z-index: 9999; font-family: monospace; font-size: 12px;">
        <strong>DEBUG INFO:</strong><br>
        Memory: <?= memory_get_usage(true) / 1024 / 1024 ?>MB<br>
        Time: <?= microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'] ?>s<br>
        Queries: <?= $db_query_count ?? 0 ?><br>
        Session: <?= session_id() ?>
    </div>
<?php endif; ?>
```

### 2. **Error Logging**
```php
// W includes.php
if (!defined('DEVELOPMENT') || !DEVELOPMENT) {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', '../logs/php_errors.log');
}

function log_error($message, $file = '', $line = '') {
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] $message";
    if ($file) $log_message .= " in $file";
    if ($line) $log_message .= " on line $line";
    error_log($log_message);
}
```

### 3. **Performance Monitoring**
```php
// Na początku każdej strony (po includes.php)
$start_time = microtime(true);
$start_memory = memory_get_usage();

// W footer.php
<?php if (defined('SHOW_STATS') && SHOW_STATS): ?>
    <div class="performance-stats" style="position: fixed; bottom: 0; left: 0; background: rgba(0,0,0,0.8); color: white; padding: 5px; font-size: 11px;">
        Load time: <?= round((microtime(true) - $start_time) * 1000, 2) ?>ms |
        Memory: <?= round((memory_get_usage() - $start_memory) / 1024, 2) ?>KB |
        Peak: <?= round(memory_get_peak_usage() / 1024 / 1024, 2) ?>MB
    </div>
<?php endif; ?>
```

## 🔄 Advanced Include Patterns

### 1. **Modular Layout System**
```php
// layout.php (zamiast separate header/footer)
<?php
function render_layout($content, $data = []) {
    extract($data);
    ob_start();
    include 'inc/header.php';
    echo $content;
    include 'inc/footer.php';
    return ob_get_clean();
}

// Użycie w stronach:
$content = '<div class="main-content">Content here</div>';
echo render_layout($content, [
    'page_title' => 'Custom Title',
    'additional_css' => ['/style/custom.css']
]);
```

### 2. **Template Inheritance**
```php
// base_template.php
class BaseTemplate {
    protected $data = [];
    protected $sections = [];
    
    public function extend($template) {
        $this->template = $template;
        return $this;
    }
    
    public function section($name, $content) {
        $this->sections[$name] = $content;
        return $this;
    }
    
    public function render() {
        extract($this->data);
        extract($this->sections);
        include $this->template;
    }
}

// Użycie:
$template = new BaseTemplate();
$template->extend('inc/layout.php')
         ->section('content', '<h1>Page Content</h1>')
         ->section('sidebar', '<div>Sidebar</div>')
         ->render();
```

### 3. **Conditional Components**
```php
// W header.php
function load_component($name, $condition = true, $data = []) {
    if ($condition && file_exists("components/{$name}.php")) {
        extract($data);
        include "components/{$name}.php";
        return true;
    }
    return false;
}

// Użycie:
<?php load_component('navigation', $show_nav, ['current_page' => $current_page]); ?>
<?php load_component('chat-widget', $enable_chat); ?>
```

## 🎯 Specific Use Cases

### 1. **API Response Headers**
```php
// W api.php (można też w header.php dla API calls)
if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}
```

### 2. **Mobile Detection**
```php
// W header.php
function is_mobile() {
    return preg_match('/Mobile|Android|BlackBerry|iPhone|iPad/', $_SERVER['HTTP_USER_AGENT'] ?? '');
}

$device_class = is_mobile() ? 'mobile' : 'desktop';
$viewport_meta = is_mobile() 
    ? '<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">'
    : '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
```

### 3. **Theme Switching**
```php
// W includes.php
$theme = $_COOKIE['theme'] ?? $_SESSION['theme'] ?? 'light';
$theme_css = "/style/theme-{$theme}.css";

// W header.php
<link rel="stylesheet" href="<?= $theme_css ?>">
<script>
    function switchTheme(newTheme) {
        document.cookie = `theme=${newTheme}; path=/; max-age=31536000`;
        location.reload();
    }
</script>
```

## 🔧 Maintenance Mode

### 1. **Maintenance Check**
```php
// W includes.php
if (file_exists('../.maintenance') && !isset($_SESSION['admin_logged'])) {
    http_response_code(503);
    include '_maintenance.php';
    exit();
}
```

### 2. **Graceful Updates**
```php
// update.php
function is_safe_to_update() {
    // Sprawdź aktywne sesje
    $active_sessions = count(glob(session_save_path() . '/sess_*'));
    return $active_sessions < 5; // Arbitrary threshold
}

if (is_safe_to_update()) {
    file_put_contents('../.maintenance', time());
    // Perform update
    unlink('../.maintenance');
}
```

## 📈 Cache Strategies

### 1. **Output Caching**
```php
// W każdej stronie
$cache_file = "cache/" . md5($_SERVER['REQUEST_URI']) . ".html";
$cache_time = 3600; // 1 hour

if (file_exists($cache_file) && filemtime($cache_file) > time() - $cache_time) {
    readfile($cache_file);
    exit();
}

ob_start();
// Reszta strony...

// W footer.php
$content = ob_get_contents();
file_put_contents($cache_file, $content);
ob_end_flush();
```

### 2. **Fragment Caching**
```php
function cache_fragment($key, $callback, $ttl = 3600) {
    $cache_file = "cache/fragments/{$key}.php";
    
    if (file_exists($cache_file) && filemtime($cache_file) > time() - $ttl) {
        include $cache_file;
        return;
    }
    
    ob_start();
    $callback();
    $content = ob_get_clean();
    
    file_put_contents($cache_file, "<?php /* cached at " . date('Y-m-d H:i:s') . " */ ?>" . $content);
    echo $content;
}
```

## 🏁 Podsumowanie najważniejszych praktyk

### ✅ DO:
- Używaj `require_once` dla kluczowych plików
- Implementuj proper error handling
- Zawsze waliduj i sanityzuj input
- Wykorzystuj caching gdzie to możliwe
- Monitoruj performance
- Implementuj rate limiting
- Używaj HTTPS w produkcji

### ❌ DON'T:
- Nie używaj `include` dla krytycznych plików
- Nie zapominaj o `exit()` po `header()`  
- Nie ładuj wszystkich skryptów na każdej stronie
- Nie ignoruj błędów PHP
- Nie trzymaj wrażliwych danych w sesji bez szyfrowania
- Nie zapominaj o CSRF protection

Te techniki dają ci pełną kontrolę nad tym, jak aplikacja się ładuje, jakie resources są dostępne i jak bezpiecznie obsługuje requesty.