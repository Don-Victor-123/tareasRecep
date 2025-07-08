-- SQL Schema: hotel_tasks
CREATE DATABASE IF NOT EXISTS hotel_tasks;
USE hotel_tasks;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    role ENUM('admin','receptionist') NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    state ENUM('Pendiente','En proceso','Realizada') DEFAULT 'Pendiente',
    date DATE NOT NULL,
    shift ENUM('Matutino','Vespertino','Nocturno') NOT NULL,
    created_by VARCHAR(50) NOT NULL,
    assigned_to VARCHAR(50)
);
