<?php

namespace App\Http\Controllers;


/**
 * Esta clase sirve únicamente como contenedor para las anotaciones globales de OpenAPI.
 *
 * @OA\OpenApi(
 * @OA\Info(
 * title="Kanban Board API",
 * version="1.0.0",
 * description="API para gestionar Tableros, Tarjetas y Tareas de un sistema Kanban, siguiendo la especificación OpenAPI 3.",
 * @OA\Contact(
 * email="tu.email@ejemplo.com",
 * name="Tu Nombre"
 * ),
 * @OA\License(
 * name="Licencia Estándar de API",
 * url="http://localhost/licencia"
 * )
 * ),
 * @OA\Server(
 * url="http://127.0.0.1:8000",
 * description="Servidor de desarrollo local"
 * ),
 * @OA\Tag(
 * name="Tableros",
 * description="Operaciones CRUD para la gestión de tableros (Boards)."
 * ),
 * @OA\Tag(
 * name="Tarjetas",
 * description="Operaciones CRUD para la gestión de tarjetas/columnas (Cards)."
 * ),
 * @OA\Tag(
 * name="Tareas",
 * description="Operaciones CRUD para la gestión de tareas individuales (Tasks)."
 * )
 * )
 *
 * @OA\ExternalDocumentation(
 * description="Documentación adicional y tutoriales",
 * url="http://localhost/docs/external"
 * )
 */
    

abstract class Controller
{
    // Esta clase está vacía a propósito. Solo existe para contener la documentación global
    // que será recogida por l5-swagger ya que está en la carpeta 'app/Http/Controllers'.
}
