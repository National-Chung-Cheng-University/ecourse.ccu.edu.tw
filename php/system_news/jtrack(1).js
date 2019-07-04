// JavaScript Document
var qs = '';
str = location.href;
chknum = str.match(/(http):\/\/.+/);
if(str && chknum)
{
	bnum = str.indexOf("/",7);
	xweb = str.substring(0,bnum);
	qs += '?xweb=' + xweb;
	xpath = str.substring(bnum,str.length);
	qs += '&xpath=' + xpath;	
	document.write('<IMG SRC="http://www.creatop.com.tw/Track/Track.php' + qs + '" BORDER="0" WIDTH="0" HEIGHT="0" style="display:none;">');
}