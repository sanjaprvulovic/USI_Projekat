<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DegustacioniPaket extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['NazivPaketa', 'Cena', 'Opis'];

    protected $searchableFields = ['*'];

    protected $table = 'degustacioni_pakets';

    public function degustacijaPakets()
    {
        return $this->hasMany(DegustacijaPaket::class);
    }

    public function prIjavas()
    {
        return $this->hasMany(PrIjava::class);
    }

    public function degustacije()
    {
        return $this->belongsToMany(
            \App\Models\Degustacija::class, 
            'degustacija_pakets',           
            'degustacioni_paket_id',        
            'degustacija_id'                
        )->withTimestamps();
    }
}
