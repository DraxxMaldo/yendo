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
        $idAfiliadoDueño = $establecimientoOrigen ? $establecimientoOrigen->id_usuario : null;

        // 3. Recuperar el nombre del negocio desde la tabla perfiles_personas del afiliado dueño
        $nombreNegocio = null;
        if ($idAfiliadoDueño) {
            $perfilPersona = DB::table('perfiles_personas')->where('id_usuario', $idAfiliadoDueño)->first();
            $nombreNegocio = $perfilPersona ? $perfilPersona->nombre_negocio : null;
        }

        // 4. Filtrar el catálogo de precios estrictamente por el dueño de la empresa (Afiliado)
        $preciosCatalogo = collect();
        if ($idAfiliadoDueño) {
            $preciosCatalogo = CatalogoPrecio::where('estado', true)
                ->where('id_usuario', $idAfiliadoDueño)
                ->get();
        } else {
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
            'preciosCatalogo',
            'nombreNegocio'
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

            // Validar que se reciban múltiples precios del catálogo
            $request->validate([
                'id_precios'   => ['required', 'array', 'min:1'],
                'id_precios.*' => ['required', 'integer', 'exists:catalogo_precios,id_precio'],
                'valor_declarado_producto' => ['required', 'numeric', 'min:0'],
                'es_pago_contraentrega'    => ['required', 'boolean'],
            ]);

            // Sumar las tarifas de todos los elementos seleccionados en el catálogo
            $sumaTarifas = 0;
            foreach ($request->id_precios as $idPrecioItem) {
                $itemCatalogo = CatalogoPrecio::findOrFail($idPrecioItem);
                $sumaTarifas += floatval($itemCatalogo->precio);
            }

            $valorDeclarado = floatval($request->valor_declarado_producto);
            $esContraentrega = boolval($request->es_pago_contraentrega);

            // CÁLCULO FINANCIERO:
            // - Si es contraentrega: Suma total de tarifas + Valor declarado.
            // - Si NO es contraentrega: Únicamente la suma total de las tarifas.
            $montoTotalTransaccion = $esContraentrega ? ($sumaTarifas + $valorDeclarado) : $sumaTarifas;

            // BUSCAR UN CONCEPTO FINANCIERO VÁLIDO DE MANERA DINÁMICA
            $conceptoFinanciero = DB::table('conceptos_financieros')->first();

            if (!$conceptoFinanciero) {
                return back()->with('error', 'No existen conceptos financieros registrados en el sistema. Contacta al administrador.');
            }

            DB::beginTransaction();

            // Generar código único y armar datos (asignamos el primer id_precio al campo principal de encomienda)
            $codigoUnicoSeguro = self::generarCodigoUnico();

            $request->merge([
                'codigo_rastreo'            => $codigoUnicoSeguro,
                'id_establecimiento_origen' => $empleadoSucursal->id_establecimiento,
                'id_precio'                 => $request->id_precios[0],
                'fecha_ingreso'             => Carbon::now()
            ]);

            $datosValidados = Encomienda::rules($request->all());

            // Insertar encomienda
            $encomienda = Encomienda::insertar($datosValidados);

            // Registrar automáticamente en transacciones_paquetes
            DB::table('transacciones_paquetes')->insert([
                'id_encomienda'     => $encomienda->id_encomienda,
                'id_concepto'       => $conceptoFinanciero->id_concepto,
                'monto'             => $montoTotalTransaccion,
                'fecha_transaccion' => Carbon::now(),
                'id_usuario_cajero' => $usuario->id_usuario,
            ]);

            Bitacora::registrar(
                Bitacora::CREADO,
                'Encomiendas y Transacciones',
                $usuario->id_usuario,
                "Se registró la encomienda {$encomienda->codigo_rastreo} con transacción financiera múltiple por un monto de \${$montoTotalTransaccion}."
            );

            DB::commit();

            return redirect()->route('encomiendas.create')
                ->with('success', '¡Encomienda y transacción registrada con éxito! Monto total cobrado: $' . number_format($montoTotalTransaccion, 2));

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
