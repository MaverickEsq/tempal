<?php
if (!file_exists('./inc/paste.db')) {
	$db = new SQLite3('./inc/paste.db');
	$db->exec("CREATE TABLE pastes(pid TEXT, data BLOB, ip TEXT)");
	$db->close();
}

$config = parse_ini_file('config.ini');
ini_set('post_max_size', $config['max_size']);

//maybe only run this when we need it
$db = new SQLite3('./inc/paste.db');

if (isset($_POST[$config['name']])) {
        $db = new SQLite3('./inc/paste.db');
        $idchars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pasteid = substr(str_shuffle($idchars), 0, 8);

        $db->exec('INSERT INTO pastes(pid, data, ip) VALUES(\'' . $pasteid . '\', \'' . SQLite3::escapeString($_POST[$config['name']]) . '\', \'' . $_SERVER['REMOTE_ADDR'] . '\')');

        print 'https://' . $config['url'] . '/' . $pasteid . "\n";
        $db->close();
        die;
} else if (isset($_GET['id'])) {
        //get paste
        $db = new SQLite3('./inc/paste.db');
        $paste = $db->querySingle('SELECT data FROM pastes WHERE pid=\'' . SQLite3::escapeString($_GET['id']) . '\'');
        $db->close();
        if ($paste == NULL) {
                die("No such paste found");
        }

        if (isset($_GET['hl'])) {
                //highlighting
                include_once './inc/geshi.php';
                //null for the path cause we leave it default, true to
                //return it instead of echoing it.
                $paste = geshi_highlight($paste, $_GET['hl'], null, true);
        } else {
                header("Content-Type: text/plain");
        }
        print $paste;
        die;
}

print '<html ml-update="aware"><head><title>' . $config['name'] . '</title></head><body>

<style> a { text-decoration: none } </style>
<pre>' . $config['name'] . '(1)                          ' . strtoupper($config['name']) . '                          ' . $config['name'] . '(1)

NAME
    ' . $config['name'] . ': command line pastebin.

SYNOPSIS
    <code>&lt;command&gt; | curl -F \'' . $config['name'] . '=<-\' http://' . $config['url'] . '</code>

DESCRIPTION
    add <b>?&lt;lang&gt;</b> to resulting url for line numbers and syntax highlighting
    use <a onclick=\'var x = document.getElementById("paste"); if (x.style.display === "none") {x.style.display = "block";} else {x.style.display = "none";}\' href="#">this form</a> to paste from a browser
    <div id="paste" style="display:none;"><form action="http://' . $config['url'] . '" method="POST" accept-charset="UTF-8"><textarea name="' . $config['name'] . '" cols="80" rows="24"></textarea><br><button type="submit">' . $config['name'] . '</button></form></div>
EXAMPLES
    ~$ cat crash/bang | curl -F \'' . $config['name'] . '=<-\' http://' . $config['url'] . '
       http://' . $config['url'] . '/aXZI
    ~$ firefox http://' . $config['url'] . '/aXZI?py

SEE ALSO
    http://github.com/MaverickEsq/tempal

</pre>
</body>
</html>';
