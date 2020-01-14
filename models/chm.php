<?php

/**
  *
Shape Marker

Sample: chm=N,FF0000,0,-0.5,5  标数字
Sample: chm=o,FF0000,0,-0.5,5  标圆圈
Sample: chm=-                  不标marker

  */
class chm
{
	private $_strChm = null;
	private $_arrChm = null;
	
	function __construct($strChm = null)
	{
		$this->_strChm = $strChm;		
		$this->_arrChm = array();
		$this->parse();
	}
	
	private function parse()
	{
		if (!empty($this->_strChm)) {
			$this->_arrChm = explode(',', $this->_strChm);
		}
	}
	
	public function getVal()
	{
		return $this->_arrChm;
	}	

	public function getMarkerType()
	{
		if (isset($this->_arrChm[0])) {
			return $this->_arrChm[0];
		}

		return null;
	}

	public function getMarkerColor()
	{
		if (isset($this->_arrChm[1])) {
			return $this->_arrChm[1];
		}

		return null;
	}
}
