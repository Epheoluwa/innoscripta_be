<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    use HasFactory;
    protected $table = 'user_data';
    protected $fillable = [
        'user_id',
        'sources',
        'category',
        'authors',
      
    ];

    protected $casts = [
        'sources' => 'array',
        'category' => 'array',
        'authors' => 'array'
     ];


     public function user()
     {
         return $this->belongsTo(User::class, 'user_id','id');
     }
}
