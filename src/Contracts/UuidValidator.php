<?php

namespace Bdelespierre\HasUuid\Contracts;

interface UuidValidator
{
    public function validate(string $uuid): bool;
}
