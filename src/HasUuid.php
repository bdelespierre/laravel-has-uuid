<?php

namespace Bdelespierre\HasUuid;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;

trait HasUuid
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setKeyType('string')->setIncrementing(false);
    }

    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (! $model->getAttribute($model->getKeyName())) {
                $model->setAttribute(
                    $model->getKeyName(),
                    $model->getUuid()
                );
            }
        });
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (! is_string($value) || ! Uuid::validate($value)) {
            return null;
        }

        return parent::resolveRouteBinding($value);
    }

    public function getUuid(): string
    {
        return (string) Uuid::generate(
            $this->getUuidVersion(),
            $this->getUuidNode(),
            $this->getUuidNamespace()
        );
    }

    public function getUuidVersion(): int
    {
        return $this->uuidVersion ?? 4;
    }

    public function getUuidNode(): ?string
    {
        if (! isset($this->uuidNode)) {
            return null;
        }

        return $this->interpolate($this->uuidNode);
    }

    public function getUuidNamespace(): ?string
    {
        // when no explicit namespace is provided,
        // the namespace is calculated from the
        // app key.
        if (! isset($this->uuidNamespace)) {
            $key = Config::get('app.key');

            $key = Str::startsWith($key, 'base64:')
                ? self::hash($key)
                : self::strToHex($key);

            // Webpatser/UUID needs 16 bytes for namespace
            // which is *slightly* unsafe because Laravel
            // generates 32 bytes keys...
            return substr($key, 32);
        }

        return $this->interpolate($this->uuidNamespace);
    }

    protected function interpolate(string $value): ?string
    {
        if (Str::startsWith($value, ':')) {
            $value = $this->getAttribute(
                Str::after($value, ':')
            );
        }

        return $value;
    }

    protected static function hash(string $key): string
    {
        return bin2hex(base64_decode(Str::after($key, 'base64:')));
    }

    protected static function strToHex(string $string): string
    {
        for ($i = 0, $hex = ''; $i < strlen($string); $i++) {
            $hex .= substr('0'.dechex(ord($string[$i])), -2);
        }

        return strtoupper($hex);
    }
}
