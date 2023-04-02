<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = "clientes";
    use HasFactory;
    public function pedidos() {
        return $this->hasMany(Pedido::class);
    }
}
