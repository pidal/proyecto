<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Apr 2019 00:13:36 +0200.
 */

namespace pfg\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class ArchivosHistoricosFallado
 * 
 * @property int $id
 * @property string $descripcion
 * @property int $archivos_historicos_id
 * 
 * @property \pfg\Models\ArchivosHistorico $archivos_historico
 *
 * @package pfg\Models
 */
class ArchivosHistoricosFallado extends Model
{
	public $timestamps = false;

	protected $casts = [
		'archivos_historicos_id' => 'int'
	];

	protected $fillable = [
		'descripcion'
	];

	public function archivos_historico()
	{
		return $this->belongsTo(\pfg\Models\ArchivosHistorico::class, 'archivos_historicos_id');
	}
}
