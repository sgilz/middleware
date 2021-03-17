<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelsUsers extends Model
{
    use HasFactory;
    protected $fillable = ['channel_id','user_id'];

    public function getId()
    {
        return $this->attributes['id'];
    }

    public function setId($id)
    {
        $this->attributes['id'] = $id;
    }

    public function getUserId()
    {
        return $this->attributes['user_id'];
    }

    public function setUserId($userId)
    {
        $this->attributes['user_id'] = $userId;
    }

    public function getChannelId()
    {
        return $this->attributes['channel_id'];
    }

    public function setChannelId($channelId)
    {
        $this->attributes['channel_id'] = $channelId;
    }
}
