<?php
/**
 * AI Code Generation API
 * Handles streaming responses from OpenAI for code generation
 */

require_once(__DIR__ . '/../inc/includes.php');
require_once(__DIR__ . '/key.php');

// Admin-only access
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Set headers for SSE (Server-Sent Events)
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no');

// Get request data
$input = json_decode(file_get_contents('php://input'), true);
$userPrompt = $input['prompt'] ?? '';
$systemPrompt = $input['system_prompt'] ?? 'You are a helpful coding assistant.';
$history = $input['history'] ?? [];

if (empty($userPrompt)) {
    echo 'data: {"error": "No prompt provided"}' . PHP_EOL . PHP_EOL;
    exit;
}

// Get configuration
$config = $settings->get(1);
$apiKey = $config->API_KEY;

if (empty($apiKey)) {
    echo 'data: {"error": "API key not configured"}' . PHP_EOL . PHP_EOL;
    exit;
}

// Prepare messages for OpenAI
$messages = [
    ['role' => 'system', 'content' => $systemPrompt]
];

// Add history (last 10 messages to keep context manageable)
$recentHistory = array_slice($history, -10);
foreach ($recentHistory as $msg) {
    $messages[] = $msg;
}

// Add current user prompt
$messages[] = ['role' => 'user', 'content' => $userPrompt];

// Prepare API request
$data = [
    'model' => $config->API_MODEL ?? 'gpt-4',
    'messages' => $messages,
    'temperature' => 0.7,
    'max_tokens' => 4000,
    'stream' => true,
    'frequency_penalty' => 0.3,
    'presence_penalty' => 0.3
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
    CURLOPT_RETURNTRANSFER => false,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ],
    CURLOPT_WRITEFUNCTION => function($curl, $data) {
        $trimmed = trim($data);

        if (empty($trimmed)) {
            return strlen($data);
        }

        if ($trimmed === 'data: [DONE]') {
            echo "data: " . json_encode(['done' => true]) . "\n\n";
            flush();
            return strlen($data);
        }

        if (strpos($trimmed, 'data: ') === 0) {
            $json = substr($trimmed, 6);

            try {
                $decoded = json_decode($json, true);

                if (isset($decoded['choices'][0]['delta']['content'])) {
                    $content = $decoded['choices'][0]['delta']['content'];

                    echo "data: " . json_encode([
                        'content' => $content,
                        'done' => false
                    ]) . "\n\n";

                    flush();
                }
            } catch (Exception $e) {
                // Skip invalid JSON
            }
        }

        return strlen($data);
    },
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_TIMEOUT => 120
]);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'data: {"error": "' . curl_error($ch) . '"}' . PHP_EOL . PHP_EOL;
}

curl_close($ch);
?>
