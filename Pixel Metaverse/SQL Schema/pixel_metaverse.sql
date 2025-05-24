CREATE DATABASE pixel_metaverse;

USE pixel_metaverse;

-- Users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Art table (simulated NFTs)
CREATE TABLE artworks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  art_data TEXT NOT NULL, -- JSON of pixel data
  title VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);