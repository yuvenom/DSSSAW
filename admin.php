<?php
$link_data='?page=admin';
$link_update='?page=update_admin';

$list_data='';
$q="select * from admin order by id_admin";
$q=mysqli_query($con,$q);
if(mysqli_num_rows($q) > 0){
	while($r=mysqli_fetch_array($q)){
		$id=$r['id_admin'];
		$list_data.='
		<tr>
		<td></td>
		<td>'.$r['username'].'</td>
		<td>';
		if ($r['username']!="admin"){
			$list_data.='
			<a href="'.$link_update.'&amp;id='.$id.'&amp;action=edit" class="btn btn-success btn-xs" title="Ubah">Ubah</a> &nbsp;
			<a href="#" data-href="'.$link_update.'&amp;id='.$id.'&amp;action=delete" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger btn-xs" title="Hapus">Hapus</a>';
		}else{
			$list_data.='-';
		}
		$list_data.='
		</td>
		</tr>';
	}
}
?>
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Data Admin</h3>
		<div class="button-right">
			<a href="<?php echo $link_update;?>" class="btn btn-primary">Tambah Admin</a>
		</div>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered" id="dataTables1">
				<thead>
					<tr>
						<th>No</th>
						<th>Username</th>
						<th width="80">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $list_data;?>
				</tbody>
			</table>
		</div>
	</div>
</div>