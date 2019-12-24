       _____                          _
      |_   _|__ _ __ ___  _ __   __ _| |
        | |/ _ \ '_ ` _ \| '_ \ / _` | |
        | |  __/ | | | | | |_) | (_| | |
        |_|\___|_| |_| |_| .__/ \__,_|_|
                         |_|
	     Where fleetingness is a virtue
       
## Description
Inspired by *sprunge*, *ix.io* and others, I decided to make a small, command line pastebin. The things I saw when looking at other pastebins were that they used things like weird google storage APIs and didn't have a web form that could be quickly used.

To this end, I decided to make a pastebin that met a few criteria.
1. It had to be portable, not requiring any installs or
	setups and could be dragged and dropped.
2. It had to use a local database. Tinfoil hat compatibility.
3. It needed a webform, others had web forms that required
	pasting things into the address bar.
4. It should meet the alternatives in terms of functionality.

So, tempal was written in php, using an sqlite3 database and as little complexity as possible.

## Deployment
Simply `git clone` or extract from zip into a web directory and `chmod ./inc 777`. Ensure you edit `config.ini` to include your domain and the name of your pastebin. (This will be used for POST details and the client)  
`Tempal` expects to be at the root directory, theres probably a simple htaccess fix for those who wish to run it from a subdirectory.  
The first time the script runs it will make a database.

### Requirements  
`PHP>=7.0`  
`SQLite3` from `php-pdo`  

## Credits
This uses GeSHi (http://qbnz.com/highlighter/) but for simplicity's sake I have included it.

Credit obviously to sprunge.us and ix.io who inspire this and whose main page I shamelessly ripped off

`mpan` probably deserves a credit, for input to this project but also just in general.

## License
Tempal is licensed under the WTFPL under the condition that any fork or derative work remains under the WTFPL.

            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
                    Version 2.1, December 2019

	 Copyright (C) 2019 Sloth <sloth@faggotry.org>

	 Everyone is permitted to copy and distribute verbatim or modified
	 copies of this license document, and changing it is allowed as long
	 as the name is changed.

            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
     TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
     This program is free software. It comes without any warranty, to 
     the extent permitted by applicable law.

    0. You just DO WHAT THE FUCK YOU WANT TO.
