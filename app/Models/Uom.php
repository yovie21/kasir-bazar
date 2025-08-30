<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    protected $table = 'uoms';
    protected $primaryKey = 'uomId';   // ✅ primary key bukan "id"
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'uomKode',
        'uomName',
        'konvPcs',
    ];
}
