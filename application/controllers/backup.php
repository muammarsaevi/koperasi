<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends MY_Controller {

	function __construct()
    {
        parent::__construct();
    }


    public function backup_database(){

        $this->load->dbutil();

        $prefs = array(     
            'format'      => 'sql',             
            'filename'    => "nananina_".date("Ymd-His").'.sql'
            );

        $backup =& $this->dbutil->backup($prefs); 

        $db_name = "nananina_".date("Ymd-His") .'.sql';
        $save = FCPATH.'assets/db/'.$db_name;
        $this->load->helper('file');
        write_file($save, $backup); 


        $this->load->helper('download');
        force_download($db_name, $backup);

            }

}