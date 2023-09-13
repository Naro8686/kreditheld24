<?php

namespace App\Constants;

use ReflectionClass;

class Status
{
    const PENDING = 'pending';
    const DENIED = 'denied';
    const APPROVED = 'approved';
    const REVISION = 'revision';
    const DEADLINE_ENDS = 'ends';
    const DEADLINE_EXPIRED = 'expired';
    const DEADLINE_NOT_EXPIRED = 'not_expired';

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
