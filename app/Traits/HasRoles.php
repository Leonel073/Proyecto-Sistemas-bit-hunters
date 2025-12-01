<?php

namespace App\Traits;

trait HasRoles
{
    /**
     * Verifica si el empleado tiene el rol especificado.
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        // Si el rol es una cadena (ej: 'Gerente'), la convertimos a un array.
        $roles = is_array($roles) ? $roles : explode('|', $roles);
        
        // Verifica si el rol del empleado (this->rol) está en el array de roles.
        // El campo 'rol' en tu tabla empleados es el que define el permiso.
        return in_array($this->rol, $roles);
    }
}