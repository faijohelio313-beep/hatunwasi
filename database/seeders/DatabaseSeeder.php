<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Client;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crea 10 usuarios aleatorios usando el Factory de User
        User::factory(10)->create();

        // Crea un usuario específico con datos manuales
        User::factory()->create([
            'name' => 'helio paul faijo calisaya',
            'email' => 'faijohelio313@gmail.com',
            'password' => bcrypt("helio12325"),
        ]);

        // Crea 3000 registros de productos usando el Factory que configuramos antes
        $products = Product::factory(3000)->create();

        // Sembrar combos y productos del catálogo real
        $this->call(AmbientesSeeder::class);

        // Volver a obtener productos (incluyendo los de los combos) para relacionarlos con clientes
        $allProducts = Product::all();

        // Crea 50 clientes y les asocia entre 1 y 5 productos aleatorios
        $clients = Client::factory(50)->create();
        $clients->each(function ($client) use ($allProducts) {
            $client->products()->attach(
                $allProducts->random(rand(1, 5))->pluck('id')->toArray()
            );
        });
    }
}

