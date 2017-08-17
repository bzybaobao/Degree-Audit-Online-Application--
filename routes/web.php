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
use Illuminate\Http\Request;

/*
Route::get('/auth/register', function () {
	return view('/auth/register');
});

Route::post('/auth/register/insert', 'UsersController@store');
*/
Route::get('/','UsersController@readMajors');

Route::get('/home','UsersController@index');
/*
Route::get('/index',function(){
	return view('/index');
});
*/
//Route::post('/degreeaudit','UsersController@degreeAudit');

Route::post('/sendRule','UsersController@sendRule');

Route::post('/index','UsersController@degreeAudit');

Auth::routes();
Route::get('/course',function(){


     return view('/course');
});

/*
Route::prefix('auth')->group(function() {
	Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
	Route::post('/login', 'Auth\LoginController@login')->name('login.submit');
	Route::get('/', 'Controller@index')->name('dashboard');
});*/

Route::post('/degreeaudit', "UsersController@degreeAuditResultGenerator");

/*
(Request $request){
	 $array = $request -> all();
	 $cart = array();
	 
	 for($c = 0;$c<sizeof($array)/2;$c++){
	 	array_push($cart, explode(" , ",$array[$c]));
	 }

	 $track = $request->session()->get('curtrack');

     $index = 1;
     $index1 = 1;
     $corenum = DB::table('majors')->where('majors.major_name',"=", "Data Science")->first()->core_count;
     $totalnum =  DB::table('majors')->where('majors.major_name',"=", "Data Science")->first()->elective_count + $corenum;
   
     $cores = DB::table('core_courses') 
                    ->where('core_courses.mid','=',$track)
                    ->join('courses','courses.course_name','=','core_courses.cid')
                    ->select('courses.course_name as name','courses.prerequisite as prereq')
                    ->get();
 	
     $coreElectives = DB::table('core_electives') 
                    ->where('core_electives.mid','=',$track)
                    ->join('courses','courses.course_name','=','core_electives.cid')
                    ->select('courses.course_name as name','courses.prerequisite as prereq')
                    ->get();

     $prerequisites = array();


     $corelecourses = array();

     $corecourses = array();

     foreach($coreElectives as $coreElective)
     {
         array_push($prerequisites,$coreElective->prereq);
         array_push($corelecourses,$coreElective->name);
     }

     foreach($cores as $core)
     {
         array_push($prerequisites,$core->prereq);
         array_push($corecourses,$core->name);
     }

     $precourses = array_unique($prerequisites);



     $took = 0;
    
     foreach($cart as $pair){

     	$course = trim($pair[0]);
     	echo $course;
     	foreach($corecourses as $core){
     		if ($core==$course)
     			$took++;
     	}


     }
     /*
     $totalGP= 0.0;
     $totalCourses = 0;
     $totalMajorGP = 0.0;
     $totalMajorCourses = 0;
     foreach($cart as $pair){
     	$totalCourses++;
     	$course = trim($pair[0]);
     	//$gpa = floatval(trim($pair[0]));
     	//$totalGP+=$gpa;

     	foreach($corecourses as $core){
     		if ($core->id == $course){

     			$totalMajorCourses++;
     			//$totalMajorGP+=$gpa;
     		}
     	}

     	foreach($corelecourses as $coreE){
     		if ($coreE->id == $course){

     			$totalMajorCourses++;
     			//$totalMajorGP+=$gpa;
     		}
     	}

     	//if(floatval($gpa)>0){
     		//$totalCourses++;
     	//}
     }

     $result = "";
     //$gpa = $totalGP/$totalCourses;
     if($totalMajorCourses!=0)
	     $majorGPA = $totalMajorGP/$totalMajorCourses;
	 else 
	 	$result+="Need more major courses";
     if($totalCourse < $totalnum)
     	$result += "Need more ".$totalnum-$totalCourse." creadits to graduate.";
     else if($gpa < 3.0)
     	$result += "GPA ".$gpa." too low to graduate";  

     $left = $totalnum - sizeof($cart);
     return View::make('result')->with('result',"You still need ".$took." courses to graduate."); 
       


   
});
*/
Route::prefix('admin')->group(function() {
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    Route::resource('course','testController');
});
     Route::resource('course','testController');
