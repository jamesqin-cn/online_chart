<?php

/**
  *
图表图例
图例参数：

chdl=<第一组数据图例>|<第 n 组数据图例>

图例chdl与颜色属性chco联合使用。

  */
class chdl
{
	private $_strChdl = null;
	private $_arrChdl = null;
	
	function __construct($strChdl = null)
	{
		$this->_strChdl = $strChdl;
		$this->_strChdl = urldecode($this->_strChdl);
		$this->_strChdl = mb_convert_encoding($this->_strChdl, 'gbk', 'utf8');
		$this->parse();
	}
	
	private function parse()
	{
		if (!empty($this->_strChdl)) {
			$this->_arrChdl = explode('|', $this->_strChdl);
		}
	}
	
	public function getVal()
	{
		return $this->_arrChdl;
	}	

	public function getMaxLen()
	{
		$max = 0;
		if (!empty($this->_arrChdl)) {
			foreach($this->_arrChdl as $str) {
				$max = max($max, mb_strlen($str));
			}
		}
		return $max;
	}
}
