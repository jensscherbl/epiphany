<?php
namespace Epiphany;

use \Pool;

require 'src/Cache.php';
require 'src/Connection.php';
require 'src/Repository.php';
require 'src/Repository/Files.php';
require 'src/Server.php';

// init server with a
// pthreads threadpool
// of 8 worker threads

$server = new Server(
    new Pool(8),
    new Repository\Files(
        new Cache
    )
);

// start listening
// on port 8080

$server->listen(8080);
