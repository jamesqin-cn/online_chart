<?php

/**
  *
饼图标注
饼图标注参数：

chl=<标注一>|
...
<标注 n> 

双竖线||表示空值。


  */
class chl
{
	private $_strChl = null;
	private $_arrChl = null;
	
	function __construct($strChl = null)
	{
		$this->_strChl = $strChl;		
		$this->parse();
	}
	
	private function parse()
	{
		if (!empty($this->_strChl)) {
			$this->_arrChl = explode('|', $this->_strChl);
		}

		if (!empty($this->_arrChl)) {
			foreach ($this->_arrChl as $key => $val) {
				$this->_arrChl[$key] = urldecode(mb_convert_encoding($val, 'gbk', 'utf8'));
			}
		}
	}
	
	public function getVal()
	{
		return $this->_arrChl;
	}	
}
