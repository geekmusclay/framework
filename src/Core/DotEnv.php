<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Core;

use InvalidArgumentException;
use RuntimeException;

use function array_key_exists;
use function explode;
use function file;
use function file_exists;
use function is_readable;
use function putenv;
use function sprintf;
use function strpos;
use function trim;

use const FILE_IGNORE_NEW_LINES;
use const FILE_SKIP_EMPTY_LINES;

class DotEnv
{
    /**
     * Load dot env file and store data into $_ENV super global.
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public static function load(string $path): void
    {
        if (false === file_exists($path)) {
            throw new InvalidArgumentException(sprintf('%s does not exist', $path));
        }

        if (false === is_readable($path)) {
            throw new RuntimeException(sprintf('%s file is not readable', $path));
        }

        // phpcs:disable
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // phpcs:enable
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $name           = trim($name);
            $value          = trim($value);

            if (false === array_key_exists($name, $_SERVER) && false === array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name]    = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
