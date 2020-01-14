<?php

/**
  *
参数格式：chs=<像素宽度>x<像素高度>

例如：chs=300x200，表示一个 300 像素宽、200 像素高的图表。

单个图表最大尺寸为 300,000 平方像素，宽高最大为 1000 像素，以下尺寸均为最大可用尺寸：1000x300，300x1000，600x500，500x600，800x375 和 375x800 等。

地图最大尺寸 440 像素宽和 220 像素高。

尺寸过小时，饼图将仅显示部分图表。饼图建议尺寸： 

二维饼图：宽度约为高度两倍。 
三维饼图：宽度约为高度两倍半。 

  */
class chs
{
	private $_strChs  = null;
	private $_iWidth  = 0;
	private $_iHeight = 0;
	
	function __construct($strChs)
	{
		if (empty($strChs)) {
			$error = "The parameter 'chs' must not be empty. ";
			require_once('./models/BadRequestException.php');
			throw new BadRequestException($error);
		}
		
		$this->_strChs = $strChs;		
		$this->parse();
	}
	
	private function parse()
	{
		$arr = explode('x', $this->_strChs);
		if (count($arr) != 2) {
			$error = "The parameter 'chs' does not match the expected format.";
			require_once('./models/BadRequestException.php');
			throw new BadRequestException($error);
		}
		
		$this->_iWidth  = intval($arr[0]);
		$this->_iHeight = intval($arr[1]);
	}
	
	public function getWidth()
	{
		return $this->_iWidth;
	}	
	
	public function getHeight()
	{
		return $this->_iHeight;
	}
}