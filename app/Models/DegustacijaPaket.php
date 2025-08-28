<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DegustacijaPaket extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'DegustacijaID',
        'degustacija_id',
        'degustacioni_paket_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'degustacija_pakets';

    public function degustacija()
    {
        return $this->belongsTo(Degustacija::class);
    }

    public function degustacioniPaket()
    {
        return $this->belongsTo(DegustacioniPaket::class);
    }
}
