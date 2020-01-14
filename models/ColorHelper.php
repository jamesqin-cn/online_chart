<?php

class ColorHelper
{
	/**
	 * 获取随即颜色
	 *
	 * 预设5种经典的颜色在前面
	 *
	 * @param int $colorIndex 颜色种类
	 * @return hex 随即颜色的十六进制
	 */
	public static function getRndColor($colorIndex = 0)
	{
		static $count = 0;
		//static $colorArray = array('AFD8F8', 'F6BD0F', '8BBA00', 'A66EDD', 'F984A1', '68A3A6');
		//static $colorArray = array('76CCDE', 'F6BD0F', '8BBA00', 'FFA56D', '2E8E8E', '68A3A6');
		static $colorArray = array('EE7942', 'FF0000', 'FF00CC', '8B8B00', '00CCFF', '00FFCC', 
		                           'BCE02E', 'E0642E', 'E0D62E', '2E97E0', 'B02EE0', 'E02E75', 
								   '5CE02E', 'E0B02E');

		if ($colorIndex == 0) {
			++$count;
		} else {
			$count = $colorIndex;
		}

		if ($count <= count($colorArray)) {
			return $colorArray[$count - 1];
		}

		return dechex(rand(0,255)).dechex(rand(0,255)).dechex(rand(0,255));
	}
}

