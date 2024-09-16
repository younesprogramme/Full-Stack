<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['agent_id', 'call_time', 'duration', 'subject'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

}
