<?php
// hapus tanda komentar "//" dibawah jika tampil error yang berhubungan dengan array_column, jika tidak error abaikan saja atau hapus
// include "array_column.php";

// -------------- PERHITUNGAN METODE SAW ---------------------- START
$jml_alternatif=mysqli_num_rows(mysqli_query($con,"select id_alternatif from alternatif"));
$jml_kriteria=mysqli_num_rows(mysqli_query($con,"select id_kriteria from kriteria"));
$jml_nol_koma=3; // jumlah angka pembulatan di belakang koma
$tampilkan_perhitungan=true; //ubah ke false jika tidak ingin menampilkan rumus perhitungan dan langsung tampil hasilnya
$var_rumus=''; // variabel untuk menampilkan rumus perhitungan

//mencari nilai matriks keputusan X
$i = 0;
$sql2=mysqli_query($con,"SELECT id_alternatif FROM alternatif order by id_alternatif");
while($ralternatif=mysqli_fetch_array($sql2)){
	$j = 0;
	$query2=mysqli_query($con,"select id_kriteria from kriteria order by kode_kriteria");
	while($rkriteria=mysqli_fetch_array($query2))
	{
		$rk=mysqli_fetch_array(mysqli_query($con,"select id_subkriteria from opt_alternatif where id_alternatif='".$ralternatif['id_alternatif']."' AND id_kriteria='".$rkriteria['id_kriteria']."'"));
		$rsub=mysqli_fetch_array(mysqli_query($con,"select bobot from subkriteria where id_subkriteria=".$rk['id_subkriteria']));
		$arrX[$i][$j] = $rsub['bobot'];
		$j++;
	}
	$i++;
}

//mencari nilai matriks R
$var_rumus.='<h3 class="page-header">Normalisasi Matriks</h3>';
$i=0;
$sql2=mysqli_query($con,"SELECT kode_kriteria,tipe FROM kriteria order by kode_kriteria");
while($r2=mysqli_fetch_array($sql2)){
	$var_rumus.='<h4>Kriteria '.$r2['kode_kriteria'].'</h4>';
	for ($j=0;$j<$jml_alternatif;$j++){
		$show_j=$j+1; // untuk kebutuhan menampilkan rumus perhitungan
		$show_i=$i+1; // untuk kebutuhan menampilkan rumus perhitungan
		$show_array=implode("; ", array_column($arrX, $i)); // untuk kebutuhan menampilkan rumus perhitungan
		if ($r2['tipe'] == "cost"){
			$min = min(array_column($arrX, $i));
			$arrR[$j][$i] = $min / $arrX[$j][$i];
			$var_rumus.="r<sub>".$show_j."".$show_i."</sub> = ";
			$var_rumus.="min{".$show_array."} / ".$arrX[$j][$i]." = ";
			$var_rumus.=$min." / ".$arrX[$j][$i]." = ".round($arrR[$j][$i],$jml_nol_koma);
		}elseif ($r2['tipe'] == "benefit"){
			$max = max(array_column($arrX, $i));
			$arrR[$j][$i] = $arrX[$j][$i] / $max;
			$var_rumus.="r<sub>".$show_j."".$show_i."</sub> = ";
			$var_rumus.=$arrX[$j][$i]." / max{".$show_array."} = ";
			$var_rumus.=$arrX[$j][$i]." / ".$max." = ".round($arrR[$j][$i],$jml_nol_koma);
		}
		$var_rumus.="<br>";
	}
	$i++;
}

$tampil_hasil=''; // digunakan untuk kebutuhan menampilkan rumus perhitungan
$tampil_hasil='<table class="table table-striped table-bordered"><tbody>';
for ($i=0;$i<$jml_alternatif;$i++){
	$tampil_hasil.='<tr>';
	for ($j=0;$j<$jml_kriteria;$j++){
		$tampil_hasil.='<td>'.round($arrR[$i][$j],$jml_nol_koma).'</td>';
	}
	$tampil_hasil.='</tr>';
}
$tampil_hasil.='</tbody></table>';

$var_rumus.='<br><h3 class="page-header">Hasil Matriks Normalisasi</h3>';
$var_rumus.=$tampil_hasil;

//mencari nilai bobot preferensi W
$var_rumus.='<br><h3 class="page-header">Bobot Preferensi W</h3>';
$var_rumus.='W = [';
$i=0;
$sql=mysqli_query($con,"SELECT bobot FROM kriteria order by kode_kriteria");
while($r=mysqli_fetch_array($sql)){
	$bobot[$i] = $r['bobot'];
	$var_rumus.=$bobot[$i].", ";
	$i++;
}
$var_rumus = substr($var_rumus, 0, -2);
$var_rumus.='] <br>';

//mencari nilai V
$hasil = array();
$var_rumus.='<br><h3 class="page-header">Menghitung Nilai V</h3>';
$var_rumus.='<table class="table table-bordered table-striped"><tbody>';
$i=0;
$sql2=mysqli_query($con,"SELECT * FROM alternatif order by id_alternatif");
while($r2=mysqli_fetch_array($sql2)){
	$var_rumus.='<tr>';
	$id_alternatif = $r2['id_alternatif'];
	$nama_alternatif = $r2['nama_alternatif'];
	$var_rumus.='<td>'.$nama_alternatif.'</td>';
	$nilai_v = 0;
	$string_tampil=''; // untuk kebutuhan menampilkan rumus perhitungan
	for ($j=0; $j < $jml_kriteria; $j++) {
		$nilai_v = $nilai_v + ($bobot[$j] * $arrR[$i][$j]);
		$string_tampil.='('.$bobot[$j].')('.round($arrR[$i][$j],$jml_nol_koma).') + ';
	}
	$hasil[$i]["id_alternatif"] = $id_alternatif;
	$hasil[$i]["nilai"] = $nilai_v;
	$string_tampil = substr($string_tampil, 0, -2);
	$var_rumus.='<td>'.$string_tampil.'</td>';
	$var_rumus.='<td>'.round($nilai_v,$jml_nol_koma).'</td>';
	$var_rumus.='</tr>';
	$i++;
}
$var_rumus.='</tbody></table>';
// -------------- PERHITUNGAN METODE SAW ---------------------- END

// fungsi untuk mengurutkan nilai berdasarkan nilai terbesar
function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }
    array_multisort($sort_col, $dir, $arr);
}
array_sort_by_column($hasil, 'nilai');

//tampilkan hasil penilaian kedalam tabel
$no=0;
$list_rekomendasi='';
foreach($hasil as $arr){
	$no++;
	$ralternatif=mysqli_fetch_array(mysqli_query($con,"select nama_alternatif from alternatif where id_alternatif='".$arr['id_alternatif']."'"));
	$list_rekomendasi.='
	<tr>
	<td>'.$no.'</td>
	<td>'.$ralternatif['nama_alternatif'].'</td>
	<td>'.round($arr['nilai'],$jml_nol_koma).'</td>
	</tr>
	';
}
?>