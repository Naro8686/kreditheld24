<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProposalNotice
 *
 * @property int $id
 * @property string $message
 * @property string $status
 * @property int $proposal_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProposalNotice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProposalNotice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProposalNotice query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProposalNotice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProposalNotice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProposalNotice whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProposalNotice whereProposalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProposalNotice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProposalNotice whereUpdatedAt($value)
 * @property int|null $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|ProposalNotice whereUserId($value)
 * @mixin \Eloquent
 */
class ProposalNotice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function proposal()
    {
        $this->belongsTo(Proposal::class);
    }

    public function getCreatedAtAttribute($created_at)
    {
        return \Carbon\Carbon::createFromInterface(date_create($created_at))->format('d.m.Y');
    }
}
