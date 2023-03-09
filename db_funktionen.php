<?php

    include 'config.php';

    // Testmodus für Fehlerausgabe
	define ("TESTMODUS", $testmodus);

    $pfad = $pathtoimages;

	// Verbindet mit der Datenbank
	function dbi_connect($Nutzer, $Passwd){
        require 'config.php';
        
		$verbindung = mysqli_connect($dbhost , $Nutzer , $Passwd , $dbname)
					or die ("Verbindungsfehler:?" . mysqli_connect_error());
				// Ergebnisse werden in UTF8 interpretiert	
		mysqli_set_charset($verbindung, 'utf8');
		return $verbindung;
	}
	
    // Verbindet mit der Datenbank ohne Nutezr zu übergeben
	function dbi_connect_wopsw(){
        require 'config.php';

		$verbindung = mysqli_connect($dbhost , $dbuser, $dbpass, $dbname)
					or die ("Verbindungsfehler:?" . mysqli_connect_error());
				// Ergebnisse werden in UTF8 interpretiert	
		mysqli_set_charset($verbindung, 'utf8');
		return $verbindung;
	}
	
	// Abfragefunktion mit Verbindungsaufbau
	function dbi_query($sql, $Nutzer, $Passwd){
        require 'config.php';
		if (TESTMODUS) {echo $sql;}
		$dbh=dbi_connect($Nutzer, $Passwd);
		$result=mysqli_query ($dbh, $sql) or die (dbi_fehler("query"));
		dbi_close($dbh);
		return $result;
	}
	
    // Abfragefunktion mit Verbindungsaufbau ohne Nutzer zu übergeben
	function dbi_query_onlysql($sql){
        require 'config.php';
		if (TESTMODUS) {echo "<br>" . $sql;}
		$dbh=dbi_connect($dbuser, $dbpass);
		$result=mysqli_query ($dbh, $sql) or die (dbi_fehler("query"));
		dbi_close($dbh);
		return $result;
	}
	
    // Funktion prüft auf SQL-Injections
	function dbi_escape_string($input){
        require 'config.php';
		if (TESTMODUS) {echo $input;}
		$dbh=dbi_connect($dbuser, $dbpass);
		$result=mysqli_escape_string($dbh, $input);
		dbi_close($dbh);
		return $result;
	}

    // Verbindungsabbau
	function dbi_close($verbindung){
		mysqli_close($verbindung);
	}

	// Fehlerausgabe
	function dbi_fehler($fehler){
		if(TESTMODUS){
			echo
				"Fehler beim MySQL-Befehl " . 
				$fehler ;
		}
	}
	
	function set_session($data){
        require 'config.php';
		$verbindung = dbi_connect($dbuser, $dbpass);
		$_SESSION["user_id"] = mysqli_real_escape_string($verbindung, $data["ID"]);
		$_SESSION["user_nickname"] = mysqli_real_escape_string($verbindung, $data["Nickname"]);
		$_SESSION["user_rechte"] = mysqli_real_escape_string($verbindung, $data["Rechte"]);
	}
	
	// Reicht ein $_POST und $_GET an nächste Seite weiter
	function form_daten() {
		if (isset($_POST)) {
			foreach ($_POST as $key => $element) {
                if (is_array($element)){
                    foreach ($element as $i => $as){
                        echo '<input type="hidden" name="' . $key . '[]" value="'. $as. '">';
                    }
                } else {
                    echo "<input type=\"hidden\" name=\"$key\" value=\"$element\">";
                }
			}
		} 
		if (isset($_GET))	{
			foreach ($_GET as $key => $element) {
				echo "<input type=\"hidden\" name=\"$key\" value=\"$element\">";
			}
		}
	}
	
	function form_post(){
		if (isset($_POST)) {
			foreach ($_POST as $key => $element) {
				echo "<input type=\"hidden\" name=\"$key\" value=\"$element\">";
			}
		} 
	}
				

	//Mit Konstanten DEBUGMODUS können Konrollausgaben ein und ausgeschaltet werden.
	//      Notwendiger Befehl: 
		define ("DEBUGMODUS", false);   	//Testausgaben ausschalten
    //	define ("DEBUGMODUS", true);		//Testausgaben einschalten   
	//Die beiden Funktionen tasuchen über das SESSION-Array Informationen aus. Das kann nur funktionieren, wenn eine Session zuvor gestartet wurde.
	//		Notwendiger Befehl: 
	//		session_start();  an Dateianfang

	// ***************************************************************************************************************************
	// **	Funktionen HTML_eingabe_kontrolle_array 								                                                                                                                       **
	// ***************************************************************************************************************************
	function html_eingabe_kontrolle_array(&$eingabe_array){
	//Die Funktion überprüft und verändert den Eingabeparameter direkt! (Seiteneffekt!!!)
	//Bei einen Array als Eingabeparameter werden ALLE Werte rekursiv überprüft
	//sollte ein Eingabefehler vorliegen wird:
	//	1. Der fehlerhafte Array-Wert wird durch den Text "- Eingabefehler -" ersetzt!
	//	2. Es wird ein Fehlertext zurückgegeben (sonst "")
	
		if (DEBUGMODUS) echo "<br>*** <b>Eingabekontrolle</b> ****<br>";
		$_SESSION['eingabefehler'] = false;
		array_walk_recursive($eingabe_array,  "html_eingabe_kontrolle" );

		if ($_SESSION['eingabefehler']){
			return html_inhalt_icon_tabelle(
				array (
					array (	"_loeschen.gif",
 						"Eingabefehler",
 						"Mindestens eine der Eingaben einthielt unzulässige Sonderzeichen oder Schlüsselwörter.<br>
						Die unzulässigen Eingaben wurden durch den Text '- Eingabefehler -' ersetzt.<br>
						Bitte kontrollieren Sie die betreffenden Datensätze!" ))) . "<br><hr><br>";
		} else {
			return "";
		}
	}

	// ***************************************************************************************************************************
	// **	Funktionen html_eingabe_kontrolle				                                                                          				         								            **
	// ***************************************************************************************************************************
	function html_eingabe_kontrolle(&$eingabe){
	//Die Funktion überprüft und verändert den Eingabeparameter direkt! (Seiteneffekt!!!)
	//sollte ein Eingabefehler vorliegen wird:
	//	1. Die Eingabe wird durch den Text "- Eingabefehler -" ersetzt!
	//	2. Die SESSION-Variable "eingabefehler" wird auf true gesetzt!
	//	3. Es wird der Boolsche Wert true zurückgegeben!
	//Die Session-Variable dirch diese Funktion nicht zurückgesetzt!
	
		$austauschen 	= array("&&", "||", ";", "\\", "\"", "'");
		$finden			= array("AND", "OR", "SELECT", "DELETE", "INSERT", "UNION");  //Achtung Großschreiben
		$alarm 			= "-";

		$eingabe = trim($eingabe);											//entfernt führende und Nachfolgende Leerzeichen und Sonderzeichen
		$ausgabe = $eingabe;
		$ausgabe = strip_tags($ausgabe);									//entfernt HTML-Tags
		$ausgabe = str_replace($austauschen, "_Sonderzeichen_", $ausgabe);	//entfernt Sonderzeichen

	//Schlüsselwürter suchen und $alarm bei Fund setzen

		$eingabe_gross = strtoupper($ausgabe);
		if (DEBUGMODUS) echo "<hr><br>***** ><b>$eingabe</b>< *****<br><br>";
			foreach ($finden as $schluesselwort){
			$regausdruck = "/$schluesselwort/";
			$ausgabe_teile = preg_split($regausdruck, $eingabe_gross);

			$anfang	= "A";
			$ende	= "E";

			if (DEBUGMODUS){
				echo "<small>>$schluesselwort<</small>";
				echo "<table border='1'>";
				echo "<tr><td>Wert</td><td>Anfang</td><td>Ende</td><td>Schlüsselwort</td></tr>";
			}
			
			foreach ($ausgabe_teile as $wert){

				$anfang = substr($wert, 0,1);

				if (	!preg_match("/^[A-Z0-9]+$/",$anfang)
					&& 	!preg_match("/^[A-Z0-9]+$/",$ende)
					)
				$alarm = $schluesselwort;
				$ende = substr($wert, -1);
				if (DEBUGMODUS)echo "<tr><td>>$wert<</td><td>>$anfang<</td><td>>$ende<</td><td>>$alarm<</td></tr>";
			}
			if (DEBUGMODUS) echo "</table><br>";
		}

		if ($ausgabe == $eingabe && $alarm == "-"){	//Eingabe korrekt
			return false;
		} else {	//Eingabe fehlerhaft
			if (DEBUGMODUS) echo "***** ><b>EINGABEFEHLER GEFUNDEN !!!</b>< *****<br><br>";
			$_SESSION['eingabefehler'] = true;
			$eingabe = "- Eingabefehler -";
			return true;
		}
	}

?>