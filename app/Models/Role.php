<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Apr 2019 00:13:36 +0200.
 */

namespace pfg\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * 
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $users
 * @property \Illuminate\Database\Eloquent\Collection $alumnos
 * @property \Illuminate\Database\Eloquent\Collection $profesors
 *
 * @package pfg\Models
 */
class Role extends Model
{
	protected $fillable = [
		'name'
	];

	public function users()
	{
		return $this->hasMany(\pfg\Models\User::class, 'roles_id');
	}

	public function alumnos()
	{
		return $this->hasMany(\pfg\Models\Alumno::class, 'roles_id');
	}

	public function profesors()
	{
		return $this->hasMany(\pfg\Models\Profesor::class, 'roles_id');
	}
}
