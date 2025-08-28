<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusDegustacija extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['Naziv'];

    protected $searchableFields = ['*'];

    protected $table = 'status_degustacijas';

    public function degustacijas()
    {
        return $this->hasMany(Degustacija::class);
    }
}
