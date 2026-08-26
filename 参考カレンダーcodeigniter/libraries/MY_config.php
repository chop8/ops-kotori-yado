<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * MY config Class 
 *
 * 共通設定ファイル
 */
class MY_config {
	
	var $CI;
	
	function __construct()
    {
		$this->CI = & get_instance();
		
		/**
		 * Smarty フォルダ
		 */
        $this->CI->smarty->template_dir = APPPATH . 'views/templates';
        $this->CI->smarty->compile_dir  = APPPATH . 'views/templates_c';
		$this->CI->smarty->error_reporting = E_ALL & ~E_NOTICE;
		
		/**
		 * KCFinder フォルダ指定
		 */
		define('KCuploadDir', 'kc/');
        $this->CI->smarty->assign('KCuploadDir', KCuploadDir);
    }
}
?>