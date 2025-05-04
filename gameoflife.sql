CREATE DATABASE IF NOT EXISTS game_of_life;
USE game_of_life;

-- Users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL
);

-- Game sessions table
CREATE TABLE game_sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  start_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  generations INT DEFAULT 0,
  duration_seconds INT DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Admin users table
CREATE TABLE admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
);
