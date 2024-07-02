<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $followerUsername = $_SESSION['username'];
    $followingUsername = $_POST['username'];

    // Establish a database connection
    $conn = mysqli_connect('localhost', 'root', 'mysql', 'projectv5');

    // Check the connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the follow relationship already exists
    $checkSql = "SELECT * FROM follows WHERE follower_username='$followerUsername' AND following_username='$followingUsername'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        // Follow relationship already exists
        echo "You are already following this user.";
    } else {
        // Add the follow relationship
        $followSql = "INSERT INTO follows (follower_username, following_username) VALUES ('$followerUsername', '$followingUsername')";
        $followResult = mysqli_query($conn, $followSql);

        if ($followResult) {
            // Update the follower count in the profile of the person being followed
            $updateFollowingSql = "UPDATE users SET followers = followers + 1 WHERE username='$followingUsername'";
            mysqli_query($conn, $updateFollowingSql);

            // Update the following count in the user's profile
            $updateFollowerSql = "UPDATE users SET following = following + 1 WHERE username='$followerUsername'";
            mysqli_query($conn, $updateFollowerSql);

            echo "Follow successful.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
