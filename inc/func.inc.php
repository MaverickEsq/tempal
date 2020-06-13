<?php
/**
 * TEMPAL v1.0 - func.inc
 * 
 * Various functions for use by Tempal
 *
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
if (!file_exists(dirname(__FILE__) . '/inc/paste.db')) {
	$db = new SQLite3(dirname(__FILE__) . '/inc/paste.db');
	$db->exec("CREATE TABLE pastes(pid TEXT, data BLOB, ip TEXT)");
	$db->close();
}

/* Returns a randomized alphanumeric string for IDs
 * 64^length possible combinations (435tb of pastes)
 *
 * @param  Int $len  - Length of ID in characters, default 8
 * @return String    - Alphanumeric ID for pastes
*/
function genid($len=8) {
    $uid = base64_encode(random_bytes($len * 3));
    $uid = preg_replace("/[^A-Za-z0-9]/", "", $uid);

    return substr($uid, 0, $len);
}

/* Converts string-like filesizes into bytes
 *
 * @param  String $s  - Input string of human-readable filesize (4M, 2G)
 * @return Int        - Number of bytes
*/
function byteconv($s) {
    return substr($s, 0, -1) * pow(1024, strpos("BKMGTX", substr($s, -1)));
}

/* Converts bytes into human-readable filesizes
 *
 * @param  Int $bytes    - Size of a file in bytes
 * @param  Int $decimals - Number of decimal places to print result to
 * @return String        - String of human-readable filesize (4.5M, 2.6G)
*/
function dfh($bytes, $decimals = 2) {
    // Conver bytes into string-like filesizes
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

?>