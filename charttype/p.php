<?php

//require_once("jpgraph/jpgraph.php");
//require_once("jpgraph/jpgraph_pie.php");

require_once('./models/chd.php');
require_once('./models/chs.php');
require_once('./models/chtt.php');
require_once('./models/chco.php');
require_once('./models/chl.php');

class p
{
	private $_chd  = null;
	private $_chs  = null;
	private $_chtt = null;
	private $_chl  = null;
	private $_chco = null;
	
	function __construct()
	{
		// 必选参数
		$this->_chd  = new chd($_REQUEST['chd']);
		$this->_chs  = new chs($_REQUEST['chs']);
		
		// 可选参数
		@$this->_chtt = new chtt($_REQUEST['chtt']);
		@$this->_chl  = new chl($_REQUEST['chl']);
		@$this->_chco = new chco($_REQUEST['chco']);

	}
	
	public function run()
	{
		$tmp = $this->_chd->getVal();
		$data = $tmp[0];
		 
		// Create the Pie Graph.
		$graph = new PieGraph($this->_chs->getWidth(), $this->_chs->getHeight());
		$graph->SetShadow();
		$graph->SetFrame(false);
		 
		// Set A title for the plot
		$graph->title->Set($this->_chtt->getTitle());
		$graph->title->SetFont(FF_SIMSUN,FS_NORMAL,12); 
		$graph->title->SetColor("darkblue");

		$legendRightPosRatio = 10 / $this->_chs->getWidth();
		$legendTopPosRatio   = 40 / $this->_chs->getHeight();
		$graph->legend->Pos($legendRightPosRatio, $legendTopPosRatio);
		$graph->legend->SetFont(FF_SIMSUN,FS_NORMAL); 

		 
		// Create pie plot，标题占20px，legend占80px
		$p1 = new PiePlot($data);
		$title = $this->_chtt->getTitle();
		if (empty($title)) {
			$centerYRatio = 0.5;
		} else {
			$centerYRatio = 1 - ($this->_chs->getHeight() - 20) / 2 / $this->_chs->getHeight();
		}
		$centerXRatio = ($this->_chs->getWidth() - 80) / 2 / $this->_chs->getWidth();
		$p1->SetCenter($centerXRatio, $centerYRatio);
		//$p1->SetSize(0.3);
		 
		// Enable and set policy for guide-lines. Make labels line up vertically
		$p1->SetGuideLines(true,true,true);
		$p1->SetGuideLinesAdjust(1.0);

		// set color
		$arrChco = $this->_chco->getVal();
		if (count($arrChco) >= count($data)) {
			$color = array();
			foreach ($arrChco as $k => $v) {
				$color[] = "#$v";
			}
			$p1->SetSliceColors($color);
		}
		//$p1->SetColor('black');
		 
		// Setup the labels
		$p1->SetLabelType(PIE_VALUE_PER); 
		//$p1->SetLabels($this->_chl->getVal());
		$p1->value->Show();
		
		$arrLegends = $this->_chl->getVal();
		if (!empty($arrLegends)) {
			$p1->SetLegends($this->_chl->getVal());
		}
		$p1->value->SetFont(FF_ARIAL,FS_NORMAL,9);    
		$p1->value->SetFormat('%2.1f%%');        
		 
		// Add and stroke
		$graph->Add($p1);
		$graph->Stroke();

	}
}
