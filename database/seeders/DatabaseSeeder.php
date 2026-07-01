<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;

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

        // DESACTIVADO: generaba 3000 productos de prueba (Faker) con nombres sin sentido
        // ("a atque", "a ea"...) que inundaban el selector de productos del panel admin.
        // Los productos reales se crean en AmbientesSeeder a partir del catálogo JSON.
        // $products = Product::factory(3000)->create();

        // Sembrar combos y productos del catálogo real
        $this->call(AmbientesSeeder::class);


    }
}

