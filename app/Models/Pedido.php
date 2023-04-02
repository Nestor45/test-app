<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    public function cliente() {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }

    public function facturas() {
        return $this->hasMany(Factura::class,'pedido_id');
    }

    public function articulos() {
        return $this->belongsToMany(Articulo::class, 'pedido_items', 'pedido_id', 'articulo_id')->withPivot('cantidad');
    }

}
