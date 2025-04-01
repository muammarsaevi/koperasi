<style type="text/css">
.panel * {
	font-family: "Arial","​Helvetica","​sans-serif";
}
.fa {
	font-family: "FontAwesome";
}
.datagrid-header-row * {
	font-weight: bold;
}
.messager-window * a:focus, .messager-window * span:focus {
	color: blue;
	font-weight: bold;
}
.daterangepicker * {
	font-family: "Source Sans Pro","Arial","​Helvetica","​sans-serif";
	box-sizing: border-box;
}
.glyphicon	{font-family: "Glyphicons Halflings"}

.form-control {
	height: 20px;
	padding: 4px;
}	
</style>

<?php 

if(isset($_REQUEST['tgl_dari']) && isset($_REQUEST['tgl_samp'])) {
	$tgl_dari = $_REQUEST['tgl_dari'];
	$tgl_samp = $_REQUEST['tgl_samp'];
} else {
	$tgl_dari = date('Y') . '-01-01';
	$tgl_samp = date('Y') . '-12-31';
}
$tgl_dari_txt = jin_date_ina($tgl_dari, 'p');
$tgl_samp_txt = jin_date_ina($tgl_samp, 'p');
$tgl_periode_txt = $tgl_dari_txt . ' - ' . $tgl_samp_txt;
?>

<div class="box box-solid box-primary">
	<div class="box-header">
		<h3 class="box-title">Cetak Data Simpanan</h3>
		<div class="box-tools pull-right">
			<button class="btn btn-primary btn-sm" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="box-body">
		<div>
			<form id="fmCari" method="GET">
				<input type="hidden" name="tgl_dari" id="tgl_dari">
				<input type="hidden" name="tgl_samp" id="tgl_samp">
				<table>
					<tr>
						<td>
							<div id="filter_tgl" class="input-group" style="display: inline;">
								<button class="btn btn-default" id="daterange-btn">
									<i class="fa fa-calendar"></i> <span id="reportrange"><span><?php echo $tgl_periode_txt; ?>
									</span></span>
									<i class="fa fa-caret-down"></i>
								</button>
							</div>
						</td>
						<td>
							
							<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="false" onclick="cetak()">Cetak Laporan</a>

							<a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-clear" plain="false" onclick="clearSearch()">Hapus Filter</a>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>



<div class="box box-primary">
	<div class="box-body">
	<p></p>
	<p style="text-align:center; font-size: 15pt; font-weight: bold;"> Laporan Data Simpanan Per Anggota </p>
	<table  class="table table-bordered">
		<tr class="header_kolom">
			<th style="width:5%; vertical-align: middle; text-align:center" > No. </th>
			<th style="width:5%; vertical-align: middle; text-align:center">Photo</th>
			<th style="width:25%; vertical-align: middle; text-align:center"> Identitas  </th>
			<th style="width:20%; vertical-align: middle; text-align:center">Saldo Simpanan</th>
		</tr>
	<?php

	$no = $offset + 1;
	$mulai=1;
	if (!empty($data_anggota)) {

		foreach ($data_anggota as $row) {
		if(($no % 2) == 0) {
			$warna="#EEEEEE"; }
		else {
			$warna="#FFFFFF";}


		//photo
		$photo_w = 3 * 20;
		$photo_h = 4 * 20;
		if($row->file_pic == '') {
			$photo ='<img src="'.base_url().'assets/theme_admin/img/photo.jpg" alt="default" width="'.$photo_w.'" height="'.$photo_h.'" />';
		} else {
			$photo= '<img src="'.base_url().'uploads/anggota/' . $row->file_pic . '" alt="Foto" width="'.$photo_w.'" height="'.$photo_h.'" />';
		}

		//jk
		if ($row->jk == "L") {
			$jk="Laki-Laki";
		} else {
			$jk="Perempuan";
		}

		//jabatan
		if ($row->jabatan_id == "1") {
			$jabatan="Pengurus";
		} else {
			$jabatan="Anggota";
		}
		// AG'.sprintf('%04d', $row->id).'
	 	echo '
			<tr bgcolor='.$warna.' >
				<td class="h_tengah" style="vertical-align: middle "> '.$no++.' </td>
				<td class="h_tengah" style="vertical-align: middle "> '.$photo.'</td>
				<td>
					<table>
						<tr><td> ID Anggota : '.$row->identitas.'</td></tr>
						<tr><td> Nama : <b>'.strtoupper($row->nama).'</b> </td></tr>
						<tr><td> Jenis Kelamin : '.$jk.' </td></tr>
						<tr><td> Jabatan : '.$jabatan.' - '.$row->departement.'</td></tr>
						<tr><td> Alamat  : '.$row->alamat.' Telp.'.$row->notelp.' </td></tr>
					</table>
				</td>
				<td>';
				$simpanan_arr = array();
				$simpanan_row_total = 0;
				$simpanan_total = 0;
				foreach ($data_jns_simpanan as $jenis) {
					$simpanan_arr[$jenis->id] = $jenis->jns_simpan;
					$nilai_s = $this->tabungan_m->get_jml_simpanan($jenis->id, $row->id);
					$nilai_p = $this->tabungan_m->get_jml_penarikan($jenis->id, $row->id);

					$simpanan_row=$nilai_s->jml_total - $nilai_p->jml_total;
					$simpanan_row_total += $simpanan_row;
					$simpanan_total += $simpanan_row_total;
					

					// detail transaksi user

					$transaksi_user=$control->tabungan_m->get_data_transaksi('1');

					echo'<table style="width:100%;">
							<tr>
								<td>'.$jenis->jns_simpan.'</td>
								<td class="h_kanan">'. number_format($simpanan_row).'</td>
							</tr>';
					}
					echo '<tr>
								<td><strong> Jumlah Simpanan </strong></td>
								<td class="h_kanan"><strong> '.number_format($simpanan_row_total).'</strong></td>
							</tr>
							</table>';




						'
					</td>
				</tr>';
			}
		echo '</table>
		<div class="box-footer">'.$halaman.'</div>';
	} else {
		echo '<tr>
					<td colspan="9" >
						<code> Tidak Ada Data <br> </code>
					</td>
				</tr>';
			}
	?>
</div>
</div>
	
<script type="text/javascript">
$(document).ready(function() {
	fm_filter_tgl();
}); // ready

function fm_filter_tgl() {
	$('#daterange-btn').daterangepicker({
		ranges: {
			'Hari ini': [moment(), moment()],
			'Kemarin': [moment().subtract('days', 1), moment().subtract('days', 1)],
			'7 Hari yang lalu': [moment().subtract('days', 6), moment()],
			'30 Hari yang lalu': [moment().subtract('days', 29), moment()],
			'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
			'Bulan kemarin': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
			'Tahun ini': [moment().startOf('year').startOf('month'), moment().endOf('year').endOf('month')],
			'Tahun kemarin': [moment().subtract('year', 1).startOf('year').startOf('month'), moment().subtract('year', 1).endOf('year').endOf('month')]
		},
		locale: 'id',
		showDropdowns: true,
		format: 'YYYY-MM-DD',
		<?php 
			if(isset($tgl_dari) && isset($tgl_samp)) {
				echo "
					startDate: '".$tgl_dari."',
					endDate: '".$tgl_samp."'
				";
			} else {
				echo "
					startDate: moment().startOf('year').startOf('month'),
					endDate: moment().endOf('year').endOf('month')
				";
			}
		?>
	},

	function (start, end) {
		doSearch();
	});
}

function clearSearch(){
	window.location.href = '<?php echo site_url("tabungan"); ?>';
}

function doSearch() {
	var tgl_dari = $('input[name=daterangepicker_start]').val();
	var tgl_samp = $('input[name=daterangepicker_end]').val();
	$('input[name=tgl_dari]').val(tgl_dari);
	$('input[name=tgl_samp]').val(tgl_samp);
	$('#fmCari').attr('action', '<?php echo site_url('tabungan'); ?>');
	$('#fmCari').submit();	
}

function cetak () {
	var tgl_dari = $('input[name=daterangepicker_start]').val();
	var tgl_samp = $('input[name=daterangepicker_end]').val();
	//$('input[name=tgl_dari]').val(tgl_dari);
	//$('input[name=tgl_samp]').val(tgl_samp);
	//$('#fmCari').attr('action', '<?php echo site_url('lap_simpanan/cetak'); ?>');
	//$('#fmCari').submit();
	var win = window.open('<?php echo site_url("lap_simpanan/cetak/?tgl_dari=' + tgl_dari + '&tgl_samp=' + tgl_samp + '"); ?>');
	if (win) {
		win.focus();
	} else {
		alert('Popup jangan di block');
	}

}
</script>