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

        $name = dbi_escape_string($_POST['name']);
        $vorname = dbi_escape_string($_POST['vorname']);
        $jahrgang = $_POST['jahr'];
        $workshop = dbi_escape_string($_POST['workshop']);

        $maxAnzahl = "SELECT `Obergrenze` FROM workshops WHERE id =" . $workshop;
        $resmaxAnzahl = mysqli_fetch_array(dbi_query_onlysql($maxAnzahl));

        $Anzahl = "SELECT `Anzahl Schüler` FROM workshops WHERE id =" . $workshop;
        $resAnzahl = mysqli_fetch_array(dbi_query_onlysql($Anzahl));

        if ($resAnzahl[0] == $resmaxAnzahl[0]){
            echo "Dieser workshop ist voll du Nutte!";
            
        } else{
            $erhöhenSchüler = "update test.workshops set `Anzahl Schüler` = `Anzahl Schüler`+1 where id = " . $workshop; //--> erhöhen der schülerzahl
            dbi_query_onlysql($erhöhenSchüler);

            $sql = "INSERT INTO test.schüler(Name,Vorname,Jahrgang,Workshop ) VALUES ('$name','$vorname',$jahrgang,'$workshop')"; //--> eintragen in DB
            $bool = dbi_query_onlysql($sql);
            if($bool){
                echo "Voila";
            } else {
                echo "Nö";
            }
            echo $Anzahl;
            echo $maxAnzahl;
        }
        header('refresh:10; url=index.php');


        
    ?>
    </body>
</html>