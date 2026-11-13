<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Encomienda;
use App\Models\Establecimiento;
use App\Models\TipoEnvio;
use App\Models\UsuarioCredencial;
use App\Models\EmpleadoSucursal;
use App\Models\CatalogoPrecio;
use App\Models\Bitacora;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EncomiendaController extends Controller
{
    public function create()
    {
        $usuario = Auth::user();

        // 1. Obtener la sucursal del cajero y su establecimiento asociado
        $empleadoSucursal = EmpleadoSucursal::where('id_usuario', $usuario->id_usuario)->with('establecimiento')->first();
        $establecimientoOrigen = $empleadoSucursal ? $empleadoSucursal->establecimiento : null;

        // 2. Identificar al Afiliado (Dueño de la empresa) a través del establecimiento de origen
        // En tu diagrama, la tabla establecimientos tiene la columna id_usuario que define al dueño/afiliado
        $idAfiliadoDueño = $establecimientoOrigen ? $establecimientoOrigen->id_usuario : null;

        // 3. Filtrar el catálogo de precios estrictamente por el dueño de la empresa (Afiliado)
        // Si por alguna razón el establecimiento no tuviera usuario, por seguridad se trae vacio o general
        $preciosCatalogo = collect();
        if ($idAfiliadoDueño) {
            $preciosCatalogo = CatalogoPrecio::where('estado', true)
                ->where('id_usuario', $idAfiliadoDueño)
                ->get();
        } else {
            // Fallback por si el admin global o un usuario sin sucursal entra
            $preciosCatalogo = CatalogoPrecio::where('estado', true)->get();
        }

        $establecimientos = Establecimiento::all();
        $tiposEnvio = TipoEnvio::all();
        $remitentes = UsuarioCredencial::all();

        $codigoSugerido = self::generarCodigoUnico();

        $tipoEnvioSugerido = TipoEnvio::where('nombre_tipo', 'like', '%Sucursal a Sucursal%')->first();
        $idTipoSucursalXSucursal = $tipoEnvioSugerido ? $tipoEnvioSugerido->id_tipo_envio : null;

        return view('menu.cajero.registrarEncomienda', compact(
            'establecimientos',
            'tiposEnvio',
            'remitentes',
            'codigoSugerido',
            'establecimientoOrigen',
            'idTipoSucursalXSucursal',
            'preciosCatalogo'
        ));
    }

    public function store(Request $request)
    {
        try {
            $usuario = Auth::user();

            $empleadoSucursal = EmpleadoSucursal::where('id_usuario', $usuario->id_usuario)->first();

            if (!$empleadoSucursal) {
                return back()->with('error', 'No tienes ningún establecimiento asignado como personal. Contacta al administrador.');
            }

            // Validar que se haya seleccionado un precio del catálogo
            $request->validate([
                'id_precio' => ['required', 'integer', 'exists:catalogo_precios,id_precio'],
                'valor_declarado_producto' => ['required', 'numeric', 'min:0'],
                'es_pago_contraentrega' => ['required', 'boolean'],
            ]);

            // Obtener el precio seleccionado del catálogo para hacer el cálculo
            $catalogoPrecio = CatalogoPrecio::findOrFail($request->id_precio);
            $costoTarifa = $catalogoPrecio->precio;
            $valorDeclarado = floatval($request->valor_declarado_producto);
            $esContraentrega = boolval($request->es_pago_contraentrega);

            // Cálculo financiero:
            // - Si es contraentrega: Costo de tarifa del catálogo + Valor declarado del producto.
            // - Si NO es contraentrega: Solo se cobra la tarifa del precio del catálogo.
            $montoTotalTransaccion = $esContraentrega ? ($costoTarifa + $valorDeclarado) : $costoTarifa;

            // BUSCAR UN CONCEPTO FINANCIERO VÁLIDO DE MANERA DINÁMICA
            // Buscamos el primer concepto disponible en la tabla para evitar errores de llave foránea
            $conceptoFinanciero = DB::table('conceptos_financieros')->first();

            if (!$conceptoFinanciero) {
                return back()->with('error', 'No existen conceptos financieros registrados en el sistema. Contacta al administrador para configurar la tabla conceptos_financieros.');
            }

            DB::beginTransaction();

            // Generar código único y armar datos
            $codigoUnicoSeguro = self::generarCodigoUnico();

            $request->merge([
                'codigo_rastreo' => $codigoUnicoSeguro,
                'id_establecimiento_origen' => $empleadoSucursal->id_establecimiento,
                'fecha_ingreso' => Carbon::now()
            ]);

            $datosValidados = Encomienda::rules($request->all());

            // Insertar encomienda
            $encomienda = Encomienda::insertar($datosValidados);

            // Registrar automáticamente en transacciones_paquetes usando el concepto financiero dinámico
            DB::table('transacciones_paquetes')->insert([
                'id_encomienda'        => $encomienda->id_encomienda,
                'id_concepto'          => $conceptoFinanciero->id_concepto, // <--- Dinámico obtenido de la BD
                'monto'                => $montoTotalTransaccion,
                'fecha_transaccion'    => Carbon::now(),
                'id_usuario_cajero'    => $usuario->id_usuario,
            ]);

            Bitacora::registrar(
                Bitacora::CREADO,
                'Encomiendas y Transacciones',
                $usuario->id_usuario,
                "Se registró la encomienda {$encomienda->codigo_rastreo} con transacción financiera por un monto de \${$montoTotalTransaccion}."
            );

            DB::commit();

            return redirect()->route('encomiendas.create')
                ->with('success', '¡Encomienda y transacción registrada con éxito! Monto cobrado: $' . number_format($montoTotalTransaccion, 2));

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput()->with('error', 'Por favor, corrige los errores en el formulario.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la encomienda: ' . $e->getMessage())->withInput();
        }
    }

    private static function generarCodigoUnico(): string
    {
        do {
            $caracteres = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            $codigoAleatorio = '';
            for ($i = 0; $i < 6; $i++) {
                $codigoAleatorio .= $caracteres[mt_rand(0, strlen($caracteres) - 1)];
            }
            $codigo = 'YND-' . $codigoAleatorio;
        } while (Encomienda::where('codigo_rastreo', $codigo)->exists());

        return $codigo;
    }
}
