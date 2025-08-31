# Root Katalog - Przewodnik Praktycznej Manipulacji

## 🎯 Kluczowe pliki Root i strategie pracy

### 1. **index.php** - Serce aplikacji

#### Struktura rekomendowana:
```php
<?php
// 1. Error handling na początku
ini_set('display_errors', 0);
error_reporting(E_ALL);

// 2. Sprawdzenie trybu maintenance
if (file_exists('.maintenance') && !isset($_SESSION['admin_bypass'])) {
    include '_maintenance.php';
    exit;
}

// 3. Inicjalizacja systemu
require_once __DIR__ . '/inc/includes.php';

// 4. Rate limiting dla API calls
if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    checkRateLimit($_SERVER['REMOTE_ADDR'], 100, 3600); // 100 req/h
}

// 5. Routing
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_uri = rtrim($request_uri, '/') ?: '/';

switch ($request_uri) {
    case '/':
        include '_pages.php'; // Strona główna
        break;
    case '/chat':
        include '_chat.php';
        break;
    case '/blog':
        include '_blog.php';
        break;
    case (preg_match('/^\/blog\/(.+)/', $request_uri, $matches) ? true : false):
        $_GET['slug'] = $matches[1];
        include '_blog-post.php';
        break;
    case '/pricing':
        include '_pricing.php';
        break;
    default:
        include '_404.php';
        break;
}
```

#### Praktyczne rady:
- **Zawsze sprawdzaj maintenance mode** - unikniesz problemów podczas deploymentu
- **Implementuj rate limiting** - ochrona przed spam/DoS
- **Używaj preg_match** - dla dynamicznych route'ów z parametrami
- **Loguj 404** - znajdź broken linki i problemy SEO

### 2. **.htaccess** - Konfiguracja Apache

#### Template produkcyjny:
```apache
# Bezpieczeństwo
ServerTokens Prod
Header always set X-Frame-Options DENY
Header always set X-Content-Type-Options nosniff
Header always set X-XSS-Protection "1; mode=block"

# HTTPS redirect
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Blokada dostępu do wrażliwych plików
<FilesMatch "\.(inc|conf|sql|log|bak)$">
    Require all denied
</FilesMatch>

# Blokada katalogów
<DirectoryMatch "(vendor|node_modules|logs|\.git)">
    Require all denied
</DirectoryMatch>

# Pretty URLs
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Kompresja
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Cache statycznych plików
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 month"
</IfModule>
```

### 3. **Webhooki - PayPal i Stripe**

#### Uniwersalny template webhook:
```php
<?php
// webhook-template.php
require_once 'inc/includes.php';

class WebhookHandler {
    private $logFile;
    private $provider;
    
    public function __construct($provider) {
        $this->provider = $provider;
        $this->logFile = "logs/{$provider}_" . date('Y-m') . ".log";
    }
    
    public function handle() {
        try {
            $payload = file_get_contents('php://input');
            $headers = getallheaders();
            
            // Log wszystkie próby
            $this->log("Webhook received", ['payload' => $payload, 'headers' => $headers]);
            
            // Weryfikacja podpisu
            if (!$this->verifySignature($payload, $headers)) {
                http_response_code(400);
                $this->log("Invalid signature", ['ip' => $_SERVER['REMOTE_ADDR']]);
                exit('Invalid signature');
            }
            
            $event = json_decode($payload, true);
            
            // Sprawdź czy już przetworzony (idempotency)
            if ($this->isProcessed($event['id'] ?? '')) {
                http_response_code(200);
                exit('Already processed');
            }
            
            // Przetwórz event
            $this->processEvent($event);
            
            // Oznacz jako przetworzony
            $this->markProcessed($event['id'] ?? '');
            
            http_response_code(200);
            echo 'OK';
            
        } catch (Exception $e) {
            $this->log("Error processing webhook", ['error' => $e->getMessage()]);
            http_response_code(500);
            exit('Internal error');
        }
    }
    
    private function verifySignature($payload, $headers) {
        switch ($this->provider) {
            case 'stripe':
                return $this->verifyStripeSignature($payload, $headers);
            case 'paypal':
                return $this->verifyPayPalSignature($payload, $headers);
            default:
                return false;
        }
    }
    
    private function processEvent($event) {
        $eventType = $event['type'] ?? $event['event_type'] ?? '';
        
        switch ($eventType) {
            case 'payment_intent.succeeded':
            case 'PAYMENT.COMPLETED':
                $this->handlePaymentSuccess($event);
                break;
            case 'payment_intent.payment_failed':
            case 'PAYMENT.FAILED':
                $this->handlePaymentFailed($event);
                break;
        }
    }
    
    private function log($message, $data = []) {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'message' => $message,
            'data' => $data
        ];
        file_put_contents($this->logFile, json_encode($logEntry) . PHP_EOL, FILE_APPEND);
    }
}

// stripe-webhook.php
$handler = new WebhookHandler('stripe');
$handler->handle();
```

### 4. **Widoki (_*.php)** - Optymalne wzorce

#### Template dla widoku:
```php
<?php
// _template-view.php
require_once 'inc/includes.php';

// 1. Autoryzacja (jeśli wymagana)
if ($requires_auth && !isLoggedIn()) {
    header('Location: /login');
    exit;
}

// 2. Walidacja parametrów
$param = filter_var($_GET['param'] ?? '', FILTER_SANITIZE_STRING);
if (empty($param) && $param_required) {
    include '_404.php';
    exit;
}

// 3. Logika biznesowa
try {
    $model = new SomeModel();
    $data = $model->getData($param);
    
    if (!$data && $param_required) {
        include '_404.php';
        exit;
    }
} catch (Exception $e) {
    error_log("Error in view: " . $e->getMessage());
    include '_404.php';
    exit;
}

// 4. Ustawienia dla header/footer
$page_title = $data['title'] ?? 'Default Title';
$meta_description = $data['meta_description'] ?? 'Default description';
$additional_css = ['/style/specific.css'];
$page_scripts = ['/js/specific.js'];

// 5. Cache headers (jeśli statyczna treść)
if ($is_cacheable) {
    header('Cache-Control: public, max-age=3600');
    header('ETag: "' . md5($page_title . filemtime(__FILE__)) . '"');
}

include 'inc/header.php';
?>

<!-- 6. HTML template -->
<main class="template-view">
    <h1><?= htmlspecialchars($page_title) ?></h1>
    
    <?php if ($data): ?>
        <div class="content">
            <?= $data['content'] ?>
        </div>
    <?php else: ?>
        <p>Brak danych do wyświetlenia.</p>
    <?php endif; ?>
</main>

<?php 
// 7. Inline scripts (jeśli wymagane)
$inline_js = "
    console.log('View loaded: $page_title');
    // Specific JS for this view
";

include 'inc/footer.php';
?>
```

### 5. **SEO i wydajność**

#### Optymalizacje dla _sitemap.php:
```php
<?php
// _sitemap.php
header('Content-Type: application/xml; charset=utf-8');

// Cache sitemap na 24h
$cache_file = 'cache/sitemap.xml';
if (file_exists($cache_file) && filemtime($cache_file) > time() - 86400) {
    readfile($cache_file);
    exit;
}

ob_start();
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static pages -->
    <url>
        <loc><?= $_SERVER['HTTP_HOST'] ?>/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    