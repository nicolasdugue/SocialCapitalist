<?

	function getIdKlout($username, $key) {

		$content = file_get_contents("http://api.klout.com/v2/identity.json/twitter?screenName=$username&key=$key");

		$id = json_decode($content);
		return $id->{'id'};

	}

	function getKloutScore($klout_id, $key) {

		$content = file_get_contents("http://api.klout.com/v2/user.json/$klout_id/score?key=$key");

		$score = json_decode($content);
		return $score->{'score'};

	}

	function getKredScore($username, $appid, $key) {

		$content = file_get_contents("http://api.kred.com/kredscore?term=$username&source=twitter&app_id=$appid&app_key=$key");
	
		$score = json_decode($content);
		return ($score->{'data'}[0]->{'influence'} / 10);

	}
	
?>
