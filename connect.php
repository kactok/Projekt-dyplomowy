<?php

$servername = "localhost";
$username = "serwer169985_stacjapogodowa";
$password = "yTgph&?3";
$dbname = "serwer169985_stacjapogodowa";
$data = date("Y-m-d H:i");
//połączenie 
$conn = new mysqli($servername, $username, $password, $dbname);
//sprawdzenie 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// else {
//     echo "Połączono poprawnie <br>";
// }
