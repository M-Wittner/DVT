<?php $this->load->view('includes/header'); ?>

     <div class="row">
          <div class="col-lg-4 col-sm-4 well">
          <?php 
          $attributes = array("class" => "form-horizontal", "id" => "login_view", "name" => "loginform");
          echo form_open("login/index", $attributes);?>
          <fieldset>
               <legend>Login</legend>
               <div class="form-group">
               <div class="row colbox">
               <div class="col-lg-4 col-sm-4">
                    <label for="username" class="control-label">Username</label>
               </div>
               <div class="col-lg-8 col-sm-8">
                    <input class="form-control" id="username" name="username" placeholder="Username" type="text" value="<?php echo set_value('username'); ?>" autofocus />
                    <span class="text-danger"><?php echo form_error('username'); ?></span>
               </div>
               </div>
               </div>
               
               <div class="form-group">
               <div class="row colbox">
               <div class="col-lg-4 col-sm-4">
               <label for="password" class="control-label">Password</label>
               </div>
               <div class="col-lg-8 col-sm-8">
                    <input class="form-control" id="password" name="password" placeholder="Password" type="password" value="<?php echo set_value('password'); ?>" />
                    <span class="text-danger"><?php echo form_error('password'); ?></span>
               </div>
               </div>
               </div>
                              
               <div class="form-group">
               <div class="col-lg-12 col-sm-12 text-center">
                    <input id="btn_login" name="btn_login" type="submit" class="btn btn-default" value="Login" />
                    <input id="btn_cancel" name="btn_cancel" type="reset" class="btn btn-default" value="Logout" />
               </div>
               </div>
          </fieldset>
          <?php echo form_close(); ?>
          <?php echo $this->session->flashdata('msg'); ?>
          </div>
     </div>
</div>

<?php $this->load->view('includes/footer'); ?>