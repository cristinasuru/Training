<?php

namespace App\Transformer;

interface OmdbTransformerInterface
{
    public function transform(array|string $value): object;
}