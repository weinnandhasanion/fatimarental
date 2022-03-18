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
  -- 0 = Admin, 1 = Manager
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
    `room_number` int,
    `status` TINYINT CHECK (`status` < 2),
    -- Status: 0 = Unoccupied, 1 = Occupied
    `price` INT,
    `capacity` int,
    `description` TEXT,
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  );
insert into
  `rooms` (
    id,
    room_number,
    `status`,
    price,
    capacity,
    `description`
  )
values
  (
    1,
    101,
    0,
    2000,
    2,
    'Our king size four poster provides views over landscaped gardens. It has a seating area, ample storage, digital safe and mini fridge.'
  ),
  (
    2,
    102,
    0,
    3000,
    3,
    'Our king size sleigh bedded also provides views over landscaped gardens. It has ample storage, a seating area, digital safe and mini fridge.'
  ),
  (
    3,
    103,
    0,
    4000,
    4,
    'Our Deluxe Twin/Large Double also provides views over landscaped gardens. It has a seating area, digital safe and mini fridge. This room can be configured with either 2 single beds or zip and linked to provide a large double bed.'
  );
create table `tenants` (
    `id` int AUTO_INCREMENT,
    `room_id` int,
    `username` VARCHAR(255),
    `password` VARCHAR(255),
    `first_name` VARCHAR(255),
    `last_name` VARCHAR(255),
    `middle_initial` VARCHAR(2),
    `gender` TINYINT CHECK (`gender` < 3),
    -- Gender: 0 = Male, 1 = Female, 2 = Unspecified
    `email_address` VARCHAR(255),
    `address` VARCHAR(255),
    `contact_number` VARCHAR(255),
    `birthdate` DATE,
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
create table `tenant_room_history` (
    `id` int AUTO_INCREMENT,
    `tenant_id` INT,
    `room_id` INT,
    -- Status: 0 = Inactive, 1 = Active
    `end_date` DATE,
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`),
    FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`)
  );
create table `reservations` (
    `id` INT AUTO_INCREMENT,
    `room_id` int,
    `name` VARCHAR(255),
    `email_address` VARCHAR(255),
    `contact_number` VARCHAR(255),
    `move_date` DATE,
    `message` VARCHAR(255),
    `admin_id` int,
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`)
  );
create table `bills` (
    `id` INT AUTO_INCREMENT,
    `room_id` int,
    `admin_id` int,
    `room_charge` FLOAT,
    `electricity_balance` FLOAT,
    `water_balance` FLOAT, 
    `start_period` timestamp NULL,
    `end_period` TIMESTAMP NULL,
    `date_added` timestamp not null DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`),
    FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`)
  );
create table `tenant_bills` (
    `id` INT AUTO_INCREMENT,
    `tenant_id` int,
    `bill_id` int,
    `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`),
    FOREIGN KEY (`bill_id`) REFERENCES `bills`(`id`)
  );