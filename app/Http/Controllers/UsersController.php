<?php

namespace App\Http\Controllers;

use DB;
use View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
class UsersController extends Controller
{
    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        return view('home');
    }


    public function store(Request $request)
    {
    	$name = $request->name;
    	$email = $request->email;
    	$password = $request->password;
    	$data = array('name'=>$name,'email'=>$email,'password'=>$password);
    	DB::table('usersses')->insert($data);
    	return view('/home');
    }

    public function readMajors()
    {
        //updated
        $majors = DB::table('majors') -> get();
        return view('/home',compact('majors',$majors));
    }

    //parameter id is the major id selected by the user
    public function getPrereq(int $id)
    {
        //updated
        $cores = $this->getCore($id);
        $coreElectives = $this->getCoreElective($id);
        $prerequisites = array();

        foreach($coreElectives as $coreElective)
        {
            array_push($prerequisites,$coreElective->prereq);
        }

        foreach($cores as $core)
        {
            array_push($prerequisites,$core->prereq);
        }

        return (array_unique($prerequisites));
    }

    public function getCore(int $id){
        //updated
        $cores = DB::table('core_courses') 
                    ->where('core_courses.mid','=',$id)
                    ->join('courses','courses.course_name','=','core_courses.cid')
                    ->select('courses.id','courses.prerequisite as prereq')
                    ->get();

        return ($cores);
    }

    public function getCoreElective(int $id){

        $coreElectives = DB::table('core_electives') 
                    ->where('core_electives.mid','=',$id)
                    ->join('courses','courses.course_name','=','core_electives.cid')
                    ->select('courses.id','courses.prerequisite as prereq')
                    ->get();

        return ($coreElectives);
    }

    public function sendRule(Request $request)
    {
        //updated
        $id = Input::get('major');
        $request->session()->put('curtrack', $id);
        $rule = $this-> getPrereq($id);
        
        
        $inputArray = $request->all();
            
        $len = (sizeof($inputArray)-4)/2;
        $CGArray = array();
        for($i=1; $i<=$len;$i++){
            $uid = Auth::user()->id;
            $cid = $inputArray["coursename".$i];
            $grade = $inputArray["gradepoint".$i];
            if ($cid!="" && $grade!=""){   
                $rows = DB::table('completed_courses')
                    ->where([['cid',$cid],['uid',$uid]])
                    ->get();

                if (sizeof($rows)==0){
                    $data = array('uid'=>$uid,'cid'=>$cid,'grade'=>$grade);
                    DB::table('completed_courses')->insert($data);
                }else{
                    DB::table('completed_courses')
                    ->where([['cid',$cid],['uid',$uid]])
                    ->update(['grade'=>$grade]);
                }
                $buffer = array();
                array_push($buffer,$cid);
                array_push($buffer,$grade);
                array_push($buffer,"coursename".$i);
                array_push($buffer,"gradepoint".$i);
                array_push($CGArray,$buffer);
            }               
        }
           
        if (sizeof($CGArray)==0)
            $pair = array();
        else
            $pair = $CGArray;
        

        return view('/index')->with('rule',$rule)->with('pair',$pair);
    }

    public function getSearch(int $id)
    {
        $search = DB::table('histories') 
                    ->where('id',"=", $id)
                    -> first();
        $text = $search->courses;
        $coursepairArray = array();
        $pairArray = $this->split($text);
        foreach($pairArray as $pair){
            $buffer = $this->splitComma($pair);
            array_push($coursepairArray, $buffer);
        }
        //TODO HAVE ALL COURSES EXCEPT PREREQ
        //{{6313,3.3},{6314,3.3},{6315,3.3}}
        return $coursepairArray;
        //return view('/index',compact('courses',$coursepairArray));
    }

    public function getAllCourses(){
        $search = DB::table('courses')
                    ->select('id as id') 
                    ->get();
        $buffer = array();
        foreach($search as $id){
            array_push($buffer,$id);
        }
        return $buffer;
    }

    public function saveSearch(Request $request)
    {
        if(Auth::Check()){
            $inputArray = $request->all();
            
            $len = (sizeof($inputArray)-4)/2;
            $CGArray = array();
            for($i=1; $i<=$len;$i++){
                $uid = Auth::user()->id;
                $cid = $inputArray["coursename".$i];
                $grade = $inputArray["gradepoint".$i];
                if ($cid!="" && $grade!=""){   
                    $rows = DB::table('completed_courses')
                        ->where([['cid',$cid],['uid',$uid]])
                        ->get();

                    if (sizeof($rows)==0){
                        $data = array('uid'=>$uid,'cid'=>$cid,'grade'=>$grade);
                        DB::table('completed_courses')->insert($data);
                    }else{
                        DB::table('completed_courses')
                        ->where([['cid',$cid],['uid',$uid]])
                        ->update(['grade'=>$grade]);
                    }
                    $buffer = array();
                    array_push($buffer,$cid);
                    array_push($buffer,$grade);
                    array_push($buffer,"coursename".$i);
                    array_push($buffer,"gradepoint".$i);
                    array_push($CGArray,$buffer);
                }               
            }
           
            return $CGArray;
        }
        else{
            echo "Please log in to save.";
        }
    }

    public function degreeAudit(Request $request)
    {
        
        
        if(Input::get('save')){
            
            $pair = $this-> saveSearch($request);
            $rule = $this-> getPrereq($request->session()->get('curtrack'));
            return view("/index")->with("rule",$rule)->with("pair",$pair);
            //return view('/index', compact("rule",$rule));
        }else{
          
            $this->degreeAuditResultGenerator($request);
        }

    }

    public function insertMajor(string $majorName){
        $data = array('majorName'=>$majorName);
        DB::table('rules')->insert($data);
    }

    public function deleteRule(Array $data){
        $mid = $data["mid"];
        DB::table('rules')->where('mid', '=', $mid)->delete();
        DB::table('major')->where('majorName', '=', $mid)->delete();

    }

    public function updateRule(Array $data){
    
        DB::table('rules')
        ->where('id',"=",$data->mid)
        ->update(['core'=>$core,"coreElective"=>$coreElective,"coreCount"=>$coreCount,"prerequisite"=>$prereq,"elective"=>$elective]);
    }

    public function insertRule(Array $data){

        DB::table('rules')->insert($data);

    }


    public function showRule(Request $request){



        $row = DB::table('rules')->where('mid',"=","mid")->first();
    }


    public function ruleForm(Request $request){
        $majorName = $request->majorName;
        $this->insertMajor($majorName);

        $search = DB::table('majors') 
                    ->where('majorName',"=", $majorName)
                    -> first();

        $maid = $search->id;

        $inputArray = $request->all();
        //TODO CHECK
        $coreElectiveArray=array();
        $coreArray=array();
        $prereqArray=array();
        $degreeElectiveArray = arrar();
        foreach($inputArray as $input){
            if(strpos($input, 'coreCourse')){
                array_push($coreArray, $input);
            }
            if(strpos($input, 'coreElective')){
                array_push($coreElectiveArray, $input);
            }
            if(strpos($input, 'prereq')){
                array_push($prereqArray, $input);
            }
            if(strpos($input, 'degreeElective')){
                array_push($degreeElectiveArray, $input);
            }
        }
        $core = $this->combine($coreArray);
        $coreElective = $this->combine($coreElectiveArray);
        $prereq = $this->combine($prereqArray);
        $elective =$this->combine($degreeElectiveArray);

        $data = array('mid'=>$mid,'core'=>$core,"coreElective"=>$coreElective,"coreCount"=>$coreCount,"prerequisite"=>$prereq,"elective"=>$elective);

        /*
            if insert
                $this->insertRule($data)
            if update
                $this->updateRule($data)
            if delete
                $this->deleteRule($data)
        */ 
    }

    public function getSeachesClassNames(array $searches){
        $sets = array();
        foreach($searches as $seach){
            //TODO
            $text=$seach -> text;
            $set=array();
            foreach($this->split($text) as $tuple){
                $name = $this->splitComma($tuple)[0];
                array_push($set, $name);
            }
            array_push($sets, $set);
        }
        return $sets;
    }

    public function split(string $str){
        return explode("|",$str);
    }
    public function combine(array $courses){
        return implode('|',$courses);
    }
    public function splitComma(string $str){
        return explode(",",$str);
    }
    public function combineComma(array $courses){
        return implode(',',$courses);
    }

    public function degreeAuditResultGenerator(Request $request){

         $track = $request->session()->get('curtrack');

         $index = 1;
         $index1 = 1;
         $corenum = DB::table('majors')->where('id', $track)->value('core_count');
         $totalnum =  $corenum + DB::table('majors')->where('id', $track)->value('elective_count');
       
         
         $precourses = $this->getPrereq($track);
        
         $core = DB::table('core_courses')->where('mid', $track)->get(); 
         $corecourses = array();
         foreach($core as $c){

            array_push($corecourses,$c->cid);
         }
      
         $corele = DB::table('core_electives')->where('mid', $track)->get();
         $corelecourses = array();
         foreach($corele as $c){

            array_push($corelecourses,$c->cid);
         }

         $prenum = sizeof($precourses);
         $prenum1 = 0;
         $premax_gpa = 0.0;

         $corearray = array();
         $corelearray = array();
         $elearray = array();
         $result;

         $waivenum = 0;

         $all = $request->all();
         foreach($all as $a){
            if(strpos($a," , ")){
                $coursename = explode(" , ",$a)[0];
                $gradepoint = explode(" , ",$a)[1];

                if($a[0] == "5" || in_array($coursename,$precourses)){
                    if(!is_null(explode(" , ",$a)[1]))
                    {
                        $prenum1++;
                        if(explode(" , ",$a)[1]!="Waived"){

                            $premax_gpa = max($premax_gpa,explode(" , ",$a)[1]);
                        }
                    }
                } 
                else{ 
                   if(in_array($coursename, $corecourses)){
                       $corearray[$coursename] = $gradepoint;
                   }
                   else if (in_array($coursename, $corelecourses)) {
                       $corelearray[$coursename] = $gradepoint;
                   }
                   else{
                       $elearray[$coursename] = $gradepoint;
                   }



                }
            }

         }

         if($prenum1 < $prenum){
            $result = 'You did not take all prerequistes';
            return View::make('result')->with('result',$result); 
         }
    

         
         if(sizeof($corearray) < sizeof($corecourses)){
                  
                $result = "You have not taken core courses: ";  
                $keys = array_keys($corearray);
               
                 foreach ($corecourses as $corecourse) {
                    if(!in_array($corecourse, $keys)){
                       $result = $result.$corecourse." ";
                    }
                 }
                 return View::make('result')->with('result',$result); 
         }
         

         if(sizeof($corelearray) < $corenum - sizeof($corecourses)){
             
                $keys = array_keys($corelearray);
                $more = $corenum - sizeof($corecourses) - sizeof($corelearray);
                $result = "You have to take {$more} more course/courses among ";

                 foreach ($corelecourses as $corele) {
                    if(!in_array($corele, $keys)){
                       $result = $result.$corele." ";
                    }
                 } 
                 return View::make('result')->with('result',$result); 
         }


        $more = $totalnum - sizeof($corelearray) - sizeof($corearray) - sizeof($elearray) - ($premax_gpa > 0? 1:0);  
        
         if($more > 0)
         {
                $result = "You have to take {$more} more elective course/courses";
                return View::make('result')->with('result',$result); 
         }

         $coregpa = 0.0;
         $totalgpa = 0.0;

         rsort($corearray);
         rsort($corelearray);
         rsort($elearray);

         foreach ($corearray as $gpa) {
             $coregpa += $gpa;
         }
        
       
         $index2 = 0;

         for ($index2=0; $index2< $corenum- sizeof($corearray); $index2++) { 
             $coregpa += $corelearray[$index2];
         }

         $totalgpa += $coregpa;
         $new = array();
          
         foreach ($elearray as $ele) {
             $new[] = $ele;
         }
        
         for (; $index2<sizeof($corelearray); $index2++) { 
              $new[] = $corelearray[$index2];
         }

         if ($premax_gpa > 0) {
             $new[] = $premax_gpa;
         }

         rsort($new);
         
         for ($i=0; $i<$totalnum-$corenum;$i++) { 
             $totalgpa += $new[$i];
         }
         
         $totalgpa = $totalgpa*1.0 / $totalnum;

         $coregpa = $coregpa*1.0 / $corenum;

         if($totalgpa >= 3.19 || ($totalgpa>=3.0 && $coregpa>= 3.3) || ($coregpa > 3.0 && $more <= -1)){
            $result = "Cong, you passed the degree audit";
            return View::make('result')->with('result',$result); 
         }

         else{
            $result = "Sorry, you did not pass the degree audit because of the low gpa";
            return View::make('result')->with('result',$result); 

        }
         
    }
    
}
