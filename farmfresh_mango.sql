-- SQL Dump for `farmfresh mango` database
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Database: `farmfresh mango`
-- --------------------------------------------------------

-- Table structure for `blogdata`
CREATE TABLE `blogdata` (
  `blogId` int(10) NOT NULL AUTO_INCREMENT,
  `blogUser` varchar(256) NOT NULL,
  `blogTitle` varchar(256) NOT NULL,
  `blogContent` longtext NOT NULL,
  `blogCategory` varchar(100) DEFAULT 'General', -- Blog category
  `blogImage` varchar(256) DEFAULT NULL,         -- Blog images
  `blogTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `likes` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`blogId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for `blogdata`
INSERT INTO `blogdata` (`blogUser`, `blogTitle`, `blogContent`, `blogCategory`, `blogImage`, `likes`) VALUES
('ThePhenom', 'Welcome to FarmFresh', '<p>Welcome to the ultimate mango marketplace!</p>', 'Announcement', 'welcome.jpg', 10),
('John Mutembei', 'Benefits of Mango Farming', '<p>Mango farming can boost rural economies...</p>', 'Education', 'mango_farming.jpg', 15);

-- Table structure for `blogfeedback`
CREATE TABLE `blogfeedback` (
  `feedbackId` int(10) NOT NULL AUTO_INCREMENT,
  `blogId` int(10) NOT NULL,
  `comment` varchar(256) NOT NULL,
  `commentUser` varchar(256) NOT NULL,
  `commentPic` varchar(256) NOT NULL DEFAULT 'profile0.png',
  `commentTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`feedbackId`),
  FOREIGN KEY (`blogId`) REFERENCES `blogdata`(`blogId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for `blogfeedback`
INSERT INTO `blogfeedback` (`blogId`, `comment`, `commentUser`, `commentPic`) VALUES
(1, 'Excited to learn more!', 'Jane Doe', 'profile1.png'),
(2, 'Great article, very informative.', 'FarmExpert', 'profile2.png');

-- Table structure for `buyer`
CREATE TABLE `buyer` (
  `bid` int(10) NOT NULL AUTO_INCREMENT,
  `bname` varchar(100) NOT NULL,
  `busername` varchar(100) NOT NULL,
  `bpassword` varchar(255) NOT NULL,
  `bhash` varchar(255) NOT NULL,
  `bemail` varchar(255) NOT NULL,
  `bmobile` varchar(255) NOT NULL,
  `baddress` text NOT NULL,
  `bactive` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for `buyer`
INSERT INTO `buyer` (`bname`, `busername`, `bpassword`, `bhash`, `bemail`, `bmobile`, `baddress`) VALUES
('Wanjau Taiku', 'ThePhenom', 'password123', '<?= password_hash("password123", PASSWORD_DEFAULT) ?>', 'wanjautaiku@gmail.com', '0721256122', 'Kathonzweni, Makueni'),
('Jane Muthoni', 'jane_mso', 'securepass', '<?= password_hash("securepass", PASSWORD_DEFAULT) ?>', 'janemso@gmail.com', '0741745593', 'Muvau, Makueni');

-- Table structure for `farmer`
CREATE TABLE `farmer` (
  `fid` int(10) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) NOT NULL,
  `fusername` varchar(255) NOT NULL,
  `fpassword` varchar(255) NOT NULL,
  `fhash` varchar(255) NOT NULL,
  `femail` varchar(255) NOT NULL,
  `fmobile` varchar(255) NOT NULL,
  `faddress` text NOT NULL,
  `factive` int(10) NOT NULL DEFAULT '0',
  `frating` int(11) NOT NULL DEFAULT '0',
  `picExt` varchar(255) NOT NULL DEFAULT 'png',
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for `farmer`
INSERT INTO `farmer` (`fname`, `fusername`, `fpassword`, `fhash`, `femail`, `fmobile`, `faddress`, `factive`, `frating`, `picExt`) VALUES
('John Mutembei', 'johnny_farm', 'farmerpass', '<?= password_hash("farmerpass", PASSWORD_DEFAULT) ?>', 'johnny@gmail.com', '0720828777', 'Nguumo, Makueni', 1, 5, 'jpg'),
('Mary Mwende', 'mary_farm', 'marypass', '<?= password_hash("marypass", PASSWORD_DEFAULT) ?>', 'mwendemary@gmail.com', '0794385151', 'Kathonzweni, Makueni', 1, 4, 'png');

-- Table structure for `fproduct`
CREATE TABLE `fproduct` (
  `fid` INT(10) NOT NULL,
  `pid` INT(10) NOT NULL AUTO_INCREMENT,
  `product` VARCHAR(255) NOT NULL,
  `pcat` VARCHAR(255) NOT NULL,
  `pinfo` TEXT NOT NULL,
  `price` FLOAT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 0,
  `pimage` VARCHAR(255) NOT NULL DEFAULT 'blank.png',
  `picStatus` INT(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`),
  FOREIGN KEY (`fid`) REFERENCES `farmer`(`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for `fproduct`
INSERT INTO `fproduct` (`fid`, `product`, `pcat`, `pinfo`, `price`, `quantity`, `pimage`, `picStatus`) VALUES
(1, 'Apple Mango', 'Fruit', 'Fresh and juicy Apple Mangoes from Nguumo, Makueni', 150.00, 50, 'apple_mango1.jpg', 1),
(2, 'Kent Mango', 'Fruit', 'Delicious Kent Mangoes perfect for smoothies', 200.00, 30, 'kent_mango1.jpg', 1);

-- Table structure for `likedata`
CREATE TABLE `likedata` (
  `likeId` int(10) NOT NULL AUTO_INCREMENT,
  `blogId` int(10) NOT NULL,
  `blogUserId` int(10) NOT NULL,
  PRIMARY KEY (`likeId`),
  FOREIGN KEY (`blogId`) REFERENCES `blogdata`(`blogId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for `likedata`
INSERT INTO `likedata` (`blogId`, `blogUserId`) VALUES
(1, 1),
(2, 2);

-- Table structure for `mycart`
CREATE TABLE `mycart` (
  `cartId` int(10) NOT NULL AUTO_INCREMENT,
  `bid` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  PRIMARY KEY (`cartId`),
  FOREIGN KEY (`bid`) REFERENCES `buyer`(`bid`),
  FOREIGN KEY (`pid`) REFERENCES `fproduct`(`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for `mycart`
INSERT INTO `mycart` (`bid`, `pid`) VALUES
(1, 1),
(2, 2);

-- Table structure for `review`
CREATE TABLE `review` (
  `reviewId` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rating` int(10) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`reviewId`),
  FOREIGN KEY (`pid`) REFERENCES `fproduct`(`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for `review`
INSERT INTO `review` (`pid`, `name`, `rating`, `comment`) VALUES
(1, 'Wanjau Taiku', 5, 'Fresh mangoes! Very sweet.'),
(2, 'Jane Muthoni', 4, 'Perfect mangoes for smoothies.');

-- Table structure for `transaction`
CREATE TABLE `transaction` (
  `tid` int(10) NOT NULL AUTO_INCREMENT,
  `bid` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `addr` varchar(255) NOT NULL,
  PRIMARY KEY (`tid`),
  FOREIGN KEY (`bid`) REFERENCES `buyer`(`bid`),
  FOREIGN KEY (`pid`) REFERENCES `fproduct`(`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for `transaction`
INSERT INTO `transaction` (`bid`, `pid`, `name`, `city`, `mobile`, `email`, `pincode`, `addr`) VALUES
(1, 1, 'Wanjau Taiku', 'Makueni', '0721256122', 'wanjautaiku@gmail.com', '12345', 'Kathonzweni, Makueni'),
(2, 2, 'Jane Muthoni', 'Makueni', '0741745593', 'janemso@gmail.com', '67890', 'Muvau, Makueni');

-- Table structure for `members`
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,            
    username VARCHAR(255) NOT NULL,               -- User's username
    password VARCHAR(255) NOT NULL,               -- User's password (hashed)
    email VARCHAR(255) NOT NULL,                  -- User's email address
    mobile VARCHAR(15),                           -- User's mobile number
    address TEXT,                                 -- User's address
    picStatus INT DEFAULT 0,                      -- Profile picture status (0 = no picture, 1 = has picture)
    picName VARCHAR(255) DEFAULT 'profile0.png',  -- Profile picture file name (default to 'profile0.png')
    picExt VARCHAR(10) DEFAULT 'png',             -- Profile picture extension (default to 'png')
    rating DECIMAL(2,1) DEFAULT 0,                -- User's rating (default to 0)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- When the account was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- Last time user data was updated
);

-- Example indexes for username and email to improve performance on lookups
CREATE INDEX idx_username ON members(username);
CREATE INDEX idx_email ON members(email);

-- Table structure for `messages`
CREATE TABLE IF NOT EXISTS messages ( 
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,  -- buyer or farmer id
    recipient_id INT NOT NULL,  -- recipient id (buyer or farmer)
    message TEXT NOT NULL,
    date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    message_type ENUM('buyer', 'farmer') NOT NULL,
    FOREIGN KEY (sender_id) REFERENCES buyer(bid),
    FOREIGN KEY (recipient_id) REFERENCES farmer(fid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Data for Messages Table
INSERT INTO messages (sender_id, recipient_id, message, message_type) VALUES
(1, 2, 'Hi John, I love your mangoes! Can you send more next week?', 'buyer'),
(2, 1, 'Hello Jane, thank you for the feedback. I will send more mangoes next week.', 'farmer');

-- --------------------------------------------------------


