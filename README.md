       _____                          _          /\\
      |_   _|__ _ __ ___  _ __   __ _| |         | ||
        | |/ _ \ '_ ` _ \| '_ \ / _` | |    -----| |/\
        | |  __/ | | | | | |_) | (_| | |           /  \
        |_|\___|_| |_| |_| .__/ \__,_|_|    ------/ ^^ \
                         |_|                 O O  | || |
	     Where fleetingness is a virtue           | || |
                                           -------------
[![License: WTFPL](https://img.shields.io/badge/License-WTFPL-brightgreen.svg)](http://www.wtfpl.net/about/)[![PHP](https://img.shields.io/badge/Made%20with-php-9cf)](https://php.net/)[![Maintainability](https://api.codeclimate.com/v1/badges/1e03fc80af9a5f03f52c/maintainability)](https://codeclimate.com/github/MaverickEsq/tempal/maintainability)
## :card_index: Description
Inspired by *sprunge*, *ix.io* and others, I decided to make a small, command line pastebin. The things I saw when looking at other pastebins were that they used things like weird google storage APIs and didn't have a web form that could be quickly used.

To this end, I decided to make a pastebin that met a few criteria.
1. It had to be portable, not requiring any installs or
	setups and could be dragged and dropped.
2. It had to use a local database. Tinfoil hat compatibility.
3. It needed a webform, others had web forms that required
	pasting things into the address bar.
4. It should meet the alternatives in terms of functionality.

So, tempal was written in php, using an sqlite3 database and as little complexity as possible.

## :rocket: Deployment
Simply `git clone` or extract from zip into a web directory and `chmod ./inc 777`.  
Ensure you edit `config.ini` to include your domain and the name of your pastebin.  
  
The first time the script runs it will make a database.

### :pushpin: Requirements  
:package: `PHP>=7.0`  
:package: `SQLite3` from :package: `php-pdo`  

## :beers: Credits
This uses :package: GeSHi (http://qbnz.com/highlighter/) but for simplicity's sake I have included it.

Credit obviously to :sparkles: sprunge.us and :sparkles: ix.io who inspire this and whose main page I shamelessly ripped off

:art: `mpan` probably deserves a credit, for input to this project but also just in general.

## :page_facing_up: License
Tempal is licensed under the WTFPL2.1 under the condition that any fork or derative work remains under the WTFPL2.1.

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
