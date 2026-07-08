<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * Carga el catálogo completo de la empresa Hatun Wasi a partir de los
 * documentos oficiales de la carpeta HatunWasi/ (catálogos de los demás
 * equipos del curso): Revestimientos, Accesorios, Sanitarios y
 * Cerámicos y Componentes (tablones, pegamentos y porcelanatos).
 */
class CatalogoEmpresaSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================================
        // REVESTIMIENTOS — del catálogo "hatunhasi_final" (PPTX)
        // =====================================================================
        $revestimientos = [
            ['Cerámica Nacional — Modelos en Piedra', 'IMP-PT-001', 'Porcelatino', '27 x 45 cm', 'Gris / Beige / Arena / Rústico', 'Cerámica', 'Uso: Piso y Pared · Tránsito: Alto'],
            ['Cerámica Nacional — Color Entero', 'IMP-CE-002', 'Porcelatino', '46 x 46 cm', 'Cemento Marfil / Beige / Blanco', 'Cerámica', 'Uso: Piso y Pared · Tránsito: Alto'],
            ['Cerámica Nacional — Marmolizado', 'IMP-MZ-003', 'Porcelatino', '45 x 45 cm', 'Blanco/Gris con venas · Luce Blanco', 'Cerámica', 'Uso: Piso y Pared Interior · Tránsito: Alto'],
            ['Cerámica Nacional — Amaderados', 'NAC-AM-001', 'San Lorenzo', '30 x 60 cm', 'Aspen Miel · Ebony Caramelo', 'Cerámica', 'Uso: Piso y Pared · Tránsito: Alto'],
            ['Cerámica Importada — Alto Relieve', 'NAC-AR-001', 'Importado', '30 x 60 cm', 'Blanco / Crema / Marfil', 'Cerámica', 'Uso: Pared interior · Tránsito: Medio-Alto'],
            ['Cerámica Importada — Marmolizado', 'NAC-MZ-002', 'Importado', '30 x 60 cm', 'Blanco / Crema / Marfil', 'Cerámica', 'Uso: Pared interior · Tránsito: Medio-Alto'],
            ['Cerámica Decorativa — Cocina', 'DEC-BA-005', 'Hatun Wasi', '27 x 45 / 30 x 60 cm', 'Azul / Blanco / Gris / Multicolor', 'Cerámica Decorativa', 'Uso: Pared cocina · Solo pared'],
            ['Cerámica Decorativa — Baño', 'DEC-BA-006', 'Importado', '30 x 60 cm', 'Blanco Mármol con venas doradas', 'Cerámica Decorativa', 'Uso: Pared baño · Ambiente completo'],
            ['Lápiz y Cenefas Decorativas', 'LAP-CE-007', 'Hatun Wasi', 'Varias medidas', 'Dorado / Plateado / Tonos neutros', 'Cenefa', 'Uso: Cenefa, zócalo y separador decorativo'],
            ['Zócalo Decorativo Premium', 'LAP-CE-008', 'Hatun Wasi', 'Varias medidas', 'Dorado / Blanco / Negro / Plateado', 'Cenefa', 'Uso: Zócalo decorativo y separador'],
            ['Porcelanato Color Entero', 'POR-CE-002', 'Porcelatino', '60 x 60 cm', 'Blanco Ártico · Gris · Beige · Negro Carbón', 'Porcelanato', 'Uso: Piso y Pared, Interior y Exterior · Tránsito: Alto'],
            ['Porcelanato Marmolizado', 'POR-MZ-001', 'Porcelatino', '60 x 60 cm', 'Blanco/Gris con venas · Dorado', 'Porcelanato', 'Uso: Piso y Pared, Interior y Exterior · Tránsito: Alto'],
            ['Porcelanato Gran Formato Texturizado', 'POR-GF-005', 'Porcelatino', '120 x 60 cm', 'Negro · Mármol · Amaderado', 'Porcelanato', 'Uso: Piso Interior, Exterior y Comercial · Tránsito: Muy Alto'],
            ['Porcelanato Importado Especial', 'IMP-SP-006', 'Importado Premium', '120 x 60 cm', 'Decorativos / Flores / Texturas', 'Porcelanato', 'Uso: Pared decorativa · Ambiente'],
            ['Porcelanato Mármol Premium', 'POR-MP-003', 'Porcelatino', '120 x 60 cm', 'Verde · Gris · Beige · Onyx', 'Porcelanato', 'Uso: Piso y Pared Interior · Tránsito: Alto'],
            ['Porcelanato Texturizado Satinado', 'POR-CE-004', 'Porcelatino / Importado', '120 x 60 cm', 'Gris satinado · Texturas decorativas', 'Porcelanato', 'Uso: Piso y Pared · Tránsito: Alto'],
        ];

        foreach ($revestimientos as [$nombre, $codigo, $marca, $formato, $color, $tipo, $desc]) {
            Product::firstOrCreate(
                ['codigo' => $codigo, 'categoria' => 'revestimientos'],
                ['nombre' => $nombre, 'marca' => $marca, 'formato' => $formato, 'color' => $color,
                 'tipo_producto' => $tipo, 'descripcion' => $desc, 'precio' => $this->precioReferencial($tipo), 'cantidad' => 100, 'disponible' => true]
            );
        }

        // =====================================================================
        // ACCESORIOS — del "catálogo de accesorios" (DOCX)
        // =====================================================================
        $accesorios = [
            ['Porta Jabón', 'Cerámica', 'Blanco', null, 'Para jabón de mano o tocador; evita el contacto del jabón con superficies húmedas.', 15.00],
            ['Toallero', 'Acero inoxidable', null, 'Mediano', 'Para colgar toallas de baño o de mano; resistente a la humedad y fácil de instalar.', 35.00],
            ['Dispensador de Jabón Líquido', 'Cerámica y plástico', null, '250 ml', 'Almacena y distribuye jabón líquido en baños o cocinas.', 25.00],
            ['Porta Cepillos', 'Cerámica', null, 'Diseño moderno', 'Organizador para cepillos dentales y pasta dental.', 18.00],
            ['Esquinero para Baño', 'Vidrio templado y aluminio', null, null, 'Repisa de esquina para shampoo, jabón y cremas.', 45.00],
            ['Ganchos Adhesivos (x4)', 'Plástico resistente', null, 'Paquete de 4', 'Para colgar objetos ligeros sin perforar paredes.', 10.00],
            ['Marco para Espejo', 'PVC', 'Negro y plateado', null, 'Complemento decorativo alrededor de espejos.', 50.00],
            ['Rejilla de Desagüe', 'Acero inoxidable', null, '10 x 10 cm', 'Drenaje seguro para pisos de baños y lavanderías.', 20.00],
            ['Separadores para Cerámica (x100)', 'Plástico', null, 'Bolsa de 100', 'Mantienen espacios uniformes entre piezas al instalar.', 8.00],
            ['Pegamento para Cerámica', 'Cemento especial', null, 'Bolsa de 25 kg', 'Adhesivo para fijar cerámicos y porcelanatos.', 30.00],
        ];

        foreach ($accesorios as [$nombre, $material, $color, $formato, $desc, $precio]) {
            Product::firstOrCreate(
                ['nombre' => $nombre, 'categoria' => 'accesorios'],
                ['marca' => 'Hatun Wasi', 'formato' => $formato, 'color' => $color,
                 'tipo_producto' => 'Accesorio · ' . $material, 'descripcion' => $desc,
                 'precio' => $precio, 'cantidad' => 100, 'disponible' => true]
            );
        }

        // =====================================================================
        // SANITARIOS — del "catálogo de revestimientos y sanitarios" (DOCX)
        // =====================================================================
        $sanitarios = [
            ['Inodoro Negro Mate y Cerámico Floral Premium', 'Inodoro premium one-piece en negro mate satinado, con pared de acento floral calada.', 'Moderno / Glam / Monocromático'],
            ['Panel de Grifería de Alta Ingeniería', 'Mezcladoras empotrables, duchas tipo lluvia y grifos monocomando con acabado cromado espejo.', 'Funcional / Comercial'],
            ['Ambiente Rústico Ladrillo y Madera', 'Cerámico tipo ladrillo con textura madera y cenefa zen; incluye sanitario clásico marfil.', 'Rústico Moderno / Spa'],
            ['Inodoro Compacto Blanco con Gris Texturado', 'Inodoro one-piece blanco compacto con pared de piedra natural mate y mosaico estriado.', 'Urbano / Industrial Elegante'],
            ['Pared de Ondas 3D Negro y Plata', 'Cerámico negro con relieve ondulado tridimensional y líneas plateadas; incluye inodoro moderno.', 'Vanguardista / Lujo Moderno'],
            ['Lavatorio de Pedestal y Mosaico Estriado', 'Lavatorio de pedestal en loza vitrificada blanca con grifería monocomando cromada.', 'Minimalista / Contemporáneo'],
            ['Cerámico Cocina Gourmet Relieve Diamante', 'Mármol negro con vetas doradas y relieve geométrico diamante; listelo con motivos culinarios.', 'Cocina Gourmet / Moderna'],
            ['Baño Clásico Gris con Cenefa Árabe', 'Cerámico gris claro tipo piedra caliza con listelos arabescos; inodoro y lavatorio de pedestal.', 'Clásico / Tradicional'],
            ['Combo Sanitario Mármol Calacatta y Gris Oxford', 'Inodoro one-piece gris cemento satinado con urinario suspendido blanco.', 'Neoclásico / Lujo Atemporal'],
            ['Baño Comercial con Cenefa Botánica', 'Base gris claro satinado con cenefa de flores de loto; urinario e inodoro de loza blanca.', 'Nórdico / Spa Funcional'],
            ['Cocina Mosaico Ladrillo Gris y Cenefa de Frutas', 'Mosaico de ladrillos rústicos con cenefa gastronómica; incluye repostero de melamina.', 'Rústico Hogareño'],
            ['Baño Mármol Líquido y Madera', 'Cerámico gran formato tipo mármol líquido con vetas doradas; lavatorio y sanitario marfil.', 'Art Déco Moderno'],
            ['Mosaico Lineal y Textura Piedra Gris', 'Detalle de revestimientos texturizados con listelo decorativo de alta definición.', 'Muestrario de Texturas'],
            ['Esquina Mármol Negro Biselado Alto Brillo', 'Listones biselados negros con vetas blancas y doradas; incluye inodoro de loza blanca.', 'Lujo Tridimensional'],
        ];

        foreach ($sanitarios as [$nombre, $desc, $estilo]) {
            Product::firstOrCreate(
                ['nombre' => $nombre, 'categoria' => 'sanitarios'],
                ['marca' => 'Hatun Wasi', 'tipo_producto' => 'Ambiente Sanitario',
                 'descripcion' => $desc . ' Estilo: ' . $estilo . '.',
                 'precio' => rand(950, 2800) + 0.90, 'cantidad' => 10, 'disponible' => true]
            );
        }

        // =====================================================================
        // CERÁMICOS Y COMPONENTES — tablones + pegamentos (XLSX) + porcelanatos (DOCX)
        // =====================================================================
        $tablones = [
            ['HW-TAB-001', 'Tablón Madera Ebony Gris', 'Porcelanato', 'Listón (20x61 cm)', 'Gris', 'Maderas Frías · Mate · Uso: Piso/Pared', 34.90],
            ['HW-TAB-002', 'Cerámico Madera Lara Gris', 'Cerámica', 'Estructurado (45x45 cm)', 'Gris', 'Maderas Frías · Mate · Uso: Piso Interior', 25.50],
            ['HW-TAB-003', 'Tablón Madera Amaru Mix', 'Cerámica', 'Estructurado (45x45 cm)', 'Mix Multicolor', 'Maderas Mix · Satinado · Uso: Piso', 31.00],
            ['HW-TAB-004', 'Tablón Canela Natural', 'Cerámica', 'Listón (20x61 cm)', 'Marrón Claro', 'Maderas Cálidas · Mate · Uso: Piso/Pared', 37.90],
            ['HW-TAB-005', 'Tablón Paralelo Castaño', 'Cerámica', 'Listón (20x61 cm)', 'Castaño', 'Maderas Cálidas · Con Relieve · Uso: Piso/Pared', 37.90],
            ['HW-TAB-006', 'Porcelanato Listón Anden Mix', 'Porcelanato', 'Listón (34x60 cm)', 'Mix Oscuro', 'Maderas Mix · Con Relieve · Uso: Piso Alto Tránsito', 39.90],
            ['HW-TAB-007', 'Tablón Canela Gris', 'Cerámica', 'Listón (20x61 cm)', 'Gris Ceniza', 'Maderas Frías · Mate · Uso: Piso/Pared', 37.90],
            ['HW-TAB-008', 'Tablón Cedro Real Premium', 'Porcelanato', 'Listón Grande (20x120 cm)', 'Marrón Oscuro', 'Maderas Cálidas · Con Relieve · Uso: Piso Exterior/Terraza', 48.50],
            ['HW-TAB-009', 'Tablón Haya Nórdica', 'Cerámica', 'Listón (20x61 cm)', 'Blanco Desgastado', 'Maderas Frías · Mate · Uso: Pared Interior', 29.90],
        ];

        foreach ($tablones as [$codigo, $nombre, $tipo, $formato, $color, $desc, $precio]) {
            Product::firstOrCreate(
                ['codigo' => $codigo, 'categoria' => 'ceramicos-componentes'],
                ['nombre' => $nombre, 'marca' => 'Hatun Wasi', 'formato' => $formato, 'color' => $color,
                 'tipo_producto' => $tipo . ' Madera', 'descripcion' => $desc,
                 'precio' => $precio, 'cantidad' => 100, 'disponible' => true]
            );
        }

        $pegamentos = [
            ['HW-PEG-001', 'Pegamento Interiores Gris', 'Gris', 'Cerámicos e instalaciones básicas en interiores.', 18.50],
            ['HW-PEG-002', 'Pegamento Extra Fuerte Gris', 'Gris', 'Cerámico sobre cerámico y pisos de tránsito medio.', 36.90],
            ['HW-PEG-003', 'Pegamento Blanco Flexible', 'Blanco', 'Porcelanatos, piedras naturales y alto tránsito.', 45.90],
            ['HW-PEG-004', 'Pegamento Impermeable Extrafuerte', 'Blanco', 'Zonas húmedas, piscinas y fachadas exteriores.', 41.90],
            ['HW-PEG-005', 'Pegamento Porcelanato Grandes Formatos', 'Blanco', 'Porcelanatos de formato pesado (ej. 60x120 cm).', 49.90],
        ];

        foreach ($pegamentos as [$codigo, $nombre, $color, $desc, $precio]) {
            Product::firstOrCreate(
                ['codigo' => $codigo, 'categoria' => 'ceramicos-componentes'],
                ['nombre' => $nombre, 'marca' => 'Hatun Wasi', 'formato' => 'Bolsa 25 kg', 'color' => $color,
                 'tipo_producto' => 'Pegamento', 'descripcion' => 'Uso: ' . $desc,
                 'precio' => $precio, 'cantidad' => 200, 'disponible' => true]
            );
        }

        // Porcelanatos 60x60 — del "Catálogo de Porcelanatos" (DOCX)
        $porcelanatosMate = [
            ['Porcelanato Jersey', 'Beige'], ['Porcelanato Rust', 'Swing Plata'], ['Porcelanato Niebla', 'Gris'],
            ['Porcelanato Texturado', 'Negro'], ['Porcelanato Mármol', 'Plata'], ['Porcelanato Fancy', 'Crema'],
            ['Porcelanato Mate', 'Gris'], ['Porcelanato Madera', 'Café'], ['Porcelanato Madera', 'Parota'],
            ['Porcelanato Cantera', 'Gris'], ['Porcelanato Legño', 'Caramelo'], ['Porcelanato Madera', 'Nevada'],
            ['Porcelanato Mate', 'Blanca'], ['Porcelanato', 'Gris Pleno'], ['Porcelanato Caving Nathan', 'Terracota'],
            ['Porcelanato Liso', 'Gris Satin'], ['Porcelanato Texturizado', 'Negro'], ['Porcelanato Atticus', 'Grey'],
            ['Porcelanato Mármol Torino', 'Hueso'], ['Porcelanato', 'Negro Mate'], ['Porcelanato Lineal', 'Beige Mate'],
            ['Porcelanato Grafito Mate WFG66003', 'Grafito Mate'], ['Porcelanato Lineal Gray', 'Gris Mate'],
            ['Porcelanato Madera Pino Honey', 'Madera'], ['Porcelanato Cuarzo', 'Gris'],
        ];

        $porcelanatosBrillo = [
            ['Porcelanato Panal Geométrico', 'Gris'], ['Porcelanato Corriente Dorada', 'Dorado'],
            ['Porcelanato Murette Aston', 'Gris Claro'], ['Porcelanato Mármol Grafito', 'Grafito'],
            ['Porcelanato Nube Plata', 'Plata'], ['Porcelanato Escarchado', 'Gris'],
            ['Porcelanato Vitri Carving', 'Gold'], ['Porcelanato Vitri Chispeado', 'Negro'],
            ['Porcelanato Bola Navidad', 'Blanco'], ['Porcelanato Flor', 'Azul'],
            ['Porcelanato Lyon', 'Blanco'], ['Porcelanato Vitri Galaxia', 'Dorado'],
            ['Porcelanato Nano', 'Blanco'], ['Porcelanato Caramel Stone', 'Blanco Perlado'],
            ['Porcelanato Vitri Urbano', 'Gris'], ['Porcelanato Murette Aston', 'Perla'],
            ['Porcelanato Nube', 'Plata'], ['Porcelanato Vitri Chispeado', 'Blanco'],
            ['Porcelanato Vitri Mármol', 'Concreto'], ['Porcelanato Tigrillo', 'Marrón'],
            ['Porcelanato Vitri Flor', 'Dorado'], ['Porcelanato Carving', 'Blanco'],
            ['Porcelanato Vitri Carving Mármol', 'Gris'], ['Porcelanato', 'Dorado Esmeralda'],
            ['Porcelanato Liso Doble Carga', 'Gris'],
        ];

        foreach ([['Porcelanato Mate', $porcelanatosMate], ['Porcelanato Brillo', $porcelanatosBrillo]] as [$tipo, $lista]) {
            foreach ($lista as [$nombre, $color]) {
                Product::firstOrCreate(
                    ['nombre' => $nombre, 'color' => $color, 'categoria' => 'ceramicos-componentes'],
                    ['marca' => 'Hatun Wasi', 'formato' => '60 x 60 cm',
                     'tipo_producto' => $tipo, 'descripcion' => $tipo . ' de 60 x 60 cm, acabado ' . strtolower($color) . '.',
                     'precio' => rand(45, 95) + 0.90, 'cantidad' => 100, 'disponible' => true]
                );
            }
        }
    }

    /** Precio referencial según el tipo de revestimiento */
    private function precioReferencial(string $tipo): float
    {
        $t = mb_strtolower($tipo);
        return match (true) {
            str_contains($t, 'cenefa')      => rand(15, 35) + 0.90,
            str_contains($t, 'decorativa')  => rand(45, 80) + 0.90,
            str_contains($t, 'porcelanato') => rand(45, 95) + 0.90,
            default                         => rand(35, 70) + 0.90,
        };
    }
}
