<?php
// submit_feedback.php

// 1. Database Connection Details
// IMPORTANT: Replace these with your actual database credentials
$servername = "localhost"; // Usually "localhost" for local development
$username = "root";      // Your MySQL username
$password = "";          // Your MySQL password (often empty for local XAMPP/WAMP/MAMP)
$dbname = "website_feedback_db"; // The database name you created

// 2. Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. Process form data when it's submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input from the form
    // mysqli_real_escape_string helps prevent SQL injection
    $feedback = $conn->real_escape_string($_POST['feedback']);
    $email = $conn->real_escape_string($_POST['email']);

    // Check if feedback is not empty (as it's a required field in HTML)
    if (!empty($feedback)) {
        // 4. Prepare and execute the SQL INSERT statement
        $sql = "INSERT INTO feedback_entries (feedback, email) VALUES ('$feedback', '$email')";

        if ($conn->query($sql) === TRUE) {
            // Data inserted successfully
            // Redirect back to index.html with a success message
            header("Location: index.html?status=success");
            exit(); // Always exit after a header redirect
        } else {
            // Error inserting data
            // Redirect back to index.html with an error message
            header("Location: index.html?status=error&message=" . urlencode($conn->error));
            exit();
        }
    } else {
        // Feedback is empty
        header("Location: index.html?status=error&message=" . urlencode("Feedback cannot be empty."));
        exit();
    }
} else {
    // If someone tries to access this page directly (not via POST method)
    header("Location: index.html");
    exit();
}

// 5. Close the database connection
$conn->close();
?>