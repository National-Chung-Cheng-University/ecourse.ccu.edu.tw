<HTML>
<head>
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=big5">

<title>Check Homework</title>

<script language="javascript">

function searchTD(id){
        if(id.indexOf('pubwork',0) != -1)
                return 'pubwork';
        if(id.indexOf('pubans',0) != -1)
                return 'pubans';
        if(id.indexOf('delay',0) != -1)
                return 'delay';
        return 'no';
}

function showState(id){
        var flag;
        var root = document.getElementById(id);
        var tmp = root.getElementsByTagName('input')[2].getAttribute('value');
        var tmp2 = root.getElementsByTagName('DIV');
        if(tmp2.length == 0){
                var tdId = searchTD(id);
                switch(tdId){
                        case 'pubwork':
                                if(tmp == "不公佈")     flag = "目前狀態：[公佈作業]";
                                else    flag = "目前狀態：[不公佈]";
                                break;
                        case 'pubans':
                                if(tmp == "不公佈")     flag = "目前狀態：[公佈答案]";
                                else    flag = "目前狀態：[不公佈]";
                                break;
                        case 'delay':
                                if(tmp == "關閉補交")   flag = "目前狀態：[允許補交]";
                                else    flag = "目前狀態：[不允許補交]";
                                break;
                        case 'no':
                                flag = "";
                                break;
                }
                var newnode = document.createElement("DIV");
                newnode.setAttribute("value",id);
                newnode.setAttribute("id","showStateDiv"+id);
                newnode.style.position = "absolute";
                newnode.style.backgroundColor = "#FFFF99";
                newnode.style.width = "100";
                newnode.style.left = window.event.clientX;
                newnode.style.top  = window.event.clientY;
                newnode.style.filter = "alpha(opacity=60)";
                newnode.appendChild(document.createTextNode(flag));
                root.appendChild(newnode);
        }
}

function delState(id){
        var tmpNode = document.getElementById(id).getElementsByTagName("DIV")[0];
        var parent;
        if(tmpNode!=null){
                parent = tmpNode.parentNode;
                if(parent.hasChildNodes)
                        parent.removeChild(parent.lastChild);
        }
}
</script>
</head>

<BODY background="/images/img/bg.gif">
<p align="center"> <font size="2"><font color="#000000">Check Homework</font></font></p>
<BR><BR>
<center>
<table border="0" align="center" cellpadding="0" cellspacing="0" width="80%">
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_01.GIF" width="12" height="11"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_02.GIF" width="100%" height="11"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_03.GIF" width="17" height="11"></div>
</td>
</tr>
<tr> 
<td height=10> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_04.GIF" width="12" height="100%"></div>
</td>
<td bgcolor="#CCCCCC"> 
<table border=0 align="center" width="100%" cellpadding="3" cellspacing="1">
<tr bgcolor="#000066">
  <td><div align="center"><font color="#FFFFFF">Chapter</font></div></td>
<td> 
<div align="center"><font color="#FFFFFF">Name</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">Topic</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">Deaa Line</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">Ratio</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">See Student's Homework</font></div>
</td>
<td>
<div align="center"><font color="#FFFFFF">Download Student's Homework</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">Public Homework</font></div>
</td>
<td> 
<div align="center"><font color="#FFFFFF">Public Answer</font></div>
</td>
<td>
<div align="center"><font color="#FFFFFF">Hand in Overdue Homework</font></div>
</td>
<!-- BEGIN DYNAMIC BLOCK: row -->
<tr bgcolor="COLOR">
<td>CHAP_NUM</td>
<td>WORKNAME</td>
<td><a href=check_allwork.php?work_id=WORKID&action=showwork>Topic</a></td>
<td>WORKDUE</td>
<td>WORKRATIO</td>
<td><a href=check_allwork.php?work_id=WORKID&action=checkstudent>See Student's Homework & Handin Status</a>
</td>
<form method=POST action=check_allwork.php><td>
<input type=hidden name=action value=downloadallwork>
<input type=hidden name=work_id value=WORKID>
<input type=submit value=Download DISABLED>
</td></form>
<form method=POST action=check_allwork.php><td id='pubworkWORKID' onMouseOver="showState(this.id);" onMouseOut="delState(this.id);">
<input type=hidden name=action value=pubwork>
<input type=hidden name=work_id value=WORKID>
<input type=submit value=ISPUBWORK>
</td></form>
<form method=POST action=check_allwork.php><td id='pubansWORKID' onMouseOver="showState(this.id);" onMouseOut="delState(this.id);">
<input type=hidden name=action value=pubans>
<input type=hidden name=work_id value=WORKID>
<input type=submit value=ISPUBANS>
</td></form>
<form method=POST action=check_allwork.php><td id='delayWORKID' onMouseOver="showState(this.id);" onMouseOut="delState(this.id);">
<input type=hidden name=action value=delay />
<input type=hidden name=work_id value=WORKID />
<input type=submit value=ISDELAY />
</td></form>
</tr>
<!-- END DYNAMIC BLOCK: row -->
</table>
</td>
<td height=10> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_06.GIF" width="17" height="100%"></div>
</td>
</tr>
<tr> 
<td> 
<div align="right"><img src="/images/skinSKINNUM/bor/bor_07.GIF" width="12" height="17"></div>
</td>
<td> 
<div align="center"><img src="/images/skinSKINNUM/bor/bor_08.GIF" width="100%" height="17"></div>
</td>
<td> 
<div align="left"><img src="/images/skinSKINNUM/bor/bor_09.GIF" width="17" height="17"></div>
</td>
</tr>
</table>
<p><br>
<font color=#ff0000>In the "Public Homework" and "Public Answer" fields, enter the "Public" button, then students will see the homowork and answer. Enter the "Never_Public" button, then students won't see the homework and answer.</font> </p>
</center>
</BODY>
</HTML>
