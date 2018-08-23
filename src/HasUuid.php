<?php

namespace Bdelespierre\HasUuid;

use Webpatser\Uuid\Uuid;

trait HasUuid
{
    /**
     * Uuid models are no longer capable to auto-increment index.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Generate a new random UUID and use if as primary key
     * when creating a new model.
     *
     * @return void
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (! $model->getAttribute($model->getKeyName())) {
                $model->setAttribute($model->getKeyName(), $model->getUuid());
            }
        });
    }

    /**
     * Generates an UUID.
     *
     * @return string
     */
    public function getUuid(): string
    {
        return (string) Uuid::generate(
            $this->getUuidVersion(),
            $this->getUuidNode(),
            $this->getUuidNamespace()
        );
    }

    /**
     * Get the UUID version (default 4).
     *
     * @return int
     */
    public function getUuidVersion(): int
    {
        return $this->uuidVersion ?? 4;
    }

    /**
     * Get the UUID node.
     *
     * @return string|null
     */
    public function getUuidNode()
    {
        if (! isset($this->uuidNode)) {
            return;
        }

        // if the node is a string starting with ':'
        // then we use the value of the corresponding
        // property as the node.
        if (starts_with($this->uuidNode, ':')) {
            return $this->{str_after($this->uuidNode, ':')};
        }

        return $this->uuidNode;
    }

    /**
     * Get the UUID namespace.
     *
     * @return string|null
     */
    public function getUuidNamespace()
    {
        // when no explicit namespace is provided,
        // the namespace is calculated from the
        // app key.
        if (! isset($this->uuidNamespace)) {
            $key = config('app.key');

            if (starts_with($key, 'base64:')) {
                $key = bin2hex(base64_decode(substr($key, 7)));
            } else {
                $key = self::strToHex($key);
            }

            // Webpatser/UUID demands a 16 bytes namespace
            // which is *slightly* unsafe because Laravel
            // generates 32 bytes keys...
            $key = substr($key, 32);

            return $key;
        }

        // if the namespace is a string starting with ':'
        // then we use the value of the corresponding
        // property as namespace.
        if (starts_with($this->uuidNamespace, ':')) {
            return $this->{str_after($this->uuidNamespace, ':')};
        }

        return $this->uuidNamespace;
    }

    /**
     * Helper that converts a string to hexadecimal characters.
     *
     * @param  string $string
     * @return string
     */
    protected static function strToHex(string $string): string
    {
        for ($i = 0, $hex = ''; $i < strlen($string); $i++) {
            $hex .= substr('0'.dechex(ord($string[$i])), -2);
        }

        return strtoupper($hex);
    }
}
