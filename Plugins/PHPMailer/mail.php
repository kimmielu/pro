<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require __DIR__ . '/../../vendor/autoload.php';

// Database connection
$host = "localhost";
$user = "root";       
$pass = "0000";           
$db   = "pro_db";     

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userEmail = $_POST['email'] ?? '';
$userName = $_POST['name'] ?? 'User';

if (empty($userName) || empty($userEmail)) {
    die("Name and Email are required!");
}

if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address!");
}

$stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ss", $userName, $userEmail);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$stmt->close();

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'kimberly.lumumba@strathmore.edu';                     //SMTP username
    $mail->Password   = 'tgyp uadi hfvy iqit';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('kimberly.lumumba@strathmore.edu', 'Kimberly Lumumba');
    $mail->addAddress('lumumbaamy8@gmail.com', 'Daniella Lando');     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Welcome to NextWave Thrifts';
    $mail->Body    = 'Welcome to <b>NextWave Thrifts</b>! We are excited to see you in our store!';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
