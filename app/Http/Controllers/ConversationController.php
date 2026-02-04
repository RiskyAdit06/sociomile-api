<?php

namespace App\Http\Controllers;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index($ticket_id) {
        $tenant_id = auth()->user()->tenant_id;
        $convs = Conversation::whereHas('ticket', fn($q)=>$q->where('tenant_id',$tenant_id))
            ->where('ticket_id',$ticket_id)
            ->with('sender')
            ->orderBy('created_at','asc')->get();
        return response()->json($convs);
    }

    public function store(Request $request, $ticket_id) {
        $tenant_id = auth()->user()->tenant_id;
        $ticket = \App\Models\Ticket::where('tenant_id',$tenant_id)->findOrFail($ticket_id);

        $conv = Conversation::create([
            'ticket_id'=>$ticket->id,
            'sender_id'=>auth()->id(),
            'message'=>$request->message
        ]);
        return response()->json($conv);
    }
}

