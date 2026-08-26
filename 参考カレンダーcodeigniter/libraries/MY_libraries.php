<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * MY libraries Class 
 *
 * 共通ライブラリファイル
 */
class MY_libraries {
	
	var $CI;
	
	function __construct()
    {
		$this->CI = & get_instance();
		
		/**
		 * XXXXXX
		 * @var array
		 */
		//define('XXXXXXXXXXXX', true);

    }

    /**
     * XOR id 暗号化
     * @param int $id
     * @return string
     */
    function xor_id_encrypt($id) {
        $plain = $id.'::'.$id;
        $seed=str_repeat('key27',strlen($plain));
        return bin2hex($plain^$seed);
    }
    /**
     * XOR id 複合
     * @param string $xor
     * @return int
     */
    function xor_id_decrypt($xor) {
        $seed=str_repeat('key27',strlen($xor));
        $plain = pack("H*",$xor)^$seed;
        list($prefix, $id) = preg_split("/::/",$plain);
        return $id;
    }

    //----------------------------------------------------------------------------------------------------

    /**
     * XOR 暗号化
     *
     * @return string
     */
    function xor_encrypt($plain,$key) {
        $seed=str_repeat($key,strlen($plain));
        return bin2hex($plain^$seed);
    }
    /**
     * XOR 複合
     *
     * @return string
     */
    function xor_decrypt($enc,$key) {
        $seed=str_repeat($key,strlen($enc));
        return pack("H*",$enc)^$seed;
    }

    //----------------------------------------------------------------------------------------------------
	
	/**
	 * 一部タグ除去
	 * (出力時に利用)
     * @param string $str
     * @return string
	 */
	function i_strip_tags($str)
	{
		$str = preg_replace('/size=("|\')\d{1,3}("|\')?/i', '', $str);
		$str = preg_replace('/<font >|<font>/i', '', $str);
		$str = preg_replace('/<br><br>/i', '<br>', $str);
		$str = strip_tags($str,'<a><br><font>');
		return $str;
	}
	
	//----------------------------------------------------------------------------------------------------会員登録機能
	
	/**
	* 認証用パスワードの作成
	*
	* @return string
	*/
	function make_pass(){
		$key_array1 = array(16,17,18,19,20);
		$key_array2 = array('a','b','c','d','1','e','f','2','g','h','3','i','j','k','4','l','m','5','n','6','o','p','7','q','8','r','s','t','u','v','w','9','x','y','z');
		$pass_array = array_rand($key_array2,$key_array1[array_rand($key_array1)]);
		$key = "";
		foreach($pass_array as $value){
			$key .= $key_array2[$value];
		}
		return $key;
	}

    //----------------------------------------------------------------------------------------------------
    /**
     * 祝日一覧の取得
     *
     * @return array
     */
    function get_holidays() {
        // 祝日一覧（内閣府csv）
        $url = 'https://www8.cao.go.jp/chosei/shukujitsu/syukujitsu.csv';
        $csv = @file_get_contents( $url );
        $holidays = array();
        if ($csv) {
            $csv = mb_convert_encoding($csv, 'UTF-8', 'SJIS');
            $lines = explode("\n", $csv);
            array_shift($lines);
            foreach ($lines as $line) {
                if(!$line){continue;}
                $cols = explode(",", $line);
                $date = date("Y-m-d", strtotime($cols[0]." 12:12:12"));
                $name = trim(@$cols[1]);
                $holidays[$date] = $name;
            }
        }
        return $holidays;
    }
    //----------------------------------------------------------------------------------------------------
	
	/**
	 * パスワードハッシュの作成
	 * 
     * @param string $password
     * @return password_hash
	 */
	function password_hash($password) {
		//salt22文字の生成
		$str  = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'),array(".","/"));
		$max  = 22;
		$salt = "$2a$07$";
		for ($i = 1; $i <= $max; $i++) {
			$salt .= $str[rand(0, count($str)-1)];
		}
		
		//ハッシュ値
		$hash = crypt($password, $salt);
		return $hash;
	}
	
	/**
	 * パスワードハッシュの作成
	 * 
     * @param string $password
     * @return boolean 1:OK / 0:NG
	 */
	function password_verify($password, $hash) {
		//照合
		if(crypt($password, $hash) == $hash){
			//OK
			return true;
		} else {
			//NG
			echo false;
		}
	}
	
	//----------------------------------------------------------------------------------------------------
	/**
	 * XSS Filter
	 * 
     * @param string $str
     * @return string
	 */
	function my_xss_filter($str)
	{
		$str = str_replace(array('<?', '?'.'>'),  array('&lt;?', '?&gt;'), $str);
		$str = preg_replace('/<\?(php)/i', "&lt;?\\1", $str);
		
		$words = array(
				'javascript', 'expression', 'vbscript', 'script',
				'applet', 'alert', 'document', 'write', 'cookie', 'window'
			);

		foreach ($words as $word)
		{
			$word = implode("\s*", str_split($word)) . "\s*";

			// We only want to do this when it is followed by a non-word character
			// That way valid stuff like "dealer to" does not become "dealerto"
			$str = preg_replace_callback('#('.substr($word, 0, -3).')(\W)#is', array($this, '_compact_exploded_words'), $str);
		}
		
			if (preg_match("/<a/i", $str))
			{
				$str = preg_replace_callback("#<a\s+([^>]*?)(>|$)#si", array($this, '_js_link_removal'), $str);
			}

			if (preg_match("/<img/i", $str))
			{
				$str = preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si", array($this, '_js_img_removal'), $str);
			}

			if (preg_match("/script/i", $str) OR preg_match("/xss/i", $str))
			{
				$str = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '[removed]', $str);
			}	
				
		$naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
		$str = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', array($this, '_sanitize_naughty_html'), $str);
		$str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);
		
		return $str;
	}
	protected function _compact_exploded_words($matches)
	{
		return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
	}
	protected function _js_link_removal($match)
	{
		$attributes = $this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]));

		return str_replace($match[1], preg_replace("#href=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
	}
	protected function _js_img_removal($match)
	{
		$attributes = $this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]));

		return str_replace($match[1], preg_replace("#src=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
	}
	protected function _filter_attributes($str)
	{
		$out = '';

		if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches))
		{
			foreach ($matches[0] as $match)
			{
				$out .= preg_replace("#/\*.*?\*/#s", '', $match);
			}
		}

		return $out;
	}
	protected function _sanitize_naughty_html($matches)
	{
		// encode opening brace
		$str = '&lt;'.$matches[1].$matches[2].$matches[3];

		// encode captured opening or closing brace to prevent recursive vectors
		$str .= str_replace(array('>', '<'), array('&gt;', '&lt;'),
							$matches[4]);

		return $str;
	}
}
?>