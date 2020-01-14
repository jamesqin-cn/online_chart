<?php

/**
  *
图表数据
图表数据有三种格式： 

文本编码 使用正数 0 至 100 之间的浮点数。
配合数值缩放参数使用时，也可以使用负数。注意负数对地图图表无效。
每十个数值占据 5 个像素，在仅使用整数的情况下，适用于 500 像素以内的图表。如需要更高精度，可使用一位小数数字（如 92.6）。文本编码可用于所有类型的图表，但同时也是 URL 地址最长的数据编码形式。 
简单编码 使用大小写字母与数字，A代表0，B代表1，直至9代表61，共包含 62 个数值。
每个数值占据 5 个像素，适用于 300 像素大小的折线图和柱状图，所有图表类型都可以使用简单编码数据。该编码类型数据的图表 URL 地址长度最短。 
扩展编码 使用一对字母数字表示一个数据（还有其它字符，下文详解），AA代表0，AB代表1，直至..代表4095。
包含 4096 个数值，适用于高精度或大型图表。该编码类型数据的图表 URL 地址长度是简单编码的两倍。 

文本编码
文本编码格式为

chd=t:<数值字符串>

其中<数值字符串>为0.0至100.0的浮点数、-1（负一）和|（竖线）。

含义如下 

浮点数字0.0= 0，以此类推100.0= 100。 
负一-1表示空值。 
竖线|为数据分组符号。 

示例：chd=t:10.0,58.0,95.0|30.0,8.0,63.0

  */
class chd
{
	private $_strChd     = null;
	private $_strChdType = null;
	private $_strChdVal  = null;
	private $_arrChdVal  = null;
	
	function __construct($strChd)
	{
		if (empty($strChd)) {
			$error = "The parameter 'chd' must not be empty. ";
			require_once('./models/BadRequestException.php');
			throw new BadRequestException($error);
		}
		
		$this->_strChd = $strChd;
		$this->parse();
	}
	
	private function parse()
	{
		$arrChd = explode(":", $this->_strChd, 2);
		$this->_strChdType = $arrChd[0];
		$this->_strChdVal  = empty($arrChd[1]) ? null : $arrChd[1];
		
		switch ($this->_strChdType) {
			case 't':
				break;
			default:
				$error = "The parameter 'chd' have invalid type '{$this->_strChdType}'. ";
				require_once('./models/BadRequestException.php');
				throw new BadRequestException($error);
		}
		
		unset($this->_arrChdVal);
		$this->_arrChdVal = array();
		$i = $j = 0;
		
		$arrGroup = explode('|', $this->_strChdVal);
		if (!empty($arrGroup)) {
			foreach ($arrGroup as $group) {
				$row = explode(',', $group);
				if (!empty($group)) {
					$j = 0;
					foreach ($row as $one) {
						$one = trim($one);
						if (strlen($one) == 0) {
							// for pChart, when the value of point is missing, NULL means this point will be skip
							$this->_arrChdVal[$i][$j] = NULL;
						} else {
							$this->_arrChdVal[$i][$j] = floatval($one);
						}
						++$j;
					}
				}
									
				++$i;
			}
		}
	}
	
	public function getMaxNum()
	{
		$max = 0;
		foreach ($this->_arrChdVal as $row) {
			$thismax = max($row);
			
			if ($thismax > $max) {
				$max = $thismax;
			}			
		}
		return $max;
	}
	
	public function getMinNum()
	{
		$min = 0;
		foreach ($this->_arrChdVal as $row) {
			$thismin = min($row);
			
			if ($thismin < $min) {
				$min = $thismin;
			}
		}
		return $min;
	}	
	
	public function getVal()
	{
		return $this->_arrChdVal;
	}
}
