<?php

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

require __DIR__ . '/vendor/autoload.php';

// Create transport from DSN
$dsn = '';
$transport = Transport::fromDsn($dsn);
$mailer = new Mailer($transport);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

header('Content-Type: application/json');

$allowedOrigins = [
    'https://maxenjulie.nl',
    'https://www.maxenjulie.nl'
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
}
header('Access-Control-Allow-Origin: https://maxenjulie.nl');
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
// names can be an array of guest names (strings)
$names = [];
if (isset($data['names']) && is_array($data['names'])) {
    $names = array_map(function($n) {
        return trim(strip_tags($n));
    }, $data['names']);
}
$allergies = isset($data['allergies']) ? trim(strip_tags($data['allergies'])) : '';
$allergiesOther = isset($data['allergiesOther']) ? trim(strip_tags($data['allergiesOther'])) : '';$guestAllergies = [];
if (isset($data['guestAllergies']) && is_array($data['guestAllergies'])) {
    $guestAllergies = array_map(function($g) {
        $types = [];
        if (isset($g['types']) && is_array($g['types'])) {
            $types = array_map(function($t){ return trim(strip_tags($t)); }, $g['types']);
        } elseif (isset($g['type'])) {
            $types = [trim(strip_tags($g['type']))];
        }
        return [
            'types' => $types,
            'other' => isset($g['other']) ? trim(strip_tags($g['other'])) : '',
        ];
    }, $data['guestAllergies']);
}

$guestDiets = [];
if (isset($data['guestDiets']) && is_array($data['guestDiets'])) {
    $guestDiets = array_map(function($g) {
        return [
            'type' => isset($g['type']) ? trim(strip_tags($g['type'])) : '',
            'other' => isset($g['other']) ? trim(strip_tags($g['other'])) : '',
        ];
    }, $data['guestDiets']);
} $diet = isset($data['diet']) ? trim(strip_tags($data['diet'])) : '';
$travel = isset($data['travel']) ? trim(strip_tags($data['travel'])) : '';
$arrivalDate = isset($data['arrivalDate']) ? trim(strip_tags($data['arrivalDate'])) : '';
$leavingDate = isset($data['leavingDate']) ? trim(strip_tags($data['leavingDate'])) : '';
$car = isset($data['car']) ? trim(strip_tags($data['car'])) : '';
$lodging = isset($data['lodging']) ? trim(strip_tags($data['lodging'])) : '';
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

if ($rsvp === 'yes') {
    if (empty($amount) || !is_numeric($amount) || (int)$amount < 1) {
        $errors[] = 'Please enter a valid total number of guests (at least 1)';
    }
} else {
    // Not attending — normalize amount to 0 to bypass guest validations
    $amount = '0';
}

// Interpret amount as the total number of guests (including submitter)
$amountInt = (int)$amount;
$totalCount = $amountInt;
if ($totalCount > 0) {
    // if names omitted but we have main $name, set it as first name (fallback)
    if (empty($names) && !empty($name)) {
        $names = [$name];
    }

    if (empty($names) || !is_array($names) || count($names) !== $totalCount) {
        $errors[] = 'Please provide a name for each person';
    } else {
        foreach ($names as $nm) {
            if (!is_string($nm) || trim($nm) === '') {
                $errors[] = 'Please ensure all guest names are filled in';
                break;
            }
        }
    }

    // Validate per-guest allergy information (optional selections allowed, multi-select)
    if (empty($guestAllergies) || !is_array($guestAllergies) || count($guestAllergies) !== $totalCount) {
        $errors[] = 'Please provide allergy status for each person';
    } else {
        foreach ($guestAllergies as $ga) {
            $types = isset($ga['types']) && is_array($ga['types']) ? $ga['types'] : (isset($ga['type']) ? [$ga['type']] : []);
            if (in_array('other', $types) && empty($ga['other'])) {
                $errors[] = 'Please specify allergy details for persons marked as Other';
                break;
            }
        }
    }

    // Validate per-guest diet information (optional).
    // Allow missing or empty selections (treated as 'No preference').
    // Only enforce details when a guest selects 'other'.
    if (!is_array($guestDiets) || empty($guestDiets)) {
        // Normalize missing diets to an array of empty preferences to simplify later processing
        $guestDiets = array_fill(0, $totalCount, ['type' => '', 'other' => '']);
    } elseif (count($guestDiets) !== $totalCount) {
        // If provided but count mismatches, normalize to expected length
        if (count($guestDiets) < $totalCount) {
            for ($i = count($guestDiets); $i < $totalCount; $i++) {
                $guestDiets[] = ['type' => '', 'other' => ''];
            }
        } else {
            $guestDiets = array_slice($guestDiets, 0, $totalCount);
        }
    }

    foreach ($guestDiets as $gd) {
        $type = isset($gd['type']) ? trim($gd['type']) : '';
        if ($type === 'other' && empty($gd['other'])) {
            $errors[] = 'Please specify diet details for persons marked as Other';
            break;
        }
    }

    // If user indicated they know travel details, require structured fields
    if (!empty($travel) && $travel === 'yes') {
        if (empty($arrivalDate)) {
            $errors[] = 'Please provide an arrival date';
        }
        if (empty($leavingDate)) {
            $errors[] = 'Please provide a leaving date';
        }
        if (!empty($arrivalDate) && !empty($leavingDate)) {
            if (strtotime($leavingDate) < strtotime($arrivalDate)) {
                $errors[] = 'Leaving date must be on or after arrival date';
            }
        }
        if (empty($car) || !in_array($car, ['yes', 'no'])) {
            $errors[] = 'Please indicate if you will have/use a car';
        }
        if (empty($lodging)) {
            $errors[] = 'Please provide accommodation address';
        }
    }
}

// Top-level allergies selection is optional. If provided and 'other' is chosen, require details.
if (!empty($allergies) && $allergies === 'other' && empty($allergiesOther)) {
    $errors[] = 'Please specify your allergies or dietary requirements';
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
$to = 'm.j.deruiter99@hotmail.com'; // CHANGE THIS to your email
$email_subject = "RSVP Response from " . $name;

// Create email body
$email_body = "You have received a new RSVP.\n\n";
$email_body .= "Name: $name\n";
$email_body .= "Attending: " . ucfirst($rsvp) . "\n";
$email_body .= "Number of Guests: $amount\n\n";

if (!empty($names)) {
    $email_body .= "Guest Names:\n";
    foreach ($names as $g) {
        if (strlen(trim($g)) > 0) {
            $email_body .= "- $g\n";
        }
    }
    $email_body .= "\n";

    if (!empty($guestAllergies)) {
        $email_body .= "Guest Allergies:\n";
        foreach ($guestAllergies as $i => $ga) {
            $guestName = isset($names[$i]) ? $names[$i] : 'Guest ' . ($i + 1);
            $types = isset($ga['types']) ? $ga['types'] : (isset($ga['type']) ? [$ga['type']] : []);
            if (empty($types) || in_array('none', $types) || (count($types) === 1 && $types[0] === '')) {
                $al = 'No allergies';
            } else {
                $parts = [];
                foreach ($types as $t) {
                    if ($t === 'other') {
                        $parts[] = !empty($ga['other']) ? $ga['other'] : 'Other';
                    } else {
                        $parts[] = ucwords(str_replace('-', ' ', $t));
                    }
                }
                $al = implode(', ', $parts);
            }
            $email_body .= "- {$guestName}: {$al}\n";
        }
        $email_body .= "\n";
    }

    if (!empty($guestDiets)) {
        $email_body .= "Guest Diets:\n";
        foreach ($guestDiets as $i => $gd) {
            $guestName = isset($names[$i]) ? $names[$i] : 'Guest ' . ($i + 1);
            $type = $gd['type'];
            if ($type === 'other') {
                $d = $gd['other'];
            } elseif ($type === '' || $type === 'no-preference') {
                $d = 'No preference';
            } else {
                $d = ucwords(str_replace('-', ' ', $type));
            }
            $email_body .= "- {$guestName}: {$d}\n";
        }
        $email_body .= "\n";
    }
}

if (!empty($allergies)) {
    if ($allergies === 'other' && !empty($allergiesOther)) {
        $email_body .= "Allergies: $allergiesOther\n";
    } else {
        $email_body .= "Allergies/Diet: " . ucwords(str_replace(['-'], [' '], $allergies)) . "\n";
    }
}
if (!empty($diet)) {
    $email_body .= "Dietary Requirements: $diet\n";
}
if (!empty($travel)) {
    $email_body .= "Travel Assistance Needed: " . ucfirst($travel) . "\n";
    if ($travel === 'yes') {
        if (!empty($arrivalDate)) $email_body .= "Arrival date: $arrivalDate\n";
        if (!empty($leavingDate)) $email_body .= "Leaving date: $leavingDate\n";
        if (!empty($car)) $email_body .= "Has car: " . ucfirst($car) . "\n";
        if (!empty($lodging)) $email_body .= "Accommodation: $lodging\n";
        if (!empty($details)) $email_body .= "Additional travel details: $details\n";
    } else {
        if (!empty($details)) $email_body .= "Travel Details: $details\n";
    }
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
    ->from('contact@maxenjulie.nl')   // TODO update
    ->to($to)
    ->replyTo('contact@maxenjulie.nl') // TODO update
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