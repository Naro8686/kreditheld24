<?php

namespace App\View\Components\Proposal;

use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
use Illuminate\View\Component;

class Edit extends Component
{
    public $proposal;
    /**
     * @var string
     */
    public $formData;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
        $this->formData = $formData ?? ProposalResource::make($proposal)->toJson();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.proposal.edit');
    }
}
