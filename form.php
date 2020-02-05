<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$source = "https://www.cs.uky.edu/~paul/public/P4_Sources.json";

function head(){
	echo	"<html>" .
		"<head>" .
		"<title>PHP project</title>" . 
		"</head>" .
		"<body>";	
}

function footer(){
	echo	"</body>" .
		"</html>" .
		"\n";
}

//check if the form has been submitted
if(isset($_GET['submit']) && isset($_GET['sourcedata']) && isset($_GET['fielddata'])){
	result();
} else {
	form();
}

function result(){
	
	head();
	
	$i_name = $_GET["sourcedata"];
	$i_field = $_GET["fielddata"];
	
	$url = " ";
	$title = " ";
	$groups = array();
	$source_file = file_get_contents("https://www.cs.uky.edu/~paul/public/P4_Sources.json") or die("That file does not exist in this directory");
	$source_array = json_decode($source_file, true);
	
	foreach($source_array as $key => $value) {
		
		if ($key == "sources") {
			
			foreach($value as $num => $name){
				$infoName = $name['name'];
				
				if($infoName == $i_name) {
					$url = $name['url'];
					
					$groupfield = $name['groupfields'];
					foreach($groupfield as $g => $f) {
						array_push($groups, $f);
					}		
				}	
				 	
			}
		} else if($key == "title") {$title = $value;}
	}
	
	$result = file_get_contents($url) or die ("Could not access file");
	$result_array = json_decode($result, true);
	
	switch (json_last_error()) {
        	case JSON_ERROR_NONE:
            	//echo " - No errors \n";
        		break;
        	case JSON_ERROR_DEPTH:
            	echo "<p> Maximum stack depth exceeded </p>\n";
        		break;
        	case JSON_ERROR_STATE_MISMATCH:
        	    echo "<p> Underflow or the modes mismatch </p> \n";
        		break;
        	case JSON_ERROR_CTRL_CHAR:
        	    echo "<p> Unexpected control character found </p> \n";
        		break;
        	case JSON_ERROR_SYNTAX:
        	    echo "<p> Syntax error, malformed JSON  </p>\n";
        		break;
        	case JSON_ERROR_UTF8:
        	    echo "<p> Malformed UTF-8 characters, possibly incorrectly encoded </p> \n";
        		break;
        	default:
        	    echo "<p> Unknown error </p> \n";
        		break;
    	}
	
	$i = 0;
	$groupField1 = array();
	$groupField2 = array();
	
	

	foreach($result_array as $key => $value){
		$title = $key;
		echo "<p style='font-size:20px; color:red;'>" . $title . "</p><br>";

		foreach($groups as $key1 => $value1) {
			//check if this groupfield exists
				if($key == $value1){
					//fill in groupfield
						
					foreach($value as $v => $x){
						if (!is_array($x)){
							echo $x . "<br>";
						}else {
							foreach($x as $k => $v) {
								echo $k . ": " . $v . "<br>";
							}
						echo "<br>";		
						}	
					}
											
			} 
		}
	}	

	footer();
}

function form(){
	
	head();
	
	$source_file = file_get_contents("https://www.cs.uky.edu/~paul/public/P4_Sources.json") or die("That file does not exist in this directory");
	$source_array = json_decode($source_file, true);
	$title = " ";
	$global_name = array();
	$global_searchfields = array();
	
	foreach($source_array as $key => $value) {
		if ($key == "title") {
			$title = $value; 
			echo $title;
		} else {

			foreach($value as $array_num => $name) {
				$infoName = $name["name"];
				if(!in_array($infoName, $global_name)){
					array_push($global_name, $infoName);
				}
				
				$searchFields = $name["searchfields"];
				
				foreach($searchFields as $fields => $fieldname){
					if (!in_array($fieldname, $global_searchfields)){array_push($global_searchfields, $fieldname);}
				}
			}
		}
	}
 	
	//the form shown to the user
	echo	'<form action="form.php" method="get">' . 
		
		'<label for="sourcedata">Names</label>' .
		'<select name="sourcedata" id="sourcedata">';  
		foreach($global_name as $key => $value) {
			echo '<option value="' . $value . '">' . $value .'</option>';
		}
  
	echo	'</select><br>';	
		
	echo	'<label for="fielddata">Fields</label>' .
		'<select name="fielddata" id="fielddata">';  
		foreach($global_searchfields as $key => $value) {
			echo '<option value="' . $value . '">' . $value .'</option>';
		}
  
	echo	'</select><br>';	
	echo '<input type="submit" name="submit" value"submit"><br>';
	echo "</form>";
	footer();
}

?>
