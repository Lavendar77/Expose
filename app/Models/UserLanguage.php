<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Models\UUID;

class UserLanguage extends Model
{
    use UUID;

    protected $fillable = [
    	'name'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
