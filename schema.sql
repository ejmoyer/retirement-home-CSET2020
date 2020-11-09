DROP DATABASE IF EXISTS retirement;
CREATE DATABASE IF NOT EXISTS retirement;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS rosters;
DROP TABLE IF EXISTS meds;

CREATE TABLE roles (
  roleId INT AUTO_INCREMENT,
  role VARCHAR(30),
  PRIMARY KEY (roleId)
);

CREATE TABLE users (
  id INT AUTO_INCREMENT,
  firstName VARCHAR(30),
  lastName VARCHAR(30),
  roleId INT,
  age INT,
  email VARCHAR(30),
  password VARCHAR(30),
  phone INT,
  dateOfBirth date,
  approved INT,
  PRIMARY KEY (id),
  FOREIGN KEY (roleId)
    REFERENCES roles(roleId)
    ON DELETE CASCADE
);

CREATE TABLE patients (
  patientId INT AUTO_INCREMENT,
  userId INT,
  familyCode INT,
  emergencyContact varchar(60),
  emergencyRelation varchar(30),
  groupId INT,
  admissionDate DATE,
  PRIMARY KEY (patientId),
  FOREIGN KEY (userId)
    REFERENCES users(id)
    ON DELETE CASCADE
);

CREATE TABLE employees (
  employeeId INT AUTO_INCREMENT,
  userId INT,
  salary INT,
  groupId INT,
  PRIMARY KEY (employeeId)
  FOREIGN KEY (userId)
    REFERENCES users(id)
    ON DELETE CASCADE
);

CREATE TABLE appointments (
  appointmentId INT AUTO_INCREMENT,
  doctorId INT,
  patientId INT,
  appDate DATE,
  PRIMARY KEY (appointmentId),
  FOREIGN KEY (doctorId)
    REFERENCES employees(doctorId)
    ON DELETE CASCADE,
  FOREIGN KEY (patientId)
    REFERENCES patients(patientId)
    ON DELETE CASCADE
);

CREATE TABLE rosters (
  rosterId INT AUTO_INCREMENT,
  supervisorId INT,
  doctorId INT,
  caregiverOne INT,
  caregiverTwo INT,
  caregiverThree INT,
  caregiverFour INT,
  PRIMARY KEY (rosterId),
  FOREIGN KEY (supervisorId, doctorId, caregiverOne, caregiverTwo, caregiverThree, caregiverFour)
    REFERENCES employees(supervisorId, doctorId, caregiverOne, caregiverTwo, caregiverThree, caregiverFour)
    ON DELETE CASCADE
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
);
