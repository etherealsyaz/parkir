<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';
    protected $fillable = [
        'location_name', 'max_motorcycle', 'max_car', 'max_other'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_lokasi');
    }
}
