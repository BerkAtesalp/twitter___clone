<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the tweet content from the form
    $content = $_POST['content'];
    
    // Sanitize the input to prevent SQL injection
    $content = htmlspecialchars($content);
    
    // Establish a database connection
    $conn = mysqli_connect('localhost', 'root', 'mysql', 'projectv5');
    
    // Check the connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Retrieve the username from the session
    $username = $_SESSION['username'];
    
    // Insert the tweet into the database
    $sql = "INSERT INTO tweets (username, content) VALUES ('$username', '$content')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Tweet posted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
    // Close the database connection
    mysqli_close($conn);
}
?>
