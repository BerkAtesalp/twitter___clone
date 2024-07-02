<?php

//Create connection
$conn = mysqli_connect('localhost', 'root', 'mysql', 'projectv5');

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the registration data from the form
$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];

// Perform necessary validation on the input data

// Insert the user data into the 'users' table
$sql = "INSERT INTO users (name, username, password) VALUES ('$name', '$username', '$password')";
if (mysqli_query($conn, $sql)) {
    echo "Registration successful!";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
