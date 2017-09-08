<? 
	/**
	 * @file
	 * Take the user when they return from Twitter. Get access tokens.
	 * Verify credentials and redirect to based on response from Twitter.
	 */

	/* Start session and load lib */
	session_start();

	/* Load required lib files. */
	require_once('twitteroauth/twitteroauth.php');
	require_once('config.php');

    include_once('../mongodb.php');


	/* If access tokens are not available redirect to connect page. */
	if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
	    header('Location: ./clearsessions.php');
	}
	/* Get user access tokens out of the session. */
	$access_token = $_SESSION['access_token'];

	/* Create a TwitterOauth object with consumer/user tokens. */
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

	/* Get logged in user to help with tests. */
	$user = $connection->get('account/verify_credentials');

	$active = true;

	$id_tweet = (isset($_GET["id_tweet"])) ? $_GET["id_tweet"] : NULL;

    $tmp = new MongoClient();

    $standard = $myDb->getTweetById($id_tweet);

    //var_dump($standard);

    //echo $standard['text'];
    if ($standard != NULL) {

        $text =  $standard["text"];
        $created_at = $standard["created_at"];
        $name = $standard["user"]["name"];
        $screen_name = $standard["user"]["screen_name"];
        $source = $standard["source"];
        $user_id  = $standard["user"]["id"];

        $source = utf8_decode(addslashes(trim($standard['source'])));

    } else {

        $method="statuses/show";
        $parameter=array("id" => $id_tweet);

        $standard=$connection->get($method, $parameter);
        $myDb->addTweet($standard);

        $text =  $standard->{"text"};
        $created_at = $standard->{"created_at"};
        $name = $standard->{"user"}->{"name"};
        $screen_name = $standard->{"user"}->{"screen_name"};
        $source = $standard->{"source"};
        $user_id  = $standard->{"user"}->{"id"};
        $source = utf8_decode(addslashes(trim($standard->{'source'})));
    }

	$method="users/show";
	$parameter=array("user_id" => $user_id);
	$infos=$connection->get($method, $parameter);
	$profilepic = $infos->{'profile_image_url'};
	
	$profilepic = str_replace("_normal", "", $profilepic);
	

    header('Content-Type: text/plain');

    if ($id_tweet) {

        $obj = array("text" => $text,
            "profile_pic" => $profilepic,
            "created_at" => $created_at,
            "name" => $name,
            "screen_name" => $screen_name,
            "source" => $source);

        echo  json_encode($obj);

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

?>
