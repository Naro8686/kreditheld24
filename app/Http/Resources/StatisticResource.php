<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticResource extends JsonResource
{

    private $time_format = "Y-m-d\TH:i:s";

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $createdAt = $this->unit
            ? $this->date_format($this->unit, $request->get('unit', 'hour'))
            : $this->created_at;
        return [
            "amount" => number_format($this->sum ?? $this->amount, 2, ".", ""),
            "timestamp" => $createdAt->getTimestamp(),
            "created_at" => $createdAt->format($this->getTimeFormat())
        ];
    }

    /**
     * @param $date
     * @param $unit
     * @return Carbon|false
     */
    private function date_format($date, $unit)
    {
        $timezone = "Europe/Moscow";
        switch ($unit) {
            case 'week':
                [$year, $week] = explode('-', $date);
                $result = (new Carbon())
                    ->timezone($timezone)
                    ->setISODate($year, (int)$week)
                    ->endOfWeek();
                break;
            case 'day':
                [$year, $month, $day] = explode('-', $date);
                $result = Carbon::create($year, $month, $day, 0, 0, 0, $timezone);
                break;
            case 'month':
                [$year, $month] = explode('-', $date);
                $result = Carbon::create($year, $month, 1, 0, 0, 0, $timezone);
                break;
            case 'year':
                [$year] = explode('-', $date);
                $result = Carbon::create($year, 1, 1, 0, 0, 0, $timezone);
                break;
            case 'hour':
            default:
                $result = Carbon::createFromFormat("Y-m-d H", $date, $timezone);
                break;
        }
        return $result;
    }

    /**
     * @param string $time_format
     * @return StatisticResource
     */
    public function setTimeFormat(string $time_format): StatisticResource
    {
        $this->time_format = $time_format;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeFormat(): string
    {
        return $this->time_format;
    }

}
