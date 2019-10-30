<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Apr 2019 00:13:36 +0200.
 */

namespace pfg\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RelUsersSubject
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $users_id
 * @property int $subject_id
 * 
 * @property \pfg\Models\Subject $subject
 * @property \pfg\Models\User $user
 *
 * @package pfg\Models
 */
class RelUsersSubject extends Model
{
	protected $table = 'rel_users_subject';

	protected $casts = [
		'users_id' => 'int',
		'subject_id' => 'int'
	];

	protected $fillable = [
		'users_id',
		'subject_id'
	];

	public function subject()
	{
		return $this->belongsTo(\pfg\Models\Subject::class);
	}

	public function user()
	{
		return $this->belongsTo(\pfg\Models\User::class);
	}
}
