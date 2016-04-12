<?php
namespace Epiphany\Repository;

use \Epiphany\Cache;
use \Epiphany\Repository;

final class Files implements Repository
{
    /**
     * @var Cache
     */
    private $cache;

    /**
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     */
    public function get(string $handle): string
    {
       if (!isset($this->cache[$handle])) {

            // from disk

            $this->cache[$handle] = file_get_contents($handle);
        }

        // from cache

        return $this->cache[$handle];
    }
}
