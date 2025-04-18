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
</style>

<?php
	if(isset($_REQUEST['periode'])) {
		$tanggal = $_REQUEST['periode']; 
	} else {
		$tanggal = date('Y-m'); 
	}

	$txt_periode_arr = explode('-', $tanggal);
	if(is_array($txt_periode_arr)) {
		$txt_periode = jin_nama_bulan($txt_periode_arr[1]) . ' ' . $txt_periode_arr[0];
	}
?>

<div class="box box-solid box-primary">
	<div class="box-header">
		<h3 class="box-title">Jatuh Tempo Pembayaran Kredit </h3>
		<div class="box-tools pull-right">
			<button class="btn btn-primary btn-sm" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="box-body">
	<div>
		<form id="fmCari">
		<table>
			<tr>
				<td>
					<div class="input-group date dtpicker col-md-5" data-date="<?php echo $tanggal; ?>">
						<input id="txt_periode" style="width: 125px; text-align: center;" class="form-control" type="text" value="<?php echo $txt_periode;?>" readonly />
						<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
					</div>
					<input type="hidden" name="periode" id="periode" value="<?php echo $tanggal; ?>" />
				</td>
				<td>
					<input id="jenis_anggota_id" name="jenis_anggota_id" value="" style="width:200px; height:25px" class="">

					<input id="anggota_id" name="anggota_id" value="" style="width:200px; height:25px" class="">
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
<p style="text-align:center; font-size: 15pt; font-weight: bold;"> Laporan Jatuh Tempo Pembayaran Kredit <br> Periode <?php echo $txt_periode; ?> </p>
	<table  class="table table-bordered">
		<tr class="header_kolom">
			<th style="width:5%; vertical-align: middle; text-align:center" > No. </th>
			<th style="width:10%; vertical-align: middle; text-align:center">Kode Pinjam</th>
			<th style="width:15%; vertical-align: middle; text-align:center">Nama Anggota</th>
			<th style="width:15%; vertical-align: middle; text-align:center"> Tanggal Pinjam  </th>
			<th style="width:15%; vertical-align: middle; text-align:center"> Tanggal Tempo  </th>
			<th style="width:10%; vertical-align: middle; text-align:center"> Lama Pinjam  </th>
			<th style="width:10%; vertical-align: middle; text-align:center"> Jumlah Tagihan  </th>
			<th style="width:10%; vertical-align: middle; text-align:center"> Dibayar  </th>
			<th style="width:10%; vertical-align: middle; text-align:center"> Sisa Tagihan  </th>
		</tr>
	<?php

	$no = $offset + 1;
	$jml_tagihan = 0;
	$jml_dibayar = 0;
	$jml_sisa = 0;
	
	foreach ($data_tempo as $rows) {
	
		if(($no % 2) == 0) {
			$warna="#eeeeee"; 
		} else {
			$warna="#FFFFFF"; 
		}

		$tgl_pinjam = explode(' ', $rows->tgl_pinjam);
		$tgl_pinjam = jin_date_ina($tgl_pinjam[0],'p');

		$tgl_tempo = explode(' ', $rows->tempo);
		$tgl_tempo = jin_date_ina($tgl_tempo[0],'p');

		$jml_bayar = $this->general_m->get_jml_bayar($rows->id); 
		$jml_denda = $this->general_m->get_jml_denda($rows->id); 
		$total_tagihan = $rows->tagihan + $jml_denda->total_denda;
		$sisa_tagihan = $total_tagihan - $jml_bayar->total;

		$jml_tagihan += $total_tagihan;
		$jml_dibayar += $jml_bayar->total;
		$jml_sisa += $sisa_tagihan;

		echo '<tr bgcolor='.$warna.'>
					<td class="h_tengah">'.$no++.'</td>
					<td class="h_tengah">'.'TPJ' . sprintf('%05d', $rows->id) .'</td>
					<td class="h_kiri">'.$rows->identitas.' - '.$rows->nama.'</td>
					<td class="h_tengah">'.$tgl_pinjam.'</td>
					<td class="h_tengah">'.$tgl_tempo.'</td>
					<td class="h_tengah">'.$rows->lama_angsuran.' Periode</td>
					<td class="h_kanan">'.number_format(nsi_round($total_tagihan)).'</td>
					<td class="h_kanan">'.number_format(nsi_round($jml_bayar->total)).'</td>
					<td class="h_kanan">'.number_format(nsi_round($sisa_tagihan)).'</td>
				</tr>';
	}
	echo '<tr class="header_kolom">
				<td colspan="6" class="h_tengah"><strong>Jumlah Total</strong></td>
				<td class="h_kanan"><strong>'.number_format(nsi_round($jml_tagihan)).'</strong></td>
				<td class="h_kanan"><strong>'.number_format(nsi_round($jml_dibayar)).'</strong></td>
				<td class="h_kanan"><strong>'.number_format(nsi_round($jml_sisa)).'</strong></td>
			</tr>';
	echo '</table>
	<div class="box-footer">'.$halaman.'</div>';
	?>
</div>
</div>
	
<script type="text/javascript">
$(document).ready(function() {
	$(".dtpicker").datetimepicker({
		language:  'id',
		weekStart: 1,
		autoclose: true,
		todayBtn: true,
		todayHighlight: true,
		pickerPosition: 'bottom-right',
		format: "MM yyyy",
		linkField: "periode",
		linkFormat: "yyyy-mm",
		startView: 3,
		minView: 3
	}).on('changeDate', function(ev){
		doSearch();
	});
	
	<?php 
		if(isset($_REQUEST['anggota_id'])) {
			echo 'var anggota_id = "'.$_REQUEST['anggota_id'].'";';
		} else {
			echo 'var anggota_id = "";';
		}
		echo '$("#anggota_id").val(anggota_id);';
		
		if(isset($_REQUEST['jenis_anggota_id'])) {
			echo 'var jenis_anggota_id = "'.$_REQUEST['jenis_anggota_id'].'";';
		} else {
			echo 'var jenis_anggota_id = "";';
		}
		echo '$("#jenis_anggota_id").val(jenis_anggota_id);';
	?>

		
	$('#jenis_anggota_id').combogrid({
		panelWidth:300,
		url: '<?php echo site_url('lap_anggota/list_anggota'); ?>',
		idField:'id',
		valueField:'id',
		textField:'nama',
		mode:'remote',
		fitColumns:true,
		columns:[[
			{field:'id', title:'ID', align:'center', width:15},
			{field:'nama',title:'Nama Anggota',align:'left',width:20}
		]],
		onSelect:function(record){
			show_anggota($('input[name=jenis_anggota_id]').val())
			$('input[name=anggota_id]').val('')
			doSearch();
		}
	});
	
	$('#anggota_id').combogrid({
		panelWidth:300,
		url: '<?php echo site_url('lap_shu_anggota/list_anggota'); ?>'+'/'+jenis_anggota_id ,
		idField:'id',
		valueField:'id',
		textField:'id_nama',
		mode:'remote',
		fitColumns:true,
		columns:[[
			{field:'photo',title:'Photo',align:'center',width:5},
			{field:'id',title:'ID', hidden: true},
			{field:'id_nama', title:'IDNama', hidden: true},
			{field:'kode_anggota', title:'ID', align:'center', width:15},
			{field:'nama',title:'Nama Anggota',align:'left',width:20}
		]],
		onSelect:function(record){
			doSearch()
		}
	});	
}); // ready

function show_anggota(id){
	$('#anggota_id').combogrid({
		panelWidth:300,
		url: '<?php echo site_url('lap_shu_anggota/list_anggota'); ?>'+'/'+id ,
		idField:'id',
		valueField:'id',
		textField:'id_nama',
		mode:'remote',
		fitColumns:true,
		columns:[[
			{field:'photo',title:'Photo',align:'center',width:5},
			{field:'id',title:'ID', hidden: true},
			{field:'id_nama', title:'IDNama', hidden: true},
			{field:'kode_anggota', title:'ID', align:'center', width:15},
			{field:'nama',title:'Nama Anggota',align:'left',width:20}
		]],
		onSelect:function(record){
			doSearch()
		}
	});
}

function doSearch() {
	$('#fmCari').attr('action', '<?php echo site_url('lap_tempo'); ?>');
	$('#fmCari').submit();
}

function clearSearch(){
	window.location.href = '<?php echo site_url("lap_tempo"); ?>';
}

function cetak () {
	//$('#fmCari').attr('action', '<?php echo site_url('lap_tempo/cetak'); ?>');
	//$('#fmCari').submit();
	var periode 	= $('#periode').val();
	var win = window.open('<?php echo site_url("lap_tempo/cetak/?periode=' + periode + '"); ?>');
	if (win) {
		win.focus();
	} else {
		alert('Popup jangan di block');
	}

}

</script>