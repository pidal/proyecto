<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Apr 2019 00:13:36 +0200.
 */

namespace pfg\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class ArchivosHistorico
 * 
 * @property int $id
 * @property \Carbon\Carbon $updated_at
 * @property int $intentos
 * @property int $archivos_id
 * @property int $total
 * @property int $pasados
 * @property int $fallados
 * @property float $nota
 * 
 * @property \pfg\Models\StudentFile $student_file
 * @property \Illuminate\Database\Eloquent\Collection $archivos_historicos_fallados
 *
 * @package pfg\Models
 */
class ArchivosHistorico extends Authenticatable
{
	public $timestamps = false;

	protected $casts = [
		'intentos' => 'int',
		'archivos_id' => 'int',
		'total' => 'int',
		'pasados' => 'int',
		'fallados' => 'int',
		'nota' => 'float'
	];

	protected $fillable = [
		'intentos',
		'archivos_id',
		'total',
		'pasados',
		'fallados',
		'nota'
	];

	public function student_file()
	{
		return $this->belongsTo(\pfg\Models\StudentFile::class, 'archivos_id');
	}

	public function archivos_historicos_fallados()
	{
		return $this->hasMany(\pfg\Models\ArchivosHistoricosFallado::class, 'archivos_historicos_id');
	}
}
