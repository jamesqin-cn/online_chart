<?php

//require_once("jpgraph/jpgraph.php");
//require_once("jpgraph/jpgraph_bar.php");

require_once('./models/chd.php');
require_once('./models/chs.php');
require_once('./models/chtt.php');
require_once('./models/chl.php');
require_once('./models/chco.php');
require_once('./models/chdl.php');
require_once('./models/chxl.php');

class bvs
{
	private $_chd  = null;
	private $_chs  = null;
	private $_chtt = null;
	private $_chl  = null;
	private $_chco = null;
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
		@$this->_chdl = new chdl($_REQUEST['chdl']);
		@$this->_chxl = new chxl($_REQUEST['chxl']);
	}
	
	public function run()
	{
		// Create the graph. These two calls are always required
		$graph = new Graph($this->_chs->getWidth(), $this->_chs->getHeight());  
		$graph->SetScale("textlin");
		 
		$graph->SetShadow();
		$graph->SetFrame(false);		
		 
		// Create the bar plots
		$arrPlot = array();
		
		$arrVal   = $this->_chd->getVal();
		$arrColor = $this->_chco->getVal();
		$arrLegends = $this->_chdl->getVal();

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

		// legend位置调整
		$legendRightPosRatio = 5 / $this->_chs->getWidth();
		$legendTopPosRatio   = 5 / $this->_chs->getHeight();
		$graph->legend->Pos($legendRightPosRatio, $legendTopPosRatio);
		$graph->legend->SetFont(FF_SIMSUN,FS_NORMAL);

		// 至少要有一个颜色，缺颜色就用前一个颜色补
		if (count($arrColor) == 0) {
			throw new Exception("class chco must return one color at least.");
			return;
		}
		
		$len = count($arrVal);
		for ($i = 0, $coIndex = 0; $i < $len; ++$i) {
			$arrPlot[$i] = new BarPlot($arrVal[$i]);
			
			if (empty($arrColor[$coIndex])) {
				$color = $arrColor[$coIndex - 1];
			} else {
				$color = $arrColor[$coIndex++];
			}

			$arrPlot[$i]->SetFillColor('#'.$color);

			if (!empty($arrLegends[$i])) {
				$arrPlot[$i]->SetLegend($arrLegends[$i]);
			}
		}
		 
		// Create the grouped bar plot
		$gbplot = new AccBarPlot($arrPlot);
		 
		// ...and add it to the graPH
		$graph->Add($gbplot);
		 
		$graph->title->Set($this->_chtt->getTitle());
		//$graph->xaxis->title->Set("X-title");
		//$graph->yaxis->title->Set("Y-title");
		
		$graph->title->SetFont(FF_SIMSUN,FS_NORMAL,12);
		$graph->yaxis->title->SetFont(FF_SIMSUN,FS_BOLD);
		$graph->yaxis->SetLabelFormatCallback('number_format');
		$graph->xaxis->title->SetFont(FF_SIMSUN,FS_BOLD);
		$graph->xaxis->SetFont(FF_SIMSUN,FS_NORMAL);

		$graph->legend->SetFont(FF_SIMSUN,FS_NORMAL,9);
		
		$chxl = $this->_chxl->getVal();
		if (!empty($chxl) && !empty($chxl[0])) {
			$graph->xaxis->SetTickLabels($chxl[0]);
		}
		 
		// Display the graph
		$graph->Stroke();

	}
}
