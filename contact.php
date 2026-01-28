<?php

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

require __DIR__ . '/vendor/autoload.php';

// Create transport from DSN
$dsn = 'smtp://test@test.nl:password@mail.axc.nl:465'; //change email and password
$transport = Transport::fromDsn($dsn);
$mailer = new Mailer($transport);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Wrap everything in try-catch to capture errors
try {

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input exists
if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit();
}

// Extract and sanitize data
$name = isset($data['name']) ? trim(strip_tags($data['name'])) : '';
$rsvp = isset($data['rsvp']) ? trim(strip_tags($data['rsvp'])) : '';
$amount = isset($data['amount']) ? trim(strip_tags($data['amount'])) : '';
$allergies = isset($data['allergies']) ? trim(strip_tags($data['allergies'])) : '';
$diet = isset($data['diet']) ? trim(strip_tags($data['diet'])) : '';
$travel = isset($data['travel']) ? trim(strip_tags($data['travel'])) : '';
$details = isset($data['details']) ? trim(strip_tags($data['details'])) : '';
$taxi = isset($data['taxi']) ? trim(strip_tags($data['taxi'])) : '';
$questions = isset($data['questions']) ? trim(strip_tags($data['questions'])) : '';
$song = isset($data['song']) ? trim(strip_tags($data['song'])) : '';

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($rsvp) || !in_array($rsvp, ['yes', 'no'])) {
    $errors[] = 'Please select if you will attend';
}

if (empty($amount) || !is_numeric($amount) || $amount < 0) {
    $errors[] = 'Please enter a valid number of guests';
}

// Return errors if validation fails
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => implode(', ', $errors)
    ]);
    exit();
}

// Configure email settings
$to = 'test@test.nl'; // CHANGE THIS to your email
$email_subject = "RSVP Response from " . $name;

// Create email body
$email_body = "You have received a new RSVP.\n\n";
$email_body .= "Name: $name\n";
$email_body .= "Attending: " . ucfirst($rsvp) . "\n";
$email_body .= "Number of Guests: $amount\n\n";

if (!empty($allergies)) {
    $email_body .= "Allergies: $allergies\n";
}
if (!empty($diet)) {
    $email_body .= "Dietary Requirements: $diet\n";
}
if (!empty($travel)) {
    $email_body .= "Travel Assistance Needed: " . ucfirst($travel) . "\n";
}
if (!empty($details)) {
    $email_body .= "Travel Details: $details\n";
}
if (!empty($taxi)) {
    $email_body .= "Taxi Needed: " . ucfirst($taxi) . "\n";
}
if (!empty($questions)) {
    $email_body .= "\nQuestions/Comments:\n$questions\n";
}
if (!empty($song)) {
    $email_body .= "\nSong Request: $song\n";
}

// Build the email
$email = (new Email())
    ->from('test@test.nl')   // TODO update
    ->to($to)
    ->replyTo('test@test.nl') // TODO update
    ->subject($email_subject)
    ->text($email_body);

// Send it
$mailer->send($email);

$mail_sent = true;

if ($mail_sent) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your RSVP has been received.'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send RSVP. Please try again later.'
    ]);
}

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>