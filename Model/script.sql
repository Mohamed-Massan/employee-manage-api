
CREATE DATABASE employees_db;

CREATE TABLE employees_db.employees (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  phone VARCHAR(20) NOT NULL
);

INSERT INTO employees (name, email, phone)
VALUES
('otman', 'otman@ntic.com', '06555-1234'),
('mohamed', 'mohamed@ntic.com', '06555-5678'),
('alae', 'alae@ntic.com', '06555-9876'),
('soad', 'soad@ntic.com', '06555-5555'),
('aicha', 'aicha@ntic.com', '06555-1111');

