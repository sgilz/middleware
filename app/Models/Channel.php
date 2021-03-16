<?php
/*
 * @author Manuel Gutierrez magutierrm@eafit.edu.co
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = [
        'id',
        'name',
        'user_id'
    ];

    public function getId()
    {
        return $this->attributes['channel_id'];
    }

    public function setId($channel_id)
    {
        $this->attributes['channel_id'] = $channel_id;
    }

    public function getName()
    {
        return $this->attributes['name'];
    }

    public function setName($name)
    {
        $this->attributes['name'] = $name;
    }

    public function messages()
    {
        return $this->hasMany(Messages::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}