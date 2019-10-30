<?php
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','root');
define('DB_NAME','adcash');
// Establish database connection.
try
{
    $dbh = new PDO("mysql:host=".DB_HOST.":3307;dbname=".DB_NAME,DB_USER, DB_PASS);
}
catch (PDOException $e)
{
    exit("Error: " . $e->getMessage());
}
?>