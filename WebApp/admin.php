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
    <script type="text/javascript" src="results/scripts/requests2.js"></script>

    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">

    <link rel="stylesheet" type="text/css" href="results/ddp.css" />
    <link rel="stylesheet" type="text/css" href="results/media.css" />
    <link rel="stylesheet" type="text/css" href="mycss.css" />

    <link href="http://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Arvo:400,700" rel="stylesheet" type="text/css" />

    <script type="text/javascript">

        // set the 'casoc' value of a user (0, 1 or 2)
        function setCapitalist(username, value) {

            var xhr = getXMLHttpRequest();

            xhr.open("GET", "setCapitalist.php?username=" + username + "&value=" + value, true);

            xhr.send(null);

        }

        function getScore(type, elem, username) {

            var xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {

                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {

                    //updateTab(xhr.responseText);
                    elem.html(JSON.parse(xhr.responseText)['score']);

                }
            };

            xhr.open("GET",  "getScore.php?type=" + type + "&username=" + username, true);

            xhr.send(null);

        }

        function setActiveResClassifier(id, val) {

            var xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {

                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {

                    requestDataClassifier(n_class, from_class);

                }
            };

            xhr.open("GET",  "results/setActiveResClassifier.php?id=" + id + "&val=" + val, true);

            xhr.send(null);

        }

        function deleteResClassifier(id) {

            var xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {

                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {

                    requestDataClassifier(n_class, from_class);

                }
            };

            xhr.open("GET",  "results/deleteResClassifier.php?id=" + id, true);

            xhr.send(null);

        }

        // return an object JSON containing a list of user
        function requestData(n, from, crits) {

            var xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {

                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {

                    updateTab(xhr.responseText);

                }
            };

            xhr.open("GET", "results/admin_results.php?n=" + n + "&from=" + from + "&s=" + crits, true);
            xhr.send(null);

            return xhr.responseText;

        }

        // return an object JSON containing a list of classifer coef & results
        function requestDataClassifier(n, from) {

            var xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {

                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {

                    updateTabClassifier(xhr.responseText);

                }
            };

            xhr.open("GET", "results/admin_results_classifier.php?n=" + n + "&from=" + from, true);
            xhr.send(null);

            return xhr.responseText;
        }

        function requestRefreshScores() {

            var xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {

                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {

                    requestData(n, from, JSON.stringify(crit));

                }
            };

            xhr.open("GET", "results/updateScores.php", true);
            xhr.send(null);

            return xhr.responseText;
        }

        function createLink(username, value) {

            var classes = ['not-ks', 'ks', 'unknown-ks'];
            var a = $('<a></a>');

            a.val(value);
            a.addClass(classes[value]);
            a.addClass('unselectable');

            a.on('click', function(u) {

                return function() {

                    var current = $(this).val();
                    var next = (current + 1) % classes.length;

                    setCapitalist(u, next);

                    $(this).removeClass(classes[current]);
                    $(this).addClass(classes[next]);
                    $(this).val(next);

                };
            }(username));

            return a;
        }

        function addColumnGetScore(row, type, username, score) {
            var td = $('<td>' + score + '</td>');

            td.on('click', function() {
                getScore(type, td, username);
            });

            row.append(td);
        }

        function addRow(tab, user) {

            var username = user['username'];
            var feats = user['vals'];

            var tr = $('<tr></tr>');

            tr.append([
                $('<td>' + username + '</td>'),
                $('<td>' + feats['screen_name'] + '</td>'),
                $('<td>' + feats['friends'] + '</td>'),
                $('<td>' + feats['followers'] + '</td>'),
                $('<td>' + (feats['score']).toFixed(4) + '</td>')
            ]);

            addColumnGetScore(tr, 'klout', username, feats['klout_score']);
            addColumnGetScore(tr, 'kred', username, feats['kred_score']);

            var a = createLink(username, feats['casoc']);

            tr.append($('<td></td>').append(a));

            tab.append(tr);
        }

        function updateStats(data) {
            var countTotal = data['count'];

        }

        // update the table with the list of users contained in data
        function updateTab(data) {

            var tab = $('#table_features');
            var jdata = JSON.parse(data);

            // clean the tab
            tab.find('tbody').remove();

            var users = jdata['users'];

            for ( var i in users ) {

                if ( users.hasOwnProperty(i) ) {

                    addRow(tab, users[i]);
                }
            }

            updatePagination(jdata['countTotal']);
            updateStats(jdata);

        }

        function updateTabClassifier(data) {

            var tab = $('#table_classifier');
            var jdata = JSON.parse(data);

            // clean the tab
            tab.find('tbody').remove();

            for ( var i in jdata ) {
                if ( jdata.hasOwnProperty(i) ) {
                    addRowClassifier(tab, jdata[i]);
                }
            }

        }

        function addRowClassifier(tab, results) {

            var date = results['date'];
            var coefs = results['coefs'];
            var means = results['means'];
            var stds = results['stds'];
            var id = results['_id']['$id'];
            var active = results['active'];

            var round = 2;

            var accuracy = results['accuracy'] !== undefined ? results['accuracy'].toFixed(round) : 0;
            var sensitivity = results['sensitivity'] !== undefined ? results['sensitivity'].toFixed(round) : 0;
            var specificity = results['specificity'] !== undefined ? results['specificity'].toFixed(round) : 0;
            var f_score = results['f-score'] !== undefined ? results['f-score'].toFixed(round) : 0;

            var t_accuracy = results['train_accuracy'] !== undefined ? results['train_accuracy'].toFixed(round) : 0;
            var t_sensitivity = results['train_sensitivity'] !== undefined ? results['train_sensitivity'].toFixed(round) : 0;
            var t_specificity = results['train_specificity'] !== undefined ? results['train_specificity'].toFixed(round) : 0;
            var t_f_score = results['train_f-score'] !== undefined ? results['train_f-score'].toFixed(round) : 0;

            var tr = $('<tr></tr>');

            if (active == 1) {
                tr.addClass('green');
            }

            tr.append([
                $('<td>' + date + '</td>'),
                $('<td>' + results['features'].length+ '</td>'),
                $('<td>' + accuracy + '% <span class="small">' + t_accuracy + '%</span></td>'),
                $('<td>' + sensitivity + '% <span class="small">' + t_sensitivity + '%</span></td>'),
                $('<td>' + specificity + '% <span class="small">' + t_specificity + '%</span></td>'),
                $('<td>' + f_score + '% <span class="small">' + t_f_score + '%</span></td>')
            ]);

            tr.on('click', function() {
               setActiveResClassifier(id, (active + 1) % 2);
            });

            var tmp = $('<td></td>');

            var del = $('<a href="#">delete</a>');

            del.on('click', function(){
               deleteResClassifier(id);
            });

            tmp.append(del);
            tr.append(tmp);
            tab.append(tr);
        }

        function updatePagination(nrow) {

            var nbPages = Math.ceil(nrow / n);
            var currentPage = ((from / n) + 1);

            $('#page_features #currentPage').html(currentPage);
            $('#page_features #maxPage').html(nbPages);

            var prev = $('#page_features #prev');
            var next = $('#page_features #next');

            prev.unbind( "click" );
            next.unbind( "click" );

            if ( currentPage != 1 ) {
                prev.on('click', back).addClass('clickable');
            } else {
                prev.removeClass('clickable');
            }

            if ( currentPage < nbPages ) {
                next.on('click', forward).addClass('clickable');
            } else {
                next.removeClass('clickable');
            }
        }

        function back() {

            var tmp = Math.max(from - n, 0);

            if ( tmp != from ) {

                from = tmp;

                requestData(n, from, JSON.stringify(crit));

            }
        }

        function forward() {

            var tmp = from + n;

            if ( tmp != from ) {

                from = tmp;

                requestData(n, from, JSON.stringify(crit));

            }
        }

        var n = 20, from = 0;
        var n_class = 10, from_class = 0;

        var crit = {
            search: '',
            sort_by: '',
            sort_inv: 1
        };

        $(function() {

            $('#refreshScores').on('click', function(e) {
                e.preventDefault();

                requestRefreshScores();

                return false;
            });

        });

        $(function() {

            requestDataClassifier(n_class, from_class);

        });

        $(function() {

            $('#page_features #myinput').on('change input', function() {
                from = 0;
                crit.search = $('#page_features #myinput').val();
                requestData(n, from, JSON.stringify(crit));
            });

            $('#table_features thead th').on('click', function(){
                if (crit.sort_by == $(this).attr('id')) {
                    crit.sort_inv *= -1;
                }

                crit.sort_by = $(this).attr('id');

                requestData(n, from, JSON.stringify(crit));
            });

            requestData(n, from, JSON.stringify(crit));

        });

    </script>

</head>

<body>

<div id="content">

    <h1>Régression logistique</h1>

    <a href="results/startClassifier.php">Start Classifier</a>

    <table class="table1" id="table_classifier">
        <thead>

        <tr>
            <th>Date</th>
            <th>nb features</th>
            <th>accuracy <span class="small">train</span></th>
            <th>sensitivity <span class="small">train</span></th>
            <th>specificity <span class="small">train</span></th>
            <th>f-score <span class="small">train</span></th>
            <th>delete</th>
        </tr>
        </thead>
    </table>

    <!--
    <div class="pagination" id="page_classifier">
        <a class="p_left unselectable" id="prev">prev</a>
        <span class="p_left">page <span id="currentPage"></span> of <span id="maxPage"></span></span>
        <a class="p_left unselectable" id="next">next</a>
        <input class="p_left" id="myinput" />
    </div>
    -->

    <h1>Compte twitters</h1>

    <a id="refreshScores" href="#">Refresh Scores</a>

    <table class="table1" id="table_features">
        <thead>
            <tr>
                <th>username</th>
                <th id="screen_name_l">Screen Name</th>
                <th id="friends">friends</th>
                <th id="followers">Followers</th>
                <th id="score">Score</th>
                <th id="klout_score">klout</th>
                <th id="kred_score">kred</th>
                <th id="casoc">Capitalist</th>
            </tr>
        </thead>
    </table>

    <div class="pagination" id="page_features">
        <a class="p_left unselectable" id="prev">prev</a>
        <span class="p_left">page <span id="currentPage"></span> of <span id="maxPage"></span></span>
        <a class="p_left unselectable" id="next">next</a>
        <input class="p_left" id="myinput" />
    </div>

</div>
</body>

</html>
