<!doctype html>

<html lang="de">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tag der Besinnung</title>
	<link rel="stylesheet" href="style.css" />

</head>
	<body>
		<?php 
            include_once ("db_funktionen.php");
			include_once ("menu.php");
        

        $residDesworkshops = dbi_query_onlysql("SELECT id from workshops");
            
        while($datenWorkshop = mysqli_fetch_assoc($residDesworkshops)){    
            

            $resmaxAnzahl = mysqli_fetch_array(dbi_query_onlysql("SELECT `Obergrenze` FROM workshops WHERE id =" . $datenWorkshop['id']));

            $resNameDesWorkshops = mysqli_fetch_array(dbi_query_onlysql("SELECT `Workshop` FROM workshops WHERE id =" . $datenWorkshop['id']));
            
            $resNameDesBetreuers = mysqli_fetch_array(dbi_query_onlysql("SELECT `Betreuer` FROM workshops WHERE id =" . $datenWorkshop['id']));

            $residDesSchülers = dbi_query_onlysql("SELECT id, Name, Vorname, Jahrgang FROM `schüler` WHERE EXISTS (SELECT `id` FROM `schüler` WHERE `Workshop`=".$datenWorkshop['id'].")");


            echo "<h2>" . $resNameDesWorkshops[0] . " (" . $resNameDesBetreuers[0] . ")</h2>";
            echo "<h3> Max. " . $resmaxAnzahl[0] . " Schüler </h3>";

            echo "<table> <tr> <th>Name            </th> <th>Vorname          </th> <th>Jahrgang           </th> </tr>";
           
            while ($daten = mysqli_fetch_assoc($residDesSchülers)) {   
            echo "<tr>
            <td>" . $daten['Name'] . "</td>
            <td>" . $daten['Vorname'] . "</td>
            <td>" . mysqli_fetch_array(dbi_query_onlysql("SELECT `Jahr` FROM Jahrgang WHERE id =". $daten['Jahrgang']))[0] . "</td>
            </tr>";
            } echo"</table>";
            
        }
        ?>
        
 
    </body>
</html>
