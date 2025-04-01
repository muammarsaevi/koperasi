<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tabungan_m extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	//panggil data simpanan
	function get_data_jenis_simpan($limit, $start) {
		$this->db->select('*');
		$this->db->from('jns_simpan');
		$this->db->where('tampil','Y');
		$this->db->order_by('id', 'ASC');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		if($query->num_rows()>0){
			$out = $query->result();
			return $out;
		} else {
			return FALSE;
		}
	}


	function get_jml_data_simpan() {
		$this->db->where('tampil', 'Y');
		return $this->db->count_all_results('jns_simpan');
	}

	//menghitung jumlah simpanan
	function get_jml_simpanan($jenis, $id) {
		$this->db->select('SUM(jumlah) AS jml_total');
		$this->db->from('tbl_trans_sp');
		$this->db->where('anggota_id',$id);
		$this->db->where('dk','D');
		$this->db->where('jenis_id', $jenis);

		if(isset($_REQUEST['tgl_dari']) && isset($_REQUEST['tgl_samp'])) {
			$tgl_dari = $_REQUEST['tgl_dari'];
			$tgl_samp = $_REQUEST['tgl_samp'];
		} else {
			$tgl_dari = date('Y') . '-01-01';
			$tgl_samp = date('Y') . '-12-31';
		}
		$this->db->where('DATE(tgl_transaksi) >= ', ''.$tgl_dari.'');
		$this->db->where('DATE(tgl_transaksi) <= ', ''.$tgl_samp.'');


		$query = $this->db->get();
		return $query->row();
	}


	//menghitung jumlah simpanan

	//menghitung jumlah penarikan
	function get_jml_penarikan($jenis, $id) {
		$this->db->select('SUM(jumlah) AS jml_total');
		$this->db->from('tbl_trans_sp');
		$this->db->where('dk','K');
		$this->db->where('anggota_id', $id);
		$this->db->where('jenis_id', $jenis);
		

		if(isset($_REQUEST['tgl_dari']) && isset($_REQUEST['tgl_samp'])) {
			$tgl_dari = $_REQUEST['tgl_dari'];
			$tgl_samp = $_REQUEST['tgl_samp'];
		} else {
			$tgl_dari = date('Y') . '-01-01';
			$tgl_samp = date('Y') . '-12-31';
		}
		$this->db->where('DATE(tgl_transaksi) >= ', ''.$tgl_dari.'');
		$this->db->where('DATE(tgl_transaksi) <= ', ''.$tgl_samp.'');


		$query = $this->db->get();
		return $query->row();
	}
	//panggil data jenis simpan
	function get_jenis_simpan() {
		$this->db->select('*');
		$this->db->from('jns_simpan');
		$this->db->where('tampil','Y');
		$query = $this->db->get();
		if($query->num_rows()>0){
			$out = $query->result();
			return $out;
		} else {
			return array();
		}
	}

	//percobaan saja
	//panggil data jenis kas untuk laporan
	function get_transaksi_kas($kas_id) {
		$this->db->select('*');
		$this->db->from('v_hitung_pinjaman');
		
		if(isset($_REQUEST['tgl_dari']) && isset($_REQUEST['tgl_samp'])) {
			$tgl_dari = $_REQUEST['tgl_dari'];
			$tgl_samp = $_REQUEST['tgl_samp'];
		} else {
			$tgl_dari = date('Y') . '-01-01';
			$tgl_samp = date('Y') . '-12-31';
		}

		$where = "(DATE(tgl) >= '".$tgl_dari."' AND  DATE(tgl) = '".$tgl_samp."') AND (dari_kas = '".$kas_id."' OR  untuk_kas = '".$kas_id."')";
		$this->db->where($where);
		$this->db->order_by('tgl', 'ASC');
		$query = $this->db->get();

		if($query->num_rows()>0) {
			$out = $query->result();
			return $out;
		} else {
			return array();
		}
	}
//curiga ini yg eror
	function get_data_transaksi($id) {
		$sql = "SELECT v_hitung_pinjaman.* FROM v_hitung_pinjaman ";
		$where = " WHERE dk = 'K' ";

		$where .=" AND tbl_anggota.id='".$id."' ";
		$sql .= " LEFT JOIN tbl_anggota ON (v_hitung_pinjaman.anggota_id = tbl_anggota.id) ";

		$sql .= $where;

		$result['data'] = $this->db->query($sql)->result();

		return $result;
	}

	//PAnggil data Anggota
	function get_data_anggota($limit, $start, $q='') {
		$anggota_id = isset($_REQUEST['anggota_id']) ? $_REQUEST['anggota_id'] : '';
		$jenis_anggota_id = isset($_REQUEST['jenis_anggota_id']) ? $_REQUEST['jenis_anggota_id'] : '';
		$sql = '';
		$sql = "SELECT * FROM tbl_anggota WHERE aktif='Y'";
		$q = array('anggota_id' => $anggota_id, 'jenis_anggota_id' => $jenis_anggota_id);
		if (is_array($q)){
			if($q['jenis_anggota_id'] != '') {
				$sql .=" AND category = '".$q['jenis_anggota_id']."'";
			}
			if($q['anggota_id'] != '') {
				$q['anggota_id'] = str_replace('AG', '', $q['anggota_id']);
				$sql .=" AND (id LIKE '".$q['anggota_id']."' OR nama LIKE '".$q['anggota_id']."') ";
			}
		}
		$sql .= "LIMIT ".$start.", ".$limit." ";
		//$this->db->limit($limit, $start);
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			$out = $query->result();
			return $out;
		} else {
			return array();
		}
	}

//panggil data jenis simpan untuk laporan
	function lap_jenis_simpan() {
		$this->db->select('*');
		$this->db->from('jns_simpan');
		$this->db->where('tampil','Y');
		$query = $this->db->get();
		if($query->num_rows()>0){
			$out = $query->result();
			return $out;
		} else {
			return FALSE;
		}
	}

	
}