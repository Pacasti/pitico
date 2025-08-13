<?php

declare(strict_types=1);

require_once './classes/Database.php';      #NOSONAR
require_once './classes/Sanitizer.php';     #NOSONAR
require_once './classes/Translation.php';   #NOSONAR
require_once './classes/Response.php';      #NOSONAR
require_once './classes/Status.php';        #NOSONAR

use database\Database;
use database\Response;
use database\Sanitizer;
use database\Status;
use database\Translation;

session_start();
ob_start();

$language = explode('/', $_SERVER['REQUEST_URI'])[0];

if (!in_array($language, ['de', 'en'])) {
    $language = 'de';
}

$translation = new Translation($language);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo Response::with(Status::HTTP_FORBIDDEN, $translation->forKey('invalid_method'));
    exit;
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo Response::with(Status::HTTP_FORBIDDEN, $translation->forKey('invalid_csrf_token'));
    exit;
}

$hostname = 'localhost';
if (stripos($_SERVER['HTTP_HOST'], $hostname) === false) {
    echo Response::with(Status::HTTP_BAD_REQUEST, $translation->forKey('wrong_hostname'));
    exit;
}

if (!isset($_POST['email'])) {
    echo Response::with(Status::HTTP_BAD_REQUEST, $translation->forKey('email_already_exists'));
    exit;
}

$recaptchaSecret = '6Ldeh_wqAAAAAPkLfjFhBiqm5TWoEzbtwhzTYjny';
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? null;

if (!$recaptchaResponse) {
    echo Response::with(Status::HTTP_FORBIDDEN, $translation->forKey('missing_recaptcha'));
    exit;
}

// Verify with Google
$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
$response = file_get_contents($verifyUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
$responseData = json_decode($response);

if (!$responseData->success) {
    echo Response::with(Status::HTTP_FORBIDDEN, $translation->forKey('invalid_recaptcha'));
    exit;
}

$input = Sanitizer::sanitize($_POST);

array_walk(
    $input,
    callback: function (string $value, string $key) use ($translation): void {
        if (strlen($value) === 0 && $key != 'message') {
            echo Response::with(Status::HTTP_BAD_REQUEST, sprintf($translation->forKey('empty_key'), $key));
            exit;
        }
    }
);

try {
    $database = new Database();
    $email = trim($input['email']);

    if ($database->exsists($email)) {
        echo Response::with(Status::HTTP_BAD_REQUEST, sprintf($translation->forKey('email_already_exists'), $email));
    } else {
        $database->save($input);

        // Email setup
        $adminEmail = 'admin@yourdomain.com';
        $subject = 'New form submission received';
        $message = "A new visitor has submitted the form.\n\n";
        foreach ($input as $key => $value) {
            $message .= ucfirst($key) . ': ' . $value . "\n";
        }

        $headers = 'From: you@localhost' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        $emailSent = mail($adminEmail, $subject, $message, $headers);

        if (!$emailSent) {
            error_log('Failed to send admin email');
        }

        ob_clean(); // Clean output buffer just in case

        echo Response::with(Status::HTTP_OK, $translation->forKey('data_saved'));
    }
} catch (Exception $e) {
    echo Response::with(Status::HTTP_BAD_REQUEST, sprintf($translation->forKey('database_error'), $e->getMessage()));
}
exit;
