<div class="panel panel-primary">
  <div class="panel-heading">New Task</div>
  <form action="/reports" method="POST">
  <div class="panel-body" id="reportBody">
    <div id="desc" class="">
      <p class="panel panel-heading">
        Report Description:
        <div id="test" class="cil-md-12 panel-heading">
            <span class="label label-primary">Task Title</span>
            <input id="taskTitle" required class="formInput form-control" type="text" name="taskTitle" placeholder="Task Title">
        </div>
        <div class="col-md-2 panel-body">
              <span class="label label-primary">Station:</span>
              <select id="station" class="formInput form-control" name="test[station]">
                <option value="TalynA-AD">TalynA-AD</option>
                <option value="TalynA-AY">TalynA-AY</option>
                <option value="M - Calibration">M - Calibration</option>
                <option value="Robot">Robot</option>
              </select> 
        </div>
            <div class="col-md-2 panel-body">
              <span class="label label-primary">Test Name:</span>
              <input id="testName" required class="formInput form-control" type="text" name="test[name]" placeholder="Test Name">
            </div>
          <div class="panel-body col-md-6">
              <span class="label label-primary">Details:</span>
			  
            <textarea type="text" name="test[params]" id="editor" class="form-control"></textarea>
              
          </div>
        <div class="line col-md-2">
                <span class=" label label-primary">Chip S\N:</span>
                <div class="button-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" name="test[chipSN]">
                        <span class="glyphicon glyphicon-cog">Chip</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                      <li><a href="#" class="small" data-value="YA95100006" tabIndex="-1"><input name="chipSN" type="checkbox"/>&nbsp;YA95100006</a></li>
                      <li><a href="#" class="small" data-value="YA95100007" tabIndex="-1"><input name="chipSN" type="checkbox"/>&nbsp;YA95100007</a></li>
                      <li><a href="#" class="small" data-value="YA95100008" tabIndex="-1"><input name="chipSN" type="checkbox"/>&nbsp;YA95100008</a></li>
                      <li><a href="#" class="small" data-value="YA95100009" tabIndex="-1"><input name="chipSN" type="checkbox"/>&nbsp;YA95100009</a></li>
                      <li><a href="#" class="small" data-value="YA95100010" tabIndex="-1"><input name="chipSN" type="checkbox"/>&nbsp;YA95100010</a></li>
                      <li><a href="#" class="small" data-value="YA95100011" tabIndex="-1"><input name="chipSN" type="checkbox"/>&nbsp;YA95100011</a></li>
                    </ul>
               </div>  
            </div>
        
        <div class="col-md-12">
            <button class="pull-right btn btn-xs btn-primary col-lg-5 addButton" onclick="test.forEach(addTest)">
              Add Test!
            </button>
        <div>
      </p>
    </div>

  </div>
        <div class="col-md-12" id="subBTN">
        <hr>
          <button class="btn btn-lg btn-success">Submit!</button>
      </div>
      </form>
</div>