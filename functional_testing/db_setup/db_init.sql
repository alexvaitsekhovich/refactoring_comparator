CREATE DATABASE `legacy_db`;
CREATE USER 'legacy_user' IDENTIFIED WITH mysql_native_password BY 'legacy_password';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON legacy_db.* TO 'legacy_user';

CREATE DATABASE `refactored_db`;
CREATE USER 'refactored_user' IDENTIFIED WITH mysql_native_password BY 'refactored_password';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON refactored_db.* TO 'refactored_user';
