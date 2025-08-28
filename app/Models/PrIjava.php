<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrIjava extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'Datum',
        'status_prijava_id',
        'degustacija_id',
        'user_id',
        'degustacioni_paket_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'pr_ijavas';

    protected $casts = [
        'Datum' => 'datetime',
    ];

    public function statusPrijava()
    {
        return $this->belongsTo(StatusPrijava::class);
    }

    public function degustacija()
    {
        return $this->belongsTo(Degustacija::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function degustacioniPaket()
    {
        return $this->belongsTo(DegustacioniPaket::class);
    }
}
