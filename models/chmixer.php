<?php

/**
  *
水印参数：

chmixer=<图表标题>
使用加号+代表空格。

竖线|表示换行。

  */
class chmixer
{
	private $_strChmixer  = null;
	
	function __construct($strChmixer = null)
	{
		$this->_strChmixer = $strChmixer;		
		$this->parse();
	}
	
	private function parse()
	{
		if (!empty($this->_strChmixer)) {
			$this->_strChmixer = str_replace('+', ' ', $this->_strChmixer);
			$this->_strChmixer = str_replace('|', "\n", $this->_strChmixer);
			$this->_strChmixer = urldecode($this->_strChmixer);
			$this->_strChmixer = mb_convert_encoding($this->_strChmixer, 'gbk', 'utf8');
		}
	}
	
	public function getMixer()
	{
		return $this->_strChmixer;
	}
}
