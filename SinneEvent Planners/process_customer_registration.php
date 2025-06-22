<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shinneeventplanners";
echo "hello";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 echo "Connection success: ";

// Get form data

$customer_id = "TEST1234";
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$dob = $_POST['dob'];

// $firstName = 'firstName';
// $lastName ='lastName';
// $email ='email';
// $phone = 'phone';
// $dob = 'dob';


// Prepare and bind
$stmt = $conn->prepare("INSERT INTO customer (customer_id,first_name, last_name, email, contact_number, date_of_birth) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $customer_id, $firstName, $lastName, $email, $phone, $dob);

// Execute the statement
if ($stmt->execute()) {
    // Success - redirect to thank you page
   // header("Location: registration_success.html");
   echo "success: ";
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>