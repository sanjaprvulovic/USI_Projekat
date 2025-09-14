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

    protected $with = ['statusDegustacija'];

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
            DegustacioniPaket::class,   
            'degustacija_pakets',      
            'degustacija_id',           
            'degustacioni_paket_id'     
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

    public function aktivnePrijave()
    {
        return $this->prIjavas()
            ->whereHas('statusPrijava', fn($q) =>
                $q->whereNotIn('Naziv', ['Otkazana', 'Odbijena'])
            );
    }


    public function aktivnePrijaveQuery()
    {
        return $this->aktivnePrijave();
    }

    public function aktivnePrijaveCount(): int
    {
        return $this->aktivnePrijave()->count();
    }

    
    public function remainingCapacity(): ?int
    {
        if (is_null($this->Kapacitet)) {
            return null; 
        }
        return max(0, $this->Kapacitet - $this->aktivnePrijaveCount());
    }

    public function isFull(): bool
    {
        return !is_null($this->Kapacitet) && $this->remainingCapacity() <= 0;
    }
}
