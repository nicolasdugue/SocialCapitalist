<?
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
?>


<!DOCTYPE HTML>

<html>

<head>
	
	<title></title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="title" content="DDP - what is your influence on Twitter?" />
	<meta name="description" content="Find out the real influence of a Twitter user based on its activity and the way he obtains it." /> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<link rel="image_src" href="/images/notify_better_image.png" />

	<meta content="http://www.thepetedesign.com/demos/onepage_scroll_demo.html" property="og:url" />
	<meta content="http://www.thepetedesign.com/images/onepage_scroll_image.png" property="og:image" />

	<meta name="author" content="Maximilien Danisch, Nicolas Dugué, Anthony Perez">

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src = "jquery/jquery.tagcanvas.min.js" type="text/javascript"></script>
	<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">

	<link rel="stylesheet" type="text/css" href="ddp.css" />
	<link rel="stylesheet" type="text/css" href="media.css" />

	<link href="http://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css" />
	<link href="http://fonts.googleapis.com/css?family=Arvo:400,700" rel="stylesheet" type="text/css" />
	
	<?	
	        /*
			$host = "172.18.1.60";
			$user = "perez"; 
			$pwd = "3h6N7krWI67G";
			$db = "bd_perez";

			$connect = mysql_connect($host, $user, $pwd) or die("probleme connect".mysql_error()); 	
			mysql_set_charset('utf8', $connect);
			$bdd = mysql_select_db($db) or die("probleme select : ".mysql_error());
            */
			/* BUG Donnees du formulaire */
			$username = $_POST['username'];

			include_once("scripts/requests.js");
			include_once("scripts/charts.js");
			include_once("scripts/keys.php");
			include_once("scripts/scores.php");
			
			/* If the user has already been checked, we get its scores */
			/*
            $scores = "SELECT * FROM scores WHERE username = '$username'";
			$result = mysql_query($scores) or die("problem while checking the existence of scores");
			$data = mysql_fetch_array($result);
			
			$nb = mysql_num_rows($result);
            */

			$nb = 0;
			?>	

			<script type="text/javascript">

            function init_cloud_mentions() {

                if(!$('#canvasMention').tagcanvas({

                    textColour: '#2649AF',
                    outlineColour: '#ff00ff',
                    reverse: true,
                    depth: 0.8,
                    maxSpeed: 0.05,
                    weight: true
                },'mentions')) {
                    $('#cloudMention').hide();
                }
            }

            function init_cloud_hashtags() {
                if(!$('#canvasHashtag').tagcanvas({
                    textColour: '#2649AF',
                    outlineColour: '#ff00ff',
                    reverse: true,
                    depth: 0.8,
                    maxSpeed: 0.05,
                    weight: true
                },'hashtags')) {
                    $('#cloudHashtag').hide();
                }
            }

            function init_cloud_url() {
                if(!$('#canvasUrl').tagcanvas({
                    textColour: '#2649AF',
                    outlineColour: '#ff00ff',
                    reverse: true,
                    depth: 0.8,
                    maxSpeed: 0.05,
                    weight: true
                },'urls')) {
                    $('#cloudUrl').hide();
                }
            }


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
                $("#img_best_tweet").attr("src", features.profile_pic);

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
                $("#img_best_hashtags").attr("src", features.profile_pic);

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
                $("#img_best_mentions").attr("src", features.profile_pic);

            }

            function getFeatures(callback) {

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

                return xhr.responseText;

            }

            function requestScores(type, score) {

                var xhr = getXMLHttpRequest();

                xhr.onreadystatechange = function() {

                    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {

                        var result = JSON.parse(xhr.responseText);

                        displayScores(result['kred_score'], result['klout_score'], score)

                    }
                };

                xhr.open("GET",  "../getScore.php?type=" + type + "&username=" + username, true);

                xhr.send(null);

            }

            function displayScores(kred, klout, score) {

                var mesure;

                if (score >= 0.5) {

                    mesure = 2 * (1 - score) * klout;

                } else {

                    mesure = klout;

                }

                $("#score").append(Math.ceil(mesure));
                $("#score_klout").append(klout);
                $("#score_kred").append(kred);

            }


            function readFeatures(feat) {

                var features = JSON.parse(feat);

                if(features.error == "no") {

                if ((features["klout_score"] == null) || (features["kred_score"] == null)) {

                    requestScores("both", features['score']);

                } else {

                    displayScores(features['klout_score'], features['kred_score'], features['score'])

                }

                document.getElementById("score").style.display = "inline";

                /* First, some cosmetic stuff */
                $("#profilepic").attr("src", features.profile_pic);

                $("title").text("Is " + features.screen_name + " a social capitalist?");

                $("#main_results #ksoc h1").append(features.screen_name);

                /* We append the different standard features */
                $("#followers").append(features.followers);
                $("#friends").append(features.friends);
                $("#tweets").append(features.statuses);
                $("#retweeted").append(features.retweeted);
                $("#listed").append(features.listed);
                $("#favorited").append(features.favorites);

                $("#username_resume").append(features.screen_name);
                $("#score_resume").append(Math.ceil(parseFloat(features.score)*100));

                /* We now create the charts */
                pieRetweets(features.tweets, features.retweets);
                pieSources(features.web, features.management, features.follow, features.automatic, features.tierces, features.devices);
                /* TODO: preciser le fuseau horaire */
                lineTweets(features.h0, features.h1, features.h2, features.h3, features.h4, features.h5, features.h6, features.h7, features.h8, features.h9, features.h10, features.h11, features.h12, features.h13, features.h14, features.h15, features.h16, features.h17, features.h18, features.h19, features.h20, features.h21, features.h22, features.h23);

                /* Finally, we display some content-related stuff */
                getBestTweet(displayBestTweet, features.id_best_tweet);
                getBestHashtags(displayBestHashtags, features.id_best_hashtags);
                getBestMentions(displayBestMentions, features.id_best_mentions);

                $("#affichageTextMention").text("There are " + Object.keys(features.mention_unique).length + " unique mentions over a total of "+features.count_mentions+" mentions");

                var maxMention = 0;
                for(var m in features.mention_unique){
                    maxMention=Math.max(maxMention, features.mention_unique[m]);
                }
                var maxSize = 8;

                var minSize = 3;


                for(var m in features.mention_unique){
                    var i = (features.mention_unique[m]/maxMention)*maxSize;
                    if(i<minSize){
                        i=minSize;
                    }

                    $("#listMention").append("<li><a class='cloudWordContent' href='http://twitter.com/"+m+"' target='_blank' data-weight ="+i+" style='font-size:"+i+"ex'>"+m+"</a></li>");
                }

                init_cloud_mentions();


                $("#affichageTextHashtag").text("There are " + Object.keys(features.hashtag_unique).length + " unique hashtags over a total of "+features.count_hashtags+" hashtags");

                var maxHashtag = 0;
                for(var m in features.hashtag_unique){
                    maxHashtag=Math.max(maxHashtag, features.hashtag_unique[m]);
                }
                var maxSize = 8;

                var minSize = 3;

                for(var m in features.hashtag_unique){
                    var i = (features.hashtag_unique[m]/maxHashtag)*maxSize;
                    if(i<minSize){
                        i=minSize;
                    }

                    $("#listHashtag").append("<li><a class='cloudWordContent' href='http://twitter.com/search?q="+m+"' target='_blank' data-weight ="+i+" style='font-size:"+i+"ex'>"+m+"</a></li>");
                }

                init_cloud_hashtags();

                $("#affichageTextUrl").text("There are " + features.count_link_unique + " unique urls over a total of "+features.count_links+" urls");

                var maxUrl = 0;
                for(var j=0; j<features.link_unique.length;j++){
                    maxUrl=Math.max(maxUrl, features.link_unique[j].count);
                }
                var maxSize = 8;

                var minSize = 3;

                for(var j=0; j<Math.min(features.link_unique.length,10);j++){

                    var i = (features.link_unique[j].count/maxUrl)*maxSize;
                    if(i<minSize){
                        i=minSize;
                    }

                    $("#listUrl").append("<li><a class='cloudWordContent' href='"+features.link_unique[j].url+"' target='_blank' data-weight ="+i+" style='font-size:"+i+"ex'>"+features.link_unique[j].url+"</a></li>");
                }

                init_cloud_url();

                //Polarity
                /*var count_polarity_pos = 0;
                var count_polarity_neg = 0;

                for (var p in features.polarity_tweets){

                    if(features.polarity_tweets[p] == "pos")
                        count_polarity_pos++;
                    else
                        count_polarity_neg++;
                }*/


                piePolarity(features.polarity_tweets_pos, features.polarity_tweets_neg);

                // Miscellaneous

                $("#table_miscellaneous>table").append("<tr> <td>Last 5000 Followers/Followees intersection</td><td>" + features.cardinal_followers_followees + "</td></tr>");
                $("#table_miscellaneous>table").append("<tr> <td>Website total Google results</td><td>" + features.count_results_website + "</td></tr>");
                $("#table_miscellaneous>table").append("<tr> <td>Reverse profile image search result</td><td>" + -1 + "</td></tr>");
                $("#table_miscellaneous>table").append("<tr> <td>Tweet similarity (mean)</td><td>" + features.similarity_mean.toFixed(3) + "</td></tr>");
                $("#table_miscellaneous>table").append("<tr> <td>Tweet similarity (standard deviation)</td><td>" + features.similarity_std.toFixed(3) + "</td></tr>");

                /* To conclude, we append the JSON format to id showjson */

                var feat_names = ['screen_name', 'id', 'statuses', 'listed', 'favorites', 'friends', 'followers',
                    'avg_length', 'avg_hashtags', 'avg_url', 'avg_mentions', 'avg_retweets', 'percent_retweets',
                    'avg_retweeted', 'follow', 'management', 'web', 'automatic', 'tierces', 'devices',
                    'std_ids_friends', 'std_ids_followers', 'mean_ids_friends', 'mean_ids_followers',
                    'cardinal_followers_followees', 'polarity_tweets_pos', 'polarity_tweets_neg',
                    'count_mentions', 'count_hashtags', 'count_links', 'count_link_unique'
                ];

                var div = $("#showjson");

                div.append("{<br>");

                for (var f of feat_names) {
                    div.append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"' + f + '" : "' + features[f] + '",<br>');
                }

                div.append("}");


                }

                else {

                    $("#main_results").css("opacity", 0.2);
                    $("#main_results").css("z-index", -1000);
                    $("#charts").css("opacity", 0.2);
                    $("#charts").css("position", "fixed");
                    $("#error").css("visibility", "visible");
                    $("#error").css("opacity", 1);
                    $("#error").css("height", "20%");

                }

                return features;

            }

            var json;
            var username = '<?php echo $username; ?>';

            function showJSON() {

            	$("#main_results").css("opacity", 0.2);
            	$("#main_results").css("z-index", -1000);	
            	$("#charts").css("opacity", 0.2);
            	$("#charts").css("position", "fixed");
            	$("#charts").css("z-index", -1000);

            	$("#showjson").css("visibility", "visible");
            	$("#showjson").css("opacity", 1);
            	$("#showjson").css("height", "75%");

            }

            function hideJSON() {

            	$("#main_results").css("opacity", 1);
            	$("#main_results").css("z-index", 10);	
            	$("#charts").css("opacity", 1);
            	$("#charts").css("position", "relative");

            	$("#showjson").css("visibility", "hidden");
            	$("#showjson").css("height", "0");

            }

            $(function() {

            	json = getFeatures(readFeatures);

            });

        </script>

    </head>

    <body>

    	<script src="jquery/charts/highcharts.js"></script>
    	<script src="jquery/charts/modules/exporting.js"></script>

    	<div id="onload">

    		LOADING....	

    	</div>

    	<div id="error" style="visibility: hidden; height: 0;">

    		Sorry, we cannot retrieve the information for <? echo $username; ?>. The account may be protected or suspended, 
    		or maybe it does not exist anymore!

    	</div>

    	<div id="showjson" style="visibility: hidden; height: 0;">

    		<span style="float: right;">
    			<a href="#" onclick="hideJSON();">
    				[ close ]
    			</a>
    		</span>

    	</div>

    	<div id="wrapper">

    		<div id="main_results">

    			<div id="profile">

    				<div id="picture">

    					<img id="profilepic">

    					<div id="ddp">
    						<p>
    							<span id="score" style="display: none;"></span>
    						</p>
    					</div>

    				</div>

    				<div id="scores">

    					<img src="../images/klout.png">
    					<span id="score_klout">

    					</span>
    					<br />
    					<img src="../images/kred.png">
    					<span id="score_kred">

						</span>

					</div>

				</div>
				
				<div id="ksoc">

					<h1></h1>

					<!-- TODO: change text according to features -->
					<span>According to our algorithm, <span id="username_resume"></span> has <span id="score_resume"></span>% of chance of being a social capitalist. <!--This observation can come from the fact that his tweets are posted in a small amount of time, or because his tweets contain a number of hashtags and mentions above average.--></span>

				</div>

				<div id="stats">

					<div class="cadre_stats">

						<h2>Followers</h2>

						<span id="followers"></span>

					</div>

					<div class="cadre_stats">

						<h2>Friends</h2>

						<span id="friends"></span>

					</div>

					<div class="cadre_stats">

						<h2>Tweets</h2>

						<span id="tweets"></span>

					</div>

					<div style="clear: both;"></div>

					<div class="cadre_stats">

						<h2>Listed</h2>

						<span id="listed"></span>

					</div>

					<div class="cadre_stats">

						<h2>Favorited</h2>

						<span id="favorited"></span>

					</div>

				</div>

			</div>

			<div style="clear: both;"></div>

			<div id="charts">

				<div id="json" style="width: 20%; float: right; font-size: 0.6em; text-transform: uppercase; margin-top: 1%;">

					<a href="#" onclick="showJSON();">
						[ export features (JSON format) ]
					</a>

				</div>

				<h2>Activity</h2>

				<div style="clear: both;"></div>

				<div class="pie">

					<div id="activity" ></div>
					<div class="legend" style="margin-top: 1%;">
						Proportion of original tweets and retweets in the user timeline.
					</div>

				</div>
				<div class="pie">

					<div id="activity1"></div>
					<div class="legend" style="margin-top: 1%;" >
						Repartition of the different sources used by the user to send tweets.
					</div>

				</div>
				<div class="pie">

					<div id="activity2" ></div>
					<div class="legend" style="margin-top: 1%;">
						Number of tweets posted every hour (GMT time), taken over the last 200 tweets.
					</div>

				</div>

				<div style="clear: both;"></div><br />

				<h2>Popular tweets</h2>

				<div id="popular">

					<div id="best_tweet" class="tweet"><img id="img_best_tweet" /></div>
					<div id="best_hashtags" class="tweet"><img id="img_best_hashtags" /></div>
					<div id="best_mentions" class="tweet"><img id="img_best_mentions" /></div>

				</div>
				
                <div style="clear: both;"></div>

                <h2> Analysis </h2>

				<div id="analysis">
    				<div id="uniques">
    						<div id="mentionZone">
    							<span id="affichageTextMention"></span>

    							<div id="cloudMention">
    								<canvas width="450" height="450" id="canvasMention">
    									<p>Anything in here will be replaced on browsers that support the canvas element</p>
    								</canvas>
    							</div>

    							<div id="mentions">
    								<ul id="listMention">

    								</ul>
    							</div>
    						</div>

    						<div id="hashtagZone">
    							<span id="affichageTextHashtag"></span>

    							<div id="cloudHashtag">
    								<canvas width="450" height="450" id="canvasHashtag">
    									<p>Anything in here will be replaced on browsers that support the canvas element</p>
    								</canvas>
    							</div>

    							<div id="hashtags">
    								<ul id="listHashtag">

    								</ul>
    							</div>
    						</div>
    						<div id="urlZone">
    							<span id="affichageTextUrl"></span>
    							
    							<div id="cloudUrl">
    								<canvas width="550" height="450" id="canvasUrl">
    									<p>Anything in here will be replaced on browsers that support the canvas element</p>
    								</canvas>
    							</div>

    							<div id="urls">
    								<ul id="listUrl">

    								</ul>
    							</div>
    						</div>
    				</div>
                        
                    <div class="pie">
                        <div id="polarity">

                        </div>
                        <div class="legend" style="margin-top: 1%;">
                            Pourcentage of positive and negatives tweets
                        </div>
                    </div>
                    
                    <div id="table_miscellaneous">
                      <table>
                        
                      </table>
                    </div>
                   
				</div>

			</div>

		</div>
	</body>

	</html>
