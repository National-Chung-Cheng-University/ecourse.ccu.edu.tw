<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:ie>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>電子白板</title>
<object id="VMLRender" classid="CLSID:10072CEC-8CC1-11D1-986E-00A0C955B42E">
</object>
<style>v\:*  { behavior: url(#VMLRender) }</style>
<script language="javascript">
//////////////////////////////////////////////////////////////////////////////
//
// control the stroke-width & color & fill of shapes
//
var strokeColor = 'red';
var strokeWeight = 4;
var filled = new Boolean( false );
var fillColor = 'black';
var clientID = '';
var serialCode = 0;

function changeFillColor()
{
	tmp = showModalDialog( "/learn/eboard/colorpad.html" );
	if( tmp != '' )
		clrFilled.bgColor = fillColor = tmp;
	document.body.focus();
}

function changeStrokeColor()
{
	tmp = showModalDialog( "/learn/eboard/colorpad.html" );
	if( tmp != '' )
		clrStroke.bgColor = strokeColor = tmp;
	document.body.focus();
}

function changeStrokeWeight( n )
{
	strokeWeight = n;
	document.body.focus();
}

function changeFilled( )
{
	filled = chkFilled.checked;
	document.body.focus();
}
// the object of javascript
// a rectangle object: left, top, right, bottom
function CRect( l, t, r, b )
{
	this.left	= l;
	this.top	= t;
	this.right	= r;
	this.bottom	= b;

	this.width = function()
	{	return Math.abs( this.right - this.left );	};

	this.height = function()
	{	return Math.abs( this.bottom - this.top );	};

	this.normalize = function()
	{
		var tmp;
		if( this.top > this.bottom )
		{
			tmp = this.top;
			this.top = this.bottom;
			this.bottom = tmp;
		}
		if( this.left > this.right )
		{
			tmp = this.left;
			this.left = this.right;
			this.right = tmp;
		}
	}
}

// the drawing shape of html, including <DIV>[<SHAPE>|<SPAN>]</DIV>
function CShape()
{
	this.html = '';
	this.tool = '';
	this.objShape = null;
	this.rect = null;

	this.check = function()
	{
		if( this.tool == 'freehand' )
		{
			return;
		}
		if( this.rect.width() < 10 && this.rect.height() < 10 )
		{
			this.objShape.style.visibility = 'hidden';
			this.objShape.removeNode( true );
			this.objShape = null;
		}
	}

	this.remove = function()
	{
		this.objShape.style.visiblity = 'hidden';
		this.objShape.removeNode( true );
		this.objShape = null;
	}

	this.final = function()
	{
		with( this.objShape )
		{
			style.filter = 'alpha(opacity=70)';
		}
	}

	this.create = function( shape )
	{
		var vml = '';
		with( this.rect )
		{
			if( shape == 'arrow' )
			{
				vml+= '<v:line style="position:absolute" tool="shape" from="'+left+'px,'+top+'px" to="'+(right+1)+'px,'+(bottom+1)+'px" ';
				vml+= 'strokeweight="'+ strokeWeight+'px" strokecolor="'+strokeColor+'">';
				vml+= '<v:stroke endarrow="classic" /></v:line>';
			}
			else if( shape == 'arrow2' )
			{
				vml+= '<v:line style="position:absolute" tool="shape" from="'+left+'px,'+top+'px" to="'+(right+1)+'px,'+(bottom+1)+'px" ';
				vml+= 'strokeweight="'+ strokeWeight+'px" strokecolor="'+strokeColor+'">';
				vml+= '<v:stroke startarrow="classic" endarrow="classic" /></v:line>';
			}
			else if( shape == 'line' )
			{
				vml+= '<v:line style="position:absolute" tool="shape" from="'+left+'px,'+top+'px" to="'+(right+1)+'px,'+(bottom+1)+'px" ';
				vml+= 'strokeweight="'+ strokeWeight+'px" strokecolor="'+strokeColor+'" />';
			}
			else if( shape == 'rect' )
			{
				vml+= '<v:rect tool="shape" style="position:absolute; top:'+top+'px; left:'+left+'px; width:'+width()+'px; height:'+height()+'px" ';
				vml+= 'stroked="t" strokecolor="'+strokeColor+'" strokeweight="'+strokeWeight+'px" ';
				vml+= 'filled="'+filled+'" fillcolor="'+fillColor+'" />';
			}
			else if( shape == 'circle' )
			{
				vml+= '<v:oval tool="shape" style="position:absolute; top:'+top+'px; left:'+left+'px; width:'+width()+'px; height:'+height()+'px" ';
				vml+= 'stroked="t" strokecolor="'+strokeColor+'" strokeweight="'+strokeWeight+'px" ';
				vml+= 'filled="'+filled+'" fillcolor="'+fillColor+'" />';
			}
			else if( shape == 'rrect' )
			{
				vml+= '<v:roundrect tool="shape" arcsize="0.2" style="position:absolute; top:'+top+'px; left:'+left+'px; width:'+width()+'px; height:'+height()+'px" ';
				vml+= 'stroked="t" strokecolor="'+strokeColor+'" strokeweight="'+strokeWeight+'px" ';
				vml+= 'filled="'+filled+'" fillcolor="'+fillColor+'" />';
			}
			else if( shape == 'right' )
			{
				vml+= '<v:shape tool="shape" coordsize="3 2" style="position:absolute; top:'+top+'px; left:'+left+'px; width:'+width()+'px; height:'+height()+'px" ';
				vml+= 'filled="f" stroked="t" strokecolor="'+strokeColor+'" strokeweight="'+strokeWeight+'px" path="m0,1 l1,2,3,0 e" />';
			}
			else if( shape == 'wrong' )
			{
				vml+= '<v:shape tool="shape" coordsize="3 3" style="position:absolute; top:'+top+'px; left:'+left+'px; width:'+width()+'px; height:'+height()+'px" ';
				vml+= 'filled="f" stroked="t" strokecolor="'+strokeColor+'" strokeweight="'+strokeWeight+'px" path="m0,0 l3,3 m0,3 l3,0 e" />';
			}
			else if( shape == 'quest' )
			{
				vml+= '<v:shape tool="shape" coordsize="10 20" style="position:absolute; top:'+top+'px; left:'+left+'px; width:'+width()+'px; height:'+height()+'px" ';
				vml+= 'filled="f" stroked="t" strokecolor="'+strokeColor+'" strokeweight="'+strokeWeight+'px" path="wr0,0,10,10,0,5,5,10 l5,13 m5,14 l5,16 e" />';
			}
			else if( shape == 'freehand' )
			{
				w = mask.style.pixelWidth;
				h = mask.style.pixelHeight;

				vml+= '<v:shape tool="shape" coordsize="'+w+','+h+'" style="position:absolute; top:0px; left:0px; width:'+w+'px; height:'+h+'px" ';
				vml+= 'filled="f" stroked="t" strokecolor="'+strokeColor+'" strokeweight="'+strokeWeight+'px" path="m'+left+','+top+'l'+left+','+top+'e" />';
			}
		}
		this.html = vml;
	}

	this.insert = function( obj )
	{
		obj.insertAdjacentHTML( "BeforeEnd", this.html );	// insert HTML & get object control
		this.objShape = obj.lastChild;
		this.objShape.id = clientID + "_" + serialCode++;
		if( this.objShape == null )
		{
			window.status = 'cannot get object' ;
			return;
		}
	}

	this.adjust = function( x, y )
	{
		if( this.tool == 'line' || this.tool.search("arrow") != -1 )
		{
			this.objShape.to = x + 'px,' + y + 'px';
			with( this.rect )
			{
				right = x;
				bottom = y;
			}
		}
		else if( this.tool == 'freehand' )
		{
			var re = / *e/i;
			var str = new String(this.objShape.path);
			this.objShape.path = str.replace( re, ',' + x + ',' + y + 'e' );
		}
		else
		{
			this.rect.right = x;
			this.rect.bottom = y;
			with( this.rect )
				var r = new CRect( left, top, right, bottom );

			with( r )
			{
				normalize();
				this.objShape.style.top = top;
				this.objShape.style.left = left;
				this.objShape.style.width = width();
				this.objShape.style.height = height();
			}
		}
	}
}

var bMouseDown = new Boolean( false );
var activeTool = 'cursor';
var activeShape = null;
var imagePos = '';

function Image_Paste( url )
{
	var id = clientID + '_' + serialCode++;
	vml = '<img id="'+id+'" tool="shape" src="'+url+'" style="position:absolute;'+imagePos+'">';
	shapes.insertAdjacentHTML( 'BeforeEnd', vml );
	adjustOpacity( shapes.lastChild );
	mySend( 'p', vml );
}

function Panel_OnMouseUp()
{
	if( activeTool == 'image' && event.button == 1 )
	{
		imagePos = 'top:' + event.offsetY + ';left:' + event.offsetX;
		open( "/learn/eboard/image.php?PHPSESSID=PHPSID", "image", "toolbar=no,menubar=no,location=no, status=no" );
		return false;
	}
	if( bMouseDown == true )
	{
		bMouseDown = false;
		activeShape.final();
		activeShape.check();
		if( activeShape.objShape != null )
		{
			var re = /\<\?xml[^\>]*\>/;
			var vml = activeShape.objShape.outerHTML.replace( re,'' );
//			activeShape.remove();
			delete activeShape;
			activeShape = null;
			mySend( 'p', vml );
		}
		adjustOpacity( shapes.lastChild );
	}
}

function Panel_OnMouseMove()
{
	if( event.srcElement.id != 'mask' )
		return;
/*	if( search_panel( event.srcElement, 'panel' ) == false )
		return;
	if( event.offsetX < 1 || event.offsetY < 1 ||
			event.offsetX > panel.style.pixelWidth ||
			event.offsetY > panel.style.pixelHeight )
		return false;*/

	if( bMouseDown == true && event.button == 1 )
	{
		activeShape.adjust( event.offsetX, event.offsetY );
		event.returnValue = false;
		event.cancelBubble = true;
	}
	return false;
}

function Panel_OnSelectStart()
{
	event.cancelBubble = true;
	event.returnValue = false;
	return false;
}

function Panel_OnDragStart()
{
	event.cancelBubble = true;
	event.returnValue = false;
	return false;
}

function Panel_OnMouseDown()
{
	if( event.srcElement.id != 'mask' )
		return;
/*	if( event.offsetX < 1 || event.offsetY < 1 ||
			event.offsetX > panel.style.pixelWidth ||
			event.offsetY > panel.style.pixelHeight )
		return false;*/

	if( event.button == 1 )
	{
		if( activeTool == 'del' )
		{
		}
		else if( activeTool == 'font' )
		{
			var fontStr = showModalDialog( '/learn/eboard/font.html' );
			if( fontStr != '' )
			{
				shapes.insertAdjacentHTML( 'BeforeEnd', fontStr );
				var obj = shapes.lastChild;
				with( obj )
				{
					style.position = 'absolute';
					style.top = event.offsetY;
					style.left = event.offsetX;
					id = clientID + '_' + serialCode;
				}
				mySend( 'p', obj.outerHTML );
				adjustOpacity( obj );
			}
		}
		else if( activeTool == 'image' )
		{
		}
		else if( activeTool != 'cursor' )
		{
			activeShape = new CShape();
			activeShape.tool = activeTool;
			activeShape.rect = new CRect( event.offsetX, event.offsetY, event.offsetX, event.offsetY );
			activeShape.create( activeTool );
			activeShape.insert( shapes );
			bMouseDown = true;
		}
	}
	return false;
}

//////////////////////////////////////////////////////////////////////////////
//
//	the javascript below is to control the toolbar
//
function reset_menu()
{
	line_tools.style.visibility = 'hidden';
	rect_tools.style.visibility = 'hidden';
}

function line_menu(n)
{
	event.cancelBubble = true;
	if( n == '' )
	{
		reset_menu();
		line_tools.style.pixelLeft = event.clientX;
		line_tools.style.pixelTop = 22;
		line_tools.style.visibility = 'visible';
	}
	else
	{
		reset_menu();
		line_img.src = event.srcElement.src;
		line_img.parentElement.tool = n;
		line_img.parentElement.click();
	}
	return false;
}

function rect_menu(n)
{
	event.cancelBubble = true;
	if( n == '' )
	{
		reset_menu();
		rect_tools.style.pixelLeft = event.clientX;
		rect_tools.style.pixelTop = 22;
		rect_tools.style.visibility = 'visible';
	}
	else
	{
		reset_menu();
		rect_img.src = event.srcElement.src;
		rect_img.parentElement.tool = n;
		rect_img.parentElement.click();
	}
	return false;
}
// reset all cell to normal button shape
function resetCell()
{
	var td = document.all.item("TOOL");
	if( td != null )
	{
		reset_menu();
		for( i = 0; i < td.length; i++ )
		{
			var o = td[i];
//			o.borderColorLight	= "#C0C0C0";
//			o.borderColorDark	= "#C0C0C0";
			o.borderColorLight	= "#808080";
			o.borderColorDark	= "#FFFFFF";
		}
	}
}

// find the cell of the tool.
function upToElement( o, str )
{
	if( o == null )
		return null;
	if( o.tagName == str )
		return o;
	if( o.tagName == "BODY" )
		return null;
	return upToElement( o.parentElement, str );
}

// make the toolbar button checked.
function Tool_MouseDown()
{
	o = event.srcElement;
	o = upToElement( o, 'TD' );
	if( o != null && o.id == 'tool' )
	{
		resetCell();
		o.borderColorLight = "#FFFFFF";
		o.borderColorDark = "#808080";
		activeTool = o.tool;

		if( o.tool == 'del' )
		{
			SelectTool(0);
			if( selectShape != null )
			{
				var id = selectShape.id;

				selectShape.style.visibility = 'hidden';
				selectShape.removeNode( true );
				selectShape = null;
				mySend( 'd', id );
			}
			else
				alert( "Select an Object to Delete" );
			return;
		}

		with( mask.style )
		{
			if( o.tool == 'cursor' )
				cursor = 'auto';
			else if( o.tool == 'move' )
				cursor = 'hand';
			else
				cursor = 'crosshair';
		}

		if( o.tool == 'cursor' )
			mask.style.visibility = 'hidden';
		else
		{
			Shape_OnBlur();
			mask.style.visibility = 'visible';
		}
	}
}

function SelectTool( index )
{
	var t = document.all.item('tool');
	if( t != null && t.length > 0 && index < t.length )
		t[index].click();
}

function EBoard_OnUnload()
{
	eboard.mySend( "r\t" + clientID );
	eboard.onUnload();
}

function EBoard_OnLoad()
{
	document.onclick = Tool_MouseDown;
	mask.onmousedown = Panel_OnMouseDown;
	mask.onmousemove = Panel_OnMouseMove;
	mask.onmouseup = Panel_OnMouseUp;
	mask.onselectstart = Panel_OnSelectStart;
	mask.ondragstart = Panel_OnDragStart;
	shapes.onmousedown = Shape_OnMouseDown;
	shapes.onmousemove = Shape_OnMove;
	shapes.onmouseup = Shape_OnMouseUp;
	shapes.onkeydown = Shape_OnKeyDown;
	tool_table.style.visibility = 'visible';
	resetCell();
	SelectTool(0);
	clientID = eboard.getClientID();
	eboard.mySend( "a\t" + clientID + "\tUSER_NAME" );
	//eboard.sendUser();
}

var selectShape = null;
var selShapeMouseDown = false;
var selShapeMouseMove = false;
var shapeTop, shapeLeft;
var oldShapeTop, oldShapeLeft;

function Shape_OnKeyDown()
{
	if( event.keyCode == 46 && selectShape != null )
	{
		var id = selectShape.id;

		selectShape.style.visibility = 'hidden';
		selectShape.removeNode( true );
		selectShape = null;
		mySend( 'd', id );
	}
}

function Shape_OnMove()
{
	if( selShapeMouseDown == true && event.button == 1)
	{
		x = event.clientX + document.body.scrollLeft - document.body.clientLeft;
		y = event.clientY + document.body.scrollTop - document.body.clientTop;
		selectShape.style.pixelTop = y - shapeTop;
		selectShape.style.pixelLeft = x - shapeLeft;
		selShapeMouseMove = true;
	}
	event.returnValue = false;
	return false;
}

function Shape_OnFocus( obj )
{
	Shape_OnBlur();
	selectShape = obj;
	if( selectShape != null )
	{
		selectShape.style.borderStyle = 'dotted';
		selectShape.style.borderWidth = 1;
	}
}

function Shape_OnBlur()
{
	if( selectShape != null )
	{
		selectShape.style.borderWidth = 0;
		selectShape = null;
	}
}

function Shape_OnMouseUp()
{
	if( selShapeMouseDown == true && event.button == 1 )
	{
		if( selShapeMouseMove == true )
			mySend( 'm', selectShape.id + "\t" + selectShape.style.top + "\t" + selectShape.style.left );
		selShapeMouseDown = false;
		selShapeMouseMove = false;
	}
}

function Shape_OnMouseDown()
{
	if( event.srcElement.tool == 'shape' && event.button == 1 )
	{
		Shape_OnFocus( event.srcElement );
		x = event.clientX + document.body.scrollLeft - document.body.clientLeft;
		y = event.clientY + document.body.scrollTop - document.body.clientTop;
		shapeLeft = x - selectShape.style.pixelLeft;
		shapeTop = y - selectShape.style.pixelTop;
		selShapeMouseDown = true;
		selShapeMouseMove = false;
	}
	else
		Shape_OnBlur();
	return false;
}

// mySend cmd, p: paste + cmd, e: eval + cmd, d: delete + objID, m: move + objID + left + top*/
function mySend( cmd, cmd2 )
{
	var buf = cmd + "\t" + clientID + "\t" + cmd2;
	try
	{
		var result = eboard.mySend( buf );
	}
	catch( e )
	{
		alert( e.message );
		return;
	}
	switch( result )
	{
	case 1:
		alert( 'connection problem' );
		break;
	case 2:
		alert( 'not connected to server' );
		break;
	default:
	}
}

function Socket_OnReceive( buf )
{
	var ss = buf.split( "\t" );
	if( ss.length > 0 )
	{
		if( ss[0] == 'a' )		// add user
		{
			var o = new Option;
			o.value = ss[1];
			o.text = ss[2];
			userList.add( o );
		}
		else if( ss[0] == 'r' )		// remove user
		{
			for( i = 0; i < userList.length; i++ )
			{
				if( userList.item(i).value == ss[1] )
				{
					userList.remove( i );
					break;
				}
			}
		}

		if( ss[1] == clientID )
			return;

		if( ss[0] == 'p' )		// paste
		{
			shapes.insertAdjacentHTML( 'BeforeEnd', ss[2] );
			adjustOpacity( shapes.lastChild );
		}
		else if( ss[0] == 'e' )		// eval
			eval( ss[2] );
		else if( ss[0] == 'd' )		// delete shape
		{
			var obj = document.all( ss[2] );
			if( obj != null && obj.tool == "shape" )
			{
				obj.style.visibility = 'hidden';
				obj.removeNode( true );
			}
		}
		else if( ss[0] == 'm' )		// move shape
		{
			var obj = document.all( ss[2] );
			if( obj != null && obj.tool == "shape" )
			{
				obj.style.top = ss[3];
				obj.style.left = ss[4];
			}
		}
	}
}

var showID = '';

function adjustOpacity( obj )
{
	if( obj == null )
		return;
	if( obj.tool == 'shape' && (showID == '' || obj.id.substr( 0, showID.length ) == showID ))
		obj.style.filter = 'Alpha(Opacity=70)';
	else
		obj.style.filter = 'Alpha(Opacity=20)';
}

function changeTrans( id )
{
	showID = id;
	obj = shapes.firstChild;
	while( obj != null )
	{
		adjustOpacity( obj );
		obj = obj.nextSibling;
	}
}
</script>
</head>

<body topmargin="0" leftmargin="0" bgcolor="#C0C0C0" text="#000000" onload="EBoard_OnLoad()" onunload="EBoard_OnUnload()">

<table id="tool_table" cellpadding="0" cellspacing="0" style="visibility:hidden">
	<tr>
		<td>
			<table border="1" cellpadding="0" cellspacing="1" height="100%">
				<tr>
					<td bgcolor="#C0C0C0" bordercolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0"><img border="0" src="/learn/eboard/img/bar.gif" width="6" height="22"></td>
					<td bgcolor="#C0C0C0" bordercolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="font-family:新細明體:font-size: 9pt" nowrap>工具</td>
					<td id="tool" tool="cursor" bordercolorlight="#FFFFFF" bordercolordark="#808080" bgcolor="#C0C0C0"><img border="0" src="/learn/eboard/img/cursor.gif" width="22" height="22" alt=""指標工具"></td>
					<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0">　</td>
					<td id="tool" tool="right" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/learn/eboard/img/right.gif" width="22" height="22" alt="打勾線條"></td>
					<td id="tool" tool="wrong" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/learn/eboard/img/wrong.gif" width="22" height="22" alt="打叉線條"></td>
					<td id="tool" tool="quest" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/learn/eboard/img/quest.gif" width="22" height="22" alt="疑問問號線條"></td>
					<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0">　</td>
					<td id="tool" tool="freehand" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/learn/eboard/img/freehand.gif" width="22" height="22" alt="隨手筆隨意繪製工具"></td>
					<td id="tool" tool="line" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img id="line_img" border="0" src="/learn/eboard/img/line.gif" width="22" height="22" alt="線條工具"><img onclick="return line_menu('');" border="0" src="/learn/eboard/img/down.gif" width="9" height="22"></td>
					<td id="tool" tool="rect" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img id="rect_img"  border="0" src="/learn/eboard/img/rect.gif" width="22" height="22"><img onclick="return rect_menu('');" border="0" src="/learn/eboard/img/down.gif" width="9" height="22"></td>
					<td id="tool" tool="font" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/learn/eboard/img/font.gif" width="22" height="22"></td>
					<td id="tool" tool="image" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/learn/eboard/img/image.gif" width="22" height="22"></td>
					<td id="tool" tool="del" bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img border="0" src="/learn/eboard/img/del.gif" width="22" height="22"></td>
					<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0">　</td>
				</tr>
			</table>
		</td>
		<td>
			<table border="1" cellpadding="0" cellspacing="1" style="font-family:新細明體:font-size: 9pt">
				<tr>
					<td bgcolor="#C0C0C0" bordercolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0"><img border="0" src="/learn/eboard/img/bar.gif" width="6" height="22"></td>
					<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="font-family:新細明體:font-size: 9pt" nowrap>線條</td>
					<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" nowrap><select size="1" onchange="changeStrokeWeight( this.value );">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option selected value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
						</select></td>
					<td onclick="changeStrokeColor()" id="clrStroke" width="20" bgcolor="#FF0000" bordercolorlight="#FFFFFF" bordercolordark="#808080" nowrap></td>
					<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0"></td>
					<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" nowrap>填滿</td>
					<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" nowrap>
						<input id="chkFilled" type="checkbox" onclick="changeFilled()">
					</td>
					<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" nowrap>
					<td onclick="changeFillColor()" id="clrFilled" width="20" bgcolor="#000000" bordercolorlight="#FFFFFF" bordercolordark="#808080" nowrap></td></td>
				</tr>
			</table>
		</td>
		<td>
			<table border="1" cellpadding="0" cellspacing="1" style="font-family:新細明體:font-size: 9pt">
				<tr>
					<td bgcolor="#C0C0C0" bordercolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0"><img border="0" src="/learn/eboard/img/bar.gif" width="6" height="22"></td>
					<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" nowrap>觀看</td>
					<td bgcolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" nowrap><select id="userList" size="1" onchange="changeTrans( this.value );" style="font-family:新細明體:font-size: 9pt">
						<option value="">Normal</option>
					</select></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<v:line from="0,0" to="1,1" />
<div id="line_tools" style="position:absolute;visibility:hidden;z-index:10">
<table border="1" border="0" cellpadding="0" cellspacing="1" bgcolor="#C0C0C0">
<tr>
<td bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img onclick="return line_menu('line')" border="0" src="/learn/eboard/img/line.gif" width="22" height="22" alt="線條工具"></td>
<td bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img onclick="return line_menu('arrow')"border="0" src="/learn/eboard/img/arrow.gif" width="22" height="22" alt="線條工具"></td>
<td bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img onclick="return line_menu('arrow2')"border="0" src="/learn/eboard/img/arrow2.gif" width="22" height="22" alt="線條工具"></td>
</tr>
</table>
</div>

<div id="rect_tools" style="position:absolute;visibility:hidden;z-index:10">
<table border="1" border="0" cellpadding="0" cellspacing="1" bgcolor="#C0C0C0">
<tr>
<td bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img onclick="return rect_menu('rect')" border="0" src="/learn/eboard/img/rect.gif" width="22" height="22" alt="線條工具"></td>
<td bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img onclick="return rect_menu('circle')" border="0" src="/learn/eboard/img/circle.gif" width="22" height="22" alt="線條工具"></td>
<td bordercolorlight="#808080" bordercolordark="#FFFFFF" bgcolor="#C0C0C0"><img onclick="return rect_menu('rrect')" border="0" src="/learn/eboard/img/roundrect.gif" width="22" height="22" alt="線條工具"></td>
</tr>
</table>
</div>

<div style="position:absolute;width:800px;height:600px;top:40px;left:25px;background:black">
</div>
<div style="background:white;position:absolute;width:800px;height:600px;top:35px;left:20px;border-style:solid;border-color:black;border-width:1">
<!--iframe src="http://www.ccu.edu.tw" style="position:absoulte;top:100;left:100;width:300;height:200">
</iframe-->
</div>
<div id="shapes" style="position:absolute;width:800px;height:600px;top:35px;left:20px;border-style:solid;border-color:black;border-width:1">
</div>
<img id="mask" src="/learn/eboard/transparent.gif" style="position:absolute;width:800px;height:600px;top:35px;left:20px;border-style:solid;border-color:black;border-width:1">

<APPLET NAME="eboard" CODE="EBoardApplet.class" codebase="http://SERVERNAME/learn/eboard" WIDTH="1" HEIGHT="1" MAYSCRIPT>
<PARAM NAME="PORT" VALUE="7799">
<PARAM NAME="ROOM" VALUE="ROOM_NAME">
<PARAM NAME="USER" VALUE="USER_NAME">
<PARAM NAME="METHOD" VALUE="Socket_OnReceive">
</APPLET>
</body>

</html>