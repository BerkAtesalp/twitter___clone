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
    <title>Homepage</title>
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <div class="container">
      <h1>Homepage</h1>

      <div class="search-form">
        <form method="get" action="">
          <input type="text" id="search" name="search" placeholder="Search users" required />
          <button type="submit">Search</button>
        </form>
      </div>

      <div class="profile-btn">
        <a href="profile.php">Profile</a>
      </div>

      <div class="logout-btn">
        <a href="logout.php">Logout</a>
      </div>

      <?php
      // Establish a database connection
      $conn = mysqli_connect('localhost', 'root', 'mysql', 'projectv5');

      // Check the connection
      if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
      }

      // Check if a search query is submitted
      if (isset($_GET['search'])) {
          $search = $_GET['search'];

          // Search for users matching the query
          $searchSql = "SELECT * FROM users WHERE username LIKE '%$search%' OR name LIKE '%$search%'";
          $searchResult = mysqli_query($conn, $searchSql);

          if (mysqli_num_rows($searchResult) > 0) {
              echo "<h3>Search Results:</h3>";
              while ($row = mysqli_fetch_assoc($searchResult)) {
                  // Display each user
                  echo "<p><strong>Username:</strong> {$row['username']}</p>";
                  echo "<p><strong>Name:</strong> {$row['name']}</p>";
                  echo "<button onclick=\"followUser('{$row['username']}')\">Follow</button>";
                  echo "<hr>";
              }
          } else {
              echo "No users found.";
          }
      }

      // Retrieve and display the tweets of the followed users by calling the stored procedure
      $tweetsSql = "CALL display_tweets_homepage()";
      $tweetsResult = mysqli_query($conn, $tweetsSql);

      if ($tweetsResult && mysqli_num_rows($tweetsResult) > 0) {
          echo "<h3>Recent Tweets:</h3>";
          while ($row = mysqli_fetch_assoc($tweetsResult)) {
              // Display each tweet
              echo "<p><strong>{$row['username']}</strong>: {$row['content']}</p>";
          }
      } else {
          echo "No tweets found.";
      }

      // Close the database connection
      mysqli_close($conn);
      ?>

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
