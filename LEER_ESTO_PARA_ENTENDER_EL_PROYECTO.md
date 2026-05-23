# 🎓 LEER ESTO PARA COMPRENDER EL PROYECTO (MÓDULO COMBOS)

Esta guía explica detalladamente la arquitectura, tecnologías y el flujo de datos del proyecto para que todos los miembros del grupo entiendan el código y puedan defender el proyecto ante cualquier pregunta del docente.

---

## 💻 1. Stack Tecnológico (¿Qué tecnologías usamos y por qué?)

Cuando el docente pregunte qué herramientas usamos y por qué las elegimos:

*   **Laravel 13:** El framework de backend en PHP. Elegido por su robustez, sistema de base de datos Eloquent (ORM) y seguridad integrada.
*   **Livewire 4:** Un framework full-stack para Laravel que permite crear interfaces dinámicas y reactivas (como el buscador o el carrito de compras) escribiendo código PHP, sin necesidad de escribir APIs en Laravel ni JavaScript complejo en el cliente.
*   **Tailwind CSS:** El framework de diseño basado en clases de utilidad que nos permite dar estilos rápidos, modernos y responsivos tipo Promart.
*   **Livewire Flux:** Una librería premium de componentes listos para usar en Livewire (botones, sidebars, tablas), que le da un acabado profesional e interactivo al sistema.
*   **SQLite:** El motor de base de datos. Como es un proyecto local de desarrollo, SQLite almacena toda la base de datos en un solo archivo físico (`database/database.sqlite`), lo cual facilita que cualquiera de nosotros corra el proyecto sin tener que configurar MySQL o phpMyAdmin.

---

## 📁 2. Estructura de Archivos Clave (¿Dónde está cada cosa?)

Si el docente pide abrir una carpeta específica y explicar qué hace:

### 🗄️ A. Base de Datos y Modelado
*   **`database/migrations/2026_05_13_143227_create_products_table.php`:**
    *   *Qué hace:* Define la estructura de los productos individuales. Añadimos campos reales para el catálogo del PDF: `codigo`, `marca`, `formato`, `color`, `tipo_producto` (si es cerámico, inodoro, etc.) y `categoria` (baño o cocina).
*   **`database/migrations/2026_05_23_000001_create_combos_table.php`:**
    *   *Qué hace:* Define la tabla de combinaciones. Guarda el nombre del combo, precio original (lista), precio con descuento (oferta), porcentaje de descuento y categoría.
*   **`database/migrations/2026_05_23_000002_create_combo_product_table.php`:**
    *   *Qué hace:* La tabla pivote (relación muchos a muchos). Conecta la tabla `combos` con `products`. Tiene un campo extra fundamental: `tipo_uso` (para saber si el producto en esa combinación actúa como pared, piso, sanitario o detalle decorativo).
*   **`app/Models/Combo.php` y `app/Models/Product.php`:**
    *   *Qué hacen:* Definen las relaciones Eloquent. En `Combo.php` verán la relación `belongsToMany(Product::class)->withPivot('tipo_uso')` que permite extraer los productos que componen cada combo y su rol específico.

### ⚙️ B. Importación y Datos (Seeder)
*   **`database/data/ambientes.json`:**
    *   *Qué hace:* Contiene estructurados en formato JSON los datos de los 29 ambientes (combos) extraídos del PDF original.
*   **`database/seeders/AmbientesSeeder.php`:**
    *   *Qué hace:* Lee el archivo JSON. Crea los productos individualmente calculando precios lógicos de mercado (evitando duplicar códigos de porcelanato que se repiten en varios combos), calcula el precio total del combo sumando los componentes, aplica un descuento aleatorio (ej. 20%) y asocia los productos al combo en la tabla pivote indicando su `tipo_uso`.
*   **`database/seeders/DatabaseSeeder.php`:**
    *   *Qué hace:* El sembrador principal de Laravel que ejecuta nuestro `AmbientesSeeder` y crea usuarios y clientes de prueba.

### 🌐 C. Rutas del Sistema
*   **`routes/web.php`:**
    *   *Qué hace:* Enruta las URLs.
        *   `/tienda` apunta al componente reactivo `StoreMain` (la tienda).
        *   `/admin/combos` apunta al componente reactivo `ComboMain` (el CRUD).
        *   La raíz `/` redirige automáticamente al usuario a `/tienda`.

---

## 🏪 3. La Tienda Pública (Módulo Cliente)

### 🖥️ Backend: `app/Livewire/StoreMain.php`
Este controlador gestiona el estado de la tienda en el servidor de forma reactiva:
*   **Búsqueda interactiva (`$search`):** Filtra en tiempo real los combos por nombre, descripción o buscando si algún producto interno del combo coincide con el texto o código buscado.
*   **Filtro de Categorías (`$selectedCategory`):** Filtra los combos en base a la categoría seleccionada (Baños o Cocinas).
*   **Carrito de Compras (`$cart`):** Guarda un array en la sesión del usuario (`session()->get('cart')`) con el formato `[id_combo => cantidad]`. Tiene los métodos `addToCart()`, `removeFromCart()` y `updateQuantity()` para manejar el carrito dinámicamente y recalcular totales sin recargar la página.

### 🎨 Frontend: `resources/views/livewire/store-main.blade.php`
La maquetación visual estilo Promart:
*   **Sidebar de Categorías:** Secciones colapsables de filtros.
*   **Iconos superiores:** Slider de pestañas visuales con emojis para filtrar categorías rápidamente.
*   **Combo Cards:** Tarjetas que muestran las imágenes mockeadas con degradados, badges de descuento, precios destacados y el botón de "Agregar al Carrito".
*   **Modal de Detalle:** Al hacer clic en un combo, se abre un modal que consulta a la base de datos la lista exacta de materiales del PDF con sus respectivos formatos, marcas y códigos.
*   **Cart Drawer:** Barra lateral derecha que se despliega automáticamente al añadir un combo para gestionar cantidades y simular la compra.

---

## ⚙️ 4. El Panel de Control (Módulo Administración)

### 🖥️ Backend: `app/Livewire/ComboMain.php`
Controla las acciones administrativas:
*   Paginación y búsquedas de combos creados en la base de datos.
*   **Cálculo automático del descuento:** Tiene un método `updatedPrecioLista()` y `updatedPrecioOferta()` que calcula de forma automática y reactiva el porcentaje de descuento en tiempo real mientras el administrador escribe los precios en el formulario.
*   **Sincronización:** Guarda el combo y sincroniza la tabla pivote `combo_product` inyectando el tipo de uso de cada producto seleccionado.

### 🎨 Frontend: `resources/views/livewire/combo-main.blade.php`
La interfaz de administración:
*   Muestra una tabla con fotos representativas, nombres, categorías, precios y recuentos de ítems asociados.
*   Formulario modal emergente para Crear/Editar combos.
*   **Selector de Productos Avanzado:** Un listado con scroll de todos los productos del catálogo. Al marcar la casilla de un producto, aparece dinámicamente un menú desplegable (`select`) para asignarle su rol específico (Pared, Piso, Sanitario, Lápiz) en esa combinación.

---

## 💡 Preguntas Típicas del Docente y Cómo Responderlas

1.  **P: ¿Dónde se almacena la información de qué productos tiene cada combo?**
    *   *R:* Se almacena en la tabla intermedia `combo_product`. Es una relación muchos a muchos, lo que significa que un combo puede tener muchos productos, y un producto (como un porcelanato estándar) puede repetirse en muchos combos.
2.  **P: ¿Cómo se comunican el frontend y el backend para actualizar el carrito sin recargar la página?**
    *   *R:* Usamos Livewire. Cuando el usuario hace clic en "Agregar", Livewire envía una solicitud AJAX asíncrona al servidor ejecutando el método `addToCart()`. El servidor procesa el cambio en la sesión y re-renderiza únicamente la sección HTML del carrito, enviando el nuevo trozo de HTML al navegador. El usuario percibe una reactividad inmediata sin pestañeos.
3.  **P: ¿De dónde salieron los 29 combos iniciales?**
    *   *R:* Los extrajimos de forma estructurada desde el PDF de catálogo `combos_LP1-1.pdf` a un archivo temporal JSON. Luego, mediante el sembrador de Laravel (`AmbientesSeeder.php`), automatizamos la creación de todos los registros en la base de datos SQLite con precios lógicos.
