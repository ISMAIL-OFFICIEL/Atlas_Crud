drop DATABASE if exists RH_db;
CREATE DATABASE RH_db;
USE RH_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) not null,
  email VARCHAR(100) UNIQUE not null,
  password VARCHAR(255) not null,
  role ENUM('admin','rh','ceo') DEFAULT 'rh'
);

CREATE TABLE employees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) not null,
  tel varchar(10),
  adress VARCHAR(100) not null,
  positions VARCHAR(100) not null,
  contract VARCHAR(100) not null,
  salary DECIMAL(10,2)
);

INSERT INTO users (name, email, password, role) values(
    'Administrateur',
    'admin@yahoo.ma',
    '938fa428023f8033c86e74754c43821d0aa45945',
    'admin'
);


INSERT INTO employees (name, tel, adress, positions, contract, salary) VALUES
('Ahmed El Ghazali', 0612345678, 'Rabat, Morocco', 'Développeur', 'CDI', 15000.00),
('Fatima Zahra Idrissi', 0623456789, 'Casablanca, Morocco', 'Chef de projet', 'CDD', 18000.00),
('Imane Rguibi', 0601234567, 'El Jadida, Morocco', 'Ventes', 'CDD', 9500.00);
