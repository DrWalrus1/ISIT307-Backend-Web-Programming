DROP DATABASE IF EXISTS games;
CREATE DATABASE games;
USE games;

# Create classification table
CREATE TABLE IF NOT EXISTS `classification` (
  `cID` int NOT NULL AUTO_INCREMENT,
  `initial` char(32) NOT NULL,
  `description` varchar(255),
  PRIMARY KEY (`cID`)
) ENGINE=InnoDB;

# Create genre table
CREATE TABLE IF NOT EXISTS `genres` (
  `gID` int NOT NULL AUTO_INCREMENT,
  `gName` varchar(255) NOT NULL,
  `description` varchar(255),
  PRIMARY KEY (`gID`)
) ENGINE=InnoDB;

# Create platforms table
CREATE TABLE IF NOT EXISTS `platforms` (
  `pID` int NOT NULL AUTO_INCREMENT,
  `initial` char(32) NOT NULL,
  `pName` VARCHAR(255),
  `manufacturer` varchar(255),
  `description` varchar(255),
  PRIMARY KEY (`pID`)
) ENGINE=InnoDB;

# Create games table
CREATE TABLE IF NOT EXISTS `games` (
  `gID` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `genre` int NOT NULL,
  `platform` int NOT NULL,
  `classification` int NOT NULL,
  PRIMARY KEY (`gID`),
  KEY `GAMES_PLATFORM_PLATFORM_PID` (`platform`),
  KEY `GAMES_GENRE_GENRES_gID` (`genre`),
  KEY `GAMES_Classification_GENRES_cID` (`classification`),
  CONSTRAINT `GAMES_Classification_GENRES_cID` FOREIGN KEY (`classification`) REFERENCES `classification` (`cID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `GAMES_GENRE_GENRES_gID` FOREIGN KEY (`genre`) REFERENCES `genres` (`gID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `GAMES_PLATFORM_PLATFORM_PID` FOREIGN KEY (`platform`) REFERENCES `platforms` (`pID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

# For Later Reference
# SELECT COLUMN_NAME
# FROM information_schema.COLUMNS
# WHERE table_name = 'games'
#   AND COLUMN_KEY != 'PRI'
# ORDER BY ordinal_position;