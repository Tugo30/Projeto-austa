<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{

  protected $fillable = [
    'titulo',
    'descricao',
    'prioridade',
    'status',
    'categoria_id',
    'user_id',
    'created_at',
    'updated_at'
  ];

  public function categoria()
  {
    return $this->belongsTo(Categorias::class);
  }
}
