<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $entered_otp = $_POST['otp'];

    // Check if entered OTP matches session OTP
    if ($entered_otp == $_SESSION['otp']) 
    {
        // Retrieve email and password from session
        $email = $_SESSION['email'];
        $signupPassword = $_SESSION['signupPassword'];

        // Database connection settings (same as register.php)
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

        // Insert user details into the database
        $sql_insert = "INSERT INTO user_details (email, password_hash, created_at) VALUES (?, ?, NOW())";
        $stmt_insert = $conn->prepare($sql_insert);

        if ($stmt_insert === false) 
        {
            echo 'MySQL prepare() failed: ' . htmlspecialchars($conn->error);
            exit();
        }

        $hashed_password = password_hash($signupPassword, PASSWORD_BCRYPT); // Ensure to retrieve password securely

        $stmt_insert->bind_param("ss", $email, $hashed_password);

        if ($stmt_insert->execute()) {
            echo "User registered successfully";
            // Optionally, unset session variables after successful registration
            unset($_SESSION['otp']);
            unset($_SESSION['email']);
            unset($_SESSION['signupPassword']);
        } 
        else 
        {
            echo 'MySQL execute() failed: ' . htmlspecialchars($stmt_insert->error);
        }

        $stmt_insert->close();
        $conn->close();

        // Redirect to search page or wherever needed
        header('Location: search.html'); // Replace with your desired redirect location
        exit();
    } 
    else 
    {
        echo 'Invalid OTP. Please try again.';
    }
} 
else 
{
    echo 'Invalid request.';
}
?>
