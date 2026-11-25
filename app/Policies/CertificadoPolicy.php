<?php

namespace App\Policies;

use App\Models\Certificado;
use App\Models\Usuario;

class CertificadoPolicy
{
    /**
     * Determina si el usuario puede ver la lista de certificados.
     */
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->estado_usu;
    }

    /**
     * Determina si el usuario puede ver un certificado individual.
     */
    public function view(Usuario $usuario, Certificado $certificado): bool
    {
        return $usuario->estado_usu;
    }

    /**
     * Superadmin, Administrador y Registrador pueden crear certificados.
     */
    public function create(Usuario $usuario): bool
    {
        return $usuario->estado_usu;
    }

    /**
     * Superadmin, Administrador y Registrador pueden editar certificados.
     */
    public function update(Usuario $usuario, Certificado $certificado): bool
    {
        return $usuario->estado_usu;
    }

    /**
     * El Registrador NO puede eliminar certificados.
     * Solo Superadmin y Administrador tienen permiso de eliminación.
     */
    public function delete(Usuario $usuario, Certificado $certificado): bool
    {
        return $usuario->estado_usu && ($usuario->isSuperAdmin() || $usuario->isAdmin());
    }

    /**
     * Eliminación masiva.
     */
    public function deleteAny(Usuario $usuario): bool
    {
        return $usuario->estado_usu && ($usuario->isSuperAdmin() || $usuario->isAdmin());
    }
}
