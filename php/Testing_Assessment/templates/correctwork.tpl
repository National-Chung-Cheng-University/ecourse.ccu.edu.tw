<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<script>
var bTool = new Boolean( false );
var bMain = new Boolean( false );

function main_load()
{
	bMain = true;
}

function tool_load()
{
	bTool = true;
}

function delay( n )
{
	setTimeout( 'delay(' + n + ');', n );
}

function init()
{
	if( bTool==true && bMain==true )
	{
		tools.Tool_OnLoad();
		tools.Main_OnLoad();
	}
	else
		setTimeout( "init()", 100 );
}

if( navigator.userAgent.search( /MSIE [56].[05]/i ) == -1 )
{
	alert( '�z�ϥΪ��O ' + navigator.userAgent + '\n���{���ȾA�Ω� Microsoft Internet Explorer 5.x' );
}
else
	setTimeout( "init()", 100 );
</script>
</head>

<frameset rows="40,*">
	<frame id="tools" name="tools" scrolling="no" target="main" src="check_allwork.php?action=showtool&sid=SNO&work_id=WORKID">
	<frame id="main" name="main" src="check_allwork.php?action=seestuwork&sid=SNO&work_id=WORKID">
	<noframes>
	<body>

	<p>�������ϥήج[,���O�z���s�����ä��䴩.</p>

	</body>
	</noframes>
</frameset>

</html>
