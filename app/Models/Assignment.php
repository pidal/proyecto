<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Apr 2019 00:13:36 +0200.
 */

namespace pfg\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Assignment
 * 
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $correction_file
 * @property int $number_files_delivered
 * @property string $attempts
 * @property string $call
 * @property string $language
 * @property string $type
 * @property int $subject_id
 * @property \Carbon\Carbon $delivered_date
 * 
 * @property \pfg\Models\Subject $subject
 * @property \Illuminate\Database\Eloquent\Collection $group_assignments
 * @property \Illuminate\Database\Eloquent\Collection $student_files
 *
 * @package pfg\Models
 */
class Assignment extends Model
{
	protected $table = 'assignment';

	protected $casts = [
		'number_files_delivered' => 'int',
		'subject_id' => 'int'
	];
	

	protected $fillable = [
		'name',
		'correction_file',
		'number_files_delivered',
		'attempts',
		'call',
		'language',
		'type',
		'subject_id',
		'delivered_date',
        'created_by'
	];

	protected $dates = ['delivered_date'];

	public function subject()
	{
		return $this->belongsTo(\pfg\Models\Subject::class);
	}

	public function group_assignments()
	{
		return $this->hasMany(\pfg\Models\GroupAssignment::class);
	}

	public function student_files()
	{
		return $this->hasMany(\pfg\Models\StudentFile::class);
	}

	public function delete()
    {
        $groupAssignments = GroupAssignment::where('assignment_id',$this->id)->pluck('id');
        foreach ($groupAssignments as $groupAssignment)
        {
            GroupAssignment::find($groupAssignment)->delete();
        }

        $studenFiles = StudentFile::where('assignment_id',$this->id)->pluck('id');
        foreach($studenFiles as $studenFile)
        {
            StudentFile::find($studenFile)->delete();
        }

        parent::delete();
    }
}
