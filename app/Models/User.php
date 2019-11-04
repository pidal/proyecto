<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Apr 2019 00:13:36 +0200.
 */

namespace pfg\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PDF;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $surname
 * @property int $roles_id
 * 
 * @property \pfg\Models\Role $role
 * @property \Illuminate\Database\Eloquent\Collection $rel_users_groups
 * @property \Illuminate\Database\Eloquent\Collection $subjects
 * @property \Illuminate\Database\Eloquent\Collection $alumnos
 * @property \Illuminate\Database\Eloquent\Collection $profesors
 *
 * @package pfg\Models
 */
class User extends Authenticatable
{
    const ROLE_ALUMNO = 3;

    use Notifiable;

	protected $casts = [
		'roles_id' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'password',
		'remember_token',
		'surname',
		'roles_id',
        'token'
	];

	public function role()
	{
		return $this->belongsTo(\pfg\Models\Role::class, 'roles_id');
	}

	public function rel_users_groups()
	{
		return $this->hasMany(\pfg\Models\RelUsersGroup::class, 'users_id');
	}

	public function subjects()
	{
		return $this->belongsToMany(\pfg\Models\Subject::class, 'rel_users_subject', 'users_id')
					->withPivot('id')
					->withTimestamps();
	}

	public function alumnos()
	{
		return $this->hasMany(\pfg\Models\Alumno::class, 'users_id');
	}

	public function profesors()
	{
		return $this->hasMany(\pfg\Models\Profesor::class, 'users_id');
	}

	public static function crearPdf(User $user){

		$url = url('/') . "/createUsers/restablishpass/". $user->token;

		$filename = storage_path(DIRECTORY_SEPARATOR."txt".DIRECTORY_SEPARATOR."cambioInicial_".$user->email.".txt");
		$archivo = fopen($filename, "w");
		if ($archivo == false) {
			throw new \Exception("Error al crear el archivo");
		} else {
			fwrite($archivo, "Su usuario de SSM ha sido creado. El acceso será a través de su Email\r\n\r\n");
			fwrite($archivo, "Correo: ".$user->email."\r\n\r\n");
			fwrite($archivo, "Introduzca la contraseña para su usario a través del siguiente link\r\n");
			fwrite($archivo, "URL: $url\r\n");
			fwrite($archivo, "Las condiciones legales serán aceptadas en el momento que realiza el acceso.\r\n\r\n");
			fwrite($archivo, "Condiciones:\r\n\r\n");
			fwrite($archivo, "Lorem ipsum dolor sit amet consectetur adipiscing, elit sociis placerat suspendisse viverra mattis quam, et ultricies curabitur hac facilisis. Risus conubia maecenas platea nec justo tincidunt netus hac elementum odio, parturient nisl nibh cras aliquam sollicitudin mollis dictum curae venenatis curabitur, egestas facilisi ornare rutrum nulla aptent iaculis lectus metus. Et natoque placerat etiam vehicula varius ante tellus aptent bibendum, turpis convallis torquent tincidunt feugiat nam ridiculus scelerisque libero magna, suscipit iaculis sodales faucibus gravida a eleifend mus.
Fames congue nascetur erat montes a purus facilisi taciti, donec maecenas ultrices placerat gravida semper dignissim morbi, eget augue egestas bibendum posuere eleifend urna. Habitasse sociis ad torquent vivamus malesuada auctor class curae congue, tempor himenaeos tellus justo egestas lectus vehicula tincidunt, vel aliquet semper metus quisque libero nam id. Molestie vehicula netus pulvinar dapibus pretium platea justo tincidunt porttitor, donec ac vulputate vitae tortor leo aliquam nascetur sodales ante, per potenti tellus montes quam ad non nunc.\r\n");
			fflush($archivo);
		}
		fclose($archivo);

		return $filename;
	}

	public function delete(){

	    //Solo para alumnos
        if($this->roles_id == self::ROLE_ALUMNO){
            RelUsersGroup::where('users_id',$this->id)->delete();
            RelUsersSubject::where('users_id',$this->id)->delete();
            StudentFile::where('users_id',$this->id)->delete();

            dd("aad");
        }

	    parent::delete();
    }
}
