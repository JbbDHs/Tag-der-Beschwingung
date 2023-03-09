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
            // -> id des workshops
            // wenn existent: ausgeben alles dazu
            // dann: abgleich schüler mit id = id workshop
            // in tabelle alle mit dieser id (name u vorname)
            //
            // workshops müssen von eins aufwärts durchgängig nummeriert werden
            //
            // Uafräumen (simone abendroth)
            // max 10 schüler
            //
            // Name     Vorname    Jahrgang
        
            $idDesWorkshops = 1;
            
        // while(dbi_query_onlysql($idDesWorkshops)=true){
            //Anpassen der Werte an neuen Workshop    
            

            $resmaxAnzahl = mysqli_fetch_array(dbi_query_onlysql("SELECT `Obergrenze` FROM workshops WHERE id =" . $idDesWorkshops));

            $resNameDesWorkshops = mysqli_fetch_array(dbi_query_onlysql("SELECT `Workshop` FROM workshops WHERE id =" . $idDesWorkshops));
            
            $resNameDesBetreuers = mysqli_fetch_array(dbi_query_onlysql("SELECT `Betreuer` FROM workshops WHERE id =" . $idDesWorkshops));

            $residDesSchülers = dbi_query_onlysql("SELECT id, Name, Vorname, Jahrgang FROM `schüler` WHERE EXISTS (SELECT `id` FROM `schüler` WHERE `Workshop`=".$idDesWorkshops.")");
            
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
            $idDesWorkshops += 1;
        // }
        
        
        





        
         
        
        
        
        ?>
         
 
    </body>
</html>