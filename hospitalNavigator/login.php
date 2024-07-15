<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Get the form data
$email = $_POST['loginEmail'];
$pass = $_POST['loginPassword'];

// Check if email or password is empty
if (empty($email) || empty($pass)) {
    echo 'Email or Password cannot be empty.';
    exit();
}

// $hashed_pass= password_hash($pass, PASSWORD_BCRYPT);

// Debug: Print the email and password received
// You might want to remove these lines after debugging for security reasons
echo "Email: $email, Password: $pass<br>";

// Retrieve the stored hash
$sql = "SELECT password_hash FROM user_details WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($stored_hash);
    $stmt->fetch();

    // Debug: Print the stored hash
    echo "Stored Hash: $stored_hash<br>";

    // Verify the password
    if (password_verify($pass, $stored_hash)) {
        echo "Login successful!";
        // Start session and set session variables
        session_start();
        $_SESSION['email'] = $email;
        echo "<script>window.location.href = 'search.html';</script>"; // Redirect to the search page
    }
    else 
    {
        echo " Invalid email or password!";
    }
}
else
{
    echo "Invalid email or password!";
}

$stmt->close();
$conn->close();
?>
