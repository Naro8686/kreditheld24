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
     * @param bool $trans
     * @return array
     */
    public static function getList(bool $trans = true): array
    {
        $list = [];
        $reflectionClass = new ReflectionClass(new self);
        foreach ($reflectionClass->getConstants() as $key => $value) {
            $list[$key] = $trans ? __("status.$value") : $value;
        }
        return $list;
    }
}
