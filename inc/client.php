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
$config = parse_ini_file('config.ini');
header('Content-type: text/plain');
?>
#!/usr/bin/env bash

# Examples
#	<?= preg_replace('/^\d+?/', '', $config['name']) ?> file.txt 	# paste a file
#	uname -a | <?= preg_replace('/^\d+?/', '', $config['name']) ?> 	# paste the output of a command

<?= preg_replace('/^\d+?/', '', $config['name']) ?>() {
	if [[ "$1" == -h ]]; then
		echo "Upload a file a file:"
		echo "	<?= preg_replace('/^\d+?/', '', $config['name']) ?> file.txt"
		echo "Paste the output of a command:"
		echo "	uname -a | <?= preg_replace('/^\d+?/', '', $config['name']) ?>"
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

<?= preg_replace('/^\d+?/', '', $config['name']) ?> $*