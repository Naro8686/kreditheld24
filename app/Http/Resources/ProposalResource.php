<?php

namespace App\Http\Resources;

use App\Models\Category;
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
        $parent_categories = Category::whereNull('parent_id')->get();
        $categories = Category::whereIn('parent_id', $parent_categories->pluck('id')->toArray())
            ->get()->groupBy('parent_id');
        return [
            'id' => $this->id,
            'number' => $this->number,
            'status' => $this->status,
            'notice' => $this->notice,
            'commission' => $this->commission,
            'bonus' => $this->bonus,
            'creditType' => $this->creditType ?? '',
            'category_id' => $this->category_id ?? '',
            'parent_category_id' => $this->category->parent->id ?? '',
            'parent_categories' => $parent_categories,
            'categories' => $categories,
            'creditComment' => $this->creditComment ?? '',
            'deadline' => $this->deadline ?? '',
            'monthlyPayment' => $this->monthlyPayment ?? '',
            'creditAmount' => $this->creditAmount ?? '',
            'gender' => $this->gender ?? 'male',
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
            'applicantType' => $this->applicantType ?? '',
            'rentAmount' => $this->rentAmount ?? '',
            'familyStatus' => $this->familyStatus ?? '',
            'childrenCount' => $this->childrenCount ?? 0,
            'oldAddress' => $this->oldAddress ?? [
                    'street' => '',
                    'house' => '',
                    'postcode' => '',
                    'city' => '',
                ],
            'objectData' => $this->objectData ?? [
                    'street' => '',
                    'house' => '',
                    'postcode' => '',
                    'city' => '',
                    'objectType' => '',
                    'yearConstruction' => '',
                    'yearRepair' => '',
                    'plotSize' => '',
                    'livingSpace' => '',
                    'buildPrice' => '',
                    'accumulation' => '',
                    'brokerageFees' => '',
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
