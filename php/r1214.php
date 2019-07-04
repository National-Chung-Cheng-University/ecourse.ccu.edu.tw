<?php
//include ("../jpgraph.php");
//include ("../jpgraph_radar.php");
 
include ("./jpgraph-1.27.1/src/jpgraph.php");
include ("./jpgraph-1.27.1/src/jpgraph_radar.php");

// Some data to plot
$data = array(0.055,0.080,0.145,0,0,1,0,2,0,0);
	
// Create the graph and the plot
$graph = new RadarGraph(250,200,"auto");
$graph->SetScale("lin",0,2);
$plot = new RadarPlot($data);

// Add the plot and display the graph
$graph->Add($plot);
$graph->Stroke();
?>
