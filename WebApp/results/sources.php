<?

	$file = fopen("sources.csv", r);
	
	$map_sources = array();
	
	header('Content-Type: text/plain');
	
	echo"\$map_sources = array( ";
	
	while(!feof($file)) {
	
		$ligne = fgets($file, 4096);
		$source = explode(";", $ligne);
		
		echo "\"".utf8_decode(addslashes(trim($source[0])))."\" => \"".str_replace("\n", "", $source[1])."\", \n";
		
	}
	
	fclose($file);
	
	echo" );";
	
?>
