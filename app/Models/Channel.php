<?php
/*
 * @author Manuel Gutierrez magutierrm@eafit.edu.co
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
class Channel extends Model
{
    protected $fillable = [
        'id',
        'name',
        'user_id'
    ];

    public function getId()
    {
        return $this->attributes['id'];
    }

    public function setId($id)
    {
        $this->attributes['id'] = $id;
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

    public static function validate(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|alpha_dash|max:50',
        ]);
    }
}
