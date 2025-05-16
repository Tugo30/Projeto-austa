<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
      protected $fillable = ['nome'];

      public function atendimentos()
      {
            return $this->hasMany(Atendimento::class);
      }
}
