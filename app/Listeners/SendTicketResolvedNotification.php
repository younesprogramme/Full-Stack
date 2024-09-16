<?php

namespace App\Listeners;

use App\Events\TicketResolved;
use App\Mail\TicketResolvedMail;
use Illuminate\Support\Facades\Mail;

class SendTicketResolvedNotification
{
    public function handle(TicketResolved $event)
    {
        $ticket = $event->ticket;
        $agent = $ticket->agent;
        $supervisor = $agent->supervisor;

        Mail::to($agent->email)->send(new TicketResolvedMail($ticket));
        Mail::to($supervisor->email)->send(new TicketResolvedMail($ticket));
    }
}
