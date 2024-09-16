<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\TicketCreated; // Import the correct namespace for TicketCreated
use App\Events\TicketResolved;

class Ticket extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['status', 'description', 'call_id', 'agent_id', 'title'];
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
    protected static function booted()
    {
        static::created(function ($ticket) {
            event(new TicketCreated($ticket));
        });

        static::updated(function ($ticket) {
            if ($ticket->isDirty('status') && $ticket->status == 'résolu') {
                event(new TicketResolved($ticket));
            }
        });
    }
}
