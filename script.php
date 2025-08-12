<?php

declare(strict_types=1);

define('APP_ROOT', __DIR__);

require_once APP_ROOT . '/vendor/autoload.php';

// Load environment variables from .env file
$dotenv = \Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

require_once APP_ROOT . '/classes/Database.php';      #NOSONAR
require_once APP_ROOT . '/classes/Sanitizer.php';     #NOSONAR
require_once APP_ROOT . '/classes/Translation.php';   #NOSONAR
require_once APP_ROOT . '/classes/Response.php';      #NOSONAR
require_once APP_ROOT . '/classes/Status.php';        #NOSONAR

use database\Database;
use database\Response;
use database\Sanitizer;
use database\Status;
use database\Translation;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

    if ($database->exists($email)) {
        echo Response::with(Status::HTTP_BAD_REQUEST, sprintf($translation->forKey('email_already_exists'), $email));
    } else {
        if ($database->save($input)) {
            //-- Send email notification
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = $_ENV['MAIL_HOST'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $_ENV['MAIL_USERNAME'];
                $mail->Password   = $_ENV['MAIL_PASSWORD'];
                $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
                $mail->Port       = (int)$_ENV['MAIL_PORT'];

                //Recipients
                $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
                $mail->addAddress($_ENV['MAIL_TO_ADDRESS']);

                //Content
                $mail->isHTML(false);
                $mail->Subject = 'New form submission received';
                $body = "A new visitor has submitted the form.\n\n";
                foreach ($input as $key => $value) {
                    $body .= ucfirst($key) . ': ' . $value . "\n";
                }
                $mail->Body = $body;

                $mail->send();
            } catch (Exception $e) {
                // Log email sending failure
                error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }

            ob_clean(); // Clean output buffer just in case
            echo Response::with(Status::HTTP_OK, $translation->forKey('data_saved'));

        } else {
            echo Response::with(Status::HTTP_BAD_REQUEST, $translation->forKey('database_error'));
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo Response::with(Status::HTTP_BAD_REQUEST, $translation->forKey('database_error'));
}
exit;
