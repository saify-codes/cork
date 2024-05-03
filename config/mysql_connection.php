<?php

const host = "localhost";
const user = "root";
const password = "karachi@123";
const database = "cork";


$connection = new mysqli(host, user, password, database);
if ($connection->connect_error) die("Connection failed: " . $connection->connect_error);
?>