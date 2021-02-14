<?php
$link_data='?page=kriteria';
$link_update='?page=update_kriteria';
$link_subkriteria='?page=subkriteria';

$list_data='';
$q="select * from kriteria order by id_kriteria";
$q=mysqli_query($con,$q);
if(mysqli_num_rows($q) > 0){
	while($r=mysqli_fetch_array($q)){
		$id=$r['id_kriteria'];
		$list_data.='
		<tr>
		<td></td>
		<td>'.$r['kode_kriteria'].'</td>
		<td>'.$r['nama_kriteria'].'</td>
		<td>'.$r['bobot'].'</td>
		<td>'.$r['tipe'].'</td>
		<td>
		<a href="'.$link_update.'&amp;id='.$id.'&amp;action=edit" class="btn btn-success btn-xs" title="Ubah">Ubah</a> &nbsp;
		<a href="#" data-href="'.$link_update.'&amp;id='.$id.'&amp;action=delete" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger btn-xs" title="Hapus">Hapus</a> &nbsp;
		<a href="'.$link_subkriteria.'&amp;idk='.$id.'" class="btn btn-info btn-xs">Subkriteria</a></td>
		</tr>';
	}
}
?>
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Data Kriteria</h3>
		<div class="button-right">
			<a href="<?php echo $link_update;?>" class="btn btn-primary">Tambah Kriteria</a>
		</div>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered" id="dataTables1">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Kriteria</th>
						<th>Nama Kriteria</th>
						<th>Bobot</th>
						<th>Tipe</th>
						<th width="150">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $list_data;?>
				</tbody>
			</table>
		</div>
	</div>
</div>