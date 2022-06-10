<?php

namespace App\Casts;

use App\Models\Proposal;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Support\Collection;

class AsCustomCollection implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        if (is_null($value)) {
            return null;
        }
        $data = isset($attributes[$key]) ? new Collection(json_decode($attributes[$key], true)) : null;
        if (!is_null($data)) switch ($key) {
            case 'objectData':
                if (isset($data['buildPrice']) && !empty($data['buildPrice'])) {
                    $data['buildPrice'] = Proposal::moneyFormat($data['buildPrice']);
                }
                if (isset($data['accumulation']) && !empty($data['accumulation'])) {
                    $data['accumulation'] = Proposal::moneyFormat($data['accumulation']);
                }
                break;
            case 'otherCredit':
                $data = $data->map(function ($item) {
                    if (isset($item['creditBalance']) && !empty($item['creditBalance'])) {
                        $item['creditBalance'] = Proposal::moneyFormat($item['creditBalance']);
                    }
                    if (isset($item['monthlyPayment']) && !empty($item['monthlyPayment'])) {
                        $item['monthlyPayment'] = Proposal::moneyFormat($item['monthlyPayment']);
                    }
                    return $item;
                });
                break;
        }
        return $data;
    }

    public function set($model, $key, $value, $attributes)
    {
        if (is_null($value)) {
            return null;
        }
        return [$key => json_encode($value)];
    }
}
