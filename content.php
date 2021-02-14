<?php
switch($page){
	case 'kriteria':
		include "kriteria.php";
		break;
	case 'update_kriteria':
		include "kriteria_update.php";
		break;
	case 'subkriteria':
		include "subkriteria.php";
		break;
	case 'update_subkriteria':
		include "subkriteria_update.php";
		break;
	case 'admin':
		include "admin.php";
		break;
	case 'update_admin':
		include "admin_update.php";
		break;
	case 'alternatif':
		include "alternatif.php";
		break;
	case 'update_alternatif':
		include "alternatif_update.php";
		break;
	case 'lihat_alternatif':
		include "alternatif_lihat.php";
		break;
	case 'penilaian':
		include "penilaian.php";
		break;
	case 'password':
		include "password.php";
		break;

	default:
		include "beranda.php";
		break;
}
?>