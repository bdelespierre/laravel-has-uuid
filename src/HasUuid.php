<?php

namespace Bdelespierre\HasUuid;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;

trait HasUuid
{
    abstract public function setKeyType($type);

    abstract public function setIncrementing($value);

    abstract public function getAttribute($key);

    abstract public static function creating($callback);

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setKeyType('string');
        $this->setIncrementing(false);
    }

    protected static function bootHasUuid()
    {
        static::creating(function (Model $model) {
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
        if (! is_string($value) || ! app('uuid.validator')->validate($value)) {
            return null;
        }

        return parent::resolveRouteBinding($value);
    }

    public function getUuid(): string
    {
        return (string) app('uuid.generator')->generate(
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
        return $this->interpolate($this->uuidNode);
    }

    public function getUuidNamespace(): ?string
    {
        return $this->interpolate($this->uuidNamespace)
            ?: app('uuid.generator')->getDefaultNamespace();
    }

    protected function interpolate(?string $value): ?string
    {
        if (Str::startsWith($value, ':')) {
            return $this->getAttribute(Str::after($value, ':'));
        }

        return $value;
    }
}
