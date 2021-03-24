<?php

namespace Bdelespierre\HasUuid\Contracts;

interface UuidGenerator
{
    public const VERSION_1 = 1;
    public const VERSION_3 = 3;
    public const VERSION_4 = 4;
    public const VERSION_5 = 5;

    public function generate(int $version, ?string $node = null, ?string $namespace = null): string;

    public static function getDefaultNamespace(): string;
}
