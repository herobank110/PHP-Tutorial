<?php
// Root user
// In previous examples, the user name was "root" and password was an
// empty string. Any queries by the root user are allowed full access
// privileges to all your tables.
$databaseLink = new mysqli("localhost", "root", "", "NeatTreats");


// Customer user
// The username is "Customer" and the password is
// "CustomerPassword123". Only SELECT queries on the product table can
// be made from this database link.
$customerDatabaseLink = new mysqli("localhost", "Customer", "CustomerPassword123", "NeatTreats");


// Admin user
// The username is "Admin" and the password is "AdminPassword123". Only
// SELECT, UPDATE and DELETE queries on the product table can be made
// from this database link.
$adminDatabaseLink = new mysqli("localhost", "Admin", "AdminPassword123", "NeatTreats");


// You are allowed to have multiple connections at once, but it is
// recommended to only query with at a time, then close it before making
// queries from a different connection.

// Remember to close the connections.
$databaseLink->close();
$customerDatabaseLink->close();
$adminDatabaseLink->close();
?>