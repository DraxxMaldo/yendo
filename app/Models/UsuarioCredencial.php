<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsuarioCredencial extends Authenticatable
{
    protected $table = 'usuarios_credenciales';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'correo_electronico',
        'contrasenha',
        'id_rol',
        'estado_cuenta',
    ];

    protected $casts = [
        'id_rol' => 'integer',
        'estado_cuenta' => 'boolean',
        'contrasenha' => 'hashed',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'correo_electronico' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('usuarios_credenciales', 'correo_electronico')->ignore($id, 'id_usuario')
            ],
            'contrasenha' => [$id ? 'nullable' : 'required', 'string', 'max:255'],
            'id_rol' => ['required', 'integer', 'exists:roles,id_rol'],
            'estado_cuenta' => ['nullable', 'boolean'],
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    public function getAuthPasswordName(): string
    {
        return 'contrasenha';
    }

    public function getAuthPassword(): string
    {
        return $this->contrasenha;
    }

    // RELACIONES
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function perfil()
    {
        return $this->hasOne(PerfilPersona::class, 'id_usuario', 'id_usuario');
    }

    public function empleadoSucursal()
    {
        return $this->hasOne(EmpleadoSucursal::class, 'id_usuario', 'id_usuario');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return UsuarioCredencial::with('rol')->orderBy('id_usuario', 'ASC')->get();
    }

    public static function contarTodos(): int
    {
        return UsuarioCredencial::count();
    }

    // INSERTAR, MODIFICAR, ELIMINAR
    public static function insertar(array $datos): UsuarioCredencial
    {
        return UsuarioCredencial::create($datos);
    }

    public static function buscarXId(int $id): ?UsuarioCredencial
    {
        return UsuarioCredencial::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return UsuarioCredencial::find($id)->update($datos);
    }

    public static function eliminar(int $id): bool
    {
        return UsuarioCredencial::find($id)->delete();
    }
}
