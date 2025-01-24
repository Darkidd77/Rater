CREATE DATABASE db_assignment;

USE db_assignment;

CREATE TABLE Admin (
    adminId INT AUTO_INCREMENT PRIMARY KEY,
    adminName VARCHAR(100) NOT NULL,
    adminEmail VARCHAR(150) UNIQUE NOT NULL,
    adminPassword VARCHAR(255) NOT NULL
);

CREATE TABLE TradeCategory (
    tradeId INT AUTO_INCREMENT PRIMARY KEY,
    tradeName VARCHAR(100) NOT NULL
);

CREATE TABLE Tradesman (
    tradesmanId INT AUTO_INCREMENT PRIMARY KEY,
    tradesmanName VARCHAR(100) NOT NULL,
    tradesmanEmail VARCHAR(150) UNIQUE NOT NULL,
    tradesmanPassword VARCHAR(255) NOT NULL,
    tradesmanPhone VARCHAR(100) NOT NULL,
    hourlyRate DECIMAL(10, 2) NOT NULL,
    availableAfter DATE NOT NULL,
    skills TEXT,
    verificationStatus BOOLEAN NOT NULL,
    tradeId INT NOT NULL,
    FOREIGN KEY (tradeId) REFERENCES TradeCategory (tradeId)
);

CREATE TABLE Notification (
    notificationId INT AUTO_INCREMENT PRIMARY KEY,
    notificationMessage TEXT NOT NULL,
    notificationTimestamp DATETIME NOT NULL,
    tradesmanId INT NOT NULL,
    FOREIGN KEY (tradesmanId) REFERENCES Tradesman (tradesmanId)
);

CREATE TABLE Booking (
    bookingId INT AUTO_INCREMENT PRIMARY KEY,
    userName VARCHAR(100) NOT NULL,
    userEmail VARCHAR(150) UNIQUE NOT NULL,
    userPhone VARCHAR(100) NOT NULL,
    userAddress VARCHAR(255) NOT NULL,
    bookingDate DATE NOT NULL,
    bookingToken VARCHAR(5) NOT NULL UNIQUE,
    tradesmanId INT NOT NULL,
    FOREIGN KEY (tradesmanId) REFERENCES Tradesman (tradesmanId)
);

CREATE TABLE Rating (
    ratingId INT AUTO_INCREMENT PRIMARY KEY,
    ratingScore INT CHECK (ratingScore BETWEEN 1 AND 5),
    review TEXT,
    tradesmanId INT NOT NULL,
    bookingId INT NOT NULL,
    FOREIGN KEY (tradesmanId) REFERENCES Tradesman (tradesmanId),
    FOREIGN KEY (bookingId) REFERENCES Booking (bookingId)
);

INSERT INTO
    TradeCategory (tradeName)
VALUES
    ('Plumbing'),
    ('Electrical'),
    ('Carpentry'),
    ('Painting'),
    ('Cleaning');

INSERT INTO Admin (adminName, adminEmail, adminPassword)
VALUES ('admin', 'admin', 'password');

INSERT INTO Tradesman (tradesmanName, tradesmanEmail, tradesmanPassword, tradesmanPhone, hourlyRate, availableAfter, skills, verificationStatus, tradeId)
VALUES
('Ethan Green', 'ethan.green@example.com', SHA1('ethan1234'), '111-222-3333', 55.00, '2025-01-18', 'HVAC Installation, Repair', 1, 1),
('Sophia White', 'sophia.white@example.com', SHA1('sophia2025'), '333-444-5555', 70.00, '2025-02-01', 'Interior Design, Painting', 1, 2),
('Liam Brown', 'liam.brown@example.com', SHA1('liam5678'), '444-666-7777', 80.00, '2025-01-25', 'Roofing, Shingles Repair', 0, 3),
('Olivia Black', 'olivia.black@example.com', SHA1('olivia3456'), '222-111-4444', 60.00, '2025-01-12', 'Gardening, Landscaping', 1, 4),
('Noah Gray', 'noah.gray@example.com', SHA1('noah6543'), '999-888-7777', 50.00, '2025-01-30', 'Furniture Assembly, Wood Polishing', 0, 5),
('Ava Blue', 'ava.blue@example.com', SHA1('ava7890'), '888-555-3333', 45.00, '2025-01-20', 'Plumbing, Leak Fixing', 1, 3),
('Mason Silver', 'mason.silver@example.com', SHA1('mason1122'), '777-333-9999', 65.00, '2025-02-05', 'Tile Work, Flooring', 0, 2),
('Emma Red', 'emma.red@example.com', SHA1('emma4455'), '555-444-3333', 75.00, '2025-01-15', 'Electrical Repairs, Appliance Setup', 1, 3),
('Lucas Gold', 'lucas.gold@example.com', SHA1('lucas8888'), '666-999-2222', 40.00, '2025-01-28', 'Carpentry, Custom Woodwork', 1, 4),
('Isabella Violet', 'isabella.violet@example.com', SHA1('bella2023'), '123-123-1234', 85.00, '2025-01-19', 'Advanced Plumbing, Drainage Systems', 1, 1),
('James Blue', 'james.blue@example.com', SHA1('james9101'), '555-123-9876', 52.50, '2025-02-03', 'Painting, Wall Art', 1, 2),
('Charlotte Green', 'charlotte.green@example.com', SHA1('charlotte2022'), '456-789-0123', 68.00, '2025-02-07', 'Gardening, Lawn Care', 1, 4),
('Benjamin Black', 'benjamin.black@example.com', SHA1('benjamin2345'), '987-654-3210', 72.00, '2025-01-22', 'Roofing, Chimney Repair', 0, 3),
('Amelia Brown', 'amelia.brown@example.com', SHA1('amelia6789'), '222-333-4444', 49.00, '2025-02-10', 'Furniture Restoration', 1, 5),
('Elijah Silver', 'elijah.silver@example.com', SHA1('elijah1123'), '777-888-9999', 60.00, '2025-01-26', 'Plumbing, Pipe Installation', 0, 4),
('Harper White', 'harper.white@example.com', SHA1('harper4455'), '666-555-4444', 54.00, '2025-02-14', 'Tile Work, Grout Cleaning', 1, 2),
('Alexander Red', 'alexander.red@example.com', SHA1('alexander3344'), '333-222-1111', 73.50, '2025-01-29', 'Electrical Installations', 1, 5),
('Mia Gold', 'mia.gold@example.com', SHA1('mia9988'), '111-999-8888', 45.00, '2025-01-21', 'Woodworking, Repairs', 0, 1),
('Daniel Violet', 'daniel.violet@example.com', SHA1('daniel5555'), '222-333-6666', 82.00, '2025-01-17', 'Plumbing, Sewer Systems', 1, 2),
('Abigail Blue', 'abigail.blue@example.com', SHA1('abigail1234'), '999-777-5555', 58.00, '2025-02-04', 'HVAC Maintenance', 1, 1);
