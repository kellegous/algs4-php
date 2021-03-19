<?php

namespace Kellegous\Io;

class InputStream
{
    /**
     * @var resource
     */
    private $stream;

    public function __construct(
        $stream
    ) {
        $this->stream = $stream;
    }

    /**
     * @return string
     *
     * @throws Exception
     */
    public function readLine(): string
    {
        $line = fgets($this->stream);
        if ($line === false) {
            throw new Exception();
        }
        return $line;
    }
}