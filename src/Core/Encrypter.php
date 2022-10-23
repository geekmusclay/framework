<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Core;

use function base64_decode;
use function base64_encode;
use function intval;
use function strval;
use function trim;

/**
 * Main purpose of this class is to encrypt id in url
 * so we can pass them
 */
class Encrypter
{
    /**
     * Encryption key
     */
    protected int $key;

    /**
     * Encrypter constructor
     *
     * @param int $key The encryption key
     */
    public function __construct(int $key = 42)
    {
        $this->key = $key;
    }

    /**
     * This function will encrypt the id
     */
    public function encrypt(int $id): string
    {
        $sum = strval($id + $this->key);

        return trim(base64_encode($sum), "=");
    }

    /**
     * This function will decrypt the id
     */
    public function decrypt(string $encoded): int
    {
        $id = intval(base64_decode($encoded)) - $this->key;
        if ($this->encrypt($id) === $encoded) {
            return $id;
        }

        return -1;
    }

    /**
     * Set the encrypter key.
     *
     * @param int $key The key used to encrypt ids.
     */
    public function setKey(int $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get the encrypter key.
     *
     * @return integer The key used to encrypt ids.
     */
    public function getKey(): int
    {
        return $this->key;
    }
}
