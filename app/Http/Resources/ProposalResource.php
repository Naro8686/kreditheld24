<?php

namespace App\Http\Resources;

use App\Models\Proposal;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Proposal $this */
        return [
            'id' => $this->id,
            'number' => $this->number,
            'status' => $this->status,
            'notice' => $this->notice,
            'commission' => $this->commission,
            'bonus' => $this->bonus,
            'creditType' => $this->creditType ?? '',
            'creditComment' => $this->creditComment ?? '',
            'deadline' => $this->deadline ?? '',
            'monthlyPayment' => $this->monthlyPayment ?? '',
            'creditAmount' => $this->creditAmount ?? '',
            'firstName' => $this->firstName ?? '',
            'lastName' => $this->lastName ?? '',
            'birthday' => optional($this->birthday)->format('Y-m-d') ?? '',
            'phoneNumber' => $this->phoneNumber ?? '',
            'email' => $this->email ?? '',
            'birthplace' => $this->birthplace ?? '',
            'street' => $this->street ?? '',
            'house' => $this->house ?? '',
            'postcode' => $this->postcode ?? '',
            'city' => $this->city ?? '',
            'residenceDate' => optional($this->residenceDate)->format('Y-m-d') ?? '',
            'residenceType' => $this->residenceType ?? '',
            'familyStatus' => $this->familyStatus ?? '',
            'oldAddress' => $this->oldAddress ?? [
                    'street' => '',
                    'house' => '',
                    'postcode' => '',
                    'city' => '',
                ],
            'spouse' => $this->spouse ?? [
                    'firstName' => '',
                    'lastName' => '',
                    'birthday' => '',
                    'birthplace' => '',
                ],
            'insurance' => $this->insurance ?? [
                    'unemployment' => false,
                    'disease' => false,
                    'death' => false
                ],
            'otherCredit' => $this->otherCredit ?? [],
            'uploads' => $this->files ?? [],
        ];
    }
}
