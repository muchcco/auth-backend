<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonaAccessToken extends SanctumPersonalAccessToken
{
    // Nombre de la tabla personalizada
    protected $table = 'personal_access_tokens';
}
