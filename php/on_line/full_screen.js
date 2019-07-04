function fullscreen() {
	msplayer.FullScreen=true;
}
function fullscreen64() {
	msplayer.DisplaySize=3;
}
function MM_findObj(n, d) { //v4.0
var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
if(!x && document.getElementById) x=document.getElementById(n); return x;
}

width=240;
height=280;

function DoubleSize() {
	msplayer.width=eval(msplayer.width)*2;
	msplayer.height=(eval(msplayer.height)*2)-120;
	msplayer.DisplaySize=4;
	if (msplayer.height >= 600) self.resizeTo(820, 650);
	if (msplayer.height == 480) self.resizeTo (650, 650);
	if (msplayer.height == 408) self.resizeTo (530, 580);
}
	
function DefaultSize() {
	msplayer.width=eval(msplayer.width) / 2;
	msplayer.height=(eval(msplayer.height)+120) / 2;
	msplayer.DisplaySize=0;
	msplayer.width=width;
	msplayer.height=height;
	self.resizeTo(500,528);
}
