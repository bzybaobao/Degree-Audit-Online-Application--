@extends('layouts.top')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Course CRUD</div>

                <div class="panel-body">
                    <a href ="course/create" class ="btn btn-primary">Add new</a>
                    <table class ="table table-bordered table-responsive" style="margin-top: 10px;">

                    <thred>
                        <tr>
                            <th>id</th>
                            <th>course_name</th>
                            <th>description</th> 
                            <th>prerequisite</th>
                            <th colspan="2">action</th>

                        </tr>
                      </thred>

                      <tbody>
                      @foreach( $courses as $course)
                          <tr>
                            <td>{{$course->id}}</td>
                             <td>{{$course->course_name}}</td>
                              <td>{{$course->description}}</td>
                               <td>{{$course->prerequisite}}</td>
                               <td>
                                   <a href ="{{route('course.edit',$course->id)}}" class="btn btn-success">Edit</a>
                               </td>
                               <td>
                                {{ Form::open(['method' => 'DELETE', 'route' =>['course.destroy', $course->id]]) }}
                                  {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                  {{ Form::close() }}

                               </td>
                               
                          </tr>
                          @endforeach
                        
                      </tbody>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
