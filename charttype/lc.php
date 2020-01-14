<?php

// Standard inclusions      
//include("pChart/pData.class");   
//include("pChart/pChart.class");   

require_once('./models/chd.php');
require_once('./models/chs.php');
require_once('./models/chtt.php');
require_once('./models/chmixer.php');
require_once('./models/chl.php');
require_once('./models/chm.php');
require_once('./models/chco.php');
require_once('./models/chdl.php');
require_once('./models/chxl.php');
require_once('./models/ch2y.php');

class lc 
{
	private $_chd  = null;
	private $_chs  = null;
	private $_chtt = null;
	private $_chmixer = null;
	private $_chl  = null;
	private $_chco = null;
	private $_chdl = null;
	private $_chxl = null;
	private $_ch2y = null;
	
	function __construct()
	{
		// 必选参数
		$this->_chd  = new chd($_REQUEST['chd']);
		$this->_chs  = new chs($_REQUEST['chs']);
		
		// 可选参数
		@$this->_chtt = new chtt($_REQUEST['chtt']);
		@$this->_chmixer = new chmixer($_REQUEST['chmixer']);
		@$this->_chl  = new chl($_REQUEST['chl']);
		@$this->_chm  = new chm($_REQUEST['chm']);
		@$this->_chco = new chco($_REQUEST['chco']);
		@$this->_chdl = new chdl($_REQUEST['chdl']);
		@$this->_chxl = new chxl($_REQUEST['chxl']);
		@$this->_ch2y = new ch2y($_REQUEST['ch2y']);
	}
	
	public function run()
	{
		$arrVal     = $this->_chd->getVal();
		$chxl       = $this->_chxl->getVal();
		$arrColor   = $this->_chco->getVal();
		$arrLegends = $this->_chdl->getVal();	

		// verify val, make sure all the same of each serie 
		$maxArrSize = 0;
		foreach ($arrVal as $row) {
			$size = count($row);
			$maxArrSize = max($maxArrSize, $size);
		}
		
		foreach ($arrVal as $index => $row) {
			$size = count($row);
			for ($i = $size; $i < $maxArrSize; $i++) {
				$arrVal[$index][$i] = null;
			}
		}
		
		// verify legend
		foreach ($arrVal as $i => $row) {
			if (!isset($arrLegends[$i])) {
				$arrLegends[$i] = '';
			}

            $arrLegends[$i] = mb_convert_encoding($arrLegends[$i], 'UTF8', 'GBK');
		}

		// verify lable，确保x轴标注数量与点阵对齐, 不足要补齐，超出要截掉，否则pchart会抛出异常
		if (empty($chxl)) {
			$chxl[0] = array();
		}
		
		foreach ($arrVal[0] as $i => $v) {
			if (!isset($chxl[0][$i])) {
				$chxl[0][$i] = $i+1;
			}
		}
		
		$size_of_val = count($arrVal[0]);
		for ($i = $size_of_val; $i <= count($chxl[0]); $i++) {
			unset($chxl[0][$i]);
		}
		
		// 智能算法部分，对margin、x轴坐标等细节，根据实际参数进行智能修改，确保样式最美观
		
		// 智能算法1：调整margin
		$maxVal = $this->_chd->getMaxNum();
		if (abs($maxVal) > 1) {
			$maxVal = number_format($maxVal);
			$len = strlen(strval($maxVal));
			$leftMargin = 25 + $len * 5;
		} else {
			$leftMargin = 38;
		}

		$len = $this->_chdl->getMaxLen();
		
		// 根据title、value值对margin动态调整
		$title = $this->_chtt->getTitle();
		$topMargin = (empty($title)) ? 10 : 30;
		
		// 智能算法2：动态计算legend位置
		$rightMagin =  20;

        if (0 != $this->_chdl->getMaxLen()) {
            $rightMagin += 12 + 7 * $this->_chdl->getMaxLen();
        }
		
		// 智能算法3：砍掉部分x轴文字，横坐标太多时，自动空档跳过，避免x轴过于挤压
		$oneitem_space = ($this->_chs->getWidth() - $leftMargin - $rightMagin) / count($chxl[0]);
        $section_width = (3 + $this->_chxl->getMaxStrLenOfChxl()) * 7;
		$chxl_space = $section_width / $oneitem_space;
		if (!empty($chxl) && !empty($chxl[0])) {
			$chxl_with_skip = array();
			//$chxl_space = round(count($chxl[0]) / 15);
			if ($chxl_space <= 1) {
				$chxl_with_skip = $chxl[0];
			} else {
				foreach ($chxl[0] as $index => $x) {
					if ($index % $chxl_space == 0) {
						$chxl_with_skip[] = $x;
					} else {
						$chxl_with_skip[] = '';
					}
				}
			}
		}
		
		// Dataset definition    
		$DataSet = new pData;
		$arrDataSerie = array();
		foreach ($arrVal as $i => $row) {
			$arrDataSerie[] = "Serie".$i;
			$DataSet->AddPoint($row, "Serie".$i); 
			$DataSet->AddSerie("Serie".$i);
			$DataSet->SetSerieName($arrLegends[$i], "Serie".$i);
		}

        //$DataSet->SetYAxisFormat();

		$DataSet->AddPoint($chxl_with_skip, "XAxisLables"); 
		$DataSet->SetAbsciseLabelSerie("XAxisLables");   

		// Initialise the graph   
		$Test = new pChart($this->_chs->getWidth(), $this->_chs->getHeight());
		$Test->setFontProperties(DOC_ROOT . "/fonts/tahoma.ttf",8);   
		$Test->setGraphArea($leftMargin, $topMargin, $this->_chs->getWidth()-$rightMagin, $this->_chs->getHeight()-30);   

		//$Test->setFixedScale(min(0,$this->_chd->getMinNum()),$this->_chd->getMaxNum()*1.1,5);			
		//$Test->drawFilledRoundedRectangle(7,7,693,223,3,240,240,240);   
		//$Test->drawRoundedRectangle(5,5,695,225,3,230,230,230);   
		$Test->drawGraphArea(255,255,255,TRUE);
		$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,50,50,50,TRUE,0,0); 
		$Test->drawGrid(4,TRUE,230,230,230,50);

        // set color for each line
        require_once('./models/ColorHelper.php');
        $arrColor = $this->_chco->getVal();
        $colorID = 0;
        foreach ($arrVal as $row) {
            if (empty($arrColor) || empty($arrColor[$colorID])) {
                $color = ColorHelper::getRndColor();
            } else {
                $color = $arrColor[$colorID];
            }
            $color = base_convert($color, 16, 10);
            $Test->setColorPalette($colorID, ($color & 0xFF0000) >> 16, ($color & 0x00FF00) >> 8, $color & 0x0000FF);
            $colorID++;
        }

		// 添加水印
		$Test->setFontProperties(DOC_ROOT . "/fonts/msyhbd.ttf",24);   
		$mixer = $this->_chmixer->getMixer();
		if (!empty($mixer)) {
			$Test->drawTitle($this->_chs->getWidth()/2,$this->_chs->getHeight()/2,
					mb_convert_encoding($mixer, 'utf8', 'gbk'),0xE6,0xE6,0xE6);   
		}

		// Draw the 0 line   
		//$Test->setFontProperties("./fonts/tahoma.ttf",6);   
		//$Test->drawTreshold(0,143,55,72,FALSE);   

		// Draw the line graph
		$Test->setFontProperties(DOC_ROOT . "/fonts/tahoma.ttf",8);   
		$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());   

		if ($this->_chm->getMarkerType() != '-') {
			$Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);   
		}

		if ($this->_chm->getMarkerType() == 'N') {
			$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),$arrDataSerie);
		}

		// Finish the graph   
		$Test->setFontProperties(DOC_ROOT . "/fonts/simsun.ttc",8);   
		$Test->drawLegend($this->_chs->getWidth()-$rightMagin+5, $topMargin+5, $DataSet->GetDataDescription(), 255,255,255);   

		$Test->setFontProperties(DOC_ROOT . "/fonts/msyh.ttf",10);   
		$Test->drawTitle(0,22,mb_convert_encoding($this->_chtt->getTitle(), 'utf8', 'gbk'),20,20,20,$this->_chs->getWidth());   

		$Test->Stroke();
	}
}
