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

        // Usuario administrador: las credenciales se leen del .env
        // (ADMIN_NAME, ADMIN_EMAIL, ADMIN_PASSWORD) para no exponerlas
        // en el código fuente ni en el repositorio.
        User::factory()->create([
            'name'     => env('ADMIN_NAME', 'Administrador'),
            'email'    => env('ADMIN_EMAIL', 'admin@hatunwasi.test'),
            'password' => bcrypt(env('ADMIN_PASSWORD', 'cambiar-al-instalar')),
        ]);

        // DESACTIVADO: generaba 3000 productos de prueba (Faker) con nombres sin sentido
        // ("a atque", "a ea"...) que inundaban el selector de productos del panel admin.
        // Los productos reales se crean en AmbientesSeeder a partir del catálogo JSON.
        // $products = Product::factory(3000)->create();

        // Sembrar combos y productos del catálogo real
        $this->call(AmbientesSeeder::class);

        // Catálogo completo de la empresa (revestimientos, accesorios,
        // sanitarios, cerámicos y componentes) — módulos de otros equipos
        $this->call(CatalogoEmpresaSeeder::class);


    }
}

