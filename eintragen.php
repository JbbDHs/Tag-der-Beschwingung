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

		$sqljahrgang = "SELECT ID,Jahr FROM jahrgang";
		$resjahrgang = dbi_query_onlysql($sqljahrgang);


		$sqlworkshop = "SELECT ID, Workshop FROM workshops";
        $resworkshop = dbi_query_onlysql($sqlworkshop); 

		echo "<form action='verarbeitung.php' method='POST'>";
			echo "<table style='width 100%'>";
			echo"<colgroup>
			<col style='width:20%'>
			<col style ='width:80%'>
			</colgroup>";
			echo "<th></th>";
			echo "<th></th>";
			echo"</thead>";
		echo "<tr>
			<td>Name:</td>
			<td><input type='text' name='name' required='required' style='width:100%;' placeholder='Bitte ausfüllen!' ></td>
			</tr>";
		echo "<tr>
			<td>Vorname:</td>
			<td><input type='text' name='vorname' required='required' style='width:100%;' placeholder='Bitte ausfüllen!' ></td>
			</tr>";
		echo "<tr>
			<td>Jahrgang</td>
			<td>
			<select name = 'jahr'>";
				while($jahr = mysqli_fetch_assoc($resjahrgang)){
					echo"<option value='" . $jahr['ID'] . "'>" .  $jahr['Jahr'] . "</option>";
				}
		echo "</select>
			</td>
		</tr>";
		echo "<tr>
			<td>Workshop:</td>
			<td>
			<select name = 'workshop'>";
				while($workshop = mysqli_fetch_assoc($resworkshop)){
					echo"<option value='" . $workshop['ID'] . "'>" .  $workshop['Workshop'] .  "</option>";
				}
		echo "</select>
			</td>
		</tr>";
		echo "<tr><td colspan='2'><input type='submit' value='Speichern' </td></tr>";
		echo "</table>";
		echo"</form>";
        ?>
    </body>
</html>
