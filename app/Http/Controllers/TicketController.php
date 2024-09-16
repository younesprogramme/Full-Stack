<?php

namespace App\Http\Controllers;


use App\Models\Call;
use App\Models\Ticket;
use App\Models\Comment;
use App\Models\Agent;
use Illuminate\Http\Request;
use App\Events\TicketUpdated;
use Illuminate\Support\Facades\DB;


class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::all();
        return response()->json($tickets);
    }

    public function store(Request $request)
    {
        $ticket = Ticket::create($request->all());
        event(new TicketUpdated($ticket));
        return response()->json($ticket, 201);
    }
    public function update($id, Request $request)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->all());
        return response()->json($ticket, 200);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    // Add comment to a ticket
    public function addComment($id, Request $request)
    {
        $ticket = Ticket::findOrFail($id);

        // Créer un nouveau commentaire lié au ticket
        $comment = new Comment();
        $comment->ticket_id = $ticket->id;
        $comment->content = $request->input('comment');
        $comment->save();

        return response()->json(['comments' => $ticket->comments]); // Retourne tous les commentaires du ticket

    }
    public function getReports(Request $request)
    {
        $agentName = $request->query('agent');
        $date = $request->query('date');

        $agentName = "younes";
        $query = Ticket::query();

        if ($agentName) {
            $query->whereHas('agent', function ($q) use ($agentName) {
                $q->where('name', $agentName);
            });
        }
        
        if ($date) {
            $query->whereDate('created_at', $date);
        }

        $tickets = $query->get();

        $query = Ticket::query()
            ->join('calls', 'tickets.call_id', '=', 'calls.id') // Join with calls table
            ->select('tickets.*', 'calls.duration'); // Include response_time and satisfaction_rate

        $reportData = $tickets->groupBy('agent_id')->map(function ($tickets, $agentId) {
            $agent = Agent::find($agentId);




            $ticketsResolved = $tickets->where('status', 'résolu')->count();
            $ticketsInProgress = $tickets->where('status', 'en cours')->count();
            $avgDuration = $tickets->avg('duration'); // Assuming you have a response_time field
            $satisfactionRate = $tickets->avg('satisfaction_rate'); // Assuming you track satisfaction

            return [
                'agent' => $agent->name,
                'ticketsResolved' => $ticketsResolved,
                'ticketsInProgress' => $ticketsInProgress,
                'avgResponseTime' => round($avgDuration / 60, 2), // converting minutes to hours
                'satisfactionRate' => round($satisfactionRate, 2),
            ];
        })->values();

        return response()->json($reportData);
    }

    public function boot()
    {
        DB::listen(function ($query) {
            logger($query->sql);
            logger($query->bindings);
            logger($query->time);
        });
    }

}
