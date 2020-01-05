<?php

declare(strict_types=1);

namespace xenialdan\blockstate\states;

abstract class BlockState
{
    const TYPE_BOOL = 0;
    const TYPE_INT = 1;
    const TYPE_STRING = 2;

    public $type;
    public $value;

    public abstract static function validate(): bool;
}