<?php

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

// Check if required parameters are provided
if (isset($_GET['healthIssue'], $_GET['specialist'], $_GET['area'], $_GET['minCost'], $_GET['maxCost'])) {
    // Retrieve parameters
    $health_issue = $_GET['healthIssue'];
    $specialist = $_GET['specialist'];
    $area_of_hospital = $_GET['area'];
    $min_cost = $_GET['minCost'];
    $max_cost = $_GET['maxCost'];

    try {
        // Prepare the SQL query
        $query = "SELECT hospital_name, image_url
                  FROM hospital_images
                  WHERE health_issue = ?
                    AND specialist = ?
                    AND area_of_hospital = ?
                    AND min_cost <= ?
                    AND max_cost >= ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("sssdd", $health_issue, $specialist, $area_of_hospital, $min_cost, $max_cost);

        // Execute query
        if (!$stmt->execute()) {
            throw new Exception("Error executing query: " . $stmt->error);
        }

        // Bind result variables
        $stmt->bind_result($hospital_name, $image_url);

        // Fetch and display images and hospital names
        $foundHospitals = false;
        while ($stmt->fetch()) {
            echo "<h3>$hospital_name</h3>";
            echo "<img src='$image_url' width='300'><br>";
            $foundHospitals = true;
        }

        if (!$foundHospitals) {
            echo "No hospitals found.";
        }

        // Close statement
        $stmt->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Missing parameters to fetch hospital images.";
}

// Close connection
$conn->close();
?>
