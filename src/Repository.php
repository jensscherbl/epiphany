<?php
namespace Epiphany;

interface Repository
{
    /**
     * @param string $handle
     *
     * @return string
     */
    public function get(string $handle): string;
}
