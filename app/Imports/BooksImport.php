<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

// ¡Este es el código bueno, el que se parece a tu UsersImport!
class BooksImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. Buscar o crear el Autor
        $author = Author::firstOrCreate(['name' => trim($row['autor'])]);

        // 2. Buscar o crear la Editorial
        $publisher = Publisher::firstOrCreate(['name' => trim($row['editorial'])]);

        // 3. Buscar o crear la Categoría
        $category = Category::firstOrCreate(
            ['name' => trim($row['categoria'])],
            ['color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF))]
        );

        // 👇 3. ¡LA LÓGICA 'A PRUEBA DE BALAS'!
        // Limpiamos los valores primero
        $year = trim($row['ano_publicacion']);
        $pages = trim($row['paginas']);
        $stock = trim($row['stock']);
        $lang = trim($row['idioma']);
        $summary = trim($row['resumen']);

        // 4. Crear el Libro
        return new Book([
            'title'            => trim($row['titulo']),
            'author_id'        => $author->id,
            'publisher_id'     => $publisher->id,
            'category_id'      => $category->id,
            
            // Si $year está vacío ("" o " "), lo guarda como NULL.
            // Si tiene un valor (ej: "1984"), lo guarda como número.
            'publication_year' => empty($year) ? null : (int)$year,
            'language'         => empty($lang) ? null : $lang,
            'pages'            => empty($pages) ? null : (int)$pages,
            'stock'            => empty($stock) ? 1 : (int)$stock, // Default a 1 si está vacío
            'summary'          => empty($summary) ? null : $summary,
        ]);
    }

    /**
     * Define las reglas de validación para cada fila.
     */
    public function rules(): array
    {
        return [
            // 👇 Arregla el "pipipi" de '1984' quitando 'string'
            'titulo' => 'required|max:200|unique:books,title',
            
            'autor' => 'required|string',
            'editorial' => 'required|string',
            'categoria' => 'required|string',
            
            // El validador se encarga de que sea un número si no está vacío
            'ano_publicacion' => 'nullable|integer|digits:4',
            'stock' => 'nullable|integer',
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function customValidationMessages()
    {
        return [
            'titulo.required' => 'La columna "titulo" no puede estar vacía.',
            'titulo.unique' => 'El libro con este título ya existe en la base de datos.',
            'autor.required' => 'La columna "autor" no puede estar vacía.',
            'editorial.required' => 'La columna "editorial" no puede estar vacía.',
            'categoria.required' => 'La columna "categoria" no puede estar vacía.',
        ];
    }
    
}