DROP DATABASE IF EXISTS retirement;
CREATE DATABASE IF NOT EXISTS retirement;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS meds;

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
  groupId INT,
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

CREATE TABLE meds (
  medId INT AUTO_INCREMENT,
  patientId INT,
  morningMed VARCHAR(30),
  afternoonMed VARCHAR(30),
  nightMed VARCHAR(30),
  PRIMARY KEY (medId),
  FOREIGN KEY (patientId)
    REFERENCES patients(patientId)
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
       ('patient', 'patient', 5, 'patient@email.com', '123', 0, 0, 1);

INSERT INTO employees (userId, salary, groupId)
VALUES  (1, 0, 0), -- Dummy Admin --
        (2, 0, 1); -- Dummy Doctor --

INSERT INTO patients (userId, familyCode, emergencyContact, emergencyRelation, groupId, admissionDate)
VALUES (3, 123, "Dummy", "Dummy", 1, 0); -- Dummy Patient --
