<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Formula
 *
 * @property int $id
 * @property string $name
 * @property string $file
 * @method static \Illuminate\Database\Eloquent\Builder|Formula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Formula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Formula query()
 * @method static \Illuminate\Database\Eloquent\Builder|Formula whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formula whereName($value)
 * @mixin \Eloquent
 */
class Formula extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];
    public static array $uploadFileTypes = ['jpg', 'jpeg', 'png', 'doc', 'docx', 'csv', 'txt', 'xlx', 'xls', 'xlsx', 'pdf'];
    public const MAX_FILE_SIZE = '10000'; //kb
    public const UPLOAD_FILE_PATH = 'formulas';
}
