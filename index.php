<?php
/*
     * TEMPAL v1.0
     * Copyright (c) Sloth <sloth@faggotry.org>
     * This program is free software. It comes without any warranty, to
     * the extent permitted by applicable law. You can redistribute it
     * and/or modify it under the terms of the Do What The Fuck You Want
     * To Public License, Version 2.1, as published by Sloth so long as
     * any derivative work includes this license.
     * See the LICENSE file for more details. 
*/

/* Make the database if its not there
    +-----+--------+----+
    |       pastes      |
    +-----+--------+----+
    | pid | data   | ip |
    +-----+--------+----+
*/
if (!file_exists('./inc/paste.db')) {
	$db = new SQLite3('./inc/paste.db');
	$db->exec("CREATE TABLE pastes(pid TEXT, data BLOB, ip TEXT)");
	$db->close();
}

function genid($len=8) {
    // Paste ID generation
    // You would need about 435 exabytes of pastes before an 8 char ID
    // will definitely repeat. Good enough for me.
    $uid = base64_encode(random_bytes($len * 3));
    $uid = preg_replace("/[^A-Za-z0-9]/", "", $uid);

    return substr($uid, 0, $len);
}

function byteconv($s) {
    // Convert string-like filesizes into bytes
    return substr($s, 0, -1) * pow(1024, strpos("BKMGTX", substr($s, -1)));
}

$config = parse_ini_file('config.ini');

// Start doing things
if (isset($_POST[$config['name']]) || isset($_FILES[$config['name']])) {
    // Making a paste

    // Decide if this is _POST or _FILES
    // Double down on isset for less warnings
    if (isset($_FILES[$config['name']]) && is_uploaded_file($_FILES[$config['name']]['tmp_name'])) {
        $paste = file_get_contents($_FILES[$config['name']]['tmp_name']);
    } else {
        $paste = $_POST[$config['name']];
    }

    if ($paste == '') {
        die("Empty paste\n");
    }

    if (strlen($paste) > byteconv($config['max_size'])) {
        die("Paste larger than " . $config['max_size'] . "\n");
    }

    // Get an id for the paste
    $pasteid = genid();

    // Preparing query because blob
    $db = new SQLite3('./inc/paste.db');
    $query = $db->prepare("INSERT INTO pastes(pid, data, ip) VALUES(:id, :paste, :ip)");
    $query->bindValue(':id', $pasteid, SQLITE3_TEXT);
    $query->bindValue(':paste', $paste, SQLITE3_BLOB);
    $query->bindValue(':ip', $_SERVER['REMOTE_ADDR'], SQLITE3_TEXT);
    $query->execute() or die("Database error");

    $url = 'https://' . $config['url'] . '/' . $pasteid;

    header("Content-Type: text/plain");
    header("refresh:5;url=" . $url);
    echo $url, "\n";
} else if (isset($_GET['id'])) {
    // There is an id given, so this is a request to view

    $db = new SQLite3('./inc/paste.db');
    $paste = $db->querySingle('SELECT data FROM pastes WHERE pid=\'' . SQLite3::escapeString($_GET['id']) . '\'');
    $db->close();

    // If there is no result
    if ($paste == NULL) {
    	header("Content-Type: text/plain");
        die("No such paste found");
    }

    $finfo = new finfo(FILEINFO_MIME);
    $mime = $finfo->buffer($paste);
    if (explode('/', $mime)[0] == "text") {
        if ($_GET['hl'] !== '') {
            //highlighting
            include_once './inc/geshi.php';

            // Get language returns "text" if its not a file extension
            // This lets us use 'pl' or 'perl' to highligh a block
            if (GeSHi::get_language_name_from_extension(strtolower($_GET['hl'])) != 'text') {
                    $lexer = GeSHi::get_language_name_from_extension(strtolower($_GET['hl']));
            } else {
                    $lexer = $_GET['hl'];
            }
            // null for the path cause we leave it default, true to
            // return it instead of echoing it.
            $paste = geshi_highlight($paste, $lexer, null, true);
        } else {
            header("Content-type: text/plain");
        }
    } else {
        header('Pragma: public');
        header('Cache-Control: max-age=432000');
        header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 432000));
        header("Content-Type: " . $mime);
    }

    echo $paste;
} else {
    //Printing the page. If other criteria are met it never gets here
    include 'inc/man.inc.php';
}
?>