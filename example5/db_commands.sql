DROP DATABASE NeatTreats;
CREATE DATABASE NeatTreats;
USE NeatTreats;

CREATE TABLE Cart (
    CartID INT auto_increment,
    ProductID INT,
    Quantity INT,
    PRIMARY KEY (CartID, ProductID)
);

CREATE TABLE Product (
    ProductID INT auto_increment,
    Name VARCHAR(32),
    Description VARCHAR(255),
    Price FLOAT,
    PRIMARY KEY (ProductID)
);

INSERT INTO Product
    (ProductID, Name, Description, Price)
VALUES
    (1, 'Vanilla Cake', 'Tasty vanilla flavoured sponge cake', 12.34),
    (2, 'Chocolate Cake', 'Scrumptious chocolate flavoured sponge cake', 32.14),
    (3, 'Strawberry Cake', 'Yummy strawberry flavoured sponge cake', 42.35);

ALTER TABLE Cart ADD FOREIGN KEY (ProductID) REFERENCES Product (ProductID);
