<?php

namespace App\Http\Controllers;

use illuminate\html;
use Illuminate\Http\Request;
use App\Course;
use DB;

class testController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $courses=DB::table("courses")->get();
        return view("course/admin")->with("courses",$courses);
        //
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("course.create");
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
               'id'=>'Required',
               'course_name'=>'Required',
               'description'=>'Required',
               'prerequisite'=>'Required'

            ]);

        $course =$request->all();
        Course::create($course);
        redirect('course');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course=Course::find($id);
        return view('course/edit',compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request,[
               'id'=>'Required',
               'course_name'=>'Required',
               'description'=>'Required',
               'prerequisite'=>'Required'

            ]);
        $course= Course::find($id);
        $courseUpdate=$request->all();
        $course->update( $courseUpdate);
        return redirect('course');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::find($id);
        $course -> delete();
        return redirect("course");
      
    

    }

        //
    
}
