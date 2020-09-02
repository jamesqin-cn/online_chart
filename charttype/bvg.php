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

class bvg
{
	private $_chd  = null;
	private $_chs  = null;
	private $_chtt = null;
	private $_chl  = null;
	private $_chco = null;
	private $_chdl = null;
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

		// 根据title、value值对margin动态调整
		$maxVal = $this->_chd->getMaxNum();
		$maxVal = number_format($maxVal);
		$len = strlen(strval($maxVal));
		$leftMargin = 15 + $len * 7;
		$rightMargin = 20;
		
		$title = $this->_chtt->getTitle();
		$topMargin = empty($title) ? 10 : 35;
		$bottomMargin = empty($arrLegends) ? 30 : 55;

		$graph->img->SetMargin($leftMargin, $rightMargin, $topMargin, $bottomMargin);

		// legend位置调整
		$legendRightPosRatio = 5 / $this->_chs->getWidth();
		$legendTopPosRatio   = 1 - 25 / $this->_chs->getHeight();
		$graph->legend->SetLayout(LEGEND_HOR);
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
		$gbplot = new GroupBarPlot($arrPlot);
		 
		// ...and add it to the graph
		$graph->Add($gbplot);
		 
		$graph->title->Set($this->_chtt->getTitle());
		//$graph->xaxis->title->Set("X-title");
		//$graph->yaxis->title->Set("Y-title");
		
		$graph->title->SetFont(FF_SIMSUN,FS_NORMAL,12);
		$graph->yaxis->title->SetFont(FF_SIMSUN,FS_BOLD);
		$graph->yaxis->SetLabelFormatCallback('number_format');
		$graph->xaxis->title->SetFont(FF_SIMSUN,FS_BOLD);
		$graph->xaxis->SetFont(FF_SIMSUN,FS_NORMAL,9);

		$graph->legend->SetFont(FF_SIMSUN,FS_NORMAL,9);
		
		// 横坐标太多时，自动空档跳过，避免x轴过于挤压
		$chxl = $this->_chxl->getVal();
		$oneitem_space = ($this->_chs->getWidth() - 50) / count($chxl[0]);
		$chxl_space = 35 / $oneitem_space;
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

			//$graph->xaxis->SetTickLabels($chxl[0]);
			$graph->xaxis->SetTickLabels($chxl_with_skip);
		}
		 
		// Display the graph
		$graph->Stroke();

	}
}
