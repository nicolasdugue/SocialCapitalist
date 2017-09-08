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

<html xmlns="http://www.w3.org/1999/html">

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

        /**
         * set the 'casoc' value of a user (0, 1 or 2)
         */
        function setCapitalist(username, value) {

            var xhr = getXMLHttpRequest();

            if ( value == undefined ) {
                value = 1;
            }

            if ( isNaN(username) ) {

                xhr.open("GET", "setCapitalist.php?username=" + username + "&value=" + value, true);

            } else {

                xhr.open("GET", "setCapitalist.php?id=" + parseInt(username) + "&value=" + value, true);

            }

            xhr.send(null);

        }

        /**
         * get features list of a user
         *
         * @param success callback function to be called on success
         * @param aborted callback function to be called when request aborted
         * @param username username of user (can be a string or an ID)
         * @param file when sending multiple request at the same time, this is the number of the 'thread'
         * @param line current user
         * @returns {*} the XHR request
         */
        function getFeatures(success, aborted, username, file, line) {

            var xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {

                if (xhr.readyState === 4 && xhr.status === 200) {

                    var content = JSON.parse(xhr.responseText);

                    success(content);

                } else if ( xhr.readyState === 4 && xhr.status === 0 ) {

                    aborted();

                }
            };

            if ( isNaN(username) ) {

                xhr.open("GET", "results/results.php?mthread=" + nthread + "&bun=" + line + "&batch_file=" + file + "&batch=1&fromdb=2&username=" + username, true);

            } else {

                xhr.open("GET", "results/results.php?mthread=" + nthread + "&bun=" + line + "&batch_file=" + file + "&batch=1&fromdb=2&id=" + parseInt(username), true);
            }

            xhr.send(null);

            return xhr;
        }

        // array of xhr request, we keep a reference to be able to cancel them if desired
        var current_xhr = [];

        // array containing the list of names/id to process
        var names = [];

        // number of elements (names/id) treated
        var elemTreaded = 0;

        // number of request that will be sent at the same time
        var nthread = 10;

        // timestamp when the script was started
        var starting_time;

        /**
         * Send request, process the result & send another one
         *
         * @param file thread identifier (ex:if nthread = 10, startSync should be started with 0, 1, 2 .. 9)
         * @param user_number should always be 0 the first time the function is called
         */
        function startSync(file, user_number) {

            // if there is none, stop
            if (names.length == 0) {
                end();
                return false;
            }

            var l = names.shift();

            // username of the user
            var username = l[0];

            // if the user should be considered like a social capitalist or not
            var cap = l[1];

            // create a line to display the result of the request
            var elem = $('<tr></td>').append($('<td>' + username + '</td>'));
            $('#myTable').append(elem);

            /**
             * function that will be called on success
             */
            var success = function(data) {

                // update the number of elem that has been treated
                elemTreaded += 1;
                $('#elemTreated').html(elemTreaded);

                // update the speed
                $('#speed').html(elemTreaded / (((new Date()).getTime() - starting_time) / 1000.0));

                // if no errors
                if ( !(data['error'] === 'yes') ) {

                    elem.css('color', 'green');

                // if error
                } else {

                    elem.css('color', 'red');
                    elem.append('<td>' + data['message'] + '</td>');

                }

                // store in the database if the user is a social capitalist or not
                setCapitalist(username, cap);

                // next user_number to use
                var n = "batch_user_nb" in data ? data['batch_user_nb'] : 0;

                startSync(file, n);

            };

            /**
             * if request has been aborted
             */
            var aborted = function() {
                elem.css('color', '#ff8a76');
            };

            // send the request
            current_xhr[file] = getFeatures(success, aborted, username, file + 1, user_number);

        }


        function end() {
            $('#state').html('finished');
        }

        function stop() {

            // abort current requests
            for (var c of current_xhr) {
                c.abort();
            }

            $('#cancel').hide();
            $('#resume').show();
            $('#state').html('stopped');
        }

        function start() {

            $('#drop_zone').hide();
            $('#syntax').hide();
            $('#state_content').show();
            $('#cancel').show();
            $('#resume').hide();
            $('#state').html('ongoing');

            $('#elemTotal').html(names.length);

            starting_time = (new Date()).getTime();

            for (var i = 0; i < nthread; i++) {
                startSync(i, 0);
            }
        }

        function handleDragOver(e) {

            e.stopPropagation();
            e.preventDefault();

            e.dataTransfer.dropEffect = 'copy';
        }

        function handleFile(e) {

            e.stopPropagation();
            e.preventDefault();

            // e.dataTransfer or e.target depending if the file comes from a drag&drop or an html input
            var f = (e.target.files || e.dataTransfer.files)[0];

            var r = new FileReader();

            r.onload = function(e) {

                var contents = e.target.result;
                var lines = contents.split('\n');

                for ( var l of lines ) {
                    if ( l != "") {
                        var s = l.split(' ');
                        var ss = [];

                        ss.push(s[0]);

                        if (s.length == 1) {
                            ss.push(2);
                        } else {
                            ss.push(s[1]);
                        }

                        ss.push(0);

                        names.push(ss);
                    }
                }

                start();
            };

            r.readAsText(f);
        }


        $(function() {

            $('#cancel').on('click', stop);
            $('#resume').on('click', start);

            $.event.props.push('dataTransfer');

            $('#files').on('change', handleFile);

            $('#drop_zone').on({
                dragover    : handleDragOver,

                drop        : handleFile
            });

        });

    </script>

</head>

<body>

<div id="content">

    <div id="syntax">
        File syntax : [username] [cap] <br /> <br />

        example: <br /> <br />

        barackobama 0 <br />
        oprah 0 <br />
        teamfollowback 1 <br />
        <br /> <br /> <br />

    </div>

    <div id="drop_zone">
        <div class="drop-zone-text">Drop files here</div>
        <div>or :</div>
        <div class="drop-zone-text-computer"><input type="file" id="files" name="files"/></div>
    </div>

    <div id="state_content" style="display: none">
        State : <span id="state"></span>, <span id="elemTreated"></span> / <span id="elemTotal"></span>
        <div></div>
        Speed : <span id="speed"></span> request per seconds

        <input type="button" class="buttont button_red" id="cancel" value="Stop" />
        <input type="button" class="buttont button_green" id="resume" value="Resume" />

        <table id="myTable">

        </table>
    </div>

</div>
</body>

</html>
