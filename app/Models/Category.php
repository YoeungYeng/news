<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // table name
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'status',
        
    ];
    
    public function news()
    {
        return $this->hasMany(News::class);
    }
    


}
