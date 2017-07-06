<div class="container">
	<div class="row">
		<div class="col-lg-4 col-sm-4 well">
			<?php 
          $attributes = array("class" => "form-horizontal", "id" => "login_view", "name" => "loginform");
          echo form_open("register/index", $attributes);?>
			<fieldset>
				<legend>Sign-Up</legend>
				<div class="form-group">
					<div class="row colbox">
						<div class="col-lg-4 col-sm-4">
							<label for="username" class="control-label">Username</label>
						</div>
						<div class="col-lg-8 col-sm-8">
							<input class="form-control" id="username" name="username" placeholder="Username" type="text" value="<?php echo set_value('username'); ?>" / autofocus>
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
					<div class="row colbox">
						<div class="col-lg-4 col-sm-4">
							<label for="cpassword" class="control-label">Re-Password</label>
						</div>
						<div class="col-lg-8 col-sm-8">
							<input class="form-control" id="cpassword" name="cpassword" placeholder="Re-Password" type="password" value="<?php echo set_value('cpassword'); ?>" />
							<span class="text-danger"><?php echo form_error('cpassword'); ?></span>
						</div>
					</div>
				</div>


				<div class="form-group">
					<div class="row colbox">
						<div class="col-lg-4 col-sm-4">
							<label for="email" class="control-label">Email</label>
						</div>
						<div class="col-lg-8 col-sm-8">
							<input class="form-control" id="email" name="email" placeholder="E-mail" type="email" value="<?php echo set_value('email'); ?>" />
							<span class="text-danger"><?php echo form_error('email'); ?></span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row colbox">
						<div class="col-lg-4 col-sm-4">
							<label for="fname" class="control-label">First Name</label>
						</div>
						<div class="col-lg-8 col-sm-8">
							<input class="form-control" id="fname" name="fname" placeholder="Username" type="text" value="<?php echo set_value('fname'); ?>" / autofocus>
							<span class="text-danger"><?php echo form_error('fname'); ?></span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row colbox">
						<div class="col-lg-4 col-sm-4">
							<label for="lname" class="control-label">Last Name</label>
						</div>
						<div class="col-lg-8 col-sm-8">
							<input class="form-control" id="lname" name="lname" placeholder="Username" type="text" value="<?php echo set_value('lname'); ?>" / autofocus>
							<span class="text-danger"><?php echo form_error('lname'); ?></span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-12 col-sm-12 text-center">
						<input id="btn_signup" name="btn_signup" type="submit" class="btn btn-default" value="Sign Up" />
					</div>
				</div>
			</fieldset>
			<?php echo form_close(); ?>
			<?php echo $this->session->flashdata('msg'); ?>
		</div>
	</div>
</div>
