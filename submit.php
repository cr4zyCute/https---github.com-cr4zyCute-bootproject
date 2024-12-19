<?php
// Database credentials
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "donations";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO donations (name, email, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $name, $email, $amount);

    if ($stmt->execute()) {
        echo "<p>Thank you for your donation!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>