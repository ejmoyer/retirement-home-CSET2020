CREATE DATABASE IF NOT EXISTS retirement;
DROP TABLE roles;
DROP TABLE users;
DROP TABLE patients;


CREATE TABLE roles {
  roleId INT NOT NULL AUTO_INCREMENT,
  role VARCHAR(30),
  PRIMARY KEY (roleId)
}

CREATE TABLE users {
  id INT NOT NULL AUTO_INCREMENT,
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
}

CREATE TABLE patients {
  patientId INT NOT NULL AUTO_INCREMENT,
  userId INT,
  familyCode INT,
  emergencyContact varchar(60),
  emergencyRelation varchar(30),
  groupId INT,
  admissionDate DATE,
  PRIMARY KEY (patientId),
  FOREIGN KEY (userId)
  REFERENCES users(userId)
    ON DELETE CASCADE
}
