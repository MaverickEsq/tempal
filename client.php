<?php
$config = parse_ini_file('config.ini');
header('Content-type: text/plain');
?>
#!/usr/bin/env bash

# Examples
#	<?= $config['name'] ?> file.txt 	# paste a file
#	uname -a | <?= $config['name'] ?> 	# paste the output of a command

<?= $config['name'] ?>() {
	if [[ "$1" == -h ]]; then
		echo "Upload a file a file:"
		echo "	<?= $config['name'] ?> file.txt"
		echo "Paste the output of a command:"
		echo "	uname -a | <?= $config['name'] ?>"
		exit 1;
	fi
	if [[ $# -gt 0 ]]
	then
		for PASTE in "$@"
		do
			curl -F '<?= $config['name'] ?>=@'"$PASTE" https://<?= $config['url'] ?>

		done
	else
		curl -F '<?= $config['name'] ?>=<-' https://<?= $config['url'] ?>

	fi
}

<?= $config['name'] ?> $*