@extends('layouts.top')
@extends('layouts.layout')

<style type="text/css">
select{
    width: 300px;
}

.btn-primary{
	float: right;
}

#notice {
    display:inline-block;margin-right:10px;
} 
#checkbox {
    display:inline-block;margin-right:10px;
} 
#confirmbtn {
    display:inline-block;
} 

#disclaimer {
    font-size: 15px;
}

</style>


@section('content')
	
    <div class="jumbotron">
        <div class="container">
            <h1 class = "welcometxt">Welcome to Degree Auditor</h1>
            <h2 class = "welcometxt">Please select your major or specific track</h2>
			<br>
            <form class="form-horizontal" role="form" method="POST" id = "form" action="{{ url('/sendRule') }}">
                {{ csrf_field() }}
                <select name="major" id = "major" onchange='javascript:
                    if(this.options[this.selectedIndex].value !="0" && document.getElementById("checkbox").checked){
                        document.getElementById("confirmbtn").type = "submit";
                    } else {
                        document.getElementById("confirmbtn").type = "button";
                    }
                '>
                    <option value = "0" selected=""></option>
                    @foreach($majors as $major)
                    <option value = "{{$major->id}}" >{{$major->major_name}}</option>
                    @endforeach            
                </select>
    			<br>
    			<br>
                <p id= "notice"> Check this box to agree the disclaimer.</p>
                <input type = "checkbox" id = "checkbox" onchange='javascript:
                    var dropdown = document.getElementById("major");
                    if(this.checked && dropdown.options[dropdown.selectedIndex].value != "0"){
                        document.getElementById("confirmbtn").type = "submit";
                    }
                    else{
                        document.getElementById("confirmbtn").type = "button";
                    }
                '> 
                <input class="btn btn-primary btn-lg" id = "confirmbtn" type="button" name="submit" value="Confirm" onclick='javascript:
                    if(this.type!="submit"){
                        var errmsg = "";
                        if(!document.getElementById("checkbox").checked){
                            alert("Please check the box.");
                        }
                        var dropdown = document.getElementById("major");

                        if(dropdown.options[dropdown.selectedIndex].value == "0"){
                            alert("Please select a major.");
                        }
                    }
                '>
            </form>
        </div>
    </div>
    <footer class="jumbotron">
        <div class="container">
            <h6>Disclaimer:</h6>
            <p id = "disclaimer">This application is for academic practice only. For graduation purpose, please contact your academic advisor for a formal degree audit.</p>
        </div>
    </footer>


@stop


    