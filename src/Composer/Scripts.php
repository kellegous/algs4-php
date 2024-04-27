<?php

namespace Kellegous\Algs4\Composer;

use Exception;

final class Scripts
{
    private const array DATA_FILES = [
        'https://algs4.cs.princeton.edu/11model/tinyAllowlist.txt',
        'https://algs4.cs.princeton.edu/11model/tinyText.txt',
        'https://algs4.cs.princeton.edu/11model/largeAllowlist.txt',
        'https://algs4.cs.princeton.edu/11model/largeText.txt',
        'https://algs4.cs.princeton.edu/11model/cards.txt',
        'https://algs4.cs.princeton.edu/11model/cardsUnicode.txt',
        'https://algs4.cs.princeton.edu/11model/in1.txt',
        'https://algs4.cs.princeton.edu/11model/in2.txt',
    ];

    private function __construct()
    {
    }

    /**
     * @param string $path
     * @return resource
     * @throws Exception
     */
    private static function readFile(string $path): mixed
    {
        $f = fopen($path, 'r');
        if ($f === false) {
            throw new Exception("Failed to open file: $path");
        }
        return $f;
    }

    private static function writeFile(string $path): mixed
    {
        $dir = pathinfo($path, PATHINFO_DIRNAME);
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0777, true)) {
                throw new Exception("Failed to create directory: $dir");
            }
        }
        $f = fopen($path, 'w');
        if ($f === false) {
            throw new Exception("Failed to open file: $path");
        }
        return $f;
    }

    /**
     * @param string $url
     * @param string $dest
     * @return void
     * @throws Exception
     */
    private static function downloadFile(
        string $url,
        string $dest
    ): void {
        $r = self::readFile($url);
        try {
            $w = self::writeFile($dest);
            try {
                if (stream_copy_to_stream($r, $w) === false) {
                    throw new Exception(
                        "Failed to copy data from $url to $dest"
                    );
                }
            } finally {
                fclose($w);
            }
        } finally {
            fclose($r);
        }
    }

    private static function downloadData(): void
    {
        $root = getcwd() . '/data';
        foreach (self::DATA_FILES as $url) {
            $path = parse_url($url, PHP_URL_PATH);
            $dest = $root . $path;
            if (file_exists($dest)) {
                continue;
            }
            printf("=> %s\n", ltrim($path, '/'));
            self::downloadFile($url, $dest);
        }
    }

    private static function runPHPUnit(): bool
    {
        $status = 0;
        passthru('vendor/bin/phpunit tests', $status);
        return $status === 0;
    }

    private static function runPHPStan(): bool
    {
        $status = 0;
        passthru('vendor/bin/phpstan', $status);
        return $status === 0;
    }

    public static function postInstall(): void
    {
        self::downloadData();
    }

    public static function validate(): void
    {
        $phpunit_ok = self::runPHPUnit();
        $phpstan_ok = self::runPHPStan();
        printf("PHPUnit Tests: ..................... %s\n", $phpunit_ok ? "👍" : "👎");
        printf("PHPStan Static Analysis: ........... %s\n", $phpstan_ok ? "👍" : "👎");
        if (!$phpunit_ok || !$phpstan_ok) {
            exit(1);
        }
    }
}