<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Degustacija extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'Naziv',
        'Datum',
        'Lokacija',
        'Kapacitet',
        'user_id',
        'status_degustacija_id',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'Datum' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusDegustacija()
    {
        return $this->belongsTo(StatusDegustacija::class);
    }
    public function paketi()
    {
        return $this->belongsToMany(
            \App\Models\DegustacioniPaket::class, // cilj model
            'degustacija_pakets',                 // pivot tabela
            'degustacija_id',                     // FK na degustaciju u pivotu
            'degustacioni_paket_id'               // FK na paket u pivotu
        )->withTimestamps();
    }

    public function degustacijaPakets()
    {
        return $this->hasMany(DegustacijaPaket::class);
    }

    public function prIjavas()
    {
        return $this->hasMany(PrIjava::class);
    }
}
