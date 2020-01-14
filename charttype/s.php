<?php

//require_once("jpgraph/jpgraph.php");
//require_once("jpgraph/jpgraph_scatter.php");

require_once('./models/chd.php');
require_once('./models/chs.php');
require_once('./models/chtt.php');
require_once('./models/chl.php');
require_once('./models/chco.php');
//require_once('./models/chdl.php');
require_once('./models/chxl.php');

class s 
{
	private $_chd  = null;
	private $_chs  = null;
	private $_chtt = null;
	private $_chl  = null;
	private $_chco = null;
	//private $_chdl = null;
	private $_chxl = null;
	
	function __construct()
	{
		// 必选参数
		$this->_chd  = new chd($_REQUEST['chd']);
		$this->_chs  = new chs($_REQUEST['chs']);
		
		// 可选参数
		@$this->_chtt = new chtt($_REQUEST['chtt']);
		@$this->_chl  = new chl($_REQUEST['chl']);
		@$this->_chco = new chco($_REQUEST['chco']);
		//@$this->_chdl = new chdl($_REQUEST['chdl']);
		@$this->_chxl = new chxl($_REQUEST['chxl']);
	}
	
	public function run()
	{
		// Create the graph. These two calls are always required
		$graph = new Graph($this->_chs->getWidth(), $this->_chs->getHeight());  
		$graph->SetScale("linlin");
		 
		$graph->SetShadow();
		$graph->SetFrame(false);		
		 
		// Create the bar plots
		$arrPlot = array();
		
		$arrVal   = $this->_chd->getVal();
		$arrColor = $this->_chco->getVal();
		//$arrLegends = $this->_chdl->getVal();

		$maxVal = $this->_chd->getMaxNum();
		$maxVal = number_format($maxVal);
		$len = strlen(strval($maxVal));
		$leftMargin = 15 + $len * 7;
		
		// 根据title、value值对margin动态调整
		$title = $this->_chtt->getTitle();
		if (empty($title)) {
			$graph->img->SetMargin($leftMargin,20,10,40);
		} else {
			$graph->img->SetMargin($leftMargin,20,35,40);
		}		

		// 至少要有一个颜色，缺颜色就用前一个颜色补
		if (count($arrColor) == 0) {
			throw new Exception("class chco must return one color at least.");
			return;
		}
	
		// 散点图必须是两组数据，一组是x，一组是y
		if (count($arrVal) != 2) {
			throw new Exception("class chd must have two group dataset.");
			return;
		}

		$sp = new ScatterPlot($arrVal[1], $arrVal[0]);
		$sp->mark->SetType(MARK_FILLEDCIRCLE);
		$sp->mark->SetFillColor('#'.$arrColor[0]);
		$sp->mark->SetWidth(4);

		$graph->Add($sp);
		 
		$graph->title->Set($this->_chtt->getTitle());
		//$graph->xaxis->title->Set("X-title");
		//$graph->yaxis->title->Set("Y-title");
		
		$graph->title->SetFont(FF_SIMSUN,FS_NORMAL,12);
		$graph->yaxis->title->SetFont(FF_SIMSUN,FS_BOLD);
		$graph->yaxis->SetLabelFormatCallback('number_format');
		$graph->xaxis->title->SetFont(FF_SIMSUN,FS_BOLD);

		$graph->legend->SetFont(FF_SIMSUN,FS_NORMAL,9);
		
		$chxl = $this->_chxl->getVal();
		if (!empty($chxl) && !empty($chxl[0])) {
			$graph->xaxis->SetTickLabels($chxl[0]);
		}
		 
		// Display the graph
		$graph->Stroke();

	}
}
