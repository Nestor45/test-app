<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = "articulos";
    use HasFactory;

    public function pedidos() {
        return $this->belongsToMany(Pedido::class, 'pedido_items', 'articulo_id', 'pedido_id')->withPivot('cantidad');
    }

    public function factura() {
        return $this->belongsToMany(Factura::class, 'factura_items', 'articulo_id', 'factura_id')->withPivot('cantidad');
    
    }
}
