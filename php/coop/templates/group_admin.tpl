<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css"> 
<SCRIPT LANGUAGE="JavaScript">
<!--
var first = true;

function autogroup() {
	var errormsg = "�п�J\n";
	var errorflag = false;
	var total = 0;
	var each = 0;
	var total_student = 0;
	var method = 3;
	var base = 6;
	var other = 11;

	// error checking part START.
	if( (groupform.totalgroup.value.length > 0) && (isNaN( parseInt( groupform.totalgroup.value ) )) ) {
		errorflag = true;
		errormsg = errormsg + "  ���T�����ռƥ�\n";
	}

	if( (groupform.eachgroup.value.length > 0) && (isNaN( parseInt( groupform.eachgroup.value ) )) ) {
		errorflag = true;
		errormsg = errormsg + "  ���T���C�դH��\n";
	}

	if( (groupform.totalgroup.value.length == 0) && (groupform.eachgroup.value.length == 0) ){
		errorflag = true;
		errormsg = errormsg + "  �ܤ֤@�����ձ���\n";
	}

	if( errorflag ) {
		alert(errormsg);
		return -1;
	}
	// error checking part END.


	// processing part START.
	total_student = (groupform.length - other) / 3;

	if( (groupform.totalgroup.value.length > 0) ) {
		total = parseInt(groupform.totalgroup.value);
	}
	else {
		total = Math.ceil( total_student / parseInt(groupform.eachgroup.value) );
		method = 1;
	}

	if( (groupform.eachgroup.value.length > 0) ) {
		each = parseInt(groupform.eachgroup.value);
	}
	else {
		each = Math.floor( total_student / parseInt(groupform.totalgroup.value) );
		method = 2;
	}

	if( groupform.a[0].checked ) {  // divide by sequential
				
		switch(method) {
			case 1:  // �u�ӤH�Ƥ�
				for( i=0;i<total_student;i++ ) {
					groupform.elements[base+(3*i)].value = Math.floor(i/each) + 1;
				}
				break;
			case 2: // �u�ӲէO��
				for( i=0;i<each*total;i++ ) {
					groupform.elements[base+(3*i)].value = Math.floor(i/each) + 1;
				}
				
				j = 1;
				for( ;i<total_student;i++ ) {
					groupform.elements[base+(3*i)].value = j;
					j++;
				}
				break;
			case 3: // ��س���. �S���쪺�̭�Ӫ���.
				for( i=0;(i<each*total) && (i<total_student);i++ ) {
					groupform.elements[base+(3*i)].value = Math.floor(i/each) + 1;
				}

				if( each*total < total_student ) {
					message = "�|�� " + (total_student-each*total) + " ��ǥͥ��Q�۰ʤ���\n���Q���ժ��ǥͨ�էO����ӭ�";
					alert(message);
				}
				break;
			default: // ??????
				alert("�o�ͤ������~, ���p���t�κ޲z��.");
				break;
		}
	}
	else {  // divide by random.
		id = new Array(total_student);
		for( i=0;i<total_student;i++ ) {
			id[i] = i;
		}

		/* shuffle loop. */
		for( i=0;i<total_student;i++ ) {
			j = Math.floor( Math.random()*(total_student - i) ) + i;
			temp = id[i];
			id[i] = id[j];
			id[j] = temp;
		}
		
		switch(method) {
			case 1:  // �u�ӤH�Ƥ�
				for( i=0;i<total_student;i++ ) {
					k = base+(3*id[i]);
					groupform.elements[k].value = Math.floor(i/each) + 1;
				}
				break;
			case 2: // �u�ӲէO��
				
				for( i=0;i<each*total;i++ ) {
					k = base+(3*id[i]);
					groupform.elements[k].value = Math.floor(i/each) + 1;
				}
				
				j = 1;
				for( ;i<total_student;i++ ) {
					k = base+(3*id[i]);
					groupform.elements[k].value = j;
					j++;
				}
				break;
			case 3: // ��س���. �S���쪺�̭�Ӫ���.
				for( i=0;(i<each*total) && (i<total_student);i++ ) {
					k = base+(3*id[i]);
					groupform.elements[k].value = Math.floor(i/each) + 1;
				}

				if( each*total < total_student ) {
					message = "�|�� " + (total_student-each*total) + " ��ǥͥ��Q�۰ʤ���\n���Q���ժ��ǥͨ�p�սs������ӭ�";
					alert(message);
				}
				break;
			default: // ??????
				alert("�o�ͤ������~, ���p���t�κ޲z��.");
				break;
		}
	}
	// processing part END.

	if(first) {
		alert("�۰ʤ��է���аO�o�� \"��s���ո��\" ���s, ���Ʈw��Ƨ@��s.");
		first = false;
	}

	return 0;
}

//-->
</SCRIPT>
</head>
<body background="/images/img/bg.gif">
<center>
<form action='GRP_ADM' method="post" name="groupform">
<font color='red' size=-1>�аO�o���U���� "��s���ո��" ���Ʈw�@��s</font>
<table border="0" align="center" cellpadding="0" cellspacing="0" >
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
<tr bgcolor=#E6FFFC>
	<td bgcolor="#000066"><font color="#ffffff">���ձ���</font>
	<td>����<input type="text" name="totalgroup" size=3>��
	<td>�C��<input type="text" name="eachgroup" size=3>�H
<tr bgcolor=#F0FFEE>
	<td bgcolor="#000066"><font color="#ffffff">���դ覡</font>
	<td><input type="radio" name="a" value="1" checked>�̾Ǹ�����
	<td><input type="radio" name="a" value="2">�üƤ���
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
</table><br>
<input type="button" onClick="autogroup();" value="�۰ʤ���">
<hr>
<table border="0" align="center" cellpadding="0" cellspacing="0" >
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
<caption>�п�J�U�Ӿǥͪ��p�սs��, <br>�����ժ��ǥͽs����"<B>-1</B>"</caption>
<tr bgcolor="#000066"><td align="center"><font color=#ffffff>�Ǹ�</font>
                      <td align="center"><font color=#ffffff>�m�W</font>
                      <td align="center"><font color=#ffffff>�s�p�սs��</font>
					  <td align="center"><font color=#ffffff>��p�սs��</font>
<!-- BEGIN DYNAMIC BLOCK: user_list -->
<tr bgcolor=GRCOLOR><td>STU_ID<td>STU_NAME
    <td><input type="text" name="GRP_INPUT" size=10 maxlength=4 value="GRP_NUM">
        <input type="hidden" name="SID_INPUT" value="STU_ID">
		<input type="hidden" name="SID_STAT" value="STATUS">
    <td>GRP_NUM
<!-- END DYNAMIC BLOCK: user_list -->
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
</table><br>
<input type="hidden" name="action" value="update">
<input type="hidden" name="case_id" value="CASEID">
<input type="submit" name="submit" value="��s���ո��"><input type="reset" name="reset" value="�^�_�즳���">
<input type="button" value="�^�޲z����" onClick="location.href='Mag_case.php?PHPSESSID=PHP_ID'">
</form>
<hr>
</center>
</body>
</html>