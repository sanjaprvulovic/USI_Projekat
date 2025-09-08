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

    // učitaj status da izbegnemo N+1
    protected $with = ['statusDegustacija'];

    protected $searchableFields = ['*'];

    protected $casts = [
        'Datum' => 'datetime',
    ];

    /* =========================
     | Relacije
     |=========================*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusDegustacija()
    {
        return $this->belongsTo(StatusDegustacija::class);
    }

    /**
     * Paketi kroz pivot (preporučeno za dodelu/sync).
     */
    public function paketi()
    {
        return $this->belongsToMany(
            DegustacioniPaket::class,   // model paketa
            'degustacija_pakets',       // pivot tabela
            'degustacija_id',           // FK na degustaciju
            'degustacioni_paket_id'     // FK na paket
        )->withTimestamps();
    }

    /**
     * Ako koristiš eksplicitni pivot model (za dodatna polja u pivotu).
     */
    public function degustacijaPakets()
    {
        return $this->hasMany(DegustacijaPaket::class);
    }

    public function prIjavas()
    {
        return $this->hasMany(PrIjava::class);
    }

    /**
     * Aktivne prijave (NIJE Otkazana/Odbijena) kao RELACIJA,
     * da bismo mogli withCount('aktivnePrijave').
     */
    public function aktivnePrijave()
    {
        return $this->prIjavas()
            ->whereHas('statusPrijava', fn($q) =>
                $q->whereNotIn('Naziv', ['Otkazana', 'Odbijena'])
            );
    }

    /* =========================
     | Helperi (kapacitet)
     |=========================*/

    // zadržavam tvoju postojeću query-metodu, nije obavezna ali ne smeta
    public function aktivnePrijaveQuery()
    {
        return $this->aktivnePrijave();
    }

    public function aktivnePrijaveCount(): int
    {
        return $this->aktivnePrijave()->count();
    }

    /**
     * Preostalo mesta ili null ako kapacitet nije ograničen.
     */
    public function remainingCapacity(): ?int
    {
        if (is_null($this->Kapacitet)) {
            return null; // neograničeno
        }
        return max(0, $this->Kapacitet - $this->aktivnePrijaveCount());
    }

    public function isFull(): bool
    {
        return !is_null($this->Kapacitet) && $this->remainingCapacity() <= 0;
    }
}
