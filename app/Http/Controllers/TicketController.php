<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Get list tickets
     */
    public function index(Request $request)
    {
        $tenant_id = auth()->user()->tenant_id;

        $query = Ticket::where('tenant_id', $tenant_id);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->assigned_agent_id) {
            $query->where('assigned_agent_id', $request->assigned_agent_id);
        }

        $tickets = $query->with(['assignedAgent', 'customer'])->get();

        return response()->json([
            'message' => 'Tickets retrieved successfully',
            'data'    => $tickets
        ]);
    }

    /**
     * Create ticket
     */
    public function store(Request $request)
    {
        $tenant_id = auth()->user()->tenant_id;

        $ticket = Ticket::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'priority'     => $request->priority ?? 'medium',
            'tenant_id'    => $tenant_id,
            'customer_id'  => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Ticket created successfully',
            'data'    => $ticket
        ], 201);
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::where('tenant_id', auth()->user()->tenant_id)
            ->find($id);

        if (!$ticket) {
            return response()->json(["error" => "ID Ticket $id tidak terdaftar di database"], 404);
        }

        $request->validate([
            'status' => 'required|string'
        ]);

        $ticket->status = $request->status;
        $ticket->save();

        return response()->json([
            'message' => 'Ticket status updated successfully',
            'data'    => $ticket
        ]);
    }

    /**
     * Assign agent to ticket
     */
    public function assign(Request $request, $id)
    {
        $ticket = Ticket::where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($id);

        $ticket->assigned_agent_id = $request->assigned_agent_id;
        $ticket->save();

        return response()->json([
            'message' => 'Ticket assigned successfully',
            'data'    => $ticket
        ]);
    }
}