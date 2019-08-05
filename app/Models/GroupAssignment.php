<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Apr 2019 00:13:36 +0200.
 */

namespace pfg\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class GroupAssignment
 * 
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $members_number
 * @property int $assignment_id
 * 
 * @property \pfg\Models\Assignment $assignment
 * @property \Illuminate\Database\Eloquent\Collection $rel_users_groups
 *
 * @package pfg\Models
 */
class GroupAssignment extends Authenticatable
{
	protected $table = 'group_assignment';

	protected $casts = [
		'members_number' => 'int',
		'assignment_id' => 'int'
	];

	protected $fillable = [
		'name',
		'members_number',
		'assignment_id'
	];

	public function assignment()
	{
		return $this->belongsTo(\pfg\Models\Assignment::class);
	}

	public function rel_users_groups()
	{
		return $this->hasMany(\pfg\Models\RelUsersGroup::class);
	}
}
