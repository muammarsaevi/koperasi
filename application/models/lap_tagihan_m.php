<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_tagihan_m extends CI_Model {

  function gettahun(){
  	$query = $this->db->query("SELECT YEAR(tgl_angsuran) AS tahun FROM v_hitung_pinjaman GROUP BY YEAR(tgl_angsuran) ORDER BY YEAR(tgl_angsuran) ASC");

  	return $query->result();
  }

  function bytanggal($tanggalawal,$tanggalakhir){
  	$query = $this->db->query("SELECT * from v_hitung_pinjaman where tgl_angsuran BETWEEN 'tanggalawal' and 'tanggalakhir' ORDER BY tgl_angsuran ASC ");

  	return $query->result();
  }

  function bybulan($tahun1,$bulanawal,$bulanakhir){
  	$query = $this->db->query("SELECT * from v_hitung_pinjaman where YEAR(tgl_angsuran )='$tahun1' and MONTH(tgl_angsuran) BETWEEN 'tanggalawal' and 'tanggalakhir' ORDER BY tgl_angsuran ASC ");

  	return $query->result();
  }

function bytahun($tahun2){
  	$query = $this->db->query("SELECT * from v_hitung_pinjaman where YEAR(tgl_angsuran )='$tahun2'  ORDER BY tgl_angsuran ASC ");

  	return $query->result();
  }

	}