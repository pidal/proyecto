<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Apr 2019 00:13:36 +0200.
 */

namespace pfg\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StudentFile
 * 
 * @property int $id
 * @property string $fileName
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property float $weight
 * @property int $total
 * @property int $pass
 * @property int $fails
 * @property float $score
 * @property int $assignment_id
 * 
 * @property \pfg\Models\Assignment $assignment
 * @property \Illuminate\Database\Eloquent\Collection $archivos_fallados
 * @property \Illuminate\Database\Eloquent\Collection $archivos_historicos
 *
 * @package pfg\Models
 */
class StudentFile extends Model
{
	protected $casts = [
		'weight' => 'float',
		'total' => 'int',
		'pass' => 'int',
		'fails' => 'int',
		'score' => 'float',
		'assignment_id' => 'int',
        'left_attempts' => 'int',

    ];

	protected $fillable = [
		'fileName',
		'weight',
		'total',
		'pass',
		'fails',
		'score',
		'assignment_id',
        'left_attempts',
        'group_id',
        'users_id',
        'delivered'
	];

	public function assignment()
	{
		return $this->belongsTo(\pfg\Models\Assignment::class);
	}

	public function archivos_fallados()
	{
		return $this->hasMany(\pfg\Models\ArchivosFallado::class, 'archivos_id');
	}

	public function archivos_historicos()
	{
		return $this->hasMany(\pfg\Models\ArchivosHistorico::class, 'archivos_id');
	}
}
