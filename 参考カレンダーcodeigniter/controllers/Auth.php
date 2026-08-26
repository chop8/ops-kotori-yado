<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct()
    {
		parent::__construct();
		
		//Import
		$this->load->library(array('form_validation', 'typography'));
        $this->load->helper(array('url', 'cookie'));
		
		//Model
		$this->load->model('Calendar_conf');

		//共通ライブラリ
		$this->load->library('MY_libraries');

        //設定情報
        $this->conf = $this->Calendar_conf->get_entry();
    }

    /**
     * ロック解除
     */
    public function unlock ()
    {
        $pass = $this->input->post('pass');
        if (!$pass) {
            redirect(base_url());
        }
        $start_date = $this->input->post('start_date');
        if ($start_date) {
            $start_date = date("Y-m-d", strtotime($this->input->get('p')));
        }
        if ($pass == $this->conf->password) {

            // 認証OK
            //ログイン用のクッキーをセット
            $expire = 0;//86400 * 30;
            $cookie = array(
                'name'   => 'is_login',
                'value'  => true,
                'expire' => $expire
            );
            $this->input->set_cookie($cookie);

            $expire = 86400 * 30;
            $cookie = array(
                'name'   => 'pass',
                'value'  => $pass,
                'expire' => $expire
            );
            $this->input->set_cookie($cookie);

            //リダイレクト（戻る）
            if ($start_date) {
                redirect(base_url().'?p='.$start_date);
            } else {
                redirect(base_url());
            }

        }

        //エラー
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('pageTitle', '');
        $this->smarty->assign('menu_on', 'error');
        $this->smarty->assign('include_file', 'include_error.html');
        $this->smarty->display('main_frame.html');
    }

    /**
     * ロック
     */
    public function lock ()
    {
        //クッキーの削除
        $expire = -1;
        $cookie = array(
            'name'   => 'is_login',
            'value'  => '',
            'expire' => $expire
        );
        $this->input->set_cookie($cookie);

        //リダイレクト（戻る）
        $start_date = $this->input->post('start_date');
        if ($start_date) {
            redirect(base_url().'?p='.$start_date);
        } else {
            redirect(base_url());
        }
    }

    /**
     * パスワードの変更
     */
    public function editpass ()
    {
        //ログイン状態
        $is_login = $this->input->cookie('is_login');
        $this->smarty->assign('is_login', $is_login);

        //認証
        if ($is_login) {
            if (@$_POST['pass']) {
                //データベース更新
                $this->Calendar_conf->update_password();
                //sw
                $this->smarty->assign('is_edited', true);
            }
        }

        //エラー
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('pageTitle', '');
        $this->smarty->assign('menu_on', 'editpass');
        $this->smarty->assign('include_file', 'include_auth_editpass.html');
        $this->smarty->display('main_frame.html');
    }
}
?>