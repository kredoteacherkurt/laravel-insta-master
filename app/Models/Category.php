<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    # To count how many posts does the category have
    # See admin/categories/index table.
    public function categoryPost()
    {
        return $this->hasMany(CategoryPost::class);
    }
}
