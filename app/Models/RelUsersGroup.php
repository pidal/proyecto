<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Apr 2019 00:13:36 +0200.
 */

namespace pfg\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RelUsersGroup
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $users_id
 * @property int $group_assignment_id
 * 
 * @property \pfg\Models\GroupAssignment $group_assignment
 * @property \pfg\Models\User $user
 *
 * @package pfg\Models
 */
class RelUsersGroup extends Model
{
	protected $casts = [
		'users_id' => 'int',
		'group_assignment_id' => 'int'
	];

	protected $fillable = [
		'users_id',
		'group_assignment_id'
	];

	public function group_assignment()
	{
		return $this->belongsTo(\pfg\Models\GroupAssignment::class);
	}

	public function getNameAttribute()
	{
		$user = User::find($this->users_id);
		return $user->name;
	}
}
