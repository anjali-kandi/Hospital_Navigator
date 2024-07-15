<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users"; // Adjust database name as per your setup

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate OTP
function generateOTP() 
{
    return rand(100000, 999999); // Generate a 6-digit OTP
}

// Get the form data
$email = $_POST['signupEmail'] ?? '';
$pass = $_POST['signupPassword'] ?? '';

// Check if email or password is empty
if (empty($email) || empty($pass)) {
    echo 'Email or Password cannot be empty.';
    exit();
}

// Check if email already exists
$sql_check = "SELECT email FROM user_details WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
if ($stmt_check === false) {
    echo 'MySQL prepare() failed: ' . htmlspecialchars($conn->error);
    exit();
}

$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo 'Email already exists';
    $stmt_check->close();
    $conn->close();
    exit();
}

$stmt_check->close();

// Generate and send OTP via email
$otp = generateOTP();

// Save the OTP and email in session variables
session_start();
$_SESSION['otp'] = $otp;
$_SESSION['email'] = $email;
$_SESSION['signupPassword'] = $pass;

// Include PHPMailer
require '../vendor/autoload.php'; // Adjust path as needed

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; //phpmailer is configured to use SMTP server.

$mail = new PHPMailer(true);
try 
{   //phpmailer uses the smtp settings to connect smtp server
    // SMTP configuration (adjust as per your email provider)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = $email; // Replace with your email address
    $mail->Password = $pass; // Replace with your email password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Sender and recipient settings
    $mail->setFrom($email, 'Hospital Navigator'); // Replace with your email and name
    $mail->addAddress($email);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP for Hospital Navigator Registration';
    $mail->Body    = "Your OTP is <b>$otp</b>";

    // Send email
    $mail->send();
    echo 'OTP has been sent to your email';
} 
catch (Exception $e) 
{
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    exit();
}

// Redirect to OTP verification page
header('Location: verify_otp.php'); // Replace with your OTP verification page
exit();
?>
