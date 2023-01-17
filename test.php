   <?php
    include "connect.php";
    error_reporting(0);
    $sql = "SELECT * FROM Pomiary ORDER BY `ID` DESC LIMIT 1";
    $rows = mysqli_query($conn, $sql);
    $wynik = $conn->query($sql);
    if ($wynik->num_rows > 0) {
        while ($row = $wynik->fetch_assoc()) {
            $temperatura = $row["Temperatura"];
            $cisnienie = $row["Cisnienie"];
            $wilgotnosc = $row["Wilgotnosc"];
            $data = $row["Data"];
            $pm25 = $row["PM25"];
            $pm10 = $row["PM10"];
            $szer = $row["Szerokosc"];
            $dlu = $row["Dlugosc"];
        }
    }

    $temp = json_encode($temperatura);
    $cisn = json_encode($cisnienie);
    $data = json_encode($data);
    $wil = json_encode($wilgotnosc);
    $p25 = json_encode($pm25);
    $p10 = json_encode($pm10);
    $sze = json_encode($szer);
    $dl = json_encode($dlu);
