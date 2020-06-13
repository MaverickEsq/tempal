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

require_once(dirname(__FILE__).'/inc/func.inc.php');

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

    if (strlen($paste) > byteconv($config['max_size'])) {
        die("Paste larger than " . $config['max_size'] . " (" . dfh(strlen($paste)) . ")\n");
    }

    if ($paste == '') {
        die("Empty paste\n");
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

    $db = new SQLite3(dirname(__FILE__).'/inc/paste.db');
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

            // Header for adding things like styles to the highlit code page
            // This could be made prettier
            $header = '<html><head><style>ol{list-style:none;padding-left:0}li{background-color:#fff!important}#b{position:fixed;right:0;top:0}#b:checked ~ pre > ol{list-style:decimal!important;padding-left:40px!important;background:#f0f0f0;background:repeating-linear-gradient(0deg,#f0f0f0,#f0f0f0 1.1em,#fff 1px,#f0f0f0 1.2em)}#b:checked ~ pre > ol > li{padding-left:5px}</style><head><html><input type="checkbox" id="b" name="ln"><label for="ln" style="position: fixed;right: 0.5em;font-size: 0.5em;top: 2em;">LN</label>';
            // Create and set up a GeSHi object
            $geshi= new GeSHi($paste, $lexer);
            $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS); // Enable OL
            $geshi->set_overall_id('n'); // Makes the anchors n-\d+
            $geshi->enable_ids(true); // Enables IDs for anchoring
            $geshi->enable_keyword_links(false); // Stops linking to docs
            $paste = $geshi->parse_code(); // Returns the html
            // Add the style header to the page
            $paste = $header . $paste;

        } else {
            header("Content-type: text/plain");
        }
    } else {
        // This is here because php has broken svg detection
        // and returns an unregistered mimetype for svg
        if (explode(';', $mime)[0] == 'image/svg') {
            $mime = 'image/svg+xml';
        }
        header('Pragma: public');
        header('Cache-Control: max-age=432000');
        header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 432000));
        header("Content-Type: " . $mime);
    }

    echo $paste;
} else {
    //Printing the page. If other criteria are met it never gets here
    include(dirname(__FILE__).'/inc/man.inc.php');
}
?>