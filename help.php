<h1>1.  tencentchart常见问题</h1>

<h2>1.1  什么是 Google Chart API？</h2>
<blockquote>
Google Chart API 是一款极其方便的图表生成工具，您可以在网页中直接嵌入图表图片。图表数据和参数通过 HTTP 请求传送，Google 依照此请求返回 PNG 格式的图表图片。Google Chart API 支持多种图表类型，您可以使用 img 标签将图表直接嵌入网页。<br>
关于google chart api，您可以参考此处：<a href="http://labs.cloudream.name/google/chart/api.html" target=_blank>http://labs.cloudream.name/google/chart/api.html</a>
</blockquote>

<h2>1.2  什么是 Tencent Chart API？</h2>
<blockquote>
Tencent Chart API是研发运营部运营中心开发的一款兼容Google Chart API协议的绘图组件。项目仍在开发中，目前兼容Google Chart API部分协议。例如饼图(p/p3)、柱状图(bvs)、折线图(lc)、散点图(s)，支持chs、chd、cht、chtt、chco、chdl、chxl等参数。
</blockquote>

<h2>1.3  php调用示例</h2>
<blockquote>
<pre>
function testPie()
{
	$url = "/tencentchart/chart.php"
		 . "?cht=p&chs=300x200"
		 . "&chd=t:20,80,10,23"
		 . "&chl=hello|world"
		 . "&chtt=" . urlencode(mb_convert_encoding('中文标题', 'utf8', 'gbk'));
	echo "&lt;a href=$url&gt;&lt;img src=$url&gt;&lt;/a&gt;\n";
}
</pre>
</blockquote>

<h2>1.3  我在使用中遇到了问题，谁可以帮助我？</h2>
<blockquote>
如果您在使用tencent chart api的过程中遇到了问题，或者想到一些idea，请随时与我们的开发团队联系：jamesqin@tencent.com
</blockquote>

<h1>2.  测试</h1>
<?php

function testPie()
{
	$url = "/tencentchart/chart.php"
		 . "?cht=p&chs=300x200"
		 . "&chd=t:20,80,10,23"
		 . "&chl=hello|world"
		 . "&chtt=" . urlencode(mb_convert_encoding('中文标题', 'utf8', 'gbk'));
	echo "<a href=$url><img src=$url></a>\n";
}

function testPie3D()
{
	$url = "/tencentchart/chart.php"
		 . "?cht=p3&chs=400x200"
		 . "&chd=t:20,80,10,23"
		 . "&chl=May|Jun|Jul|Aug|Sep|Oct"
		 . "&chtt=" . urlencode(mb_convert_encoding('这个包含空格+的+主标题|这是副标题', 'utf8', 'gbk'));
	echo "<a href=$url><img src=$url></a>\n";
}

function testBvs()
{
	$url = "/tencentchart/chart.php"
		 . "?cht=bvs&chs=300x200"
		 . "&chd=t:200,50,60,80,40|50,60,100,40,20"
		 . "&chco=4d89f9,c6d9fd"
		 . "&chdl=sz|" . urlencode(mb_convert_encoding('西安', 'utf8', 'gbk'))
		 . "&chbh=20&chxl=0:|05-04|05-05|05-06|05-07|05-08"
		 . "&chtt=" . urlencode(mb_convert_encoding('中文标题', 'utf8', 'gbk'));
	echo "<a href=$url><img src=$url></a>\n";
}

function testBvs2()
{
	$url = "/tencentchart/chart.php"
		 . "?cht=bvs&chs=300x200"
		 . "&chd=t:200,50,60,80,40"
		 . "&chco=4d89f9"
		 . "&chdl=sz|bj"
		 . "&chbh=20&chxl=0:|05-04|05-05|05-06|05-07|05-08"
		 . "&chtt=" . urlencode(mb_convert_encoding('中文标题', 'utf8', 'gbk'));
	echo "<a href=$url><img src=$url></a>\n";
}

function testBvg()
{
	$url = "/tencentchart/chart.php"
		 . "?cht=bvg&chs=300x200"
		 . "&chd=t:200,50,60,80,40|50,60,100,40,20"
		 . "&chco=4d89f9,c6d9fd"
		 . "&chdl=sz|" . urlencode(mb_convert_encoding('西安', 'utf8', 'gbk'))
		 . "&chbh=20&chxl=0:|05-04|05-05|05-06|05-07|05-08"
		 . "&chtt=" . urlencode(mb_convert_encoding('中文标题', 'utf8', 'gbk'));
	echo "<a href=$url><img src=$url></a>\n";
}

function testLc()
{
	$url = "/tencentchart/chart.php"
		 . "?cht=lc&chs=300x200"
		 . "&chd=t:200,50,60,80,40|50,60,100,40,20"
		 . "&chco=0000ff,ff0000"
		 . "&chdl=sz|bj"
		 . "&chxl=0:|05-04|05-05|05-06|05-07|05-08"
		 . "&chtt=" . urlencode(mb_convert_encoding('中文标题', 'utf8', 'gbk'));
	echo "<a href=$url><img src=$url></a>\n";
}

function testS()
{
	$url = "/tencentchart/chart.php"
		 . "?cht=s&chs=300x200"
		 . "&chd=t:200,50,60,80,40|50,60,100,40,20"
		 . "&chco=ff0000"
		 . "&chdl=sz|bj"
		 . "&chtt=" . urlencode(mb_convert_encoding('中文标题', 'utf8', 'gbk'));
	echo "<a href=$url><img src=$url></a>\n";
}

testPie(); testPie3D(); echo "<br>\n";
testBvs(); testBvs2(); echo "<br>\n";
testBvg(); echo "<br>\n";
testLc(); echo "<br>\n";
testS(); echo "<br>\n";
