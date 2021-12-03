<?php

$host = 'localhost';
$dbname = 'bugme';
$username = 'root';
$pwd = '';
$conn;

try{
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $error) {
    echo 'Failed Database Connection<br/><br/>';
    die($error->getMessage());
}
