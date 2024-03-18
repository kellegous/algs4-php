<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Testing;

use Exception;

final class ErrorStream
{
    /**
     * @var resource
     */
    public mixed $context;

    private const PROTOCOL = 'error-after';

    private string $data = '';

    public function stream_open(
        string $path,
        string $mode,
        int $options,
        ?string &$opened_path
    ): bool {
        $this->data = substr($path, strlen(self::PROTOCOL) + 3);
        return true;
    }

    public function stream_read(int $count): mixed
    {
        $data = $this->data;
        if ($data === '') {
            return false;
        }

        if (strlen($data) <= $count) {
            $this->data = '';
            return $data;
        }

        $this->data = substr($data, $count);
        return substr($data, 0, $count);
    }

    public function stream_write(string $data): int
    {
        return 0;
    }

    public function stream_eof(): bool
    {
        return false;
    }

    public function stream_tell(): int
    {
        return 0;
    }

    public static function unregister(): void
    {
        stream_wrapper_unregister(self::PROTOCOL);
    }

    public static function register(): void
    {
        if (!stream_register_wrapper(self::PROTOCOL, self::class)) {
            throw new Exception("unable to register stream wrapper");
        }
    }
}