DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Post;
DROP TABLE IF EXISTS WishList;
DROP TABLE IF EXISTS ItemCategory;
DROP TABLE IF EXISTS ItemCondition;
DROP TABLE IF EXISTS PostCategory;
DROP TABLE IF EXISTS Comment;
DROP TABLE IF EXISTS Shopcart;
DROP TABLE IF EXISTS PostPhoto;

CREATE TABLE User (
    id VARCHAR(64) PRIMARY KEY,
    email VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    city VARCHAR(30),
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    biography VARCHAR(255),
    isAdmin BOOLEAN DEFAULT FALSE,
    photo VARCHAR(255) DEFAULT ''
);

CREATE TABLE Post (
    id VARCHAR(64) PRIMARY KEY,
    userId VARCHAR(64),
    name VARCHAR(50),
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(100),
    price REAL,
    condition VARCHAR(30),
    FOREIGN KEY (condition) REFERENCES ItemConditions (condition) ON DELETE NO ACTION ON UPDATE CASCADE,
    FOREIGN KEY (userId) REFERENCES User (id) ON DELETE NO ACTION ON UPDATE CASCADE
);


CREATE TABLE PostPhoto (
    postId VARCHAR(64),
    photo VARCHAR(255),
    PRIMARY KEY(postId, photo),
    FOREIGN KEY (postId) REFERENCES Post(id) ON DELETE NO ACTION ON UPDATE CASCADE

);

CREATE TABLE WishList (
    userId VARCHAR(64), 
    postId VARCHAR(64),
    PRIMARY KEY(userId, postId),
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (postId) REFERENCES Post(id) ON DELETE NO ACTION ON UPDATE CASCADE
);


CREATE TABLE Shopcart (
    userId VARCHAR(64), 
    postId VARCHAR(64),
    PRIMARY KEY(userId, postId),
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (postId) REFERENCES Post(id) ON DELETE NO ACTION ON UPDATE CASCADE
);


CREATE TABLE ItemCategory (
    category VARCHAR(30) PRIMARY KEY
);

CREATE TABLE ItemCondition (
    condition VARCHAR(30) PRIMARY KEY
);

CREATE TABLE PostCategory (
    postId VARCHAR(64),
    category VARCHAR(30),
    PRIMARY KEY(postId, category),
    FOREIGN KEY (postId) REFERENCES Post(id) ON DELETE NO ACTION ON UPDATE CASCADE,
    FOREIGN KEY (category) REFERENCES ItemCategories(category) ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE Comment (
    id VARCHAR(64) NOT NULL,
    by VARCHAR(64) NOT NULL,
    post VARCHAR(64) NOT NULL,
    text VARCHAR(1000) NOT NULL,
    timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    repliedTo VARCHAR(23) NOT NULL,
    FOREIGN KEY (by) REFERENCES User(id) ON DELETE CASCADE  ON UPDATE CASCADE,
    FOREIGN KEY (post) REFERENCES Post(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (repliedTo) REFERENCES Comments(id) ON DELETE NO ACTION ON UPDATE CASCADE
);

INSERT INTO ItemCategory (category) VALUES 
    ('Electronics'), 
    ('Clothing'), 
    ('Furniture'), 
    ('Books'), 
    ('Toys'), 
    ('Sports'), 
    ('Tools'), 
    ('Other');

INSERT INTO ItemCondition (condition) VALUES 
    ('New'), 
    ('Like New'), 
    ('Good'), 
    ('Fair'), 
    ('Poor');

INSERT INTO User (id, email, name, password, city, biography, isAdmin, photo) VALUES 
    ('0172620dfba2e09653b6f806a3e44d347cf1a5653f1bc3af5b5decd5db588b75', 'goncalocm10@gmail.com', 'goncalo marques', '$2y$10$Fyib.iSLtQFiuSaX2MbwSe5xYE1fbvbAJ/DEz.DJ5lal9DunzL2TK', 'Porto', 'I am a student at FEUP', FALSE, 'ad8178546343dc70485980ba01bca0d7.png'),
    ('27b975ef0cb693398c34a5ac483d9f8b65b9d3569d6463f823ed730f5e09d7ba', 'migas2004@gmail.com', 'Miguel', '$2y$10$MOWKyp76mp2BdiHChZfuKer6sXQRnlkMVpQ5lotAhON/dRtSylpF.', 'Lamego', 'I sell shoes', FALSE, '3b038ea77504ad0b34a4b27cb19b7e26.jpeg'),
    ('dce8d069e4de1421ef009f3bbccca5d2a228f257724296618e3a044191f3fdc7', 'nunorios11@gmail.com', 'Nuno nunes', '$2y$10$/V0l5AKnRLHEbO4qewidA.4J16zpplZB6Q25TMpSozFEdZuIPVZSS', 'Oliveira de Azemeis', 'Jordan seller', FALSE, 'a64fcdb166badeccfe4666c96f31d74a.jpeg'),
    ('517e79bd87635a4c10f5b1f8b822fbc91abfb562dd7914dc0a1eb2dd61c6fe3d', 'cris@gmail.com', 'ronaldo', '$2y$10$RofaJV1OMC2nHejO/1BXUOkys3qalpNToHMecpwpJxVuoLXhF.Oz.', 'Madeira', 'Siuuu', TRUE, '4f9745e50b4df26b4ef02bfb266009e1.jpeg'),
    ('42033e8fd565961a3146bf074df164e976410a36e329f9dbd8e1968a859953a9', 'joaobaiao@gmail.com', 'joao baiao', '$2y$10$O0Vze3P6jq1JGrx0fnSyKuVj9VjI45Bbm9fZ4M70sbdUrYUAQUQ2i', 'Portugal', 'Viva a SIC', TRUE, '1df3ad1861b6fe6e876a4462a3d3b1e2.jpeg');
    

INSERT INTO Post (id, userId, name, description, price, condition) VALUES 
    ('d32257e595d59661a2138cc4e6262cb63d905479959e7535a319545b5c35d0f2', '0172620dfba2e09653b6f806a3e44d347cf1a5653f1bc3af5b5decd5db588b75', 'Iphone17', 'Iphone17 like new. Selling because I don&#039;t like the eaten apple in the back', 700.0, 'Like New'),
    ('225736f6ffd6821a1819e28dd5850d81f6d659a28021b5f4c67b6177003db1f6', '0172620dfba2e09653b6f806a3e44d347cf1a5653f1bc3af5b5decd5db588b75', 'Boat', 'Boat for the water', 600.0, 'Good'),
    ('fe987891133268ddb2763df691eb9eb095dce63f0e65d80aeba01cf87ee7f51f', '27b975ef0cb693398c34a5ac483d9f8b65b9d3569d6463f823ed730f5e09d7ba', 'Alheira', 'Alheira de Lamego', 6.0, 'New'),
    ('0305d637cd5c7e2698a92cc10337ee66dff2bcd44276f0209c41d71439ea4cd0', 'dce8d069e4de1421ef009f3bbccca5d2a228f257724296618e3a044191f3fdc7', 'Jordan', 'Jordan shoes', 150.0, 'New');

INSERT INTO PostCategory(postId, category) VALUES 
    ('d32257e595d59661a2138cc4e6262cb63d905479959e7535a319545b5c35d0f2', 'Electronics'),
    ('225736f6ffd6821a1819e28dd5850d81f6d659a28021b5f4c67b6177003db1f6', 'Other'),
    ('fe987891133268ddb2763df691eb9eb095dce63f0e65d80aeba01cf87ee7f51f', 'Food'),
    ('0305d637cd5c7e2698a92cc10337ee66dff2bcd44276f0209c41d71439ea4cd0', 'Clothing');

INSERT INTO PostPhoto (postId, photo) VALUES 
    ('d32257e595d59661a2138cc4e6262cb63d905479959e7535a319545b5c35d0f2', 'uploads/items/664a7dc89dc11-iPhone 15 Pro Black Titanium.jpeg'),
    ('d32257e595d59661a2138cc4e6262cb63d905479959e7535a319545b5c35d0f2', 'uploads/items/664a7dc89df0e-iPhone 1200-80.jpg'),
    ('225736f6ffd6821a1819e28dd5850d81f6d659a28021b5f4c67b6177003db1f6', 'uploads/items/664a7e665e0e0-Boat.jpeg'),
    ('225736f6ffd6821a1819e28dd5850d81f6d659a28021b5f4c67b6177003db1f6', 'uploads/items/664a7e665f3fa-Boat (1).jpeg'),
    ('fe987891133268ddb2763df691eb9eb095dce63f0e65d80aeba01cf87ee7f51f', 'uploads/items/664a7f75101c2-Alheira de Lamego.jpeg'),
    ('0305d637cd5c7e2698a92cc10337ee66dff2bcd44276f0209c41d71439ea4cd0', 'uploads/items/664a7ffee3985-Jordans.jpeg'),
    ('0305d637cd5c7e2698a92cc10337ee66dff2bcd44276f0209c41d71439ea4cd0', 'uploads/items/664a7ffee3bcb-Jordans (2).jpeg'),
    ('0305d637cd5c7e2698a92cc10337ee66dff2bcd44276f0209c41d71439ea4cd0', 'uploads/items/664a7ffee3cb2-Jordans (1).jpeg');

INSERT INTO Comment (id, by, post, text, timestamp, repliedTo) VALUES 
    ('2229d1526336eedec1e72b2d57d83e95a96d648a369d395769d5b59d7475d530', 'dce8d069e4de1421ef009f3bbccca5d2a228f257724296618e3a044191f3fdc7', '225736f6ffd6821a1819e28dd5850d81f6d659a28021b5f4c67b6177003db1f6', 'Hello, nice boat', '2024-05-19 22:55:26', ''),
    ('65f817c295580f530ef30d7a74694c1e1ac5b36bff879a56697e6e2b01cf476a', '42033e8fd565961a3146bf074df164e976410a36e329f9dbd8e1968a859953a9', '225736f6ffd6821a1819e28dd5850d81f6d659a28021b5f4c67b6177003db1f6', 'Nice Boat', '2024-05-19 23:14:41', ''),
    ('0ca49e92b39ef5be287b4f064766c7d767593eaaf64b7b8439a8417f97bfa7b6', '42033e8fd565961a3146bf074df164e976410a36e329f9dbd8e1968a859953a9', '225736f6ffd6821a1819e28dd5850d81f6d659a28021b5f4c67b6177003db1f6', 'Agree', '2024-05-19 23:15:02', '65f817c295580f530ef30d7a74694c1e1ac5b36bff879a56697e6e2b01cf476a');

