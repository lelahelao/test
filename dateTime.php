<html>
<body>
<?php
	#Definieren des Dateinamen
    $fileName = 'VI.csv';
    #Einlesen der Datei in ein Array
    $file = file($fileName);
	
	#Array aus CSV Datei erstellen:
    $csv[] = array_map(function($v){return str_getcsv($v, detectDelimiter());}, $file);
    #Löschen des durch das Einlesen erstelle obere Array (csv[0] enthält das Array mit den CSV-Daten, csv[1] existiert nicht)
    $csv = $csv[0];
	
	#Associative Array erstellen:
    #csv[0] enthält die Spalten-Namen
    #array_splice entfernt diese erste Zeile dann
    $arr_key = $csv[0];
    array_splice ($csv,0,1);
    #Jedem Eintrag wird der dazugehörigen Spalten-Name als Key hinzugefügt, damit nicht nach Spalten-Nummer sondern nach Spalten-Name später gesucht werden kann
    for ($row = 0; $row < count($csv); $row++) {
      $csv[$row] = array_combine ($arr_key, $csv[$row]);
    }
	
	#Funktion zur Festellung des Trennzeichen der CSV-Datei
    #wird beim Erstellen des Arrays aus der CSV-Datei verwendet
    function detectDelimiter(){
      #vorher global definierter Datei-Name
      global $fileName;
      #mögliche Trennzeichen
      $delimiters = array(
          ';' => 0,
          ',' => 0,
          "\t" => 0,
          "|" => 0
       );
      #Öffnen der Datei, Auslesung der ersten Zeile und Schließen der Datei
       $handle = fopen($fileName, "r");
       $firstLine = fgets($handle);
       fclose($handle);
       #Iteration durch alle möglichen Trennzeichen und Zählung ihrer Anzahl in der ersten Zeile der CSV-Datei
       foreach ($delimiters as $delimiter => &$count) {
          $count = count(str_getcsv($firstLine, $delimiter));
       }
       #Rückgabe des Trennzeichen mit der höhsten Anzahl
       return array_search(max($delimiters), $delimiters);
    }
	
	#Funktion zum Filtern des Arrays:
    function filterArray ($array) {
      global $semester, $course;
      $arr_sl = array ();
      foreach ($array as $key => $values) {
        if ( $values['Studgang'] == $course && $values['Sem'] == $semester) {
          $temp_sl = array_slice ($array, $key, 1);
          $arr_sl = array_merge ($temp_sl, $arr_sl);
        }
      }
      $array = $arr_sl;
      #print_r ($array);
    }
	
	filterArray($csv);
	#print_r ($csv);
	
	
	$response = array();
	$posts = array();
	
	$year = '2018';
	$week = '14';
	$time = 'TH:i:sO';
	$time_splited = explode(".", $time);
	
	#json-Array erstellen
	#WICHTIGE Anmerkung: Anscheinend kommt es zu Problemen beim Encoden, wenn die letzte Zeile der csv-Datei mit eingelesen wird. Dort gibt es ein Ue, das den Vorgang zerstört.
	#Es müsste somit eine Anpassung bei dewr Filterung der Datei erfolgen.
	for ($row = 0; $row < count($csv)-2; $row++){
		$kw_start = $csv [$row]['LV-Start'];
		
		if($kw_start !=NULL){	
				$week_start = new DateTime();
				$week_start->setISODate($year,$week);
				$week_start->format('Y-m-d\TH:i:sO');
				/* -> Kalenderwocher, string into int umformen */
		
			if ($csv[$row]['Mo B'] = !Null){
				$week_start -> modify('+0 day');
				$start_time = $week_start;
				$end_time = $week_start;
				$start_time->modify("{$time_splited[0]} hours");
				$end_time->modify("+{$time_splited[1]} minutes");
				
		 	}
			
		/*	if ($csv[$row]['DI B'] = !NULL) {
			$week_start -> modify('+1 day');
				$start_time = $week_start;
				$end_time = $week_start;
				$start_time->modify("+{$time_splited[0]} hours");
				$end_time->modify("+{$time_splited[1]} minutes");
			
		
		}
			if ($csv[$row]['Mi B'] = !NULL) {
			$week_start -> modify('+2 day');
				$start_time = $week_start;
				$end_time = $week_start;
				$start_time->modify("+{$time_splited[0]} hours");
				$end_time->modify("+{$time_splited[1]} minutes");
				
		
			if ($csv[$row]['Do B']= !NULL) {
				$week_start -> modify('+3 day');
				$start_time = $week_start;
				$end_time = $week_start;
				$start_time->modify("+{$time_splited[0]} hours");
				$end_time->modify("+{$time_splited[1]} minutes");
			
		}
			if ($csv[$row]['Fr B'] = !NULL) {
			$week_start -> modify('+4 day');
				$start_time = $week_start;
				$end_time = $week_start;
				$start_time->modify("+{$time_splited[0]} hours");
				$end_time->modify("+{$time_splited[1]} minutes");
			
			
		}
			if ($csv[$row]['Sa B'] = !NULL) {
			$week_start -> modify('+5 day');
				$start_time = $week_start;
				$end_time = $week_start;
				$start_time->modify("+{$time_splited[0]} hours");
				$end_time->modify("+{$time_splited[1]} minutes");
				
		}  
		 		
		} 	*/ $title = $csv[$row]['Modul'];
		 		$posts[]= array('title'=> $title);			
		}
	

	}	
	
	
	print_r($posts);
	print_r("<br />\n");
	
	$fp = fopen('test.json','w');
	fwrite($fp, json_encode($posts));
	fclose($fp);
?>
</body>
</html>



		
	   $time_splited = explode(".", $time);
	   Splitted die Zeit bei dem doppeltpunkt , zb 14:00