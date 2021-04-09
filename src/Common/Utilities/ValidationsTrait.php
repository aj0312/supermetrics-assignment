<?php

namespace Src\Common\Utilities;

trait ValidationTrait {

    public function isVariableValid(mixed $var = null): bool {
        return (isset($var) && $var !== null);
    }

    public function isArrayValid(array | null $arr): bool {
        return (isset($arr) && !empty($arr) && $arr !== null);
    }
}