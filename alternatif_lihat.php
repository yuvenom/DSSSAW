<?php
$link_data='?page=alternatif';

$id=$_GET['id'];
$q=mysqli_query($con,"select * from alternatif where id_alternatif='".$id."'");
$r=mysqli_fetch_array($q);
$nama_alternatif=$r['nama_alternatif'];

$input_kriteria='';
$q2="select * from kriteria order by id_kriteria";
$q2=mysqli_query($con,$q2);
while($r2=mysqli_fetch_array($q2)){
	$id_kriteria=$r2['id_kriteria'];
	$nama_kriteria=$r2['nama_kriteria'];
	$input_name="kriteria".$id_kriteria;
	$nama_subkriteria='';
	$q_opt=mysqli_query($con,"select id_subkriteria from opt_alternatif where id_kriteria=".$id_kriteria." AND id_alternatif=".$id);
	if(mysqli_num_rows($q_opt)>0){
		$r_opt=mysqli_fetch_array($q_opt);
		if (!is_null($r_opt['id_subkriteria'])){
			$q_sub=mysqli_query($con,"select nama_subkriteria from subkriteria where id_subkriteria=".$r_opt['id_subkriteria']);
			$r_sub=mysqli_fetch_array($q_sub);
			$nama_subkriteria=$r_sub['nama_subkriteria'];
		}
	}
	$input_kriteria.='
	<div class="form-group">
		<label for="'.$input_name.'" class="col-sm-2 control-label">'.$nama_kriteria.'</label>
		<div class="col-sm-4">
			<input name="'.$input_name.'" id="'.$input_name.'" class="form-control" disabled type="text" value="'.$nama_subkriteria.'">
		</div>
	</div>';
}
?>
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Data Alternatif</h3>
	</div>
	<form class="form-horizontal" action="" method="post" >
		<div class="box-body">
			<div class="form-group">
				<label for="nama_alternatif" class="col-sm-2 control-label">Nama Alternatif</label>
				<div class="col-sm-4">
					<input name="nama_alternatif" id="nama_alternatif" class="form-control" disabled type="text" value="<?php echo $nama_alternatif;?>">
				</div>
			</div>
			<?php echo $input_kriteria; ?>
		</div>
		<div class="box-footer">
			<div class="text-center col-sm-6">
				<a href="<?php echo $link_data;?>" class="btn btn-danger">Kembali</a>
			</div>
		</div>
	</form>
</div>