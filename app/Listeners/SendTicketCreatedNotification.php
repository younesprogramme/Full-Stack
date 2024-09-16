<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Mail\TicketCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendTicketCreatedNotification
{
    public function handle(TicketCreated $event)
    {
        $ticket = $event->ticket;
        $agent = $ticket->agent;
        $supervisor = $agent->supervisor;

        Mail::to($agent->email)->send(new TicketCreatedMail($ticket));
        Mail::to($supervisor->email)->send(new TicketCreatedMail($ticket));
    }
}
