<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	dd(Session::get('errors'));
	return redirect()->to(url('/login'))->withErrors(Session::get('errors'))->withInput();;
});



Route::resource('alumno', 'AlumnoController');
Auth::routes();



Route::post('/practicas', 'AlumnoController@practicas');
Route::post('/archivos', 'AlumnoController@archivos');
Route::post('/resultados', 'AlumnoController@resultados');

Route::post('/guardar', 'AlumnoController@store');

Route::get('/mostrar', 'AlumnoController@show');

Route::get('logout', '\pfg\Http\Controllers\Auth\LoginController@logout');


Route::get('/createAssignment', 'InstructorController@createAssignment');
Route::post('/createAssignment', 'InstructorController@postcreateAssignment');
Route::get('/numberofstudentsbysubject', 'InstructorController@ajaxGetnumberStudentsBySubject')->name('numberofstudentsbysubject');

Route::get('/registerInstructor', 'InstructorController@register');
Route::post('/registerInstructor', 'InstructorController@createUsers')->name('registerInstructor');
Route::get('/usuariosInstructor', 'InstructorController@users')->name('usuariosInstructor');

Route::get('/showSubjects', 'InstructorController@showSubjects');
Route::get('/showSubjectsStudent', 'StudentController@showSubjects');

Route::get('selectedSubjectStudent/{subject}', 'StudentController@selectedSubject');
Route::post('selectedSubjectStudent/{subject}', 'StudentController@selectedSubject');

Route::get('selectedSubject/{request}', 'InstructorController@selectedSubject');

Route::get('/showAssignmentsStudent/{subject_id}', 'StudentController@showAssignmentsStudent');
Route::post('/showAssignmentsStudent', 'StudentController@showAssignmentsStudent');



Route::get('/showAssignments/{subject_id}', 'InstructorController@showAssignments')->name('showAssignments');
Route::post('/showAssignments', 'InstructorController@showAssignments');



Route::get('/showStudentsFiles/subject/{subject_id}/assignment/{assignment_id}', 'StudentController@showStudentsFiles')->name('showStudentsFiles');
Route::post('/showStudentsFiles/subject/{subject_id}/assignment/{assignment_id}', 'StudentController@showStudentsFiles');

Route::get('/sendStudentFiles/subject/{subject_id}/assignment/{assignment_id}', 'StudentController@sendStudentFiles')->name('sendStudentFiles');
Route::post('/sendStudentFiles/subject/{subject_id}/assignment/{assignment_id}', 'StudentController@sendStudentFiles')->name('sendStudentFiles');

Route::get('/showResultsStudent/subject/{subject_id}/assignment/{assignment_id}', 'StudentController@showResultsStudent')->name('showResultsStudent');
Route::post('/showResultsStudent/subject/{subject_id}/assignment/{assignment_id}', 'StudentController@showResultsStudent')->name('showResultsStudent');

Route::get('/showResultsStudentSubjects', 'StudentController@showResultsStudentSubjects');


Route::get('/showResultsAssignmentsStudent/subject/{subject_id}', 'StudentController@showResultsAssignmentsStudent')->name('showResultsAssignmentsStudent');
Route::post('/showResultsAssignmentsStudent/subject/{subject_id}', 'StudentController@showResultsAssignmentsStudent')->name('showResultsAssignmentsStudent');

Route::get('/createUsers', 'AdminController@createUsers');
Route::post('/createUsers', 'AdminController@createUsers');

Route::get('/pdfuser/{id}', 'AdminController@pdfuser');

Route::get('/users', 'AdminController@index');
Route::post('/users', 'AdminController@index');

Route::get('createUsers/restablishpass/{token}', ['as' => 'restablishpass', 'uses' => 'Controller@restablishPass']);
Route::post('createUsers/restablishpass/remember', ['as' => 'remember', 'uses' => 'Controller@remember']);

Route::get('/perdida', 'AdminController@perdida')->name('perdida');
Route::post('/perdida', 'AdminController@perdida')->name('perdida');


Route::post('sendPerdidaEmail', ['as' => 'sendPerdidaEmail', 'uses' => 'AdminController@sendPerdidaEmail']);
Route::get('sendPerdidaEmail', ['as' => 'sendPerdidaEmail', 'uses' => 'AdminController@sendPerdidaEmail']);

Route::resource('subjects', 'SubjectsController');
Route::get('/relateSubjects/{subject_id}', 'SubjectsController@relateSubjects')->name('relateSubjects');
Route::post('/postrelateSubjects/{subject_id}', 'SubjectsController@postrelateSubjects')->name('postrelateSubjects');
Route::post('/relatedUserdestroy/{id}','SubjectsController@relatedUserdestroy')->name('relatedUserdestroy');


Route::post('/newpassword','Controller@newpassword')->name('newpassword');


Route::resource('alumnos', 'AlumnosController');

Route::resource('teacherassignment','TeacherAssigmentController');
Route::get('teacherassignmentadd','TeacherAssigmentController@add')->name('teacherassignmentadd');
Route::post('teacherassignmentcreate','TeacherAssigmentController@create')->name('teacherassignmentcreate');
Route::post('teacherassignmentedit','TeacherAssigmentController@saveedit')->name('teacherassignmentedit');

Route::resource('adminalumnos', 'AdminAlumnosController');
Route::resource('adminasignaturas', 'AdminSubjectsController');
Route::get('/adminrelatedsubjects/{subject_id}', 'AdminSubjectsController@relateSubjects')->name('adminrelatedsubjects');
Route::post('/postadminrelatedsubjects/{subject_id}', 'AdminSubjectsController@postrelateSubjects')->name('postadminrelatedsubjects');
#Route::resource('admin', 'AdminAlumnosController');
Route::get('/home', 'AdminAlumnosController@index')->name('home');
