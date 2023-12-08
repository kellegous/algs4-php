#!/usr/bin/env php
<?php declare(strict_types=1);

require 'vendor/autoload.php';

/**
 * @param string $contents
 *
 * @return array<string, mixed>|null
 */
function get_section(string $contents): ?array
{
    $matches = null;
    $status = preg_match('#see <a href="(\S+)">Section (\d+)\.(\d+)</a>#', $contents, $matches);
    if ($status === false) {
        throw new \RuntimeException("match failure");
    } elseif ($status === 1) {
        return [
            'url' => $matches[1],
            'section' => [
                intval($matches[2]),
                intval($matches[3]),
            ],
        ];
    }
    return null;
}

$root = realpath(__DIR__ . '/..');
$source_path = $root . '/tmp/algs4/src/main/java/edu/princeton/cs/algs4';
$source_files = scandir($source_path);
if ($source_files === false) {
    throw new \RuntimeException("Unable to scan directory: $source_path");
}

$files_with_sections = [];
$files_with_no_section = [];

foreach ($source_files as $file) {
    if ($file === '.' || $file === '..') {
        continue;
    }

    $path = $source_path . '/' . $file;
    $contents = file_get_contents($path);
    if ($contents === false) {
        throw new \RuntimeException("Unable to read file: $path");
    }
    $section = get_section($contents);
    if ($section === null) {
        $files_with_no_section[] = $file;
        continue;
    }

    $files_with_sections[] = array_merge(
        $section,
        ['file' => $file]
    );
}

usort($files_with_sections, function (array $a, array $b): int {
    return $a['section'][0] <=> $b['section'][0]
        ?: $a['section'][1] <=> $b['section'][1];
});

foreach ($files_with_sections as ['file' => $file, 'url' => $url, 'section' => $section]) {
    printf(" - [ ] [%s](%s) %s\n", implode('.', $section), $url, $file);
}