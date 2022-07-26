<?php

namespace App\Http\Resources;

use App\Constants\Status;
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
            'auth_id' => auth()->user()->id ?? null,
            'number' => $this->number,
            'status' => $this->status ?? Status::PENDING,
            'notice' => $this->notice,
            'notices' => $this->notices,
            'commission' => $this->commission,
            'bonus' => $this->bonus,
            'creditType' => $this->credit_type ?? '',
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
            'communalAmount' => $this->communalAmount ?? '',
            'communalExpenses' => $this->communalExpenses ?? '',
            'familyStatus' => $this->familyStatus ?? '',
            'childrenCount' => $this->childrenCount ?? 0,
            'oldAddress' => $this->oldAddress ?? [
                    'street' => '',
                    'house' => '',
                    'postcode' => '',
                    'city' => '',
                ],
            'hasObjectData' => $this->hasObjectData(),
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
            'myProposal' => (!is_null($this->user_id) && $this->user_id === optional($request->user())->id) || is_null($this->id),
            'isPending' => $this->isPending(),
            'isApproved' => $this->isApproved(),
            'draft' => $this->trashed(),
            'revision_at' => optional($this->revision_at)->format('d.m.Y'),
            'approved_at' => optional($this->approved_at)->format('d.m.Y'),
            'pending_at' => optional($this->pending_at)->format('d.m.Y'),
            'denied_at' => optional($this->denied_at)->format('d.m.Y'),
        ];
    }
}
