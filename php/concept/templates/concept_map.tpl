<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML xmlns:v="urn:schemas-microsoft-com:vml" xmlns="http://www.w3.org/TR/REC-html40">
<HEAD>
<TITLE>concept map</TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<META http-equiv="Content-Type" content="text/html; charset=big5">
<STYLE>
v\:*		{behavior:url(#default#VML);}
body		{ font-size: 9pt; margin:2pt}
input		{ font-size: 9pt; z-index: 2;}
.ln			{ position:absolute; z-index:0; top:0; left:0; }
.obj		{ position:absolute; z-index:2; text-align:center; background:white; font-size:12pt; font-family:新細明體; border-style:solid; border-width:1px }

</STYLE>
</HEAD>

<BODY onload="Init();">
<v:line from="0 0" to="0 0" />
<div id="base" class="obj" style="z-index:-1; position:absolute; left:0; top:0; height:100%; width:100%; border-width:0">
GRAPH
</div>
<!--
if there is no saved file, please use the code below inside previous div tag.
<div id="root" lineNr='0' divNr='0' class="obj" deletable="f" childNode="" parentNode="" style="left:300px; top:100px; width=150px; cursor:hand"></div>
-->
<input type="button" value=" 子節點 " onclick="newChild();"> 
<input type="button" value=" 父節點 " onclick="newParent();"> 
<input type="button" value=" 刪除 " onclick="delNode();"> 
<input type="text" id="value" value="" size="20"> 
<input type="submit" value=" 修改 " onclick="changeValue(); return false;">
<input type="button" value=" ＋ " onclick="return sizeInc();"> 
<input type="button" value=" ─ " onclick="return sizeDec();"> 
<input type="button" value="save" onclick="onSave();">

<form method="POST" action="concept_map.php" name="form1">
<input type="hidden" name="a_id" value="AID">
<input type="hidden" name="action" value="record">
<input type="hidden" name="PHPSESSID" value="PHPID">
<input type="hidden" name="graph" value="">
</form>

<SCRIPT>
// VML
var currObject = null;
var bMouseDown = false;
var objX, objY;
var lineNr = 0;
var divNr = 0;
var MouseDownX, MouseDownY;
var moving = false;

function onSave() {
	base.click();
	document.all.item('root').lineNr = lineNr;
	document.all.item('root').divNr = divNr;
	form1.graph.value = document.all.item('base').innerHTML;
	form1.submit();
}

function Init()
{
	lineNr = parseInt( document.all.item('root').lineNr );
	divNr = parseInt( document.all.item('root').divNr );
}

base.onmousedown = onMouseDown;
base.onmousemove = onMouseMove;
base.onmouseup = onMouseUp;
base.onkeydown = onKeyDown;
base.onselectstart = onSelectStart;

function onSelectStart() {
	return false;
}

function onKeyDown() {
	if( event.ctrlKey ) {
		MouseDownX = event.x;
		MouseDownY = event.y;
	}
}

function findDiv( obj ) {
	if( obj == null )
		return null;
	if( obj.tagName == 'DIV' )
		return obj;
	return findDiv( obj.parentElement );
}

function unfocusObject() {
	if( currObject != null ) {
		currObject.style.borderWidth = '1';
		currObject.style.background = 'white';
		currObject = null;
	}
}

function onMouseDown() {
	if( event.srcElement.id == 'base' ) {
		unfocusObject();
		return false;
	}
	obj = findDiv(event.srcElement);
	if( obj == null || obj.tagName != 'DIV' || obj.id == 'base' )
		return false;
	unfocusObject();
	currObject = obj;
	currObject.onselectstart = new Boolean( false );
	currObject.ondragstart = new Boolean( false );
	currObject.style.background = 'yellow';
	objX = event.x - currObject.style.pixelLeft;
	objY = event.y - currObject.style.pixelTop;
	value.value = currObject.innerText;
	MouseDownX = event.x;
	MouseDownY = event.y;
	bMouseDown = true;
}

function recursiveMove( obj, offX, offY ) {
	if( obj == null )
		return;
	moving = true;
	with( obj ) {
		style.pixelTop += offY;
		style.pixelLeft += offX;
		if( childNode != '' ) {
			var ss = childNode.split( ',' );
			for( i in ss )
				recursiveMove( document.all.item('div_'+ss[i]), offX, offY );
		}
		recalcLoc( obj );
	}
	moving = false;
}

function onMouseMove() {
	if( bMouseDown && currObject ) {
		event.cancelBubble = true;
		event.returnValue = false;
		if( event.ctrlKey ) {	// move whole tree from this node
			if( !moving ) {
				var offX = event.x - MouseDownX;
				var offY = event.y - MouseDownY;
				window.status = offX + ',' + offY;
				recursiveMove( currObject, offX, offY );
			}
		}
		else {		// move only on node
			currObject.style.pixelLeft = event.x - objX;
			currObject.style.pixelTop = event.y - objY;
			recalcLoc( currObject );
		}
		MouseDownX = event.x;
		MouseDownY = event.y;
		return false;
	}
}

function recalcLoc( obj ) {
	if( obj == null || obj.tagName != 'DIV' )
		return;
	with( obj ) {
		var x = style.pixelLeft + style.pixelWidth/2;
		var y = style.pixelTop;
		if( parentNode != '' ) {
			var nr = id.split('_');
			var ln = document.all.item( 'line_' + nr[1] );
			if( ln ) ln.to = x + 'px,' + y + 'px';
		}
		y += offsetHeight-1;
		if( childNode != '' ) {
			var ss = childNode.split(',');
			for( i in ss ) {
				if( ss[i] != '' ) {
					var obj2 = document.all.item( 'line_' + ss[i] );
					if( obj2 )
						obj2.from = x + 'px,' + y + 'px';
				}
			}
		}
	}
}

function onMouseUp () {
	bMouseDown = false;
}

function changeValue() {
	if( currObject == null )
		return;
	currObject.innerText = value.value;
	recalcLoc( currObject );
}

function delNode() {
	if( currObject == null || currObject.id == 'root' )
		return;
	var old = currObject;
	unfocusObject();
	recursiveDel( old );
}

function dump( o ) {
	str = '';
	for( i in o )
		str += i + ': ' + o[i] + '\n';
	alert( str );
}

function recursiveDel( obj ) {
	if( obj == null )
		return;
	// del child node
	if( obj.childNode && obj.childNode != '' ) {
		var i, ss = obj.childNode.split(',');
		for( i in ss ) {
			if( ss[i] != '' )
				recursiveDel( document.all.item( 'div_' + ss[i] ) );
		}
	}
	// del node from parent's list
	var ss = obj.id.split( '_' );
	var p = document.all.item( obj.parentNode );
	if( p ) {
		var newChild='', ss2 = p.childNode.split(',');
		for( j in ss2 ) {
			if( ss2[j] == '' || ss2[j] == ss[1] )
				continue;
			newChild += ss2[j] + ',';
		}
		p.childNode = newChild;
	}

	// del itself & line
	var ln = document.all.item( 'line_' + ss[1] );
	if( ln )
		ln.removeNode( true );
	obj.removeNode( true );
}

function adjustDownChild( obj )
{
	if( obj == null )
		return;
	var ss = obj.childNode.split( ',' );
	for( i in ss ) {
		if( ss[i] != '' ) {
			var c = document.all.item( 'div_' + ss[i] );
			if( c != null )
				adjustDownChild( c );
		}
	}
	obj.style.pixelTop += 50;
	recalcLoc(obj);
}

function newParent() {
	if( currObject == null || currObject.id == 'root' )
		return;
	adjustDownChild( currObject );
	var ss = currObject.id.split('_');
	newDiv = "<div id='div_" + divNr + "' class='obj' style='top:" +	(currObject.style.pixelTop-50) + "px; left:" + currObject.style.pixelLeft + "px; width:" + currObject.style.pixelWidth + "px; cursor:hand' parentNode='" + currObject.parentNode + "' childNode='" + ss[1] + ",'></div>\n";
	base.insertAdjacentHTML( 'BeforeEnd', newDiv );
	var parent = document.all.item( currObject.parentNode );
	var ss2 = parent.childNode.split( ',' );
	var str='';
	for( i in ss2 ) {
		if( ss2[i] != '' && ss2[i] != ss[1] )
			str += ss2[i] + ',';
	}
	parent.childNode = str + divNr + ',';
	currObject.parentNode = 'div_' + divNr;

	var newObj = document.all.item( currObject.parentNode );
	var from = (newObj.style.pixelLeft + newObj.style.pixelWidth/2) + 'px,' + (newObj.style.pixelTop + newObj.style.pixelHeight) + 'px';
	var to = (currObject.style.pixelLeft + currObject.style.pixelWidth/2) + 'px,' + currObject.style.pixelTop + 'px';
	var newLn = "<v:line id='line_" + lineNr + "' class='ln' from='" + from + "' to='" + to + "' />\n";
	base.insertAdjacentHTML( 'BeforeEnd', newLn );
	lineNr++;
	divNr++;

	recalcLoc( newObj );
	recalcLoc( parent );
	recalcLoc( currObject );
}

function newChild() {
	if( currObject == null )
		return;
	with( currObject ) {
		_top = offsetTop;
		_left = offsetLeft;
		_width = offsetWidth;
		_height = offsetHeight;
	}

	newDiv = "<div id='div_" + divNr + "' class='obj' style='top:" + (_top+_height+20) + "px; left:" + _left + "px; width:"+currObject.style.pixelWidth+"px; cursor:hand' parentNode='" + currObject.id + "' childNode=''></div>\n";
	base.insertAdjacentHTML( 'BeforeEnd', newDiv );
	currObject.childNode += divNr + ',';
	newObj = document.all.item( 'div_' + divNr );
	with( newObj ) {
		_top2 = offsetTop;
		_left2 = offsetLeft;
		_width2 = offsetWidth;
		_height2 = offsetHeight;
	}

	from = (_left+_width/2) + ',' + (_top+_height-1) + 'px';
	to = (_left2+_width2/2) + 'px,' + (_top2) + 'px';
	newLn = "<v:line id='line_" + lineNr + "' class='ln' from='" + from + "' to='" + to + "' />\n";
	base.insertAdjacentHTML( 'BeforeEnd', newLn );
	lineNr++;
	divNr++;
}

function sizeInc() {
	if( currObject == null )
		return;
	if( currObject.style.pixelWidth < 500 ) {
		currObject.style.pixelWidth += 50;
		recalcLoc( currObject );
	}
	return false;
}

function sizeDec() {
	if( currObject == null )
		return;
	if( currObject.style.pixelWidth > 100 )
		currObject.style.pixelWidth -= 50;
		recalcLoc( currObject );
	return false;
}
</SCRIPT>
</BODY>
</HTML>