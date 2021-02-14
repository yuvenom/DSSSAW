<?php
$link_data='?page=kriteria';
$link_update='?page=update_kriteria';

$kode_kriteria='';
$nama_kriteria='';
$bobot='';
$combo_tipe='';
$combo_tipe.='<select class="selectpicker form-control" data-live-search="true" name="tipe" id="tipe" required><option value="">Pilih...</option>';
$arr=array("cost","benefit");
foreach ($arr as $arrdata) {
	$combo_tipe.='<option value="'.$arrdata.'" data-tokens="'.$arrdata.'">'.$arrdata.'</option>';
}
$combo_tipe.='</select>';

if(isset($_POST['save'])){
	$error='';
	$id=$_POST['id'];
	$action=$_POST['action'];
	$kode_kriteria=$_POST['kode_kriteria'];
	$nama_kriteria=$_POST['nama_kriteria'];
	$bobot=$_POST['bobot'];
	$tipe=$_POST['tipe'];

	if($action=='add'){
		if(mysqli_num_rows(mysqli_query($con,"select * from kriteria where kode_kriteria='".$kode_kriteria."'"))>0){
			$error='Kode Kriteria sudah ada';
		}else{
			$q="insert into kriteria(kode_kriteria,nama_kriteria,bobot,tipe) values ('".$kode_kriteria."','".$nama_kriteria."','".$bobot."','".$tipe."')";
			mysqli_query($con,$q);
			exit("<script>location.href='".$link_data."';</script>");
		}
	}
	if($action=='edit'){
		$q=mysqli_query($con,"select * from kriteria where id_kriteria='".$id."'");
		$r=mysqli_fetch_array($q);
		$kode_kriteria_tmp=$r['kode_kriteria'];
		if(mysqli_num_rows(mysqli_query($con,"select * from kriteria where kode_kriteria='".$kode_kriteria."' and kode_kriteria<>'".$kode_kriteria_tmp."'"))>0){
			$error='Kode Kriteria sudah ada';
		}else{
			$q="update kriteria set kode_kriteria='".$kode_kriteria."',nama_kriteria='".$nama_kriteria."',bobot='".$bobot."',tipe='".$tipe."' where id_kriteria='".$id."'";
			mysqli_query($con,$q);
			exit("<script>location.href='".$link_data."';</script>");
		}
	}
}else{
	if(empty($_GET['action'])){$action='add';}else{$action=$_GET['action'];}
	if($action=='edit'){
		$id=$_GET['id'];
		$q=mysqli_query($con,"select * from kriteria where id_kriteria='".$id."'");
		$r=mysqli_fetch_array($q);
		$kode_kriteria=$r['kode_kriteria'];
		$nama_kriteria=$r['nama_kriteria'];
		$bobot=$r['bobot'];
		$combo_tipe='';
		$combo_tipe.='<select class="selectpicker form-control" data-live-search="true" name="tipe" id="tipe" required><option value="">Pilih...</option>';
		$arr=array("cost","benefit");
		foreach ($arr as $arrdata) {
			if($arrdata==$r['tipe']) { $selected = "selected"; } else { $selected = ""; }
			$combo_tipe.='<option value="'.$arrdata.'" data-tokens="'.$arrdata.'" '.$selected.'>'.$arrdata.'</option>';
		}
		$combo_tipe.='</select>';
	}
	if($action=='delete'){
		$id=$_GET['id'];
		mysqli_query($con,"delete from kriteria where id_kriteria='".$id."'");
		exit("<script>location.href='".$link_data."';</script>");
	}
}
?>
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Data Kriteria</h3>
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
				<label for="kode_kriteria" class="col-sm-2 control-label">Kode Kriteria</label>
				<div class="col-sm-4">
					<input name="kode_kriteria" id="kode_kriteria" class="form-control" required type="text" value="<?php echo $kode_kriteria;?>">
				</div>
			</div>
			<div class="form-group">
				<label for="nama_kriteria" class="col-sm-2 control-label">Nama Kriteria</label>
				<div class="col-sm-4">
					<input name="nama_kriteria" id="nama_kriteria" class="form-control" required type="text" value="<?php echo $nama_kriteria;?>">
				</div>
			</div>
			<div class="form-group">
				<label for="bobot" class="col-sm-2 control-label">Bobot</label>
				<div class="col-sm-4">
					<input name="bobot" id="bobot" required type="number" class="form-control" value="<?php echo $bobot;?>">
				</div>
			</div>
			<div class="form-group">
				<label for="tipe" class="col-sm-2 control-label">Tipe</label>
				<div class="col-sm-4">
					<?php echo $combo_tipe; ?>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="text-center col-sm-6">
				<button type="submit" name="save" class="btn btn-success">Simpan</button>
				<a href="<?php echo $link_data;?>" class="btn btn-danger">Batal</a>
			</div>
		</div>
	</form>
</div>