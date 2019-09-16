<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Apr 2019 00:13:36 +0200.
 */

namespace pfg\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class ArchivosFallado
 * 
 * @property int $idarchivos_fallados
 * @property string $descripcion
 * @property int $archivos_id
 * 
 * @property \pfg\Models\StudentFile $student_file
 *
 * @package pfg\Models
 */
class ArchivosFallado extends Authenticatable
{
	public $timestamps = false;

	protected $casts = [
		'archivos_id' => 'int'
	];

	protected $fillable = [
		'descripcion'
	];

	public function student_file()
	{
		return $this->belongsTo(\pfg\Models\StudentFile::class, 'archivos_id');
	}

}
