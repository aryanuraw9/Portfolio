<?php
/**
 * Contact Form Handler
 * Sanitizes, validates, stores, and optionally emails
 */

header('Content-Type: application/json');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// ── Rate limiting (basic) ──────────────────────────────────
session_start();
$now = time();
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$session_key = 'last_contact_' . $ip;
if (isset($_SESSION[$session_key]) && ($now - $_SESSION[$session_key]) < 60) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Please wait a moment before sending another message.']);
    exit;
}

// ── Input sanitization ────────────────────────────────────
$name    = trim(htmlspecialchars(strip_tags($_POST['name']    ?? ''), ENT_QUOTES, 'UTF-8'));
$email   = trim(filter_var($_POST['email']   ?? '', FILTER_SANITIZE_EMAIL));
$message = trim(htmlspecialchars(strip_tags($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8'));

// ── Validation ────────────────────────────────────────────
$errors = [];
if (empty($name) || strlen($name) < 2)          $errors[] = 'Please enter your name (min 2 characters).';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
if (empty($message) || strlen($message) < 10)   $errors[] = 'Please enter a message (min 10 characters).';
if (strlen($name) > 255)                         $errors[] = 'Name is too long.';
if (strlen($message) > 5000)                     $errors[] = 'Message is too long.';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// ── Store in DB ───────────────────────────────────────────
$conn = getDBConnection();
if ($conn) {
    $ip   = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $message, $ip);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

// ── Optional: send email ──────────────────────────────────
$to      = ADMIN_EMAIL;
$subject = "New Portfolio Message from {$name}";
$body    = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";
$headers = "From: noreply@sovryx.com\r\nReply-To: " . str_replace(["\r", "\n"], "", $email) . "\r\nX-Mailer: PHP/" . phpversion();
@mail($to, $subject, $body, $headers); // Non-blocking

$_SESSION[$session_key] = $now;

echo json_encode([
    'success' => true,
    'message' => "Thanks {$name}! Your message has been received. I'll be in touch soon."
]);
