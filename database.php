<?php

$servername = "localhost"; 
$username = "root";       
$password = "";           
$dbname = "guest_db";  

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function addGuest($conn, $first_name, $last_name, $email) {
    $stmt = $conn->prepare("INSERT INTO guest_info (first_name, last_name, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $first_name, $last_name, $email);
    $stmt->execute();
    $stmt->close();
}

?>