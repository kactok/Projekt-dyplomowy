<?php
include "connect.php";
$temperatura = $_GET["temp"];
$cisnienie = $_GET["cisn"];
$wilgotnosc = $_GET["wilg"];
$pm25 = $_GET["pm25"];
$pm10 = $_GET["pm10"];
$szerokosc = $_GET["lat"];
$dlugosc = $_GET["lng"];
$data_wys = date("Y-m-d H:i:s");
$sql = "INSERT INTO Pomiary (Data, Temperatura, Cisnienie, Wilgotnosc, PM25, PM10, Szerokosc, Dlugosc) VALUES ('$data_wys', '$temperatura', '$cisnienie', '$wilgotnosc', '$pm25', '$pm10', '$szerokosc','$dlugosc');";
mysqli_query($conn, $sql);
$conn->close();
