<?php
class Request
{
	/**
	 * 静态GET数组
	 */
	public static $gets	= array();
	
	/**
	 * 静态POST数组
	 */
	public static $posts = array();
	
	/**
	 * 取$_GET[] ?变量
	 *
	 * 取$_GET[] ?变量,可选择是否过滤
	 *
	 * @param string 变量标识
	 * @param string 编码转换，false不转换，UG从UTF-8转到GBK，GU从GBK转换到UTF-8
	 * @param bool TRUE不过滤，FALSE过滤
	 * @return string
	 */
	static public function g($key, $orig=false)
	{
		$get_key = isset($_GET[$key]) ? $_GET[$key] : '';
		if($orig)
		{
			if(get_magic_quotes_gpc())
			{	
				return stripslashes($get_key);
			}
			else
			{
				return $get_key;
			}
		}
		
		if(!isset(self::$gets[$key]))
		{
			if(is_array($get_key))
			{
				self::$gets[$key]	= self::parse_incoming_recursively($get_key);
			}
			else
			{
				self::$gets[$key]	= self::parse_clean_value($get_key);
			}
		}
		
		return self::$gets[$key];
	}
	
	
	/**
	 * $_POST[] ?
	 *
	 * 取$_POST[] ?变量,可选择是否过滤
	 *
	 * @param string 变量标识
	 * @param bool TRUE不过滤，FALSE过滤
	 * @return string
	 */
	static public function p($key, $orig=false)
	{
		if($orig)
		{
			if(get_magic_quotes_gpc())
			{
				return stripslashes($_POST[$key]);
			}
			else
			{
				return $_POST[$key];
			}
		}
		
		if(!isset(self::$posts[$key]))
		{
			if(is_array($_POST[$key]))
			{
				self::$posts[$key]	= self::parse_incoming_recursively($_POST[$key]);
			}
			else
			{
				self::$posts[$key]	= self::parse_clean_value($_POST[$key]);
			}
		}
		
		return self::$posts[$key];
	}		
	
	/**
	 * 过滤传入数组的键和值
	 *
	 * 通过parse_clean_key()和parse_clean_value()过滤传入的数组
	 *
	 * @param array 需要过滤整理的数组
	 * @param int 当前展开的级数，最大展开级为10
	 * @return array 过滤后的数组
	 */
	public static function parse_incoming_recursively(&$data, $iteration=0)
	{
		$input	= array();
		
		if($iteration >= 10)
		{
			return $input;
		}

		if(count($data))
		{
			foreach($data as $k => $v)
			{
				if(empty($k))
				{
					$k	= '0';
				}
				if(is_array($v))
				{
					$input[$k] = self::parse_incoming_recursively($data[$k], array(), $iteration+1);
				}
				else
				{	
					$k = self::parse_clean_key($k);
					$v = self::parse_clean_value($v);

					$input[$k] = $v;
				}
			}
		}

		return $input;
	}

	/**
	 * 过滤数组键名中的非法字符
	 *
	 * 过滤可运行的HTML标签
	 *
	 * @param string 键名
	 * @return string 过滤后的键名
	 */
	public static function parse_clean_key($key)
	{
		if ($key == "")
		{
			return "";
		}

		$key = htmlspecialchars(urldecode($key));
		$key = str_replace(".." , "", $key);
		$key = preg_replace("/\_\_(.+?)\_\_/", "", $key);
		$key = preg_replace("/^([\w\.\-\_]+)$/", "$1", $key);

		return $key;
	}
	
	/**
	 * 过滤数组值中的非法字符
	 *
	 * 过滤可运行的HTML标签
	 *
	 * @param string 值
	 * @return string 过滤后的值
	 */
	public static function parse_clean_value($val)
	{
		if($val == "")
		{
			return "";
		}

		$val = str_replace( "&#032;", " ", self::txt_stripslashes($val) );

		$val = str_replace( "&#8238;"		, ''			  , $val );

		$val = str_replace( "&"				, "&amp;"         , $val );
		$val = str_replace( "<!--"			, "&#60;&#33;--"  , $val );
		$val = str_replace( "-->"			, "--&#62;"       , $val );
		$val = preg_replace( "/<script/i"	, "&#60;script"   , $val );
		$val = str_replace( ">"				, "&gt;"          , $val );
		$val = str_replace( "<"				, "&lt;"          , $val );
		$val = str_replace( '"'				, "&quot;"        , $val );
		$val = str_replace( "\n"			, "<br />"        , $val ); // Convert literal newlines
		$val = str_replace( "$"				, "&#036;"        , $val );
		$val = str_replace( "\r"			, ""              , $val ); // Remove literal carriage returns
		$val = str_replace( "!"				, "&#33;"         , $val );
		$val = str_replace( "'"				, "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.

		$val = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $val);
		$val = preg_replace("/&#(\d+?)([^\d;])/i", "&#\\1;\\2", $val);

		return $val;
	}
	
	/**
	 * 过滤字符串中生成的斜杠
	 *
	 * 过滤可运行的HTML标签
	 *
	 * @param string 需要格式化的字符串
	 * @return string 格式化后的字符串
	 */
	public static function txt_stripslashes($t)
	{
		if(@get_magic_quotes_gpc())
		{
			$t = stripslashes($t);
			$t = preg_replace("/\\\(?!&amp;#|\?#)/", "&#092;", $t);
		}
		return $t;
	}		
}
