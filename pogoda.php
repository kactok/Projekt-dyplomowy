<?php
include "connect.php";
include "test.php";
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <met name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Stacja pogodowa</title>
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="style.css">

</head>

<body style="background-color: whitesmoke;" onload="loadXML();">
    <div class="main">
        <div class="tytuł" style="text-align: center;">
            <h1>Stacja monitoringu jakości powietrza atmosferycznego</h1>
        </div>
        <script>
            function loadXML() {
                const xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                    document.getElementById("tabele").innerHTML = this.responseText;

                }
                xhttp.open("GET", "load.php");
                xhttp.send();
            }
            setInterval(function() {
                loadXML();
            }, 1000);
        </script>
        <div id="tabele"></div>

        <div class="indeks">
            <p style="font-size: 1.5vw;">Indeks jakości powietrza:</p>
            <table>
                <tr>
                    <th>PM10</th>
                    <th>PM2.5</th>
                    <th>Jakość powietrza</th>
                </tr>
                <tr>
                    <td>0-20</td>
                    <td>0-12</td>
                    <td style="background-color:rgb(0, 147, 0) ;">Bardzo dobra</td>
                </tr>
                <tr>

                    <td>21-60</td>
                    <td>13-36</td>
                    <td style="background-color:rgb(159, 255, 0) ;">Dobra</td>
                </tr>
                <tr>

                    <td>61-100</td>
                    <td>37-60</td>
                    <td style="background-color:rgb(255, 255, 0);">Umiarkowana</td>
                </tr>
                <tr>

                    <td>101-140</td>
                    <td>61-84</td>
                    <td style="background-color:rgb(245, 150, 8) ;">Dostateczna</td>
                </tr>
                <tr>

                    <td>141-200</td>
                    <td>85-120</td>
                    <td style="background-color:rgb(250, 0, 0) ;">Zła</td>
                </tr>
                <tr>
                    <td>>200</td>
                    <td>>120</td>
                    <td style="background-color:rgb(124, 9, 9); color: white;">Bardzo zła</td>
                </tr>
            </table>
        </div>


        <label for="przycisk"><span id="przy">Historia pomiarów</span></label>
        <input type="checkbox" id="przycisk">
        <div class="slide" style="overflow-y: scroll;">
            <?php
            error_reporting(0);
            $query = "SELECT * FROM Pomiary ORDER BY ID DESC";
            $wynik = $conn->query($query);
            echo "<table><tr>";
            echo "<tr> <th class='hist'> Data </th>
                            <th class='hist'> Temperatura </th>
                            <th class='hist'> Ciśnienie </th>
                            <th class='hist'> Wilgotność </th>
                            <th class='hist'> PM2.5</th>
                            <th class='hist'> PM10</th>
                         </tr></table>";

            if ($wynik->num_rows > 0) {
                while ($row = $wynik->fetch_assoc()) {
                    $wyniki[] = $row;
                    $temperaturaHist = $row["Temperatura"];
                    $cisnienieHist = $row["Cisnienie"];
                    $wilgotnoscHist = $row["Wilgotnosc"];
                    $dataHist = $row["Data"];
                    $pm25Hist = $row["PM25"];
                    $pm10Hist = $row["PM10"];
                    $szerHist = $row["Szerokosc"];
                    $dluHist = $row["Dlugosc"];
                    echo "<table><tr>";
                    echo "<td class = 'hist'>  $dataHist </td>";
                    echo "<td class = 'hist'> $temperaturaHist<sup> o</sup>C </td>";
                    echo "<td class = 'hist'> $cisnienieHist hPa </td>";
                    echo "<td class = 'hist'> $wilgotnoscHist % </td>";
                    echo "<td class = 'hist'> $pm25Hist &micro;/m<sup>3</sup></td>";
                    echo "<td class = 'hist'> $pm10Hist &micro;/m<sup>3</sup></td>";
                    echo "</tr></table>";
                }
            }
            $nowatemp = [];
            $nowadata = [];
            $nowacisn = [];
            $nowawilg = [];
            $nowap25 = [];
            $nowap10 = [];
            $nowasze = [];
            $nowadlu = [];
            foreach ($wyniki as $wyniki) {
                array_push($nowadata, strtotime($wyniki['Data']));
                array_push($nowatemp, $wyniki['Temperatura']);
                array_push($nowacisn, $wyniki['Cisnienie']);
                array_push($nowawilg, $wyniki['Wilgotnosc']);
                array_push($nowap25, $wyniki['PM25']);
                array_push($nowap10, $wyniki['PM10']);
                array_push($nowadlu, $wyniki['Dlugosc']);
                array_push($nowasze, $wyniki['Szerokosc']);
            }
            $nowajsdlu = array_values($nowadlu);
            $jsarraydlu = json_encode($nowajsdlu);

            $nowajssze = array_values($nowasze);
            $jsarraysze = json_encode($nowajssze);

            $nowajdata = array_values($nowadata);
            $jsarraydata = json_encode($nowajdata);

            $nowajstem = array_values($nowatemp);
            $jsarraytem = json_encode($nowajstem);

            $nowajscisn = array_values($nowacisn);
            $jsarraycisn = json_encode($nowajscisn);

            $nowajswil = array_values($nowawilg);
            $jsarraywil = json_encode($nowajswil);

            $nowajsp25 = array_values($nowap25);
            $jsarrayp25 = json_encode($nowajsp25);

            $nowajsp10 = array_values($nowap10);
            $jsarrayp10 = json_encode($nowajsp10);

            ?>

        </div>
        <div class="switch">

            <label for="od" style="font-size: 1.5vw; font-family: monospace;">OD: </label>
            <input type="datetime-local" name="od" id="od" class="toogle">
            <label for="do" style="font-size: 1.5vw; font-family: monospace;">DO: </label>
            <input type="datetime-local" name="do" id="do" class="toogle">

        </div>
        <div class="przycisk">
            <div class="switch">

                <button type="button" class="toogle" onclick='prawo(<?php echo $jsarraydata; ?>, <?php echo $jsarraysze; ?>, <?php echo $jsarraydlu; ?>,<?php echo $jsarraytem; ?>,<?php echo $jsarraycisn; ?>,<?php echo $jsarraywil; ?>,<?php echo $jsarrayp25; ?>,<?php echo $jsarrayp10; ?>)'>SET</button>
                <button type="button" class="toogle" onclick="usun()">RESET</button>
                <br>
                <br>
                <br>
                <button type="button" class="toogle" onclick="lewo()">Ostatni </button>
            </div>
        </div>
        <script>
            var markers = [];
            var map;
            var mark;
            var ikona1;
            var info = "<p>" + "Data: " + <?php echo $data ?> + "<br>" + "Temperatura: " + <?php echo $temp ?> + "<sup> o</sup>C" + "<br>" + "Ciśnienie: " + <?php echo $cisn ?> + "hPa" + "<br>" +
                "Wilgotność: " + <?php echo $wil ?> + "%" + "<br>" + "PM2.5: " + <?php echo $p25 ?> + "&micro;/m<sup>3</sup >" + "<br>" + "PM10: " + <?php echo $p10 ?> + "&micro;/m<sup>3</sup >" + "<br>" + "</p>";
            if (<?php echo $p25 ?> >= 0 && <?php echo $p25 ?> <= 12 && <?php echo $p10 ?> >= 0 && <?php echo $p10 ?> <= 20) {
                ikona1 = 'https://maps.google.com/mapfiles/ms/icons/green.png';
            } else if (<?php echo $p25 ?> >= 13 && <?php echo $p25 ?> <= 36 && <?php echo $p10 ?> >= 21 && <?php echo $p10 ?> <= 60) {
                ikona1 = 'https://maps.google.com/mapfiles/ms/icons/green.png';
            } else if (<?php echo $p25 ?> >= 37 && <?php echo $p25 ?> <= 60 && <?php echo $p10 ?> >= 61 && <?php echo $p10 ?> <= 100) {
                ikona1 = 'https://maps.google.com/mapfiles/ms/icons/orange.png';
            } else if (<?php echo $p25 ?> >= 61 && <?php echo $p25 ?> <= 84 && <?php echo $p10 ?> >= 101 && <?php echo $p10 ?> <= 140) {
                ikona1 = 'https://maps.google.com/mapfiles/ms/icons/yellow.png';
            } else if (<?php echo $p25 ?> >= 85 && <?php echo $p25 ?> <= 120 && <?php echo $p10 ?> >= 141 && <?php echo $p10 ?> <= 200) {
                ikona1 = 'https://maps.google.com/mapfiles/ms/icons/red.png';
            } else if (<?php echo $p25 ?> >= 120 && <?php echo $p10 ?> >= 200) {
                ikona1 = 'https://maps.google.com/mapfiles/ms/icons/red.png';
            } else {
                ikona1 = 'https://maps.google.com/mapfiles/ms/icons/purple.png';
            }

            var initMap = function() {
                map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 14,
                    center: {
                        <?php echo 'lat:' . $szer . ', lng:' . $dlu; ?>,

                    },
                });
                infowindow = new google.maps.InfoWindow({
                    content: info,
                    ariaLabel: "Uluru",
                });
                const wyglad = {
                    url: ikona1,
                    scaledSize: new google.maps.Size(50, 50)
                };
                marker = new google.maps.Marker({
                    position: {
                        <?php echo 'lat:' . $szer . ', lng:' . $dlu; ?>,

                    },
                    icon: wyglad,

                    map: map
                });


                marker.addListener("click", () => {
                    infowindow.open({
                        anchor: marker,
                        map,
                    });
                    markers.push(marker);
                });
                markers.push(marker);
            }

            function DeleteMarkers() {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }
                markers = [];
            };

            function lewo() {

                DeleteMarkers()
                infowindow = new google.maps.InfoWindow({
                    content: info,
                    ariaLabel: "Uluru",
                });
                const wyglad1 = {
                    url: ikona1,
                    scaledSize: new google.maps.Size(50, 50)
                };
                marker = new google.maps.Marker({
                    position: {
                        <?php echo 'lat:' . $szer . ', lng:' . $dlu; ?>,

                    },
                    icon: wyglad1,
                    map: map
                });
                marker.addListener("click", () => {
                    infowindow.open({
                        anchor: marker,
                        map,
                    });

                });
                markers.push(marker);


            }

            function prawo(da, x, y, z, a, g, h, f) {
                const date1 = document.getElementById('od');
                const date2 = document.getElementById('do');
                const odfiltruj = Date.parse(new Date(date1.value));
                const dofiltruj = Date.parse(new Date(date2.value));
                var aktData = new Date().setHours(new Date().getHours());
                var date = [];
                var s = [];
                var d = [];
                var t = [];
                var c = [];
                var w = [];
                var p = [];
                var p2 = [];
                var i = 0;
                date = da;
                s = x;
                d = y;
                t = z;
                c = a;
                w = g;
                p = h;
                p2 = f;
                DeleteMarkers()
                while (i < s.length) {
                    var sz = Number(s[i]);
                    var dl = Number(d[i]);
                    var ikona;
                    console.log(date[i]);
                    console.log(aktData);
                    if ((date[i] * 1000) > odfiltruj && (date[i] * 1000) < dofiltruj) {
                        if (p[i] >= 0 && p[i] <= 12 && p2[i] >= 0 && p2[i] <= 20) {
                            ikona = 'https://maps.google.com/mapfiles/ms/icons/green.png';
                        } else if (p[i] >= 13 && p[i] <= 36 && p2[i] >= 21 && p2[i] <= 60) {
                            ikona = 'https://maps.google.com/mapfiles/ms/icons/green.png';
                        } else if (p[i] >= 37 && p[i] <= 60 && p2[i] >= 61 && p2[i] <= 100) {
                            ikona = 'https://maps.google.com/mapfiles/ms/icons/orange.png';
                        } else if (p[i] >= 61 && p[i] <= 84 && p2[i] >= 101 && p2[i] <= 140) {
                            ikona = 'https://maps.google.com/mapfiles/ms/icons/yellow.png';
                        } else if (p[i] >= 85 && p[i] <= 120 && p2[i] >= 141 && p2[i] <= 200) {
                            ikona = 'https://maps.google.com/mapfiles/ms/icons/red.png';
                        } else if (p[i] >= 120 && p2[i] >= 200) {
                            ikona = 'https://maps.google.com/mapfiles/ms/icons/red.png';
                        } else {
                            ikona = 'https://maps.google.com/mapfiles/ms/icons/purple.png';
                        }
                        const wyglad2 = {
                            url: ikona,
                            scaledSize: new google.maps.Size(50, 50)
                        };
                        marker = new google.maps.Marker({
                            position: {
                                lat: sz,
                                lng: dl,


                            },
                            icon: wyglad2,
                            map: map

                        });
                        var content = "<p>" + "Data: " + (new Date(date[i] * 1000)).toLocaleString('pl-PL') + "<br>" + "Temperatura: " + Number(t[i]) + "<sup> o</sup>C" + "<br>" + "Ciśnienie: " + Number(c[i]) + "hPa" + "<br>" +
                            "Wilgotność: " + Number(w[i]) + "%" + "<br>" + "PM2.5: " + Number(p[i]) + "<br>" + "PM10: " + Number(p2[i]) + "<br>" + "</p>";
                        google.maps.event.addListener(marker, 'click', (function(marker, content, infowindow) {
                            return function() {
                                infowindow.setContent(content);
                                infowindow.open(map, marker);
                            };
                        })(marker, content, infowindow));
                    }
                    i++;
                    markers.push(marker);

                }
            }

            function usun() {
                const date1 = document.getElementById('od');
                const date2 = document.getElementById('do');
                date1.value = '';
                date2.value = '';
                DeleteMarkers()
            }
            window.initMap = initMap;
        </script>
        <div id="map"></div>
        <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBo8WKAhbpHWp_lCtsNlNaAm88jrUnzAs4&callback=initMap">
        </script>
    </div>
    <footer>
        <hr>
        <p>Copyright &copy; 2023 Kacper Tokarski | na hostingu lh.pl</p>
    </footer>
</body>


</html>