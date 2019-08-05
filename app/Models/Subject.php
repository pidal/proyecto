<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Apr 2019 00:13:36 +0200.
 */

namespace pfg\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Subject
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $grade
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $assignments
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package pfg\Models
 */
class Subject extends Authenticatable
{
	protected $table = 'subject';

	protected $casts = [
		'grade' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'grade'
	];

	public function assignments()
	{
		return $this->hasMany(\pfg\Models\Assignment::class);
	}

	public function users()
	{
		return $this->belongsToMany(\pfg\Models\User::class, 'rel_users_subject', 'subject_id', 'users_id')
					->withPivot('id')
					->withTimestamps();
	}
}
