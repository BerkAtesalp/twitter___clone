<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <div class="container">
      <?php
      // Establish a database connection
      $conn = mysqli_connect('localhost', 'root', 'mysql', 'projectv5');

      // Check the connection
      if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
      }

      // Retrieve and display the user's information
      $username = $_SESSION['username'];
      $sql = "SELECT * FROM users WHERE username='$username'";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);

          // Display the user's information
          echo "<h2>Welcome, {$row['username']}!</h2>";
          echo "<p>Username: {$row['username']}</p>";

          // Display the follower count
          $followersCount = isset($row['followers']) ? $row['followers'] : 0;
          echo "<p>Followers: $followersCount</p>";

          // Display the following count
          $followingCount = isset($row['following']) ? $row['following'] : 0;
          echo "<p>Following: $followingCount</p>";
      } else {
          echo "User not found.";
      }

      // Retrieve and display the user's tweets
      $tweetsSql = "SELECT * FROM tweets WHERE username='$username' ORDER BY created_at DESC";
      $tweetsResult = mysqli_query($conn, $tweetsSql);

      if ($tweetsResult) {
          if (mysqli_num_rows($tweetsResult) > 0) {
              echo "<h3>Your Tweets:</h3>";
              while ($tweetRow = mysqli_fetch_assoc($tweetsResult)) {
                  // Display each tweet
                  echo "<p>{$tweetRow['content']} (Created at: {$tweetRow['created_at']})</p>";
              }
          } else {
              echo "<p>No tweets found.</p>";
          }
      } else {
          echo "Error: " . mysqli_error($conn);
      }

      // Close the database connection
      mysqli_close($conn);
      ?>
      
      <div class="tweet-form">
        <h3>Write a Tweet:</h3>
        <form method="post" action="tweet.php">
          <textarea id="tweet-content" name="content" rows="3" placeholder="Write your tweet..." required></textarea>
          <button type="submit">Tweet</button>
        </form>
      </div>

      <div class="homepage-link">
        <a href="homepage.php">Go to Homepage</a>
      </div>

      <div class="logout-button">
        <form method="post" action="logout.php">
          <button type="submit">Logout</button>
        </form>
      </div>

      <script>
        function followUser(username) {
          // Send an AJAX request to update the follow relationship
          var xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
              // Display a message indicating that the user is being followed
              alert("You are now following " + username);
              location.reload(); // Refresh the page to update the UI
            }
          };
          xhr.open("POST", "follow.php", true);
          xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhr.send("username=" + username);
        }
      </script>

    </div>
  </body>
</html>
