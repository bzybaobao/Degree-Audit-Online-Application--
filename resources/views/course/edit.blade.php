@extends('layouts.top')

@section('header')
<h2>edit course</h2>
@stop



@section('content')
<h2> Add New Course</h2>
  
  {!! Form::model($course,['route'=>['course.update',$course->id],'method'=>'PATCH', 'class'=>'form-horizontal'])!!}
  <div class ="form-group">
  
    {!! Form ::label('id','id',['class'=>'control-label col-md-1'])!!}
    <div class="col-md-5">
        {!!Form::text('id',null,['class'=>'form-control'])!!}
        {!! $errors->has('id')?$errors->first('id'):'' !!}
    </div>
    </div>
   

    <div class ="form-group">
    
     {!! Form ::label('course_name','course_name',['class'=>'control-label col-md-1'])!!}
    <div class="col-md-4">
       
         {!!Form::text('course_name',null,['class'=>'form-control'])!!}
         {!! $errors->has('course_name')?$errors->first('course_name'):'' !!}
    </div>
    </div>


    <div class ="form-group">
    
    {!! Form ::label('description','description',['class'=>'control-label col-md-2'])!!}
    <div class="col-md-8" >
    
          {!!Form::textarea('description',null,['class'=>'form-control'])!!}
           {!! $errors->has('description')?$errors->first('description'):'' !!}
    </div>
    </div>

     <div class ="form-group">
    
    {!! Form ::label('prerequisite','prerequisite',['class'=>'control-label col-md-1'])!!}
    <div class="col-md-4">
       
        {!!Form::text('prerequisite',null,['class'=>'form-control'])!!}
        {!! $errors->has('prerequisite')?$errors->first('prerequisite'):'' !!}
    </div>
    </div>

    <div class ="form-group">
    	<div class="col-md-offset-1 col-md-10">
    	
    	 {!!Form::submit('Save',['class'=>'btn btn-primary'])!!}
    	</div>
    </div>
  
    {!! Form::close()!!}

    @stop


