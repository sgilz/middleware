<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'date',
        'sent',
        'queue_id',
        'channel_id'
    ];

    public function getId()
    {
        return $this->attributes['id'];
    }

    public function setId($id)
    {
        $this->attributes['id'] = $id;
    }

    public function getBody()
    {
        return $this->attributes['body'];
    }

    public function setBody($body)
    {
        $this->attributes['body'] = $body;
    }

    public function getDate()
    {
        return $this->attributes['date'];
    }

    public function setDate($date)
    {
        $this->attributes['date'] = $date;
    }

    public function getSent()
    {
        return $this->attributes['sent'];
    }

    public function setSent($value)
    {
        $this->attributes['sent'] = $value;
    }

    public function getQueueId()
    {
        return $this->attributes['queue_id'];
    }

    public function setQueueId($queue_id)
    {
        $this->attributes['queue_id'] = $queue_id;
    }

    public function getChannelId()
    {
        return $this->attributes['channel_id'];
    }

    public function setChannelId($channel_id)
    {
        $this->attributes['channel_id'] = $channel_id;
    }

    public function queue()
    {
        $this->belongsTo(Queue::class);
    }
}
