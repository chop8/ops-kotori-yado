<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indexs extends CI_Controller {
	public function __construct()
    {
		parent::__construct();
		
		//Import
		$this->load->library(array('form_validation', 'typography'));
        $this->load->helper(array('url', 'cookie'));
		
		//Model
		$this->load->model('Calendar_conf');
        $this->load->model('Calendar_data');
        $this->load->model('Calendar_history');
        $this->load->model('Calendar_news');

		//共通ライブラリ
		$this->load->library('MY_libraries');

        //表示日数
        $this->count = 7;

        //漢曜日
        $this->youbi_array = array("日","月","火","水","木","金","土");

        //設定情報
        $this->conf = $this->Calendar_conf->get_entry();

        //ログイン状態
        $this->is_login = $this->input->cookie('is_login');
        $this->smarty->assign('is_login', $this->is_login);
        $this->smarty->assign('pass', $this->input->cookie('pass'));
    }
	
	/**
	 * カレンダー
	 */
	public function index()
	{
        //----------
        // 今月のお知らせ
        //----------
        // 開始月
        $m = $this->input->get('m');
        $m = ($m)?:date("Y-m");

        $row = $this->Calendar_news->get_entry($m."-01");
        $news = ($row)? $row->comment : '';
        $this->smarty->assign('news', $news);

        //----------
        // 対象月の情報を取得
        //----------
        // 開始月
        $m = $this->input->get('m');
        $m = ($m)?:date("Y-m");

        // 基準の日付
        $target_date = new DateTime($m);

        // 1日の曜日を取得（0が日曜日、1が月曜日、2が火曜日、...、6が土曜日）
        $first_day_of_month = new DateTime($target_date->format('Y-m-01'));
        $day_of_week = $first_day_of_month->format('w');

        // 年と月を取得
        $year = $target_date->format('Y');
        $month = $target_date->format('n');

        // 月の日数を取得
        $number_of_days_in_month = $target_date->format('t');

        // 前の月の日数を取得
        $previous_month = clone $target_date;
        $previous_month->modify('-1 month');
        $number_of_days_in_previous_month = $previous_month->format('t');

        $this->smarty->assign('year', $year);
        $this->smarty->assign('month', $month);
        $this->smarty->assign('is_m', ($this->input->get('m'))? true:false);

        //----------
        // 月送り用リンク
        //----------
        // 先月リンク
        $previous_month = clone $target_date;
        $previous_month->modify('-1 month');
        $prev = $previous_month->format('Y-m');

        // 翌月リンク
        $next_month = clone $target_date;
        $next_month->modify('+1 month');
        $next = $next_month->format('Y-m');

        $this->smarty->assign('prev', $prev);
        $this->smarty->assign('next', $next);

        //----------
        // 月配列を作成　7日x6週=42日間
        //----------
        $month_data = [];
        // 前月分
        for ($i=$day_of_week; $i>0; $i--) {
            $month_data[] = [
                'day_inactive' => $number_of_days_in_previous_month - $i + 1,
            ];
        }
        // 指定月内
        for ($i=1; $i<=$number_of_days_in_month; $i++) {
            // DB読み込み
            $row = $this->Calendar_data->get_entry($year."-".$month."-".$i);
            $schedule = ($row)? nl2br($row->comment) : "";
            // 配列化
            $month_data[] = [
                'day' => $i,
                'schedule' => $schedule,
            ];
        }
        // 翌月分
        $nokori = 42-count($month_data);
        for ($i=1; $i<=$nokori; $i++) {
            $month_data[] = [
                'day_inactive' => $i,
            ];
        }

        $this->smarty->assign('month_data', $month_data);

        //コメント
        $this->smarty->assign('info', $this->conf->info);

		//view
		$this->smarty->assign('base_url', base_url());
		
		$this->smarty->assign('pageTitle', '');
		$this->smarty->assign('menu_on', 'index');
		$this->smarty->assign('include_file', 'include_index.html');
		$this->smarty->display('main_frame.html'); 
	}

    //-----------------------------------------

    /**
     * Ajax
     * データの取得
     */
    public function ajax_get() {
        //認証
        if (!$this->is_login) {
            //エラー
            $json = array(
                'result' => false,
            );
            echo json_encode($json);
            return;
        }

        //GETデータ分割
        @list($type, $date) = preg_split("/::/",$_GET['datatype']);

        //月のお知らせ
        if ($type == 'news') {
            $row = $this->Calendar_news->get_entry($date);
            $comment = ($row)? $row->comment : '';
            $json = array(
                'result' => 1,
                'type' => 'news',
                'comment' => $comment
            );
            echo json_encode($json);
            return;
        }

        //案内情報
        if ($type == 'info') {
            $json = array(
                'result' => 1,
                'type' => 'info',
                'comment' => $this->conf->info
            );
            echo json_encode($json);
            return;
        }

        //スケジュール
        if ($type == 'schedule') {
            $row = $this->Calendar_data->get_entry($date);
            $comment = ($row)? $row->comment : '';
            $json = array(
                'result' => 1,
                'type' => 'schedule',
                'comment' => $comment
            );
            echo json_encode($json);
            return;
        }

    }

    /**
     * Ajax
     * データの更新
     */
    public function ajax_edit() {
        //認証
        if (!$this->is_login) {
            //エラー
            $json = array(
                'result' => false,
            );
            echo json_encode($json);
            return;
        }

        //GETデータ分割
        @list($type, $date) = preg_split("/::/",$_GET['datatype']);

        //月のお知らせ
        if ($type == 'news') {
            $this->Calendar_news->update_entry($date, $_GET['comment']);
            $json = array(
                'result' => 1,
                'type' => 'news',
                'date' => $date,
                'comment' => $_GET['comment']
            );
            echo json_encode($json);
            //ログの記録
            $this->_record_history ($_GET['datatype'], $_GET['comment']);
            return;
        }

        //案内情報
        if ($type == 'info') {
            $data = array('info' => $_GET['comment']);
            $this->Calendar_conf->update_entry($data);
            $json = array(
                'result' => 1,
                'type' => 'info',
                'comment' => $_GET['comment']
            );
            echo json_encode($json);
            //ログの記録
            $this->_record_history ($_GET['datatype'], $_GET['comment']);
            return;
        }

        //スケジュール
        if ($type == 'schedule') {
            $this->Calendar_data->update_entry($date, $_GET['comment']);
            $json = array(
                'result' => 1,
                'type' => 'schedule',
                'date' => $date,
                'comment' => nl2br(trim($_GET['comment']))
            );
            echo json_encode($json);
            //ログの記録
            $this->_record_history ($_GET['datatype'], $_GET['comment']);
            return;
        }
    }

    /**
     * ログの記録
     */
    private function _record_history ($datatype, $comment) {
        //$this->Calendar_history->insert_entry($datatype, $comment);
    }
}
?>