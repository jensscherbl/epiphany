<?php
namespace Epiphany;

use \Pool;

final class Server
{
    const protocol = 'tcp';
    const host     = '127.0.0.1';

    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var string
     */
    private $protocol;

    /**
     * @var string
     */
    private $host;

    /**
     * @param Pool       $pool
     * @param Repository $repository
     */
    public function __construct(Pool $pool, Repository $repository)
    {
        $this->pool       = $pool;
        $this->repository = $repository;
        $this->protocol   = self::protocol;
        $this->host       = self::host;
    }

    /**
     * @param int $port
     */
    public function listen(int $port)
    {
        $socket = @stream_socket_server(
            "{$this->protocol}://{$this->host}:{$port}",
            $error_code,
            $error_message
        );

        if (!$socket) {

            throw new \Exception(
                "Couldn't bind to {$this->protocol}://{$this->host}:{$port}: {$error_message}"
            );
        }

        while (true) {

            // keep listening...

            if (!$connection = @stream_socket_accept($socket, -1)) {

                // connection failed.

                continue;
            }

            // connection established,
            // hand off to worker.

            $this->pool->submit(
                new Connection(
                    $connection,
                    $this->repository
                )
            );
        }
    }
}
