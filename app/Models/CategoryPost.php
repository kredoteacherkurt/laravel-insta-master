<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    use HasFactory;
    
    protected $fillable = ['category_id', 'post_id'];
    protected $table = 'category_post';
    public $timestamps = false;

    # To get the name of the category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
