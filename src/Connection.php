<?php
namespace Epiphany;

final class Connection extends \Threaded
{
    /**
     * @var resource
     */
    private $connection;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @param resource   $connection
     * @param Repository $repository
     */
    public function __construct($connection, Repository $repository)
    {
        $this->connection = $connection;
        $this->repository = $repository;
    }

    /**
     * Prepare request/response objects
     * and hand off to some request handler.
     *
     * Emit the response from the request
     * handler and close the connection.
     *
     * This is just a demo, so we're only
     * sending a quick hello world here.
     */
    public function run()
    {
        $response[] = "HTTP/1.1 200 OK";
        $response[] = "\r\n";
        $response[] = "Content-Type: text/html\r\n";
        $response[] = "Connection: close\r\n";
        $response[] = "\r\n";
        $response[] = "\r\n";
        $response[] = $this->getContent('hello.html');

        foreach ($response as $line) {

            // fwrite doesn't reliably write to streams,
            // so we make sure to actually write everything.

            for ($written = 0; $written < strlen($line); $written = $written + $bytes) {

                if (!$bytes = fwrite($this->connection, substr($line, $written))) {

                    break;
                }
            }
        }

        // using stream socket shutdown
        // since fclose corrupts zend_mm_heap

        stream_socket_shutdown(
            $this->connection,
            STREAM_SHUT_RDWR
        );
    }

    /**
     * @param string $handle
     *
     * @return string
     */
    private function getContent(string $handle): string
    {
        return $this->repository->get($handle);
    }
}
