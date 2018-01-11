<?php
class Request
{
	/**
	 * ��̬GET����
	 */
	public static $gets	= array();
	
	/**
	 * ��̬POST����
	 */
	public static $posts = array();
	
	/**
	 * ȡ$_GET[] ?����
	 *
	 * ȡ$_GET[] ?����,��ѡ���Ƿ����
	 *
	 * @param string ������ʶ
	 * @param string ����ת����false��ת����UG��UTF-8ת��GBK��GU��GBKת����UTF-8
	 * @param bool TRUE�����ˣ�FALSE����
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
	 * ȡ$_POST[] ?����,��ѡ���Ƿ����
	 *
	 * @param string ������ʶ
	 * @param bool TRUE�����ˣ�FALSE����
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
	 * ���˴�������ļ���ֵ
	 *
	 * ͨ��parse_clean_key()��parse_clean_value()���˴��������
	 *
	 * @param array ��Ҫ�������������
	 * @param int ��ǰչ���ļ��������չ����Ϊ10
	 * @return array ���˺������
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
	 * ������������еķǷ��ַ�
	 *
	 * ���˿����е�HTML��ǩ
	 *
	 * @param string ����
	 * @return string ���˺�ļ���
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
	 * ��������ֵ�еķǷ��ַ�
	 *
	 * ���˿����е�HTML��ǩ
	 *
	 * @param string ֵ
	 * @return string ���˺��ֵ
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
	 * �����ַ��������ɵ�б��
	 *
	 * ���˿����е�HTML��ǩ
	 *
	 * @param string ��Ҫ��ʽ�����ַ���
	 * @return string ��ʽ������ַ���
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
