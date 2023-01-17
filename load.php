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
    if ($pm25 >= 0 && $pm25 <= 12) {
        $bcolorpm25 = "rgb(0, 147, 0)";
        $status = "Bardzo dobra";
        $color = "rgb(0, 147, 0)";
        $cpm25 = "rgb(0, 0, 0)";
    } elseif ($pm25 >= 13 && $pm25 <= 36) {
        $bcolorpm25 = "rgb(159, 255, 0)";
        $status = "Dobra";
        $color = "rgb(159, 255, 0)";
        $cpm25 = "rgb(0, 0, 0)";
    } elseif ($pm25 >= 37 && $pm25 <= 60) {
        $bcolorpm25 = "rgb(255, 255, 0)";
        $status = "Umiarkowana";
        $color = "rgb(255, 255, 0)";
        $cpm25 = "rgb(0, 0, 0)";
    } elseif ($pm25 >= 61 && $pm25 <= 84) {
        $bcolorpm25 = "rgb(245, 150, 8)";
        $status = "Dostateczna";
        $color = "rgb(245, 150, 8)";
        $cpm25 = "rgb(0, 0, 0)";
    } elseif ($pm25 >= 85 && $pm25 <= 120) {
        $bcolorpm25 = "rgb(250, 0, 0)";
        $status = "Zła";
        $color = "rgb(250, 0, 0)";
        $cpm25 = "rgb(0, 0, 0)";
    } elseif ($pm25 > 120) {
        $bcolorpm25 = "rgb(124, 9, 9)";
        $status = "Bardzo zła";
        $color = "rgb(124, 9, 9)";
        $cpm25 = "rgb(255, 255, 255)";
    } else {
        $status = "Brak danych";
    }
    if ($pm10 >= 0 && $pm10 <= 20) {
        $bcolorpm10 = "rgb(0, 147, 0)";
        $cpm10 = "rgb(0, 0, 0)";
    } elseif ($pm10 >= 21 && $pm10 <= 60) {
        $bcolorpm10 = "rgb(159, 255, 0)";
        $cpm10 = "rgb(0, 0, 0)";
    } elseif ($pm10 >= 61 && $pm10 <= 100) {
        $bcolorpm10 = "rgb(255, 255, 0)";
        $cpm10 = "rgb(0, 0, 0)";
    } elseif ($pm10 >= 101 && $pm10 <= 140) {
        $bcolorpm10 = "rgb(245, 150, 8)";
        $cpm10 = "rgb(0, 0, 0)";
    } elseif ($pm10 >= 141 && $pm10 <= 200) {
        $bcolorpm10 = "rgb(250, 0, 0)";
        $cpm10 = "rgb(0, 0, 0)";
    } elseif ($pm10 > 200) {
        $bcolorpm10 = "rgb(124, 9, 9)";
        $cpm10 = "rgb(255, 255, 255)";
    }
    $aktualnadata = date("Y-m-d H:i:s");
    $zmieniona = date("Y-m-d H:i:s", strtotime('-5 minutes', strtotime($aktualnadata)));

    if ($data < $zmieniona) {
        $stacja = "Offline";
    } else {
        $stacja = "Online";
    }
    ?>

   <table id="Pomiary">
       <tr>
           <th>Data ostatniego pomiaru</th>
           <th>Temperatura</th>
           <th>Ciśnienie</th>
           <th>Wilgotność</th>
           <th>PM2.5</th>
           <th>PM10</th>
       </tr>
       <?php foreach ($rows as $row) : ?>
           <tr>
               <td><?php echo $row["Data"]; ?></td>
               <td><?php echo $row["Temperatura"]; ?><sup> o</sup>C</td>
               <td><?php echo $row["Cisnienie"]; ?> hPa</td>
               <td><?php echo $row["Wilgotnosc"]; ?> %</td>
               <td style="color:<?php echo $cpm25 ?>;  background-color: <?php echo $bcolorpm25 ?>;"><?php echo $row["PM25"]; ?> &micro;/m<sup>3</sup></td>
               <td style="color:<?php echo $cpm10 ?>; background-color: <?php echo $bcolorpm10 ?>;"><?php echo $row["PM10"]; ?> &micro;/m<sup>3</sup></td>
           </tr>
       <?php endforeach; ?>
   </table>
   <table class="status">
       <tr>
           <th>Status stacji</th>
           <td><?php echo $stacja; ?></td>
       </tr>
       <tr>
           <th>Jakość powietrza</th>
           <td><?php echo $status; ?> <span class="Led2" style="background-color: <?php echo $color ?>;"></span></td>
       </tr>

   </table>