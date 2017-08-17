@extends('layouts.layout')
@extends('layouts.top')

@section('content')
<script type="text/javascript">
      var index = 1;
      function showAlert(str){
        Alert(str);
      }
</script>

<style type="text/css"> 
#precourse2 {
    display:inline-block;
} 
#deletableLI button{
    width:10px;

}


</style>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Courses</div>
                <div class="panel-body">
                <table class="table table-striped" id="gradetable">
                  <thead>
                  <tr>
                  <th class="classname">Class</th>
                  <th class="grade">Grade Point</th>
                  <th class="waive">Waived</th>
                  </tr>
                  </thead> 
                </table>

                 <form class="form-inline" role="form" method="POST" action="{{ url('/sd') }}" id="form1">
                     {{ csrf_field() }}
                     <div id = "prereqCourses" >
                          @foreach($rule as $prereq)
                          <div class="form-group" id="prereqCourseSet">
                                <p id="coursename" type="text" class="form-control" name="coursename" style="width:120px;margin-right:30px;margin-bottom:10px;">{{$prereq}}</p>
                                <input id="{{$prereq}}" type="text" class="form-control" name="gpa" value="{{old('gradepoint')}}" style="width:100px;margin-right:170px;margin-bottom:10px;">
                                 <input type="checkbox" name="waive" id="waive" style="margin-bottom:10px;" onchange='javascript:
                                      var grade = document.getElementById("{{$prereq}}");
                                      if(this.checked == true){
                                          grade.value = "";
                                          grade.disabled = true;
                                      }
                                      else
                                          grade.disabled = false;
                                 '>
                          </div>
                          @endforeach
                      </div>
                          <table class="table table-striped" id="gradetable">
                            <thead>
                            <tr>
                            <th class="classname">Class</th>
                            <th class="grade">Grade Point</th>
                            </tr>
                            </thead> 
                          </table>
                          <div id = 'coursesWrap'>
                             
                             @foreach($pair as $ss)
                              <div class="form-group" id="course" style="margin-bottom:10px;">
                                    <input type="text" class="form-control" id = "{{$ss[2]}}" name="coursename1" style="width:120px;margin-right:80px;margin-bottom:10px;" value = "{{$ss[0]}}">
                                    <input type="text" class="form-control" id = "{{$ss[3]}}" name="gradepoint1" style="width:100px;margin-right:170px;margin-bottom:10px;" value = "{{$ss[1]}}"> 
                                    <button onclick='javascript:document.getElementById("course").remove();'>-</button>
                                    <input type="button" onclick = 'javascript:

                                        var a = document.createElement("a");

                                        a.innerHTML = document.getElementById("{{$ss[2]}}").value + " , " + document.getElementById("{{$ss[3]}}").value;

                                        var li = document.createElement("li");

                                        li.appendChild(a);

                                        if (a.innerHTML != " , ")
                                          document.getElementById("favoriteSearches").appendChild(li);
                                        else
                                          alert("Cannot save empty pair.");

                                        if (document.getElementById("deletableLI") !=null)
                                          document.getElementById("deletableLI").remove();

                                        
                                        var start = document.createElement("button");
                                        start.setAttribute("class","btn btn-primary");
                                        start.setAttribute("style","margin-right:1px;");
                                        start.innerHTML = "Start degee audit";
                                        
                                        var startli = document.createElement("li");
                                        startli.id = "deletableLI";
                                        startli.appendChild(start);
                                        document.getElementById("favoriteSearches").appendChild(startli);

                                    ' value = "+">

                                    
                             </div>
                             @endforeach
                          </div>

                          <div class="form-group" name="precourse2" style="float:right">
                                  <input type='button' class="btn btn-primary" id='showButton' value='Add Course' style = "margin-right: 481px" onclick='javascript:
                                       var oDiv = document.createElement("div");
                                       var input1 = document.createElement("input");
                                       var input2 = document.createElement("input");
                                       
                                       oDiv.id = index;
                                       var deletebutton = document.createElement("button");
                                       
                                       deletebutton.innerHTML = "-";
                                       deletebutton.onclick = function(){
                                          oDiv.remove();
                                       };
                                       input1.setAttribute("type", "text");
                                       input2.setAttribute("type", "text");
                                       oDiv.style.marginBottom= "10px";
                                       input1.style.marginBottom= "10px";
                                       input1.style.width= "120px";
                                       input1.style.marginRight= "85px";
                                       input1.className = "form-control";
                                       input1.name = "coursename" + index;
                                       input1.id = "coursename" + index;
                                       input2.style.marginRight= "170px";
                                       input2.style.width= "100px";
                                       input2.style.marginBottom= "10px";
                                       input2.className = "form-control";
                                       input2.name = "gradepoint" + index;
                                       input2.id = "gradepoint" + index;
                                       oDiv.className = "form-group";

                                       var add = document.createElement("input");
                                       add.value = "+";
                                       add.setAttribute("type","button");
                                       add.onclick = function(){

                                          var a = document.createElement("a");

                                          a.innerHTML = document.getElementById(input1.id).value + " , " + document.getElementById(input2.id).value;

                                          var li = document.createElement("li");
                                          var len = document.getElementById("favoriteSearches").children.length;

                                          var flag = 0;
                                          for(i = 0; i<len;i++){
                                            var text = document.getElementById("favoriteSearches").children[i].children[0].innerHTML;
                                            if (text.split(" , ")[0] == input1.value){
                                                document.getElementById("favoriteSearches").children[i].children[0].innerHTML = a.innerHTML;
                                                flag+=1;
                                            }
                                          }
                                          
                                          if (flag==0){


                                            li.appendChild(a);


                                            document.getElementById("favoriteSearches").appendChild(li);
                                          }

                                          if (document.getElementById("deletableLI") !=null)
                                            document.getElementById("deletableLI").remove();

                                          
                                          var start = document.createElement("button");
                                          start.setAttribute("class","btn btn-primary");
                                          start.setAttribute("style","margin-right:1px;");
                                          start.innerHTML = "Start degee audit";
                                          
                                          var startli = document.createElement("li");
                                          startli.id = "deletableLI";
                                          startli.appendChild(start);
                                          document.getElementById("favoriteSearches").appendChild(startli);

                                       };

                                       oDiv.appendChild(input1);
                                       oDiv.appendChild(input2);
                                       
                                       oDiv.appendChild(deletebutton)
                                       oDiv.appendChild(add);
                                       var wrapper = document.getElementById("coursesWrap");
                                       wrapper.appendChild(oDiv);
                                       index = index + 1;
                                                                            
                                  '>
                                  <input type = "button" class="btn btn-primary" value= "Save All Courses"style = "margin-right: 0px;"onclick='javascript:
                                      
                                      var prereq = document.getElementById("prereqCourses").children;
                        
                                      var triplets = (prereq.length);
                                      for(count = 0;count<triplets;count++){

                                        var course = prereq[count].children[0].innerHTML;

                                        var gpa = prereq[count].children[1].value;
                                        
                                        var text = "";
                                        if (prereq[count].children[2].checked == true)
                                            text = course+" , "+"Waived";
                                        else
                                            text = course + " , "+gpa;
                                        
                                        var a = document.createElement("a");
                                        a.onclick = function(){
                                          li.remove();
                                          if (document.getElementById("deletableLI") !=null)
                                            document.getElementById("deletableLI").remove();

                                          
                                          var start = document.createElement("button");
                                          start.setAttribute("class","btn btn-primary");
                                          start.setAttribute("style","margin-right:1px;");
                                          start.innerHTML = "Start degee audit";
                                          
                                          var startli = document.createElement("li");
                                          startli.id = "deletableLI";
                                          startli.appendChild(start);
                                          document.getElementById("favoriteSearches").appendChild(startli);

                                        }
                                        a.innerHTML =text;
                                        var li = document.createElement("li");

                                        var len = document.getElementById("favoriteSearches").children.length;

                                        var flag = 0;
                                        for(i = 0; i<len;i++){
                                          var text = document.getElementById("favoriteSearches").children[i].children[0].innerHTML;

                                          if (text.split(" , ")[0] == course){
                                              document.getElementById("favoriteSearches").children[i].children[0].innerHTML = a.innerHTML;
                                              flag+=1;
                                          }
                                        }
                                        
                                        if (flag==0){
                                          li.appendChild(a);
                                          document.getElementById("favoriteSearches").appendChild(li);
                                        }
                                      }




                                      for(count = 1; count<=index-1;count++){
                                          
                                          var a = document.createElement("a");
                                          a.onclick = function(){
                                            li.remove();
                                          }
                                          var input1 = document.getElementById("coursename"+count);
                                          var input2 = document.getElementById("gradepoint"+count);
                                          a.innerHTML = input1.value + " , " + input2.value;

                                          var li = document.createElement("li");
                                          
                                          var len = document.getElementById("favoriteSearches").children.length;

                                          var flag = 0;
                                          for(i = 0; i<len;i++){
                                            var text = document.getElementById("favoriteSearches").children[i].children[0].innerHTML;
                                            if (text.split(" , ")[0] == input1.value){
                                                document.getElementById("favoriteSearches").children[i].children[0].innerHTML = a.innerHTML;
                                                flag+=1;
                                            }
                                          }
                                          
                                          if (flag==0){


                                            li.appendChild(a);


                                            document.getElementById("favoriteSearches").appendChild(li);
                                          }
                                      }

                                      if (document.getElementById("deletableLI") !=null)
                                        document.getElementById("deletableLI").remove();

                                      
                                      var start = document.createElement("button");
                                      start.setAttribute("class","btn btn-primary");
                                      start.setAttribute("style","margin-right:1px;");
                                      start.innerHTML = "Start degee audit";
                                      

                                      start.onclick = function(){

                                        var formf = document.createElement("form");
                                        formf.id = "fo";
                                        form = document.getElementById("fo");
                                        form.setAttribute("role","form");
                                        form.setAttribute("method","POST");
                                        form.setAttribute("action","{{url('/degreeaudit')}}");

                                        
                                        var hid = document.createElement("input");
                                        
                                        hid.setAttribute("name","_token");
                                        
                                        hid.setAttribute("type","hidden");

                                        hid.setAttribute("value","{{ csrf_token() }}");

                                        form.appendChild(hid);
                                        var len = document.getElementById("favoriteSearches").children.length;
                                        for( count = 0; count<len-1;count++){
                                            var inputP = document.createElement("input");
                                            
                                            
                                            inputP.setAttribute("id",count);
                                            inputP.setAttribute("name",count);
                                            inputP.setAttribute("value", document.getElementById("favoriteSearches").children[count].children[0].innerHTML);
                                            form.appendChild(inputP);
                                        }
                                     
                                        var submit = document.createElement("input");
                                        submit.setAttribute("type","submit");
                                        submit.setAttribute("name","submit");
                                        submit.setAttribute("id","submit");
                                        submit.setAttribute("value","start");
                                        
                                        form.submit();
                                        
                                      }

                                      var startli = document.createElement("li");
                                      startli.id = "deletableLI";
                                      startli.appendChild(start);
                                      document.getElementById("favoriteSearches").appendChild(startli);
                                  '>
                          </div>

                </form>
                 </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
