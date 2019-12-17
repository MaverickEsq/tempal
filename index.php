<?php
// Make the database if its not there
if (!file_exists('./inc/paste.db')) {
	$db = new SQLite3('./inc/paste.db');
	$db->exec("CREATE TABLE pastes(pid TEXT, data BLOB, ip TEXT)");
	$db->close();
}

function genid() {
    // You would need about 435 exabytes of pastes before an 8 char ID
    // will definitely repeat. Good enough for me.
    $idchars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($idchars), 0, 8);
}

$config = parse_ini_file('config.ini');
ini_set('post_max_size', $config['max_size']);

// Start doing things
if (isset($_POST[$config['name']]) || isset($_FILES[$config['name']])) {

    if (is_uploaded_file($_FILES[$config['name']]['tmp_name'])) {
        $paste = file_get_contents($_FILES[$config['name']]['tmp_name']);
    } else {
        $paste = $_POST[$config['name']];
    }
    // Handling for posted text
    $pasteid = genid();

    $db = new SQLite3('./inc/paste.db');
    $query = $db->prepare("INSERT INTO pastes(pid, data, ip) VALUES(:id, :paste, :ip)");
    $query->bindValue(':id', $pasteid, SQLITE3_TEXT);
    $query->bindValue(':paste', $paste, SQLITE3_BLOB);
    $query->bindValue(':ip', $_SERVER['REMOTE_ADDR'], SQLITE3_TEXT);
    $query->execute();

    $url = 'https://' . $config['url'] . '/' . $pasteid;

    header("Content-Type: text/plain");
    header("refresh:5;url=" . $url);
    print $url . "\n";
    die;
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

    if ($_GET['hl'] != '') {
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
        $finfo = new finfo(FILEINFO_MIME);
        if (substr($finfo->buffer($paste), 0, 4) == "text") {
            header("Content-type: text/plain");
        } else {
            header("Content-Type: " . $finfo->buffer($paste));
        }
    }
    print $paste;
    die;
}
//Printing the page. If we do things, it dies, so it never gets here
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $config['name'] ?></title>
    <style> a { text-decoration: none } </style>
</head>
<body>
<pre><?= $config['name'] ?>(1)                          <?= strtoupper($config['name']) ?>                          <?= $config['name'] ?>(1)

NAME
    <?= $config['name'] ?>: command line pastebin.

SYNOPSIS
    <code>&lt;command&gt; | curl -F '<?= $config['name'] ?>=<-' http://<?= $config['url'] ?></code>

DESCRIPTION
    add <b>?&lt;lang&gt;</b> to resulting url for line numbers and syntax highlighting
    use <a onclick='var x = document.getElementById("paste"); if (x.style.display === "none") {x.style.display = "block";} else {x.style.display = "none";}' href="#">this form</a> to paste from a browser
    <div id="paste" style="display:none;"><form action="//<?= $config['url'] ?>" method="POST" accept-charset="UTF-8"><textarea name="<?= $config['name'] ?>" cols="80" rows="24"></textarea><br><button type="submit"><?= $config['name'] ?></button></form></div>
EXAMPLES
    ~$ cat crash/bang | curl -F '<?= $config['name'] ?>=<-' https://<?= $config['url'] ?>&nbsp;
       http://<?= $config['url'] ?>/aXZI
    ~$ firefox http://<?= $config['url'] ?>/aXZI?py

SEE ALSO
    http://github.com/MaverickEsq/tempal

</pre>
</body>
</html>