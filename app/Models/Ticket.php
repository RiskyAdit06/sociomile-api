<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title','description','status','priority',
        'assigned_agent_id','customer_id','tenant_id'
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function assignedAgent() { return $this->belongsTo(User::class,'assigned_agent_id'); }
    public function customer() { return $this->belongsTo(User::class,'customer_id'); }
    public function conversations() { return $this->hasMany(Conversation::class); }
}

