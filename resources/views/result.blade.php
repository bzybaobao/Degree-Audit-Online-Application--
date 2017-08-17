@extends('layouts.top')
@extends('layouts.layout')

@section('content')
   <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default" style="width:750px; height:600px;">
                <div class="panel-heading">Audit Degree Result</div>
                <div class="panel-body">
                   <p><?=$result?></p>
            
                </div>
            </div>
        </div>
    </div>

@stop