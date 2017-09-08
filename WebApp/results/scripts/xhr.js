<script type="text/javascript">

	function getBestTweet(callback, id_tweet) {

		var xhr = getXMLHttpRequest();
	
		xhr.onreadystatechange = function() {
	
			if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
				callback(xhr.responseText);
			} 
		};
	
		xhr.open("GET", "get_tweets.php?id_tweet=" + id_tweet, true);
		xhr.send(null);
		
	}
	
	function displayBestTweet(feat) {
	
		var features = JSON.parse(feat);
		
		$("#best_tweet").append(features.text);
	
	}

	function getBestHashtags(callback, id_tweet) {

		var xhr = getXMLHttpRequest();
	
		xhr.onreadystatechange = function() {
	
			if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
				callback(xhr.responseText);
			} 
		};
	
		xhr.open("GET", "get_tweets.php?id_tweet=" + id_tweet, true);
		xhr.send(null);
		
	}
	
	function displayBestHashtags(feat) {
	
		var features = JSON.parse(feat);
		
		$("#best_hashtags").append(features.text);
	
	}

	function getBestMentions(callback, id_tweet) {

		var xhr = getXMLHttpRequest();
	
		xhr.onreadystatechange = function() {
	
			if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
				callback(xhr.responseText);
			} 
		};
	
		xhr.open("GET", "get_tweets.php?id_tweet=" + id_tweet, true);
		xhr.send(null);
		
	}
	
	function displayBestMentions(feat) {
	
		var features = JSON.parse(feat);
		
		$("#best_mentions").append(features.text);
	
	}

	function getFeatures(callback) {
	
		var username = '<?php echo $username; ?>';

		var xhr = getXMLHttpRequest();
	
		xhr.onreadystatechange = function() {
	
			if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
				callback(xhr.responseText);
				document.getElementById("wrapper").style.opacity = 1;
				document.getElementById("onload").style.opacity = 0;
			} 
			else if (xhr.readyState < 4) {
				document.getElementById("wrapper").style.opacity = 0.2;
				document.getElementById("onload").style.opacity = 1;
			}
		};
	
		xhr.open("GET", "results.php?username=" + username, true);
		xhr.send(null);
		
	}

	function readFeatures(feat) {

		var features = JSON.parse(feat);
		
		var klout_score = '<? echo getKloutScore(getIdKlout($username, $key_klout), $key_klout); ?>';

		var mesure = klout_score * (1 - features.score);

		document.getElementById("score").style.display = "inline";

                /* First, some cosmetic stuff */
                $("#profilepic").attr("src", features.profile_pic);

                $("title").text("Is " + features.screen_name + " a social capitalist?");
                $("#score").append(Math.ceil(mesure));
                $("#main_results #ksoc h1").append(features.screen_name);

                /* We append the different standard features */
                $("#followers").append(features.followers);
                $("#friends").append(features.friends);
                $("#tweets").append(features.statuses);
                $("#retweeted").append(features.retweeted);
                $("#listed").append(features.listed);
                $("#favorited").append(features.favorites);
                
                /* We now create the charts */
                pieRetweets(features.tweets, features.retweets);
                pieSources(features.web, features.management, features.follow, features.automatic, features.tierces, features.devices);
		lineTweets(features.h0, features.h1, features.h2, features.h3, features.h4, features.h5, features.h6, features.h7, features.h8, features.h9, features.h10, features.h11, features.h12, features.h13, features.h14, features.h15, features.h16, features.h17, features.h18, features.h19, features.h20, features.h21, features.h22, features.h23);
		
		/* Finally, we display some content-related stuff */
		getBestTweet(displayBestTweet, features.id_best_tweet);
		getBestHashtags(displayBestHashtags, features.id_best_hashtags);
		getBestMentions(displayBestMentions, features.id_best_mentions);
		
	}
	
</script>
