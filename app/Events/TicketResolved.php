<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;

class TicketResolved
{
    use SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }
}
