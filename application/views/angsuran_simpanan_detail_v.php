<!-- Styler -->
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

	th {
		text-align: center;
		background: #3c8dbc;
		height: 30px;
		border-width: 1px;
		border-style: solid;
		color :#ffffff;
	}
</style>
<?php
$dibayar = $hitung_dibayar->total;
$sisa_bayar = $row_pinjam->jumlah - $dibayar;
$total_bayar = $sisa_bayar;
?>

<!-- menu atas -->
<p></p>
<!-- detail data anggota -->
<div class="box box-solid box-primary">
	<div class="box-header" title="Detail Pinjaman" data-toggle="" data-original-title="Detail Pinjaman">
		<h3 class="box-title"> Detail Simpanan </h3>
		<div class="box-tools pull-right">
			<button class="btn btn-primary btn-xs" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="box-body">
		<table style="font-size: 13px; width:100%">
			<tr>
				<td style="width:10%; text-align:center;">
					<?php
					$photo_w = 3 * 30;
					$photo_h = 4 * 30;
					if($data_anggota->file_pic == '') {
						echo '<img src="'.base_url().'assets/theme_admin/img/photo.jpg" alt="default" width="'.$photo_w.'" height="'.$photo_h.'" />';
					} else {
						echo '<img src="'.base_url().'uploads/anggota/' . $data_anggota->file_pic . '" alt="Foto" width="'.$photo_w.'" height="'.$photo_h.'" />';
					}
					?>
				</td>
				<td>
					<table style="width:100%">
						<tr>
							<td><label class="text-green">Data Anggota</label></td>
						</tr>
						<?php //echo 'AG' . sprintf('%04d', $row_pinjam->anggota_id) . '' ?>
						<tr>
							<td> ID Anggota</td>
							<td> : </td>
							<td> <?php echo $data_anggota->identitas; ?></td>
						</tr>
						<tr>
							<td> Nama Anggota </td>
							<td> : </td>
							<td> <?php echo $data_anggota->nama; ?></td>
						</tr>
						<tr>
							<td> Dept </td>
							<td> : </td>
							<td> <?php echo $data_anggota->departement; ?></td>
						</tr>
						<tr>
							<td> Tempat, Tanggal Lahir  </td>
							<td> : </td>
							<td> <?php echo $data_anggota->tmp_lahir .', '. jin_date_ina ($data_anggota->tgl_lahir); ?></td>
						</tr>
						<tr>
							<td> Kota Tinggal</td>
							<td> : </td>
							<td> <?php echo $data_anggota->kota; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table style="width:100%">
						<tr>
							<td><label class="text-green">Data Tabungan</label></td>
						</tr>
						<tr>
							<td> Kode Simpanan</td>
							<td> : </td>
							<td> <?php echo 'TRD' . sprintf('%05d', $row_pinjam->id) . '' ?> </td>
						</tr>
						<tr>
							<td> Tanggal Simpan</td>
							<td> : </td>
							<td> <?php
								$tanggal_arr = explode(' ', $row_pinjam->tgl_transaksi);
								$txt_tanggal_p = jin_date_ina($tanggal_arr[0], 'full');
								echo  $txt_tanggal_p;
								?>
							</td>
						</tr>
					</table>
				</td>
				<td>
					
				</td>
			</tr>
		</table>
	</div>

	<div class="box box-solid bg-light-blue">
		<table width="100%" style="font-size: 12px;">
			<tr>
				<td><strong> Detail Tabungan </strong> &raquo; </td>
				
				
				
			</code>
		</tr>
		</table>
	</div>
</div>

<label class="text-green"> Detail Tabungan :</label>
<table  class="table table-bordered">
	<tr class="header_kolom">
		<th style="width:10%; vertical-align: middle"> Nomor</th>
		<th style="width:15%; vertical-align: middle"> Jumlah Tabungan</th>
		<th style="width:20%; vertical-align: middle"> Tanggal </th>
	</tr>


<?php //var_dump($simulasi_tagihan);
if(!empty($simulasi_tagihan)) {
	$no = 1;
	$row = array();
	$jml_pokok = 0;
	$jml_bunga = 0;
	$jml_ags = 0;
	$jml_adm = 0;
	$provisi_pinjaman = 0;
	foreach ($simulasi_tagihan as $row) {
		if(($no % 2) == 0) {
			$warna="#FAFAD2";
		} else {
			$warna="#FFFFFF";
		}

		$txt_tanggal = jin_date_ina($row['tgl_tempo']);
		// $txt_tanggal = $row['tgl_tempo'];
		$jml_pokok += $row['angsuran_pokok'];
		$jml_ags += $row['jumlah_ags'];

		echo '
			<tr bgcolor='.$warna.'>
				<td class="h_tengah">'.$no.'</td>
				<td class="h_kanan">'.number_format(nsi_round($row['jumlah_ags'])).'</td>
				<td class="h_tengah">'.$txt_tanggal.'</td>
			</tr>';
		$no++;
	}
	echo '<tr bgcolor="#eee">
				<td class="h_tengah"><strong>Jumlah</strong></td>
				<td class="h_kanan"><strong>'.number_format(nsi_round($jml_ags)).'</strong></td>
				<td></td>
			</tr>
		</table>';
}
?>

