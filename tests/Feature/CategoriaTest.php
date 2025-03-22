<?php

namespace Tests\Feature;

use App\Models\Categoria;
use Database\Factories\CategoriaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_categorias_sao_listadas(): void
    {
        $response = $this->get(route('categoria.list'));

        $response->assertJson(['data' => []]);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_cadastrar_categoria(): void
    {
        $response = $this->post(route('categoria.store'), ['nome' => fake()->name, 'descricao' => fake()->streetName()]);

        
        $response->assertJsonStructure(['id',  'nome', 'descricao', 'created_at', 'updated_at'], (array)$response->json());
        $response->assertStatus(200);
    }

    public function test_atualizar_categoria(): void
    {
        $categoria = Categoria::factory()->create();
        $response = $this->put(route('categoria.update', ['categoria' => $categoria]), ['nome' => fake()->name, 'descricao' => fake()->streetName()]);

        
        $response->assertJsonStructure(['id',  'nome', 'descricao', 'created_at', 'updated_at'], (array)$response->json());
        $response->assertStatus(200);
    }

    public function test_deletar_categoria(): void
    {
        $categoria = Categoria::factory()->create();
        $response = $this->delete(route('categoria.update', ['categoria' => $categoria]));

        $response->assertStatus(200);
    }
}
