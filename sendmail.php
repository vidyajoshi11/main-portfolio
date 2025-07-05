<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // 1. Sanitize input
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

    // 2. Connect to MySQL (XAMPP)
    $servername = "localhost";  // or 127.0.0.1
    $username = "root";         // default in XAMPP
    $password = "";             // default in XAMPP
    $dbname = "portfolio";      // Make sure this DB and table exist

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check DB connection
    if ($conn->connect_error) {
        die("<h3>Database connection failed: " . $conn->connect_error . "</h3>");
    }

    // 3. Insert form data into database
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // 4. Log email content instead of sending it
        $logMessage  = "New message received:\n";
        $logMessage .= "Name: $name\n";
        $logMessage .= "Email: $email\n";
        $logMessage .= "Message:\n$message\n";
        $logMessage .= "----------------------------------------\n";

        file_put_contents("log.txt", $logMessage, FILE_APPEND);
        echo "<h3>Email skipped. Message stored and logged.</h3>";
    } else {
        echo "<h3>Failed to store the message. Please try again later.</h3>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<h3>Invalid request</h3>";
}
?>
