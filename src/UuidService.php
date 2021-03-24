<?php

namespace Bdelespierre\HasUuid;

use Bdelespierre\HasUuid\Contracts\UuidGenerator;
use Bdelespierre\HasUuid\Contracts\UuidValidator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;

class UuidService implements UuidGenerator, UuidValidator
{
    /**
     * @var string
     */
    public static $defaultNamespace;

    public function generate(int $version, ?string $node = null, ?string $namespace = null): string
    {
        return Uuid::generate($version, $node, $namespace);
    }

    public static function getDefaultNamespace(): string
    {
        if (static::$defaultNamespace) {
            return static::$defaultNamespace;
        }

        // when no explicit namespace is provided,
        // the namespace is calculated from the
        // app key.
        $key = Config::get('app.key');

        $key = Str::startsWith($key, 'base64:')
            ? self::hash($key)
            : self::strToHex($key);

        // Webpatser/UUID needs 16 bytes for namespace
        // which is *slightly* unsafe because Laravel
        // generates 32 bytes keys...
        return substr($key, 32);
    }

    public function validate(string $uuid): bool
    {
        return Uuid::validate($uuid);
    }

    protected static function hash(string $key): string
    {
        return bin2hex(base64_decode(Str::after($key, 'base64:')));
    }

    protected static function strToHex(string $string): string
    {
        for ($i = 0, $hex = ''; $i < strlen($string); $i++) {
            $hex .= substr('0' . dechex(ord($string[$i])), -2);
        }

        return strtoupper($hex);
    }
}
