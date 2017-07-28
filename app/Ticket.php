<?php

namespace TargetInk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $table = 'tickets';

    protected $fillable = [
        'title',
        'client_id',
        'type',
        'archived',
        'order',
        'priority',
        'cost'
    ];

    public function client()
    {
    	return $this->belongsTo('TargetInk\User', 'client_id');
    }

    public function responses()
    {
        return $this->hasMany('TargetInk\Response', 'ticket_id');
    }

    public function totalTime()
    {
        $totalTime = $this->responses->sum('working_time');
        return str_pad(floor($totalTime / 60), 2, 0, STR_PAD_LEFT) . ':' . str_pad($totalTime % 60, 2, 0, STR_PAD_LEFT);
    }

    public function getRef()
    {
        return str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getType()
    {
        if($this->type == 1) {
            return 'Web Amends';
        } elseif($this->type == 2) {
            return 'Add Content';
        } elseif($this->type == 3) {
            return 'Get Quote';
        } elseif($this->type == 4) {
            return 'Ask Question';
        } elseif($this->type == 5) {
            return 'Artwork';
        }
    }

    public function getPriority()
    {
        if($this->priority) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    public function getUrl()
    {
        return url($this->client->company_slug . '/tickets/' . $this->id);
    }
}
