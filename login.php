<?php
// Establish a database connection
$conn = mysqli_connect('localhost', 'root', 'mysql', 'projectv5');

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the login credentials from the form
$username = $_POST['username'];
$password = $_POST['password'];

// Check the user authentication by querying the 'users' table
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Start a session
    session_start();

    // Store the username in the session variable
    $_SESSION['username'] = $username;

    // Redirect to the homepage
    header('Location: homepage.php');
} else {
    echo "Authentication failed!";
}

// Close the database connection
mysqli_close($conn);
?>
