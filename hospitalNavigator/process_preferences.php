<?php
// Include database connection file
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

// Check if the form is submitted with GET method
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Retrieve form data
    $name = isset($_GET['name']) ? $_GET['name'] : null;
    $age = $_GET['age'];
    $health_issue = $_GET['healthIssue'];
    $specialist = $_GET['specialist'];
    $area_of_hospital = $_GET['area'];
    $min_cost = $_GET['minCost'];
    $max_cost = $_GET['maxCost'];
    $ac_preference = isset($_GET['acPreference']) ? ($_GET['acPreference'] == 'Yes' ? 1 : 0) : null;
    $number_of_beds = isset($_GET['numBeds']) ? $_GET['numBeds'] : null;
    $created_at = date('Y-m-d H:i:s');

    // Prepare the SQL query
    $query = "INSERT INTO user_preferences (name, age, health_issue, specialist, area_of_hospital, min_cost, max_cost, ac_preference, number_of_beds, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Bind the parameters
    $stmt->bind_param("sssssddiis", $name, $age, $health_issue, $specialist, $area_of_hospital, $min_cost, $max_cost, $ac_preference, $number_of_beds, $created_at);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to hospital_images.php upon successful insertion
        header("Location: hospital_images.php?healthIssue=$health_issue&specialist=$specialist&area=$area_of_hospital&minCost=$min_cost&maxCost=$max_cost");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
