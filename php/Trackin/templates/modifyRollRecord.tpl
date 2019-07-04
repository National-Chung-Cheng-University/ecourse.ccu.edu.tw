<HTML>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<SCRIPT language=JavaScript>
function validate() 
{ 
        if ( document.all.date.value == "" )
        {
                alert("請輸入日期！");
                form1.date.focus();
                return (false);
        }
}

function select_all() 
{  
	for(var i=0;i<document.all.form1.elements.length;i++)
	{
		var e=document.all.form1.elements[i]; 
		if( (e.name != 'state') && e.name.indexOf('state', 0) == 0 )
		{
			 var a = document.getElementsByName(e.name);	
			 
			 if(document.all.state[0].checked) 	 
				  a[0].checked = true; 	
			 else if(document.all.state[1].checked)
				  a[1].checked = true; 	
			 else if(document.all.state[2].checked)
				  a[2].checked = true; 	
			 else if(document.all.state[3].checked) 	 
				  a[3].checked = true; 	
			 else if(document.all.state[4].checked)
				  a[4].checked = true; 	
			 else if(document.all.state[5].checked)
				  a[5].checked = true; 
		}					
	}		
}

</SCRIPT>

</head>
<body background=/images/IMG/bg.gif onLoad="parent.options.cwin();">
<CENTER>


<form name="form1" action="do_modifyRollRecord.php" method="post" onsubmit="return validate()">
   請輸入日期:
      <input name="date" type="text" value="ROLL_DATE"> 
	  <input name="roll_id" type="hidden" value="ROLL_ID">   
   <p>
   </p>
   <table border="0" align="center" cellpadding="0" cellspacing="0" width="90%">
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
<!-- BEGIN DYNAMIC BLOCK: row -->
<TR bgcolor="COLOR">
<TD>STUDENT_ID</TD>
<TD>STUDENT_NAME</TD>
<TD>STATE</TD>
<TD>NOTE</TD>
</TR>
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

   <p>
     <input type="submit" name="Submit" value="送出">
   </p>
</form>
</CENTER>
</BODY>
</HTML>
