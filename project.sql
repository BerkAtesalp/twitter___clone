-- Table for holding information about USERS
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  followers INT DEFAULT 0,
  following INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_username (username)
);

-- Table for holding TWEETs that users post
CREATE TABLE tweets (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (username) REFERENCES users(username)
);

-- Table for holding information about who FOLLOWs who
CREATE TABLE follows (
  follower_username VARCHAR(255) NOT NULL,
  following_username VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (follower_username) REFERENCES users(username),
  FOREIGN KEY (following_username) REFERENCES users(username)
);

-- Stored procedure for displaying tweets on the homepage
DELIMITER //
CREATE PROCEDURE display_tweets_homepage()
BEGIN
  SELECT users.username, tweets.content, tweets.created_at
  FROM tweets
  INNER JOIN users ON users.username = tweets.username
  ORDER BY tweets.created_at DESC;
END //
DELIMITER ;

-- Stored procedure for displaying tweets on the profile page
DELIMITER //
CREATE PROCEDURE display_tweets_profile(username TEXT)
BEGIN
  SELECT users.username, tweets.content, tweets.created_at
  FROM tweets
  INNER JOIN users ON users.username = tweets.username
  WHERE tweets.username = username
  ORDER BY tweets.created_at DESC;
END //
DELIMITER ;
