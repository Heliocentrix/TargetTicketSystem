<?php

namespace TargetInk;

use Illuminate\Database\Eloquent\Model;

class TicketUpdateLog extends Model
{
	protected $table = 'ticket_update_log';

    protected $fillable = [
    	'ticket_id',
    	'cost',
    	'created_at',
    	'created_by'
    ];

    protected $date = [
    	'created_at'
    ];

    public $timestamps = false;



    public function tickets()
    {
        return $this->belongsTo('TargetInk\Ticket', 'ticket_id');
    }

    public function users()
    {
        return $this->belongsTo('TargetInk\User', 'created_by');
    }
}
