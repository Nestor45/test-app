<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = "facturas";
    use HasFactory;

    public function pedido() {
        return $this->belongsTo(Pedido::class);
    }
    public function articulos() {
        return $this->belongsToMany(Articulo::class, 'factura_items', 'factura_id', 'articulo_id')->withPivot('cantidad');
    }
}
