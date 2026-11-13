<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CatalogoPrecio;
use App\Models\Bitacora;

class CatalogoPrecioController extends Controller
{
    // Mostrar la lista de precios del usuario y el formulario de registro
    public function index()
    {
        $usuario = Auth::user();

        // Obtenemos únicamente los precios pertenecientes al usuario/empresa autenticado
        $precios = CatalogoPrecio::where('id_usuario', $usuario->id_usuario)
            ->orderBy('nombre_prenda', 'ASC')
            ->get();

        return view('menu.afiliados.catalogoPrecios', compact('precios'));
    }

    // Almacenar un nuevo precio en el catálogo
    public function store(Request $request)
    {
        $request->validate([
            'nombre_prenda' => ['required', 'string', 'max:100'],
            'precio'        => ['required', 'numeric', 'min:0'],
        ], [
            'nombre_prenda.required' => 'El nombre de la prenda o servicio es obligatorio.',
            'precio.required'        => 'El precio es obligatorio.',
            'precio.numeric'         => 'El precio debe ser un valor numérico válido.',
            'precio.min'             => 'El precio no puede ser negativo.',
        ]);

        try {
            $usuario = Auth::user();

            CatalogoPrecio::create([
                'id_usuario'    => $usuario->id_usuario,
                'nombre_prenda' => $request->nombre_prenda,
                'precio'        => $request->precio,
                'estado'        => true,
            ]);

            Bitacora::registrar(
                Bitacora::CREADO,
                'Catálogo de Precios',
                $usuario->id_usuario,
                "El usuario agregó la prenda/servicio '{$request->nombre_prenda}' con precio {$request->precio}."
            );

            return back()->with('success', '¡Precio registrado exitosamente en tu catálogo!');

        } catch (\Throwable $e) {
            return back()->with('error', 'Error al registrar el precio: ' . $e->getMessage())->withInput();
        }
    }
}
