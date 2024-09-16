<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['ticket_id', 'content'];

    // Relation avec le modèle Ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
