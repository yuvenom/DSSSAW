<?php
$link_data='?page=admin';
$link_update='?page=update_admin';

$username='';
$password='';

if(isset($_POST['save'])){
	$error='';
	$id=$_POST['id'];
	$action=$_POST['action'];
	$username=$_POST['username'];

	if($action=='add'){
		if(mysqli_num_rows(mysqli_query($con,"select * from admin where username='".$username."'"))>0){
			$error='Username sudah ada';
		}else{
			$password=$_POST['password'];
			$q="insert into admin(username,password) values ('".$username."','".md5($password)."')";
			mysqli_query($con,$q);
			exit("<script>location.href='".$link_data."';</script>");
		}
	}
	if($action=='edit'){
		$q=mysqli_query($con,"select * from admin where id_admin='".$id."'");
		$r=mysqli_fetch_array($q);
		$username_tmp=$r['username'];
		if(mysqli_num_rows(mysqli_query($con,"select * from admin where username='".$username."' and username<>'".$username_tmp."'"))>0){
			$error='Username sudah ada';
		}else{
			$q="update admin set username='".$username."' where id_admin='".$id."'";
			mysqli_query($con,$q);
			exit("<script>location.href='".$link_data."';</script>");
		}
	}
}else{
	if(empty($_GET['action'])){$action='add';}else{$action=$_GET['action'];}
	if($action=='edit'){
		$id=$_GET['id'];
		$q=mysqli_query($con,"select * from admin where id_admin='".$id."'");
		$r=mysqli_fetch_array($q);
		$username=$r['username'];
	}
	if($action=='delete'){
		$id=$_GET['id'];
		mysqli_query($con,"delete from admin where id_admin='".$id."'");
		exit("<script>location.href='".$link_data."';</script>");
	}
}
?>
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Data Admin</h3>
	</div>
	<form class="form-horizontal" action="<?php echo $link_update;?>" method="post" >
		<input name="id" type="hidden" value="<?php echo $id;?>">
		<input name="action" type="hidden" value="<?php echo $action;?>">
		<div class="box-body">
		<?php
			if(!empty($error)){
				echo '<div class="alert bg-danger" role="alert">'.$error.'</div>';
			}
		?>
			<div class="form-group">
				<label for="username" class="col-sm-2 control-label">Username</label>
				<div class="col-sm-4">
					<input name="username" id="username" class="form-control" required type="text" value="<?php echo $username;?>">
				</div>
			</div>
			<?php if ($action=="add") { ?>
			<div class="form-group">
				<label for="password" class="col-sm-2 control-label">Password</label>
				<div class="col-sm-4">
					<input name="password" id="password" required type="password" class="form-control" value="<?php echo $password;?>">
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="box-footer">
			<div class="text-center col-sm-6">
				<button type="submit" name="save" class="btn btn-success">Simpan</button>
				<a href="<?php echo $link_data;?>" class="btn btn-danger">Batal</a>
			</div>
		</div>
	</form>
</div>