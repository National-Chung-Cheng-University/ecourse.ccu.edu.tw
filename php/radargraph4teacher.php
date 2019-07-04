<?php

// $Id: fixscale_radarex1.php,v 1.1 2002/08/21 21:28:16 aditus Exp $
//include ("jpgraph-1.27.1/src/jpgraph.php");
//include ("jpgraph-1.27.1/src/jpgraph_radar.php");
include ("jpgraph/src/jpgraph.php");
include ("jpgraph/src/jpgraph_radar.php");

//Connect to Data base 20111029 yuwan

require_once 'common.php';
require_once 'fadmin.php';
//require_once './preprocess.php';
global $view_id;
$view_id =empty($_GET['choose_id'])?'':$_GET['choose_id']; 

$year=100;
$count=0;

//$veiw_id = 'test_student';
$Q = "select 1_1,1_2,1_3,2_1,2_2,2_3,3_1,3_2,3_3,4_1,4_2 from IEEE_Results where id = '$view_id' and year <= '$year'";// ORDER BY  `IEEE_Results`.`year` ASC ";

//$Q = "select 1_1,1_2,1_3,2_1,2_2,2_3,3_1,3_2,3_3,4_1,4_2 from IEEE_Results where `id` = 'test_student' and `year` <= '98' ";
$result = mysql_db_query ($DB,$Q);

//$row = mysql_fetch_array ($result);
$standard = array(1,1,1,1,1,1,1,1,1,1,1);

while( $row = mysql_fetch_array ($result))
{
	 for( $i=0 ; $i<11 ;$i++)
	 {
	 	if($count==0)
                	$radardata1[$i] = $row[$i]; //edited 1210
		else if ($count==1)
			$radardata2[$i] = $row[$i];
		else if($count==2)
		        $radardata3[$i] = $row[$i];
		else if ($count==3)
		        $radardata4[$i] = $row[$i];
         }
         $count++;
}

$total = $count;
/*
$graph = new RadarGraph(500,500,'auto');
$graph->SetScale("lin",0,100);
$graph->yscale->ticks->Set(25,5);
$graph->SetColor("white");
$graph->SetShadow();

$graph->SetCenter(0.5,0.55);

$graph->axis->SetFont(FF_FONT1,FS_BOLD);
$graph->axis->SetWeight(2);
*/

//123

$graph = new RadarGraph(500,500,'auto');
$graph->SetScale("lin",0,2);
$graph->yscale->ticks->Set(0.5,5);
$graph->SetColor("white");
$graph->SetShadow();

$graph->SetCenter(0.5,0.55);

$graph->axis->SetFont(FF_FONT1,FS_BOLD);
$graph->axis->SetWeight(2);

// Uncomment the following lines to also show grid lines.
//$graph->grid->SetLineStyle("longdashed");
//$graph->grid->SetColor("navy");
//$graph->grid->Show();


$graph->ShowMinorTickMarks();

$graph->title->Set("Quality result");
//$graph->title->Set("н╫зя");
$graph->title->SetFont(FF_FONT1,FS_BOLD);
//$graph->title->SetFont(FF_CHINESE, FS_NORMAL, 12);
$graph->SetTitles(array("1.1","1.2","1.3","2.1","2.2","2.3","3.1","3.2","3.3","4.1","4.2"));

if($count== 4 )
{
	$plot1 = new RadarPlot($radardata4);
	$plot1->SetLegend($year);  //edited 1210
	$plot1->SetColor("peru");
	$plot1->SetFillColor('peachPuff1');
	$plot1->SetLineWeight(2);
	$count--;
	$graph->Add($plot1);

}
if($count==3)
{
	$plot2 = new RadarPlot($radardata3);
	$plot2->SetLegend($year-($total-$count));
	$plot2->SetColor("plum");
	$plot2->SetFillColor('plum1');
	$plot2->SetLineWeight(2);
	$count--;
        $graph->Add($plot2);

}
if($count== 2)
{
	$plot3 = new RadarPlot($radardata2);
	$plot3->SetLegend($year-($total-$count));
	$plot3->SetColor("seagreen");
	$plot3->SetFillColor('yellowgreen');
	$plot3->SetLineWeight(2);
	 $count--;
        $graph->Add($plot3);

}
if ($count== 1)
{
	$plot4 = new RadarPlot($radardata1);
	$plot4->SetLegend($year-($total-$count));
	$plot4->SetColor("lightskyblue3");
	$plot4->SetFillColor('lightskyblue2');
	$plot4->SetLineWeight(2);
	$count--;
	$graph->Add($plot4);
}
	$plot = new RadarPlot($standard);
        $plot->SetLegend("Standard");
        $plot->SetColor("red");
        $plot->SetLineWeight(2);
        $graph->Add($plot);


$graph->Stroke();
//$graph->Stroke('./yuwan/'.$view_id.'.png');


?>
