<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2/15/15
 * Time: 1:12 AM
 */

/**
 * Class DB
 */
class DB {

    private $db;
    private $collection_feats;
    private $collection_tweets;
    private $collection_coefs;
    private $collection_disabledUsers;

    function __construct($dbName, $user, $pw) {

        $tmp = new MongoClient();
        $this->db = $tmp->selectDB("twitter");
        $this->collection_feats = $this->db->selectCollection("feats");
        $this->collection_disabledUsers = $this->db->selectCollection("dusers");
        $this->collection_tweets = $this->db->selectCollection("tweets");
        $this->collection_coefs = $this->db->selectCollection("coefs");
    }

    function addFeats($username, $feats) {


        $this->collection_feats->update(array("username" => $username), array("username" => $username, "vals" => $feats), array("upsert" => true));

    }

    function addDisabledUsersById($id) {

        $this->collection_disabledUsers->update(array("id" => intval($id)), array("id" => intval($id)), array("upsert" => true));

    }

    function getDisabledUsers($id) {

        $crit = array("id" => intval($id));

        $res = $this->collection_disabledUsers->find($crit);

        return $res->getNext();

    }

    function getFeats($username) {

        $crit = array("username" => $username);

        $res = $this->collection_feats->find($crit);

        return $res->getNext();

    }

    function getFeatsById($id) {

        $crit = array("vals.id" => $id);

        $res = $this->collection_feats->find($crit);

        return $res->getNext();

    }

    function getCoefs() {
        $res = $this->collection_coefs->find()->sort(array('date' => -1));

        return $res;
    }

    function getActiveCoefs() {
        $res = $this->collection_coefs->find(array('active' => 1))->sort(array('date' => -1));

        return $res;
    }

    function getAllUsers() {

        $res = $this->collection_feats->find();
        return iterator_to_array($res);

    }

    function getUsers($n, $from, $search, $sort_by, $order) {

        $regex = new MongoRegex("/.*" . $search . ".*/i");

        $request = array('$or' => array(array('vals.screen_name' => $regex), array('username' => $regex)));

        $res1 = $this->collection_feats->find($request)->sort(array('vals.' . $sort_by => $order))->skip($from)->limit($n);
        $count = $this->collection_feats->find($request)->count();

        $res = array(
            'countTotal' => $count,
            'users' => iterator_to_array($res1)
        );

        return $res;
    }


    function addTweets($timeline) {
        for ($i = 0; $i < count($timeline); $i++) {

            $tweet =  $timeline[$i];
            $id = $tweet->{'id_str'};

            $t = $this->collection_tweets->find(array("id_str" => $id));

            if ($t->count() == 0) {

                $this->collection_tweets->insert(array("id_str" => $id, "vals" => $tweet));

            }

        }
    }

    function addTweet($tweet) {
        $id = $tweet->{'id_str'};
        $this->collection_tweets->update(array("id_str" => $id), array("id_str" => $id, "vals" => $tweet), array("upsert" => true));
    }

    function getTweetById($id_tweet) {

        $crit = array("vals.id_str" => $id_tweet);

        $res = $this->collection_tweets->find($crit);

        if ($res!= NULL) {

            return $res->getNext()['vals'];

        } else {

            return NULL;

        }
    }

    function setCoefActive($id, $val) {

        if ( $val == 1 ) {
            $this->collection_coefs->update(array('active' => 1), array('$set' => array('active' => 0)));
        }

        $this->collection_coefs->update(array('_id' => new MongoId($id)), array('$set' => array('active' => intval($val))));
    }

    function deleteResClassifier($id) {
        $this->collection_coefs->remove(array('_id' => new MongoId($id)));
    }

    function updateScore($username, $score) {

        $this->collection_feats->update(array('username' => $username), array('$set' => array('vals.score' => $score)));

    }

    function setSocialCapitalist($username, $casoc) {

        return $this->collection_feats->update(array('username' => $username), array('$set' => array('vals.casoc' => intval($casoc))));

    }

    function setSocialCapitalistById($id, $casoc) {

        return $this->collection_feats->update(array("vals.id" => intval($id)), array('$set' => array('vals.casoc' => intval($casoc))));

    }

    function setKredScore($username, $kred) {

        $this->collection_feats->update(array('username' => $username), array('$set' => array('vals.kred_score' => intval($kred))));

    }

    function setKloutScore($username, $klout) {

        $this->collection_feats->update(array('username' => $username), array('$set' => array('vals.klout_score' => intval($klout))));

    }

}

try {
    //echo extension_loaded("mongo") ? "loaded\n" : "not loaded\n";
    $myDb = new DB("localhost", "root", "");

} catch (Exception $e) {

}