create database if not exists `fatimadb`;

use `fatimadb`;

create table `admins` (
    `id` INT AUTO_INCREMENT,
    `first_name` VARCHAR(255),
    `last_name` VARCHAR(255),
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email_address` VARCHAR(255),
    `contact_number` VARCHAR(255),
    `user_type` TINYINT(2),
    -- 0 = Super Admin, 1 = Admin
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

insert into
    admins (
        `id`,
        `first_name`,
        `last_name`,
        `username`,
        `password`,
        `email_address`,
        `contact_number`,
        `user_type`
    )
values
    (
        1,
        'John',
        'Doe',
        'admin',
        '$2y$10$BSvy1V0kD9GU1Ou7KTmNjulnKw2SNJ8pDhz6nIuPPQFMiKuUFN7o.',
        'admin@test.com',
        NULL,
        0
    );

create table `rooms` (
    `id` INT AUTO_INCREMENT,
    `room_name` varchar(255),
    `status` TINYINT DEFAULT 0 CHECK (`status` < 3),
    -- Status: 0 = Available, 1 = Full, 2 = Under Maintenance 
    `price` INT,
    `capacity` int,
    `description` TEXT,
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

insert into
    `rooms` (
        id,
        room_name,
        `status`,
        price,
        capacity,
        `description`
    )
values
    (
        1,
        "St. Peter",
        0,
        5000,
        3,
        'Located in the first floor with a water dispenser outside, this is a spacious room with its own comfort room.'
    ),
    (
        2,
        "St. Maximillian",
        0,
        6000,
        4,
        'Located in the second floor with a nice elevated view outside, this wide room has its own comfort room beside it.'
    ),
    (
        3,
        "St. Albert",
        0,
        4000,
        3,
        'Located in the second floor with a public CR and is near the Wi-Fi router, this room is good for students and for those who are in a budget.'
    ),
    (
        4,
        "St. Therese",
        0,
        4000,
        3,
        'Located in the corner of the second floor with a public CR, this room has a nice view outside and is favorable for those owning pets specifically cats.'
    );
    

create table `tenants` (
    `id` VARCHAR(255),
    `room_id` int,
    `username` VARCHAR(255) DEFAULT NULL,
    `password` VARCHAR(255),
    `first_name` VARCHAR(255),
    `last_name` VARCHAR(255),
    `middle_initial` VARCHAR(2),
    `gender` TINYINT CHECK (`gender` < 3),
    -- Gender: 0 = Male, 1 = Female, 2 = Unspecified
    -- `status` TINYINT(4) DEFAULT 0,
    -- -- Status: 0 = Permanent, 1 = Reserved, 2 = Rejected Reservation
    -- `reservation_account_expiry_date` TIMESTAMP NULL DEFAULT NULL,
    `email_address` VARCHAR(255),
    `address` VARCHAR(255),
    `contact_number` VARCHAR(255),
    `birthdate` DATE,
    `account_status` TINYINT DEFAULT 0,
    -- Account Status: 0 = Active, 1 = Disabled, 2 = Reservation Expired
    `valid_id` VARCHAR(255),
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`)
);

create table `room_images` (
    `id` int AUTO_INCREMENT,
    `room_id` int,
    `image_pathname` TEXT,
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`)
);

INSERT INTO `room_images` (room_id, image_pathname) 
    VALUES (1, 'stpeter.JPG'), 
    (2, 'stmaximillian.JPG'),
    (3, 'stalbert.JPG'),
    (4, 'sttherese.JPG');

create table `tenant_room_history` (
    `id` int AUTO_INCREMENT,
    `tenant_id` VARCHAR(255),
    `from_room_id` INT DEFAULT NULL,
    `to_room_id` INT,
    `end_date` DATE,
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`),
    FOREIGN KEY (`to_room_id`) REFERENCES `rooms`(`id`)
);

create table `reservations` (
    `id` INT AUTO_INCREMENT,
    `room_id` int,
    `first_name` VARCHAR(255),
    `middle_initial` VARCHAR(255),
    `last_name` VARCHAR(255),
    `gender` TINYINT CHECK (`gender` < 3),
    -- Gender: 0 = Male, 1 = Female, 2 = Unspecified,
    `email_address` VARCHAR(255),
    `contact_number` VARCHAR(255),
    `move_date` DATE,
    `date_approved` DATE,
    `message` VARCHAR(255),
    `admin_id` int DEFAULT NULL,
    `status` tinyint(3) DEFAULT 0,
    -- 0: pending, 1: approved, 2: rejected
    `expiry_date` DATE,
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`)
);

create table `bills` (
    `id` INT AUTO_INCREMENT,
    `reference_id` varchar(255),
    `room_id` int,
    `bill_to_tenant_id` VARCHAR(255),
    `admin_id` int,
    `room_charge` FLOAT,
    `electricity_bill` FLOAT,
    `water_bill` FLOAT,
    `total_amount` FLOAT,
    `start_period` timestamp NULL,
    `end_period` TIMESTAMP NULL,
    `date_added` timestamp not null DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`),
    FOREIGN KEY (`bill_to_tenant_id`) REFERENCES `tenants`(`id`),
    FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`)
);

create table `bill_receipts` (
    `id` INT AUTO_INCREMENT,
    `image_pathname` TEXT,
    `bill_id` INT,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`bill_id`) REFERENCES `bills`(`id`)
);

create table `additional_charges` (
    `id` INT AUTO_INCREMENT,
    `bill_id` INT,
    `name` VARCHAR(255),
    `charge` FLOAT,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`bill_id`) REFERENCES `bills`(`id`)
);

create table `tenant_bills` (
    `id` INT AUTO_INCREMENT,
    `tenant_id` VARCHAR(255),
    `bill_id` int,
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`),
    FOREIGN KEY (`bill_id`) REFERENCES `bills`(`id`)
);

create table `payments` (
    `id` INT auto_increment,
    `amount` INT,
    `remarks` VARCHAR(255),
    `reference_id` VARCHAR(255) UNIQUE,
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

create table `bill_payments` (
    `id` INT auto_increment,
    `payment_id` int,
    `bill_id` int,
    `date_added` TIMESTAMP NOT NULL DEFAULT current_timestamp,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`payment_id`) REFERENCES `payments`(`id`),
    FOREIGN KEY (`bill_id`) REFERENCES `bills`(`id`)
);

INSERT INTO
    `tenants` (
        `id`,
        `room_id`,
        `username`,
        `password`,
        `first_name`,
        `last_name`,
        `middle_initial`,
        `gender`,
        `email_address`,
        `address`,
        `contact_number`,
        `birthdate`,
        `valid_id`,
        `date_added`
    )
VALUES
    (
        'eaaa49ca-6d6b-4a77-85a3-392506d7f9a0',
        '3',
        NULL,
        '$2y$10$BSvy1V0kD9GU1Ou7KTmNjulnKw2SNJ8pDhz6nIuPPQFMiKuUFN7o.',
        'John',
        'Doe',
        'A',
        '0',
        'johndoe@gmail.com',
        'Cebu City',
        '09151234561',
        '1992-01-16',
        NULL,
        current_timestamp()
    );