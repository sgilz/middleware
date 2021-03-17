<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'id',
        'ip',
        'name',
        'password',
        'port',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getEmail()
    {
        return $this->attributes['email'];
    }
    
    public function setEmail($email)
    {
        $this->attributes['email'] = $email;
    }

    public function getId()
    {
        return $this->attributes['id'];
    }
    
    public function setId($id)
    {
        $this->attributes['id'] = $id;
    }
  
    public function getIp()
    {
        return $this->attributes['ip'];
    }
    
    public function setIp($ip)
    {
        $this->attributes['ip'] = $ip;
    }

    public function getName()
    {
        return $this->attributes['name'];
    }

    public function setName($name)
    {
        $this->attributes['name'] = $name;
    }

    public function getPassword()
    {
        return $this->attributes['password'];
    }

    public function setPassword($password)
    {
        $this->attributes['password'] = $password;
    }

    public function getPort()
    {
        return $this->attributes['port'];
    }

    public function setPort($port)
    {
        $this->attributes['port'] = $port;
    }
    
    public function channels()
    {
        return $this->belongsToMany(Channel::class)->withTimestamps();
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);                
    }
    
    public static function validateRegister(Request $request) 
    {
        //validating field from the request
        return Validator::make(
            $request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
        ]);
    }

    public static function validateSuscription(Request $request)
    {
        return Validator::make(
            $request->all(), [
                'port' => 'integer|between:1024,65536',
                'ip' => 'string|regex:/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/'
        ]);   
    }
}
