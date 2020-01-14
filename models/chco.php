<?php

/**
  *
数据颜色
设定折线图、柱状图、维恩图和饼图数据颜色。

chco=
<颜色参数1>,
...
<颜色参数n>

其中<颜色参数1>及后续延续参数均使用十六进制参数。

  */
class chco
{
	private $_strChco = null;
	private $_arrChco = null;
	
	function __construct($strChco = null)
	{
		$this->_strChco = $strChco;		
		$this->parse();
	}
	
	private function parse()
	{
		if (!empty($this->_strChco)) {
			$this->_arrChco = explode(',', $this->_strChco);
		} else {
			// 默认颜色
			$this->_arrChco = array('4d89f9');
		}
	}
	
	public function getVal()
	{
		return $this->_arrChco;
	}	
}
