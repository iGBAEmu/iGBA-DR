<?php
/*
iGBA Dynamic Repository (iGBA-DR)
Unoffical.

SETUP:
1. Create a MySQL user, database, and table. Add the details in the 'database information' section.
2. Add the following columns to your newly created table: id (auto increment enabled), name (text), boxart (text), url (text).
3. Edit the repository information to something other than the example.
4. Add a rewrite rule that rewrites (not redirect) json to php for this specific file and web server it's going to be hosted on.
If you don't know how to do that, search for something like "{your web server} rewrite url from json to php".


Either add your ROMs manually into the table with each column filled in or use a script (like a custom-made control panel) to manage the ROM entries.

*/

// Database information
define('DBHOST', 'localhost');
define('DBUSER', 'user');
define('DBPASS', 'pass');
define('DB', 'igbarepo');
define('DBTABLE', 'romlist');

// Information about the repository
define('REPO_NAME', 'GBA archive');
define('REPO_LOGO', 'https://example.com/path/to/icon.png');
define('REPO_AUTHOR', 'Bob');

header('Content-Type: application/json; charset=utf-8');

$con = new mysqli(DBHOST, DBUSER, DBPASS, DB);
if (!$con) exit('{"err":"error connecting"}');
 
$romstore = [];

$romstore['reponame'] = REPO_NAME;
$romstore['repologo'] = REPO_LOGO;
$romstore['author'] = REPO_AUTHOR;

$romstorecount = 0;

$query = 'SELECT `id`,`name`,`boxart`,`url` FROM `'.DBTABLE.'`';

$romlist = $con->query($query);
if (!$romlist) exit('{"err":"failed to obtain rom data"}');
 
while ($romlistdat = $romlist->fetch_assoc()) {
    $romstore['games'][$romstorecount]['name'] = $romlistdat['name'];
    $romstore['games'][$romstorecount]['boxart'] = $romlistdat['boxart'];
    $romstore['games'][$romstorecount]['url'] = $romlistdat['url'];
    ++$romstorecount;
}

$romlist->free();
$con->close();
 
echo json_encode($romstore);
