<?php

namespace App\Constants;

use ReflectionClass;

class Status
{
    const PENDING = 'pending';
    const DENIED = 'denied';
    const APPROVED = 'approved';
    const REVISION = 'revision';

    /**
     * @return array
     */
    public static function getList(): array
    {
        $list = [];
        $reflectionClass = new ReflectionClass(new self);
        foreach ($reflectionClass->getConstants() as $key => $value) {
            $list[$key] = __("status.$value");
        }
        return $list;
    }
}
