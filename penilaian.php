<?php
$link_data='?page=penilaian';

$list_data='';
$list_data.='
<thead>
	<tr>
		<th>No</th>
		<th>Alternatif</th>';
		$q2="select * from kriteria order by kode_kriteria";
		$q2=mysqli_query($con,$q2);
		while($r2=mysqli_fetch_array($q2)){
			$list_data.='<th>'.$r2['nama_kriteria'].'</th>';
		}
$list_data.='
	</tr>
</thead>
<tbody>';
$no=0;
$q=mysqli_query($con,"select * from alternatif order by id_alternatif");
while($r=mysqli_fetch_array($q)){
	$id_alternatif=$r['id_alternatif'];
	$no++;
	$list_data.='
	<tr>
		<td align="center">'.$no.'</td>
		<td>'.$r['nama_alternatif'].'</td>';
		$q2="select id_kriteria from kriteria order by id_kriteria";
		$q2=mysqli_query($con,$q2);
		while($r2=mysqli_fetch_array($q2)){
			$rk=mysqli_fetch_array(mysqli_query($con,"select id_subkriteria from opt_alternatif where id_kriteria=".$r2['id_kriteria']." AND id_alternatif=".$id_alternatif));
			$rk2=mysqli_fetch_array(mysqli_query($con,"select nama_subkriteria from subkriteria where id_subkriteria=".$rk['id_subkriteria']));
			$list_data.='<td align="center">'.$rk2['nama_subkriteria'].'</td>';
		}
	$list_data.='
	</tr>';
}
$list_data.='</tbody>';

$list_data2='';
$list_data2.='
<thead>
	<tr>
		<th>Alternatif</th>';
		$q2="select kode_kriteria from kriteria order by kode_kriteria";
		$q2=mysqli_query($con,$q2);
		while($r2=mysqli_fetch_array($q2)){
			$list_data2.='<th>'.$r2['kode_kriteria'].'</th>';
		}
$list_data2.='
	</tr>
</thead>
<tbody>';
$q=mysqli_query($con,"select * from alternatif order by id_alternatif");
while($r=mysqli_fetch_array($q)){
	$id_alternatif=$r['id_alternatif'];
	$list_data2.='
	<tr>
		<td width="200">'.$r['nama_alternatif'].'</td>';
		$q2="select id_kriteria from kriteria order by id_kriteria";
		$q2=mysqli_query($con,$q2);
		while($r2=mysqli_fetch_array($q2)){
			$rk=mysqli_fetch_array(mysqli_query($con,"select id_subkriteria from opt_alternatif where id_kriteria=".$r2['id_kriteria']." AND id_alternatif=".$id_alternatif));
			$rk2=mysqli_fetch_array(mysqli_query($con,"select bobot from subkriteria where id_subkriteria=".$rk['id_subkriteria']));
			$list_data2.='<td align="center">'.$rk2['bobot'].'</td>';
		}
	$list_data2.='
	</tr>';
}
$list_data2.='</tbody>';

include "saw.php";
?>
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Penilaian</h3>
    </div>
    <div class="box-body">
		<div class='table-responsive'>
			<table class='table table-striped table-bordered tabel-header' >
				<?php echo $list_data;?>
			</table>
		</div>
		<?php if($tampilkan_perhitungan){ ?>
		<br>
		<div class='table-responsive'>
			<table class='table table-striped table-bordered tabel-header' >
				<?php echo $list_data2;?>
			</table>
		</div>
		<?php
			echo $var_rumus;
		}
		?>
		<br>
		<h3 class="page-header">Hasil</h3>
		<div class='table-responsive'>
			<table class='table table-bordered table-striped' >
				<thead>
					<tr>
						<th>No</th>
						<th>Alternatif</th>
						<th>Nilai</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $list_rekomendasi;?>
				</tbody>
			</table>
		</div>
    </div>
</div>