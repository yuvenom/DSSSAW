<?php
$link_data='?page=alternatif';
$link_update='?page=update_alternatif';

$nama_alternatif='';

$q_var="select id_kriteria from kriteria order by id_kriteria";
$q_var=mysqli_query($con,$q_var);
while($r_var=mysqli_fetch_array($q_var)){
	$id_k=$r_var['id_kriteria'];
	$kriteria[$id_k]='';
}

$combo_kriteria='';
$q_kriteria="select * from kriteria order by id_kriteria";
$q_kriteria=mysqli_query($con,$q_kriteria);
while($r=mysqli_fetch_array($q_kriteria)){
	$id_kriteria=$r['id_kriteria'];
	$nama_kriteria=$r['nama_kriteria'];
	$combo_name="kriteria".$id_kriteria;
	$combo_subkriteria='';
	$combo_subkriteria.='<select class="selectpicker form-control" data-live-search="true" name='.$combo_name.' id='.$combo_name.' required><option value="">Pilih...</option>';
	$q2="select * from subkriteria where id_kriteria=".$id_kriteria." order by id_subkriteria";
	$q2=mysqli_query($con,$q2);
	while($r2=mysqli_fetch_array($q2)){
		$combo_subkriteria.='<option value="'.$r2['id_subkriteria'].'" data-tokens="'.$r2['nama_subkriteria'].'">'.$r2['nama_subkriteria'].'</option>';
	}
	$combo_subkriteria.='</select>';
	$combo_kriteria.='
	<div class="form-group">
		<label for="'.$combo_name.'" class="col-sm-2 control-label">'.$nama_kriteria.'</label>
		<div class="col-sm-4">'.$combo_subkriteria.'</div>
	</div>';
}

if(isset($_POST['save'])){
	$error='';
	$id=$_POST['id'];
	$action=$_POST['action'];
	$nama_alternatif=$_POST['nama_alternatif'];
	$q_post="select id_kriteria from kriteria order by id_kriteria";
	$q_post=mysqli_query($con,$q_post);
	while($r_post=mysqli_fetch_array($q_post)){
		$id_k=$r_post['id_kriteria'];
		$kriteria[$id_k]=$_POST['kriteria'.$id_k];
	}

	if($action=='add'){
		if(mysqli_num_rows(mysqli_query($con,"select * from alternatif where nama_alternatif='".$nama_alternatif."'"))>0){
			$error='Nama Alternatif sudah ada';
		}else{
			$q="insert into alternatif(nama_alternatif) values ('".$nama_alternatif."')";
			mysqli_query($con,$q);
			$id_alternatif=mysqli_insert_id($con);
			$q2="select id_kriteria from kriteria order by id_kriteria";
			$q2=mysqli_query($con,$q2);
			while($r2=mysqli_fetch_array($q2)){
				$id_subkriteria=$kriteria[$r2['id_kriteria']];
				$q="insert into opt_alternatif(id_alternatif,id_kriteria,id_subkriteria) values('".$id_alternatif."','".$r2['id_kriteria']."','".$id_subkriteria."')";
				mysqli_query($con,$q);
			}
			exit("<script>location.href='".$link_data."';</script>");
		}
	}
	if($action=='edit'){
		$q=mysqli_query($con,"select * from alternatif where id_alternatif='".$id."'");
		$r=mysqli_fetch_array($q);
		$nama_alternatif_tmp=$r['nama_alternatif'];
		if(mysqli_num_rows(mysqli_query($con,"select * from alternatif where nama_alternatif='".$nama_alternatif."' and nama_alternatif<>'".$nama_alternatif_tmp."'"))>0){
			$error='Nama Alternatif sudah ada';
		}else{
			$q="update alternatif set nama_alternatif='".$nama_alternatif."' where id_alternatif='".$id."'";
			mysqli_query($con,$q);
			$q2="select id_kriteria from kriteria order by id_kriteria";
			$q2=mysqli_query($con,$q2);
			while($r2=mysqli_fetch_array($q2)){
				$id_subkriteria=$kriteria[$r2['id_kriteria']];
				if(mysqli_num_rows(mysqli_query($con,"select * from opt_alternatif where id_alternatif='".$id."' AND id_kriteria=".$r2['id_kriteria']))>0){
					$q="update opt_alternatif set id_subkriteria='".$id_subkriteria."' where id_alternatif='".$id."' AND id_kriteria=".$r2['id_kriteria'];
					mysqli_query($con,$q);
				}else{
					$q="insert into opt_alternatif(id_alternatif,id_kriteria,id_subkriteria) values('".$id."','".$r2['id_kriteria']."','".$id_subkriteria."')";
					mysqli_query($con,$q);
				}
			}
			exit("<script>location.href='".$link_data."';</script>");
		}
	}
}else{
	if(empty($_GET['action'])){$action='add';}else{$action=$_GET['action'];}
	if($action=='edit'){
		$id=$_GET['id'];
		$q=mysqli_query($con,"select * from alternatif where id_alternatif='".$id."'");
		$r=mysqli_fetch_array($q);
		$nama_alternatif=$r['nama_alternatif'];

		$combo_kriteria='';
		$q2="select id_kriteria from kriteria order by id_kriteria";
		$q2=mysqli_query($con,$q2);
		while($r2=mysqli_fetch_array($q2)){
			$rk=mysqli_fetch_array(mysqli_query($con,"select * from opt_alternatif where id_kriteria=".$r2['id_kriteria']." AND id_alternatif=".$id));
			$kriteria[$r2['id_kriteria']]=$rk['id_subkriteria'];
		}

		$q_kriteria="select * from kriteria order by id_kriteria";
		$q_kriteria=mysqli_query($con,$q_kriteria);
		while($r=mysqli_fetch_array($q_kriteria)){
			$id_kriteria=$r['id_kriteria'];
			$nama_kriteria=$r['nama_kriteria'];
			$combo_name="kriteria".$id_kriteria;
			$combo_subkriteria='';
			$combo_subkriteria.='<select class="selectpicker form-control" data-live-search="true" name='.$combo_name.' id='.$combo_name.' required><option value="">Pilih...</option>';
			$q2="select * from subkriteria where id_kriteria=".$id_kriteria." order by id_subkriteria";
			$q2=mysqli_query($con,$q2);
			while($r2=mysqli_fetch_array($q2)){
				if($r2['id_subkriteria']==$kriteria[$id_kriteria]) { $selected = "selected"; } else { $selected = ""; }
				$combo_subkriteria.='<option value="'.$r2['id_subkriteria'].'" data-tokens="'.$r2['nama_subkriteria'].'" '.$selected.'>'.$r2['nama_subkriteria'].'</option>';
			}
			$combo_subkriteria.='</select>';
			$combo_kriteria.='
			<div class="form-group">
				<label for="'.$combo_name.'" class="col-sm-2 control-label">'.$nama_kriteria.'</label>
				<div class="col-sm-4">'.$combo_subkriteria.'</div>
			</div>';
		}
	}
	if($action=='delete'){
		$id=$_GET['id'];
		mysqli_query($con,"delete from alternatif where id_alternatif='".$id."'");
		exit("<script>location.href='".$link_data."';</script>");
	}
}
?>
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Data Alternatif</h3>
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
				<label for="nama_alternatif" class="col-sm-2 control-label">Nama Alternatif</label>
				<div class="col-sm-4">
					<input name="nama_alternatif" id="nama_alternatif" class="form-control" required type="text" value="<?php echo $nama_alternatif;?>">
				</div>
			</div>
			<?php echo $combo_kriteria; ?>
		</div>
		<div class="box-footer">
			<div class="text-center col-sm-6">
				<button type="submit" name="save" class="btn btn-success">Simpan</button>
				<a href="<?php echo $link_data;?>" class="btn btn-danger">Batal</a>
			</div>
		</div>
	</form>
</div>