<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\SubCategoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = Categoria::query()->get();

        $categorias->each(fn($categoria) =>  Categoria::factory(3)->create(['pai_id' => $categoria->id]));
        

    }
}
