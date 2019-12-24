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
    <code>&lt;command&gt; | curl -F '<?= $config['name'] ?>=<-' <?= $config['url'] ?></code>

DESCRIPTION
    add <b>?&lt;lang&gt;</b> to resulting url for line numbers and syntax highlighting
    use <a onclick='var x = document.getElementById("paste"); if (x.style.display === "none") {x.style.display = "block";} else {x.style.display = "none";}' href="#">this form</a> to paste from a browser
    <div id="paste" style="display:none;"><form action="//<?= $config['url'] ?>" method="POST" accept-charset="UTF-8"><textarea name="<?= $config['name'] ?>" cols="80" rows="24"></textarea><br><button type="submit"><?= $config['name'] ?></button></form></div>
EXAMPLES
    Use the output from a command to create a paste
        ~$ cat crash/bang | curl -F '<?= $config['name'] ?>=<-' https://<?= $config['url'] ?>&nbsp;
           http://<?= $config['url'] ?>/aXZI
        ~$ firefox http://<?= $config['url'] ?>/aXZI?py

    Upload a file
        ~$ curl -F '<?= $config['name'] ?>=@filename.ext' https://<?= $config['url'] ?>&nbsp;
           http://<?= $config['url'] ?>/aXZI

CLIENT
    A client is avalible from <?= $config['url'] ?>/client

        <code>curl <?= $config['url'] ?>/client > <?= $config['name'] ?>&nbsp;
        chmod +x <?= $config['name'] ?>&nbsp;
        ./<?= $config['name'] ?> -h</code>

    Or you may place the following in your ~/.bashrc

        <code><?= preg_replace('/^\d+?/', '', $config['name']) ?>() {
            if [[ $# -gt 0 ]]; then&nbsp;
                for FILE in "$@"&nbsp;
                do&nbsp;
                    curl -F '<?= $config['name'] ?>=@'"$FILE" https://<?= $config['url'] ?>&nbsp;
                done&nbsp;
            else&nbsp;
                curl -F '<?= $config['name'] ?>=<-' https://<?= $config['url'] ?>&nbsp;
            fi&nbsp;
        }</code>

SEE ALSO
    http://github.com/MaverickEsq/tempal

</pre>
</body>
</html>