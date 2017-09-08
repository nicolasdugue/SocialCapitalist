<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 3/22/15
 * Time: 9:49 PM
 */

if (!function_exists('stats_standard_deviation')) {
    /**
     * This user-land implementation follows the implementation quite strictly;
     * it does not attempt to improve the code or algorithm in any way. It will
     * raise a warning if you have fewer than 2 values in your array, just like
     * the extension does (although as an E_USER_WARNING, not E_WARNING).
     *
     * @param array $a
     * @param bool $sample [optional] Defaults to false
     * @return float|bool The standard deviation or false on error.
     */
    function stats_standard_deviation(array $a, $sample = false) {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
            --$n;
        }
        return sqrt($carry / $n);
    }
}

/**
 * return a valid TwitterOAuth connection object using one of the account inside the file given as parameter
 * (this is used for the batch thing)
 *
 * @param $file String containing the accounts token (tokenResearcher etc..)
 * @return TwitterOAuth valid twitteroAuth connection object
 */
function getTwitterOAuth($file) {

    // We store the current line number associated with the account used in the session variable
    // this allow us to keep track of which account is currently being used, and which one should be
    // used next once the Twitter API rate limit will be reached.

    // first, if the variable doesn't exist, we initialize it at 0 (first account in the file)
    if (!isset($_SESSION['numberCurrentToken'])) {

        $_SESSION['numberCurrentToken'] = 0;
        $_SESSION['remaining'] = 0;
        $_SESSION['token1'] = null;
        $_SESSION['token2'] = null;

    }

    $connection = null;

    if ( $_SESSION['remaining'] < -10 ) {

        $connection = new TwitterOAuth("qm3dS89iIRjhgWlPbKG1gg", "avdTgWprK4kLWIXxUT6JDAaRMYgixhYVizGlUym9TgU", $_SESSION['token1'], $_SESSION['token2']);
        $_SESSION['remaining'] -= 1;

    } else {

        // open the file
        $tokenFile = file($file);

        // goal of the while true loop is to start again from the first line of the file even if we reached the last line.
        while (true) {

            foreach ($tokenFile as $lineNumber => $lineContent) {

                // Once we reach the line of the currently used account
                if ($lineNumber == $_SESSION['numberCurrentToken']) {

                    // removes double space, tabs etc..
                    $line = preg_replace('/[ ]{2,}|[\t]/', ' ', trim($lineContent));
                    $arr = explode(" ", $line);
                    $connection = new TwitterOAuth("qm3dS89iIRjhgWlPbKG1gg", "avdTgWprK4kLWIXxUT6JDAaRMYgixhYVizGlUym9TgU", $arr[1], $arr[2]);

                    // check the remaining number of request available on this account
                    $method = "application/rate_limit_status";
                    $parameter = array();
                    $standard = $connection->get($method, $parameter);
                    //$remainingRequest = $standard->{"resources"}->{"users"}->{"/users/show/:id"}->{"remaining"};
                    $remainingRequest = $standard->{"resources"}->{"followers"}->{"/followers/ids"}->{"remaining"};

                    // if no remaining request, set session variable to the next account, and try again (on next loop iteration), else return $connection
                    if ($remainingRequest < 2) {

                        $_SESSION['numberCurrentToken'] = ($_SESSION['numberCurrentToken'] + 1) % count($tokenFile);

                    } else {

                        // save the account and the number of remaining request
                        // no need to re-read the entire file again each time

                        // next time this script will be executed, if the number of remaining request is above 0
                        // then this account will be used without doing any addition tests.
                        $_SESSION['remaining'] = $remainingRequest - 1;
                        $_SESSION['token1'] = $arr[1];
                        $_SESSION['token2'] = $arr[2];

                        return $connection;

                    }
                }
            }
        }
    }

    // this should never happen
    return $connection;
}

function getTwitterOAuth2($file, $batch_max_thread, $file_nb, &$user_nb) {

    $connection = null;

    // open the file
    $tokenFile = file($file);

    $file_size = count($tokenFile);
    $i = 0;

    // goal of the while true loop is to start again from the first line of the file even if we reached the last line.
    while (true) {

        foreach ($tokenFile as $lineNumber => $lineContent) {

            $i++;

            if ($i > 1000) {
                return null;
            }

            // saute un nombre '$user_nb' de ligne dans le fichier
            if ($lineNumber < ($file_nb + ($user_nb * $batch_max_thread))) {
                continue;
            }

            // if ((i % threads) == thread_nb) {

            // $current_line = ((($file_nb - 1) * ((int)($file_size / $batch_max_thread))) + $user_nb) % $file_size;

            // Once we reach the line of the currently used account
            //var_dump($batch_max_thread);

            if (($lineNumber % $batch_max_thread) == $file_nb) {

                // removes double space, tabs etc..
                $line = preg_replace('/[ ]{2,}|[\t]/', ' ', trim($lineContent));
                $arr = explode(" ", $line);

                if ( $file == 'tokenResearcher' ) {

                    $connection = new TwitterOAuth("qm3dS89iIRjhgWlPbKG1gg", "avdTgWprK4kLWIXxUT6JDAaRMYgixhYVizGlUym9TgU", $arr[1], $arr[2]);

                } else {

                    $connection = new TwitterOAuth("1PpYYO3tyjfyk03h4ywwg", "8vOVdwPM5RttySuXjRJDVLjtJE4TlgEWgPff1iRGrI", $arr[1], $arr[2]);

                }

                // check the remaining number of request available on this account
                $method = "application/rate_limit_status";
                $parameter = array();
                $standard = $connection->get($method, $parameter);
                //$remainingRequest = $standard->{"resources"}->{"users"}->{"/users/show/:id"}->{"remaining"};
                $remainingRequest = $standard->{"resources"}->{"followers"}->{"/followers/ids"}->{"remaining"};

                // if no remaining request, try the next one
                if ($remainingRequest < 2) {

                    $user_nb = ($user_nb + 1) % ($file_size / $batch_max_thread);

                } else {

                    return $connection;

                }
            }
        }

        $user_nb = 0;

    }

    // this should never happen
    return $connection;
}

/**
 * Compute score of a user based on the classifier values (coef, mean std) and his features.
 *
 * @param $values array coef, mean and std of the classifier
 * @param $feat array values of the features of the user
 * @return float score
 */
function getScore($values, $feat) {

    $MEAN = $values['means'];
    $STD = $values['stds'];
    $COEF = $values['coefs'];

    /*
    $MEAN = array( 7.37546379774 , 1.46087056561 , 3.32363757414 , 6.00761948018 , 6.38385767553 , 4.31349752802 , 0.302801802664 , 0.195363296176 , 0.501001595666 , 0.273517036346 , 2.49794149453 , 4.32314043713 , 0.0642785209509 , 0.0259043272832 , 0.192832064591 , 0.00133572374761 , 0.0855394163057 , 0.357631875561 , 60.4516010187 , 11.9429235309 , 28.200774762 , 45.975428502 , 49.5621619537 , 32.2215317567 , 2.34662957883 , 1.49546245343 , 3.89662837695 , 2.14334946286 , 19.6023225757 , 34.1135285778 , 0.51810532723 , 0.225457930065 , 1.20368616181 , 0.0113705642454 , 0.652058148686 , 2.81665096711 , 4.85351286393 , 5.30949196264 , 9.66557925322 , 11.3256696412 , 6.52002608503 , 0.498169345668 , 0.400102155567 , 0.772403564457 , 0.789134888441 , 3.49804005997 , 5.37217771147 , 0.151112174007 , 0.0476905005997 , 0.308141705435 , 0.0031290880802 , 0.16799662342 , 0.406071237636 , 17.7645582592 , 21.3572219542 , 22.8121678932 , 14.4434392434 , 1.02099152546 , 0.562657494808 , 1.92479288178 , 1.1233950901 , 9.97484610731 , 17.4510544627 , 0.213736846048 , 0.0534445858594 , 0.461124355779 , 0.00391713617724 , 0.241072882268 , 1.50277185493 , 38.8906612191 , 40.054590953 , 26.1383523878 , 1.89583626359 , 1.21952773018 , 3.08952006594 , 1.72184448093 , 15.4224128922 , 26.5589663689 , 0.411636883631 , 0.15695262133 , 1.13599979553 , 0.00910215984747 , 0.528227948981 , 2.16574851889 , 44.0580501102 , 27.8130299797 , 2.03124874542 , 1.32529603859 , 3.29092713931 , 2.22019503661 , 16.2945682222 , 27.6202633779 , 0.458542253765 , 0.185451505572 , 1.18644829896 , 0.00932663862829 , 0.567016945101 , 2.26946650837 , 18.9100690729 , 1.37498168397 , 0.876181046393 , 2.21548641549 , 1.21308288576 , 10.9841463934 , 18.780199981 , 0.285997193656 , 0.118067036923 , 0.838369380956 , 0.00613265897444 , 0.378483038583 , 1.54552938887 , 0.229674510023 , 0.0690965655486 , 0.182152934483 , 0.0900824272886 , 0.885725201583 , 1.31906046913 , 0.0229065604672 , 0.0118743042931 , 0.0486795244285 , 0.00100743794677 , 0.0260366649493 , 0.108024034734 , 0.0888058670279 , 0.0856453782835 , 0.051810003398 , 0.420929667617 , 0.672853551961 , 0.0170820161087 , 0.0102043665553 , 0.0353020816816 , 0.000595174447869 , 0.0392608950916 , 0.0445432989782 , 0.348225398343 , 0.154360467855 , 1.53627919607 , 2.427796291 , 0.0330983114993 , 0.0102186525062 , 0.0853493849648 , 0.000656312439694 , 0.030950571646 , 0.21119222822 , 0.391054010244 , 0.74545809506 , 1.09874526445 , 0.025194052212 , 0.00452000367163 , 0.053703182375 , 0.000476472691436 , 0.016097294192 , 0.102927114679 , 8.28826665934 , 13.3217344576 , 0.155803991183 , 0.0519901667083 , 0.405726757964 , 0.00279934118243 , 0.15218925589 , 1.09099070084 , 27.16032252 , 0.250937739435 , 0.0820416274681 , 0.689565842866 , 0.00442118669354 , 0.282153795104 , 1.91374704596 , 0.0321177396235 , 0.00065951397017 , 0.00451710137032 , 6.4127831625e-05 , 0.00363126642267 , 0.00849974093221 , 0.0142614689422 , 0.00118085770492 , 3.79822443708e-05 , 0.000755027338127 , 0.00223045221197 , 0.103905816989 , 8.95273449578e-05 , 0.00996635929092 , 0.0251039392331 , 0.00065757811776 , 8.52513775421e-05 , 9.34382285677e-05 , 0.0365400957897 , 0.0152735296712 , 0.213111803265 , 0.0 );
    $STD = array( 2.46051514665 , 1.6490512589 , 2.59190885932 , 1.67307184558 , 1.81780397425 , 0.551187943226 , 0.371464100993 , 0.225031219023 , 0.311805707907 , 0.562354373213 , 1.43127738375 , 2.91046032113 , 0.167290201052 , 0.116578020099 , 0.258305268732 , 0.0256084743753 , 0.170947664646 , 0.291909651859 , 32.2234664949 , 14.7509795748 , 24.9359689536 , 21.3616384095 , 23.9369315106 , 10.9704890539 , 3.11712564304 , 1.84022111264 , 2.83320903706 , 4.50640529476 , 12.8083469856 , 25.2567305367 , 1.39672382733 , 1.04341067159 , 1.73023894634 , 0.225160271632 , 1.35804582872 , 2.50755962881 , 8.74078379718 , 8.70346113107 , 12.0365460508 , 15.0187999323 , 7.48754268699 , 0.959951189066 , 0.754506074448 , 1.20446137775 , 2.74621965521 , 4.87201851245 , 7.98860599737 , 0.546636410081 , 0.296634496168 , 0.66726884684 , 0.0726716952685 , 0.473315481681 , 0.701458161597 , 20.7915567516 , 18.5045126959 , 20.0930148519 , 11.2524640671 , 1.60860964452 , 0.827614584001 , 2.00750118012 , 2.93806192783 , 9.48378016832 , 17.9731283682 , 0.70090518252 , 0.3197324145 , 0.938178818563 , 0.0988209005837 , 0.570667762674 , 1.69791917921 , 19.1332961336 , 19.3071912232 , 8.09010534932 , 2.50883577745 , 1.51611116465 , 2.2543220744 , 3.86070249873 , 9.90158673206 , 19.2180819748 , 1.11694271984 , 0.754612828882 , 1.61776256868 , 0.184015351288 , 1.0991631181 , 1.87468759941 , 23.9351683928 , 8.89562323818 , 2.67387221799 , 1.65019308501 , 2.45720506121 , 5.68903863471 , 10.4914418338 , 19.6991655197 , 1.24815837758 , 0.854405339557 , 1.67949363623 , 0.189386879914 , 1.18508946596 , 1.98295467731 , 3.33418938287 , 1.7517451093 , 1.02540977149 , 1.45415035645 , 2.50532804699 , 6.498599806 , 12.573377371 , 0.748340487496 , 0.536314417832 , 1.12468866611 , 0.118093987685 , 0.760037214127 , 1.264074805 , 0.548331118411 , 0.146893608484 , 0.294404528063 , 0.277012755468 , 1.32900431813 , 1.84400619332 , 0.0930043060907 , 0.0847646861452 , 0.123739518724 , 0.025025296083 , 0.0904023926955 , 0.186116427706 , 0.163084216589 , 0.118361646713 , 0.156869852262 , 0.543116206909 , 0.947083998588 , 0.065057844829 , 0.0590973845615 , 0.0802116902187 , 0.0140463068163 , 0.101770243405 , 0.0656576807785 , 0.391128742322 , 0.350516680332 , 1.32979763071 , 2.01010660833 , 0.101195415765 , 0.0610264397128 , 0.149393415835 , 0.0164838632935 , 0.0683297867583 , 0.219002017927 , 2.21971235943 , 1.59931975319 , 2.61225477488 , 0.149805027443 , 0.0628519241717 , 0.205239481543 , 0.0220289631453 , 0.073139337615 , 0.26162824385 , 6.3349682794 , 10.3427678335 , 0.461918791543 , 0.307279031612 , 0.695844080912 , 0.0690407359939 , 0.319878991341 , 1.05524555687 , 27.0319417724 , 0.788239707796 , 0.469184385026 , 1.29676285518 , 0.114689381881 , 0.687841028185 , 1.95377379421 , 0.100546658441 , 0.00752064032609 , 0.0194039095538 , 0.00237276699213 , 0.0166151830387 , 0.0273431642136 , 0.0754022133636 , 0.00938853598605 , 0.0020612127073 , 0.0075394960207 , 0.0132125718342 , 0.170350905893 , 0.00259934786202 , 0.0275082500085 , 0.0447904896601 , 0.0153573936016 , 0.0024056037214 , 0.00247348526429 , 0.101974480577 , 0.0332783584634 , 0.203928813376 , 1.0 );
    $COEF = array( -1.04660085686 , 2.48282106594 , 0.0372410466433 , -2.10439511575 , -0.506893774642 , 0.685014281249 , 1.86651433946 , -0.845988129304 , 2.09634975865 , 0.527652007674 , 2.51533711454 , 1.01363561333 , 10.1381756369 , 0.078104986754 , -3.88647439628 , -0.258117428477 , -0.401238936081 , 0.230938482068 , 0.669047758739 , -1.08908757232 , -0.69399901647 , -0.445940537726 , 0.979116440179 , 1.49538408214 , 0.727444744937 , 1.6132106815 , 0.474979930258 , 0.478102877444 , -0.403431391335 , -0.832377594525 , -0.900373532934 , -0.616564054933 , -0.0234585311718 , -0.0739277016473 , -0.592143922097 , 0.377125098205 , 0.372152953056 , 0.00271346760384 , 0.0726645927002 , 0.516008926833 , -2.88017958043 , -0.294368625175 , -0.422077259578 , -0.306694333183 , -0.120458696991 , 0.645659101576 , 0.0299590695723 , -0.670034513778 , 0.082048833055 , -0.817495083631 , 0.0331392363416 , -0.301375642622 , -0.457325442443 , -0.452276585134 , 0.0202321716237 , 1.22745254229 , 0.562795678661 , -0.14470783376 , -0.0131692701721 , -0.0938839584402 , 0.0106175255079 , 0.0343540673366 , -0.328206011282 , 0.13765321674 , 0.0122269521925 , 0.111895035318 , 0.0296348225594 , -0.00925024690668 , -0.508060908541 , 0.205939366003 , 4.13702850727 , -0.694281435592 , 0.104890720373 , 0.465609051542 , 0.0816946291916 , 0.0614470742438 , -0.100191645238 , -0.6960511303 , 0.601532607835 , -0.20829393409 , 0.25334724533 , 0.0409286411363 , 0.519792744699 , 0.532880296234 , -4.20398049629 , 3.29161936072 , -0.245115343584 , -1.19685172639 , -0.639439724823 , -0.46378495724 , -0.454617858353 , 0.85323866123 , -0.135919773322 , -0.639528530084 , 0.441819827292 , -0.0946660627979 , -0.0587157900187 , -0.371686250332 , -1.3355280588 , -0.419595106513 , -0.779109518753 , -2.87449659189 , -1.29612873742 , -2.88013276712 , 0.295295301378 , -1.46970520911 , -0.523309704194 , 0.966680331605 , 0.0300500975802 , -0.0195443327213 , 0.342333863947 , 0.241843288101 , -0.468372778708 , 0.240025505449 , 0.169208559647 , 0.449430743318 , -0.218590366696 , -0.326079402778 , 0.131747411695 , -0.236516183926 , -0.0193142854534 , -0.250201449084 , -0.696877570522 , 0.345012397622 , -0.0967494391807 , -0.0339003520029 , 0.00719610092923 , 0.158335554268 , 0.380683760683 , -0.131627913916 , 0.945743276064 , 0.0167972021785 , 0.167632542801 , 0.389624142392 , 0.685132473801 , 0.290365710454 , 0.320485616796 , 0.0759065320759 , -0.0780149130933 , 0.494099537501 , 0.0199723653393 , -0.00135189209066 , 0.118359656371 , -0.226700320197 , 0.766853434627 , -0.350314912662 , 0.150773421013 , -0.0704084658918 , 0.0611909781494 , -0.360311171224 , -0.0199641640251 , 0.00938369784829 , -0.373331377704 , 0.863771867009 , 0.232222534747 , 0.142232957209 , -0.0845549244268 , -0.491906691905 , 0.0395495127522 , -0.0456757787058 , -0.200895692701 , -0.824889272858 , 0.169212175445 , 0.141184875805 , 0.314052997362 , -0.0221230523001 , 0.0154218100017 , 0.357379473365 , -6.78900199384 , -0.223421374714 , -0.835275857029 , -0.101485362221 , -0.577698605483 , -0.947841272198 , 1.59139228344 , 0.158190116673 , 0.101530767024 , 0.185200125471 , 0.0671600273261 , 2.11347186734 , 0.0304590246853 , -0.0287539521853 , 0.144161411253 , 0.25719530246 , -0.00640758085276 , -0.0229158253607 , 0.811712045505 , 0.00772337361281 , 0.0691987650087 , -1.32319089512 );
    */
    $n = count($MEAN);
    $TAB = array();
    $TAB = array_pad($TAB, $n, 0);

    $n0 = count($feat);

    for ($i = 0; $i < $n0; $i++) {

        $TAB[$i] = log(1.0 + $feat[$i]);

    }

    $k = $n0;
    for ($i = 0; $i < $n0; $i++) {

        for ($j = $i; $j < $n0; $j++) {

            $TAB[$k] = $TAB[$i] * $TAB[$j];
            $k++;

        }

    }

    for ($i = 0; $i < $n; $i++) {

        $TAB[$i] = ($TAB[$i] - $MEAN[$i]) / $STD[$i];
    }

    $TAB[$n - 1] = 1.0;

    $sum = 0;
    for ($i = 0; $i < $n; $i++) {

        $sum += $TAB[$i] * $COEF[$i];
    }

    $score = 1. / (1. + exp(-$sum));

    return $score;
}