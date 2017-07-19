<% include ../partials/header  %>

<div class="panel panel-primary">
  <div class="panel-heading"><%= report.taskTitle %></div>
  <div class="panel-body" id="reportBody">
    <div id="author" class="pull-left col-md-10"><em><strong>Submitted by: <%= report.author.username %></strong></em></div>
    <div id="date" class="pull-right col-md-2"><%= report.date %></div>
    <div id="desc" class="">
      <% if(currentUser && report.author.id.equals(currentUser._id)){ %>
        <a class="btn btn-sm btn-warning pull-right btn-crud" href="/reports/<%= report._id %>/edit">Edit</a>
        <form action="/reports/<%= report._id %>?_method=DELETE" method="POST">
          <button class="btn btn-sm btn-danger pull-right btn-crud">
            Delete
          </button>          
        </form>
        <% }; %>
      <p class="panel panel-heading">
        Report Description:
        <div id="test" class="cil-md-12">
          

          <hr>
        <% report.tests.forEach(function(test){ %>
                  <div class="col-md-2 panel-body">
            <input name="testNum" id="testNum" class="formInput form-control" type="text" value="1" readonly>
          </div>
          <div class="col-md-2 panel-body">
            <input id="station" required class="formInput form-control" type="text" name="test[station]" value="<%= test.station %>" readonly>
            <input id="testName" required class="formInput form-control" type="text" name="test[name]" value="<%= test.name %>" readonly>
          </div>
          <div class="panel-body col-md-6">
            <textarea type="text" name="test[params]" id="editor" class="form-control" readonly><%= test.params %></textarea>
              <script>
                  $("#editor").wysiwyg();
              </script>
          </div>
          <div class="col-md-2 list-group">
            <li class="list-group-item">
              YA95100006 <span class="glyphicon glyphicon-ok pull-right
"></span>
            </li>
            <li class="list-group-item">
              YA95100007
            </li>
            <li class="list-group-item">
              YA95100008
            </li>
          </div>
        <% }); %>
        </div>
        
        <div class="panel-footer col-md-12">
        <% report.comments.forEach(function(comment){ %>
          <p id="commentP">
            <strong><%= comment.author.username  %></strong>: <%= comment.text %>
          </p>
        <% }); %>
        <div>
      </p>
    </div>
  </div>
</div>
<a  class="btn btn-xs btn-success pull-right col-lg-5 addButton" href="/reports/<%= report._id %>/comments/new">Add New Comment</a>
<script>
// testP = document.getElementById("test");
// var numbers = [4, 9, 16, 25];

// function addTest(item, index) {
//     testP.innerHTML = testP.innerHTML + "index[" + index + "]: " + item + "<br>"; 
// }
// </script>
<% include ../partials/footer  %>