<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusPrijava extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['Naziv'];

    protected $searchableFields = ['*'];

    protected $table = 'status_prijavas';

    public function prIjavas()
    {
        return $this->hasMany(PrIjava::class);
    }
}
