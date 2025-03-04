<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    private $category;

    public function __construct(Category $category) {
        $this->category = $category;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'      => 'Category1',
                'created_at'=> NOW(),
                'updated_at'=> NOW()
            ],
            [
                'name'      => 'Category2',
                'created_at'=> NOW(),
                'updated_at'=> NOW()
            ],
            [
                'name'      => 'Category3',
                'created_at'=> NOW(),
                'updated_at'=> NOW()
            ],
            [
                'name'      => 'Category4',
                'created_at'=> NOW(),
                'updated_at'=> NOW()
            ],
            [
                'name'      => 'Category5',
                'created_at'=> NOW(),
                'updated_at'=> NOW()
            ],
        ];

        $this->category->insert($categories);
    }
}
