DROP DATABASE IF EXISTS retirement;
CREATE DATABASE IF NOT EXISTS retirement;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS rosters;

CREATE TABLE roles (
  roleId INT AUTO_INCREMENT,
  role VARCHAR(30),
  accessLevel INT,
  PRIMARY KEY (roleId)
);

CREATE TABLE users (
  id INT AUTO_INCREMENT,
  firstName VARCHAR(30),
  lastName VARCHAR(30),
  roleId INT,
  email VARCHAR(30) UNIQUE,
  password VARCHAR(30),
  phone BIGINT UNIQUE,
  dateOfBirth date,
  approved INT,
  PRIMARY KEY (id),
  FOREIGN KEY (roleId)
    REFERENCES roles(roleId)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE patients (
  patientId INT AUTO_INCREMENT,
  userId INT,
  familyCode INT UNIQUE,
  emergencyContact varchar(60),
  emergencyRelation varchar(30),
  groupId INT,
  admissionDate DATE,
  lastUpdateDate DATE,
  totalDue INT,
  PRIMARY KEY (patientId),
  FOREIGN KEY (userId)
    REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE employees (
  employeeId INT AUTO_INCREMENT,
  userId INT,
  salary INT,
  PRIMARY KEY (employeeId),
  FOREIGN KEY (userId)
    REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE appointments (
  appointmentId INT AUTO_INCREMENT,
  doctorId INT,
  patientId INT,
  appDate DATE,
  appComment VARCHAR(100),
  morningMed VARCHAR(30),
  afternoonMed VARCHAR(30),
  nightMed VARCHAR(30),
  PRIMARY KEY (appointmentId),
  FOREIGN KEY (doctorId)
    REFERENCES employees(employeeId)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (patientId)
    REFERENCES patients(patientId)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE rosters (
  rosterId INT AUTO_INCREMENT,
  rosterDate DATE UNIQUE,
  supervisorId INT,
  doctorId INT,
  caregiverOne INT,
  caregiverTwo INT,
  caregiverThree INT,
  caregiverFour INT,
  PRIMARY KEY (rosterId),
  FOREIGN KEY (supervisorId)
    REFERENCES employees(employeeId)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (doctorId)
    REFERENCES employees(employeeId)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (caregiverOne)
    REFERENCES employees(employeeId)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (caregiverTwo)
    REFERENCES employees(employeeId)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (caregiverThree)
    REFERENCES employees(employeeId)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (caregiverFour)
    REFERENCES employees(employeeId)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE checkboxes (
  checkboxesId INT AUTO_INCREMENT,
  checkboxDate DATE,
  patientId INT,
  caregiverId INT,
  morningMed BOOLEAN DEFAULT 0,
  afternoonMed BOOLEAN DEFAULT 0,
  nightMed BOOLEAN DEFAULT 0,
  breakfast BOOLEAN DEFAULT 0,
  lunch BOOLEAN DEFAULT 0,
  dinner BOOLEAN DEFAULT 0,
  PRIMARY KEY (checkboxesId),
  FOREIGN KEY (patientId)
    REFERENCES patients(patientId)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (caregiverId)
    REFERENCES employees(employeeId)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

INSERT INTO roles (role, accessLevel)
VALUES ('admin', 1),
       ('supervisor', 2),
       ('doctor', 3),
       ('caregiver', 4),
       ('patient', 5),
       ('family', 6);

INSERT INTO users (firstName, lastName, roleId, email, password, phone, dateOfBirth, approved)
VALUES ('admin', 'admin', 1, 'admin@email.com', '123', 2, 0, 1),
       ('doctor', 'doctor', 3, 'doctor@email.com', '123', 1, 0, 1),
       ('patient', 'patient', 5, 'patient@email.com', '123', 0, 0, 1),
       ('patienttwo', 'patienttwo', 5, 'patienttwo@email.com', '123', 8, 0, 1),
       ('patienttwo', 'patientthree', 5, 'patientthree@email.com', '123', 9, 0, 1),
       ('supervisor', 'supervisor', 2, 'supervisor@email.com', '123', 3, 0, 1),
       ('caregiverone', 'caregiverone', 4, 'caregiverone@email.com', '123', 4, 0, 1),
       ('caregivertwo', 'caregivertwo', 4, 'caregivertwo@email.com', '123', 5, 0, 1),
       ('caregiverthree', 'caregiverthree', 4, 'caregiverthree@email.com', '123', 6, 0, 1),
       ('caregiverfour', 'caregiverfour', 4, 'caregiverfour@email.com', '123', 7, 0, 1);

INSERT INTO employees (userId, salary)
VALUES  (1, 0), -- Dummy Admin --
        (2, 0), -- Dummy Doctor --
        (6, 0), -- Dummy Supervisor --
        (7, 0), -- Dummy Caregivers --
        (8, 0),
        (9, 0),
        (10, 0);

INSERT INTO patients (userId, familyCode, emergencyContact, emergencyRelation, groupId, admissionDate)
VALUES (3, 123, "Dummy", "Dummy", 1, 0), -- Dummy Patient --
       (4, 1234, "Dummy", "Dummy", 2, 0),
       (5, 12345, "Dummy", "Dummy", 3, 0);
