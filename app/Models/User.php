<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = ['name','email','password','role','tenant_id'];
    protected $hidden = ['password'];

    public function getJWTIdentifier() { return $this->getKey(); }
    public function getJWTCustomClaims() {
        return ['role'=>$this->role,'tenant_id'=>$this->tenant_id];
    }

    public function ticketsAssigned() { return $this->hasMany(Ticket::class,'assigned_agent_id'); }
    public function ticketsCustomer() { return $this->hasMany(Ticket::class,'customer_id'); }
}

