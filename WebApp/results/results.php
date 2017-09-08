<? 
	/**
	 * @file
	 * Take the user when they return from Twitter. Get access tokens.
	 * Verify credentials and redirect to based on response from Twitter.
     *
	 */

	session_start();
    session_write_close();

    require_once('twitteroauth/twitteroauth.php');
	require_once('config.php');
    include_once('polarity.php');

    include_once('../mongodb.php');
    include_once('scripts/keys.php');
    include_once('scripts/scores.php');
    include_once('include.php');

    // 0 : force from twitter (default)
    // 1 : force from database
    // 2 : use database if user available, otherwise get the data from twitter
    $fromdb = (isset($_GET["fromdb"])) ? intval($_GET["fromdb"]) : 0;

    // string : username of the user
    $username = (isset($_GET["username"])) ? $_GET["username"] : NULL;

    // number : id of the user (optional if username is set)
    $id = (isset($_GET["id"])) ? $_GET["id"] : NULL;

    // if true, the script will use tokens from "tokenResearch" file
    $batch = (isset($_GET["batch"])) ? $_GET["batch"] : 0;

    $batch_max_thread = (isset($_GET["mthread"])) ? $_GET["mthread"] : 1;

    $batch_file = (isset($_GET["batch_file"])) ? $_GET["batch_file"] : 1;

    $batch_user_nb = (isset($_GET["bun"])) ? $_GET["bun"] : 0;

    $res = 0;

    $time_init = microtime(true);

    if ( $myDb == NULL ) {

        $fromdb = 0;

    } else {

        if ( $username != null ) {
            $res = $myDb->getFeats(strtolower($username));
        } else if ( $id != null ) {
            $res = $myDb->getFeatsById(intval($id));
        } else {
            exit('username or user id required');
        }

    }


    // check if the user has been flag as 'banned' or 'account removed'
    if ($id != null) {

        $res2 = $myDb->getDisabledUsers($id);

        if (count($res2) > 0) {

            header('Content-Type: text/plain');

            $a = array(
                'error' => 'yes',
                'message' => "User has been suspended or page doesn't exist"
            );

            exit(json_encode($a));
        }
    }

    // if we use the database
    if ( $fromdb == 1 || (count($res) > 0 && $fromdb == 2)) {

        if ( count($res) > 0 ) {

            header('Content-Type: text/plain');

            exit(json_encode($res['vals']));

        } else {

            exit("{
                \"error\" : \"yes\"
            }");

        }

    } else {

        $connection = null;

        if ($batch == 1) {

            $max_split = round(($batch_max_thread / 2));

            if ( $batch_file >= $max_split ) {

                $connection = getTwitterOAuth2('tokenResearcher', $max_split, ($batch_file - 1) % $max_split, $batch_user_nb);

            } else {

                $connection = getTwitterOAuth2('tokenLip6', $max_split, ($batch_file - 1) % $max_split, $batch_user_nb);

            }

        } else {

            // If access tokens are not available redirect to connect page.
            if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
                header('Location: ./clearsessions.php');
            }

            // Get user access tokens out of the session.
            $access_token = $_SESSION['access_token'];

            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        }

        if ( $connection == null ) {
            $a = array(
                'error' => 'yes',
                'message' => "no more request available : ("
            );

            exit(json_encode($a));
        }

        // Get logged in user to help with tests.
        $user = $connection->get('account/verify_credentials');
        $active = true;

        $method = "users/show";
        $standard = null;

        if ( $username != null ) {

            $parameter = array("screen_name" => $username);
            $standard = $connection->get($method, $parameter);

        } else if ( $id != null ) {

            $parameter = array("user_id" => $id);
            $standard = $connection->get($method, $parameter);

        } else {
            // neither the username or id is set, exit
            exit('username or user id required');
        }

        // if twitter returned an error when retrieving the user
        if (isset($standard->{'errors'})) {

            $error = $standard->{'errors'}[0];

            $a = array(
                'error' => 'yes',
                'message' => $error->{'message'},
                'code' => $error->{'code'}
            );

            if ($id != null) {
                $myDb->addDisabledUsersById($id);
            }

            exit(json_encode($a));

        } else {

            // We get the main features
            $username = $standard->{'screen_name'};
            $name = $standard->{'name'};
            $id = $standard->{'id'};
            $statuses = $standard->{'statuses_count'};
            $listed = $standard->{'listed_count'};
            $favorites = $standard->{'favourites_count'};
            $friends = $standard->{'friends_count'} >= 0 ? $standard->{'friends_count'} : 0; // bug dans l'api de twitter, des fois le nombre de friends est negatif :x
            $followers = $standard->{'followers_count'};
            $profilepic = $standard->{'profile_image_url'};

            $user_website = "";

            if ($standard->{'entities'} != null && array_key_exists('url', $standard->{'entities'})){
              $user_website = $standard->{'entities'}->{'url'}->{'urls'}[0]->{'expanded_url'};
            }

            $profilepic = str_replace("_normal", "", $profilepic);

            // We get the timeline-related features
            $method = "statuses/user_timeline";

            $parameters = array("screen_name" => $username, "count" => 200);
            $timeline = $connection->get($method, $parameters);

            // some user have the timeline protected, example : https://twitter.com/sylvialestarii
            if ( !is_array($timeline) ) {

                $myDb->addDisabledUsersById($id);

                $a = array(
                    'error' => 'yes',
                    'message' => 'Timeline protected'
                );

                exit(json_encode($a));
            }

            // save the tweets to the database for future usage
            if ($myDb != NULL) {

                //$myDb->addTweets($timeline);

            }

            /* We compute the different average stuff */
            /* TODO: it seems that 'text' has some encodage issue */
            $length_tweets = 0;
            $count_hashtags = 0;
            $count_url = 0;
            $count_mentions = 0;
            $count_retweets = 0;
            $count_retweeted = 0;

            $number_retweets = 0;
            $number_tweets = 0;
            $number_hashtags = 0;
            $number_mentions = 0;

            $second_between_tweets = 0;

            $tweets_per_day = array();
            $tweets_per_day = array_pad($tweets_per_day, 24, 0);

            $tweets_per_hour = array();
            $tweets_per_hour = array_pad($tweets_per_hour, 24, 0);

            $good_day = false;
            $fetch = true;

            $s = array();
            $s = array_pad($s, 6, 0);

            // Map for the sources
            include_once("map_sources.php");

            header('Content-Type: text/plain');

            $last_day = "";
            $today = date("D M d G:i:s O Y");

            $max_rt = -1;
            $max_hashtags = -1;
            $max_mentions = -1;
            $mention_unique = array();
            $hashtag_unique = array();
            $link_unique = array();


            $polarity_tweets = getPolarityFromTimeline($timeline);
            

            for ($i = 0; $i < count($timeline); $i++) {

                ajoutOcc($timeline[$i], $mention_unique, $hashtag_unique, $link_unique);
                
                
                $length_tweets += strlen($timeline[$i]->{'text'});
                $count_hashtags += count($timeline[$i]->{'entities'}->{'hashtags'});
                $count_url += count($timeline[$i]->{'entities'}->{'urls'});
                $count_mentions += count($timeline[$i]->{'entities'}->{'user_mentions'});

                if (!isset($timeline[$i]->{'retweeted_status'})) {
                    $number_tweets++;
                    $count_retweets += $timeline[$i]->{'retweet_count'};

                    if ($max_rt < $timeline[$i]->{'retweet_count'}) {
                        $max_rt = $timeline[$i]->{'retweet_count'};
                        $id_best_tweet = $timeline[$i]->{'id'};
                    }

                    /* We preserve the tweet having the most hahstags and mentions, respectively */
                    if ($max_hashtags < count($timeline[$i]->{'entities'}->{'hashtags'})) {
                        $max_hashtags = count($timeline[$i]->{'entities'}->{'hashtags'});
                        $id_best_hashtags = $timeline[$i]->{'id'};
                    }

                    if ($max_mentions < count($timeline[$i]->{'entities'}->{'user_mentions'})) {
                        $max_mentions = count($timeline[$i]->{'entities'}->{'user_mentions'});
                        $id_best_mentions = $timeline[$i]->{'id'};
                    }

                }

                /* Percentage of retweets */
                if (isset($timeline[$i]->{'retweeted_status'})) {
                    $number_retweets++;
                    $count_retweeted += $timeline[$i]->{'retweeted_status'}->{'retweet_count'};
                }

                /* Seconds between two tweets */
                $last_tweet = strtotime($timeline[$i]->{'created_at'});

                $date = explode(" ", $timeline[$i]->{'created_at'});
                $time = explode(":", $date[3]);

                $hour = $time[0];

                /* We need to get rid of the "0"x */
                if ($hour < 10)
                    $hour = substr($hour, 1, 1);

                if ($last_tweet + 86400 < strtotime($today))
                    $tweets_per_day[$hour]++;

                $tweets_per_hour[$hour]++;

                if ($i == count($timeline) - 1)
                    $second_between_tweets = strtotime($timeline[0]->{'created_at'}) - strtotime($timeline[$i]->{'created_at'});

                /* If there are more than two tweets parsed */
                /*if($i > 0) {
                    $first_tweet = strtotime($timeline[$i-1]->{'created_at'});
                    $second_between_tweets += $first_tweet - $last_tweet;

                }*/

                /* We get some more sophisticated features */
                /* TOCHECK: does this feature exist when retweeted_status is set? */
                $source = utf8_decode(addslashes(trim($timeline[$i]->{'source'})));

                $s[$map_sources[stripslashes($source)]]++;

            }
            arsort($link_unique);
            $tabUrl = array();
            foreach($link_unique as $key => $value){
                array_push($tabUrl, array("url" => $key,"count" => $value));
            }
            
            /*echo '<pre>';
            print_r($tabUrl);
            echo'</pre>';*/

            if (count($timeline) > 0) {

                $avg_length = $length_tweets / count($timeline);
                $avg_hashtags = $count_hashtags / count($timeline);
                $avg_url = $count_url / count($timeline);
                $avg_mentions = $count_mentions / count($timeline);
                $avg_retweets = $count_retweets / count($timeline);
                $percent_retweets = $number_retweets / count($timeline);
                $avg_second = $second_between_tweets / count($timeline);

                /* Proportion for sources */
                $s0 = $s[0] / count($timeline);
                $s1 = $s[1] / count($timeline);
                $s2 = $s[2] / count($timeline);
                $s3 = $s[3] / count($timeline);
                $s4 = $s[4] / count($timeline);
                $s5 = $s[5] / count($timeline);

            } else {

                $avg_length = 0;
                $avg_hashtags = 0;
                $avg_url = 0;
                $avg_mentions = 0;
                $avg_retweets = 0;
                $percent_retweets = 0;
                $avg_second = 0;

                /* Proportion for sources */
                $s0 = 0;
                $s1 = 0;
                $s2 = 0;
                $s3 = 0;
                $s4 = 0;
                $s5 = 0;

            }

            $avg_retweeted = $number_retweets != 0 ? $count_retweeted / $number_retweets : 0;

            $nb_urls = 0;

            foreach ($link_unique as $cle => $valeur) {
                $nb_urls += $valeur;
            }


            $feat = array($statuses, $listed, $favorites, $friends, $followers, $avg_length, $avg_hashtags, $avg_url, $avg_mentions, $avg_retweets, $percent_retweets * 100, $avg_retweeted, $s2, $s1, $s0, $s3, $s4, $s5);

            // compute score based on the features and values from the database
            $values = $myDb->getActiveCoefs()->getNext();
            $score = getScore($values, $feat);

            $klout = null;
            $kred = null;

            $ids_friends = getFriends($connection, $username);
            $ids_followers = getFollowers($connection, $username);

            if ( $ids_friends === NULL || $ids_followers === NULL ) {


                $a = array(
                    'error' => 'yes',
                    'message' => 'Error while retrieving friends & followers ids'
                );

                exit(json_encode($a));


            }

            $inter = intersection($ids_friends, $ids_followers);

            $std_ids_friends = count($ids_friends) != 0 ? stats_standard_deviation($ids_friends) : 0;
            $std_ids_followers = count($ids_followers) != 0 ? stats_standard_deviation($ids_followers) : 0;

            $mean_ids_friends = count($ids_friends) != 0 ? array_sum($ids_friends) / count($ids_friends) : 0;
            $mean_ids_followers = count($ids_followers) != 0 ? array_sum($ids_followers) / count($ids_followers) : 0;

            $similarity_tweets = getSimilarityFromTimeline($timeline);


            if ($username) {

                // create result array
                $res = array(
                    "error" => "no",
                    "screen_name" => $name,
                    "screen_name_l" => strtolower($name), // de façon à pouvoir trier les résultat en fonction du screen name sans prendre en compte les maj
                    "profile_pic" => $profilepic,
                    "score" => $score,
                    "id" => $id,
                    "statuses" => $statuses,
                    "listed" => $listed,
                    "favorites" => $favorites,
                    "friends" => $friends,
                    "followers" => $followers,
                    "avg_length" => $avg_length,
                    "avg_hashtags" => $avg_hashtags,
                    "avg_url" => $avg_url,
                    "avg_mentions" => $avg_mentions,
                    "avg_retweets" => $avg_retweets,
                    "percent_retweets" => $percent_retweets,
                    "avg_retweeted" => $avg_retweeted,
                    "avg_second" => ceil($avg_second),
                    "web" => $s0,
                    "management" => $s1,
                    "follow" => $s2,
                    "automatic" => $s3,
                    "tierces" => $s4,
                    "devices" => $s5,
                    "tweets" => count($timeline),
                    "retweets" => $number_retweets,
                    "id_best_tweet" => $id_best_tweet . "", // j'ai mis en string car js n'aime pas beaucoup les nombres en 64 bits apparement!
                    "id_best_hashtags" => $id_best_hashtags . "",
                    "id_best_mentions" => $id_best_mentions . "",
                    "klout_score" => $klout,
                    "kred_score" => $kred,
                    "casoc" => 2, // it hasn't yet been confirmed if this account is a social capitalist or not (0 = no, 1 = yes, 2 = unknown)
                    "mention_unique" => $mention_unique,
                    "hashtag_unique" => $hashtag_unique,
                    "link_unique" => $tabUrl,
                    "count_mentions" => $count_mentions,
                    "count_hashtags" => $count_hashtags,
                    "count_links" => $nb_urls,
                    "count_link_unique" => count($link_unique),
                    "polarity_tweets_pos" => $polarity_tweets["pos"],
                    "polarity_tweets_neg" => $polarity_tweets["neg"],
                    "cardinal_followers_followees" => $inter,
                    "std_ids_friends" => $std_ids_friends,
                    "std_ids_followers" => $std_ids_followers,
                    "mean_ids_friends" => $mean_ids_friends,
                    "mean_ids_followers" => $mean_ids_followers,
                    "count_results_website" => getNumberGoogleResults($user_website),
                    "similarity_mean" => $similarity_tweets['mean'],
                    "similarity_max" => $similarity_tweets['max'],
                    "similarity_std" => $similarity_tweets['std_deviation']
                );

                foreach ($tweets_per_hour as $key => $value) {
                    $res["h" . $key] = $value;
                }

                // save the results to the database
                if ($myDb != NULL) {
                    $myDb->addFeats(strtolower($username), $res);
                }

                $res["batch_user_nb"] = $batch_user_nb;

                // send the result to the requesting webpage
                echo json_encode($res);

            } else {

                echo "{
                    success:false,
                    general_message:\"You have reached your max number of Foos for the day\",
                    errors: {
                        last_name:\"This field is required\",
                        mrn:\"Either SSN or MRN must be entered\",
                        zipcode:\"996852 is not in Bernalillo county. Only Bernalillo residents are eligible\"
                    }
                } ";

            }
        }
    }

function getFollowers($connection, $username) {
    $method = "followers/ids";
    $parameters = array("screen_name" => $username, "count" => 5000);
    $followers = $connection->get($method, $parameters);

    return $followers->{"ids"};
}

function getFriends($connection, $username) {

    $method = "friends/ids";
    $parameters = array("screen_name" => $username, "count" => 5000);
    $friends = $connection->get($method, $parameters);

    return $friends->{"ids"};
}

function intersection($friends, $followers) {
  return count(array_intersect($friends, $followers));
}

function ajoutOcc($tweets, &$mention, &$hashtag, &$link){

    foreach ($tweets->entities->user_mentions as $m) {

        if (array_key_exists($m->screen_name, $mention)) {
            $mention[$m->screen_name]++;
        } else {
            $mention[$m->screen_name] = 1;
        }

    }

    foreach ($tweets->entities->hashtags as $m) {
        if (array_key_exists($m->text, $hashtag)) {
            $hashtag[$m->text]++;
        } else{
            $hashtag[$m->text] = 1;
        }
    }

    foreach ($tweets->entities->urls as $m) {
       /* $url= getRealUrl($m->expanded_url);*/
       $url = $m->expanded_url;
        if (array_key_exists($url, $link)) {
            $link[$url]++;
        } else{
            $link[$url] = 1;
        }
    }

    if (array_key_exists('media', $tweets->entities)) {
       foreach ($tweets->entities->media as $m) {
           /*$url = getRealUrl($m->media_url);*/
           $url = $m->media_url;
            if (array_key_exists($url, $link)) {
                $link[$url]++;
            } else {
                $link[$url] = 1;
            }
        }
    }
}

function getNumberGoogleResults($url){
  if($url != ""){
    $request = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=" . $url;
    $body = file_get_contents($request);
    if($body !== FALSE){
        $json = json_decode($body);
        return $json->responseData->cursor->resultCount;
        //Pour recuperer un entier au lieu d'un string : 
        //return $json->responseData->cursor->resultCount;
    }
  }
  return -1;  
}

function getRealUrl($url){
    $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$a = curl_exec($ch);

$url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); 

return $url;
}