<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
var first = true;

function Fun_round(objnum, decimal) { //�Nobjnum�|�ˤ��J �A decimal = 0 => �p���I��0��(�Ӧ��)
  	var tNum = 0;
  	tNum = Math.round( objnum * Math.pow(10,decimal) ) / Math.pow(10,decimal)
  	return tNum;
}

function autogroup() {
	var errormsg = "�п�J\n";
	var errorflag = false;
	var total = 0;
	var each = 0;
	var total_student = 0;
	var method = 3;
	var base = 6;
	var other = 9;

	// error checking part START.
	if( (groupform.totalgroup.value.length > 0) && (isNaN( parseInt( groupform.totalgroup.value ) )) ) { //�����X��
		errorflag = true;
		errormsg = errormsg + "  ���T�����ռƥ�\n";
	}

	if( (groupform.eachgroup.value.length > 0) && (isNaN( parseInt( groupform.eachgroup.value ) )) ) {   //�C�մX�H
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
	//total_student = Fun_round(total_student, 0);

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
					groupform.elements[(base-1)+(3*i)].value = Math.floor(i/each) + 1;
				}
				break;
			case 2: // �u�ӲէO��
				for( i=0;i<each*total;i++ ) {
					groupform.elements[(base-1)+(3*i)].value = Math.floor(i/each) + 1;
				}
				
				j = 1;
				for( ;i<total_student;i++ ) {
					groupform.elements[(base-1)+(3*i)].value = j;
					j++;
				}
				break;
			case 3: // ��س���. �S���쪺�̭�Ӫ���.
				for( i=0;(i<each*total) && (i<total_student);i++ ) {
					groupform.elements[(base-1)+(3*i)].value = Math.floor(i/each) + 1;
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
					k = (base-1)+(3*id[i]);
					groupform.elements[k].value = Math.floor(i/each) + 1;
				}
				break;
			case 2: // �u�ӲէO��
				
				for( i=0;i<each*total;i++ ) {
					k = (base-1)+(3*id[i]);
					groupform.elements[k].value = Math.floor(i/each) + 1;
				}
				
				j = 1;
				for( ;i<total_student;i++ ) {
					k = (base-1)+(3*id[i]);
					groupform.elements[k].value = j;
					j++;
				}
				break;
			case 3: // ��س���. �S���쪺�̭�Ӫ���.
				for( i=0;(i<each*total) && (i<total_student);i++ ) {
					k = (base-1)+(3*id[i]);
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
		alert("�۰ʤ��է���аO�o�� \"1.��s���ո��\" �H�έ����U�誺 \"2.�M�ΦܰQ�װ�\" ���s, ���Ʈw��Ƨ@��s.");
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
<font color='red' size=-1>�аO�o���U���� "1.��s���ո��" �H�έ����U�誺 "2.�M�ΦܰQ�װ�" ���s �H�M�Χ�s�ܰQ�װ�</font>
<table border=1>
<tr>
	<td bgcolor="#4d6be2"><font color="#ffffff">���ձ���</font>
	<td>����<input type="text" name="totalgroup" size=3>��
	<td>�C��<input type="text" name="eachgroup" size=3>�H
<tr>
	<td bgcolor="#4d6be2"><font color="#ffffff">���դ覡</font>
	<td><input type="radio" name="a" value="1" checked>�̾Ǹ�����
	<td><input type="radio" name="a" value="2">�üƤ���
</table><br>
<input type="button" onClick="autogroup();" value="�۰ʤ���">
<hr>
<table border=1>
<caption>�п�J�U�Ӿǥͪ��p�սs��, <br>�����ժ��ǥͽs����"<B>-1</B>"</caption>
<br>
<font color='red' size=-1>�аO�o�� "1.��s���ո��" �H�έ����U�誺 "2.�M�ΦܰQ�װ�" ���s �H�M�Χ�s�ܰQ�װ�</font>
<tr bgcolor="#4d6be2"><td align="center"><font color=#ffffff>�Ǹ�</font>
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
</table><br>
<input type="hidden" name="action" value="update">
<input type="submit" name="submit" value="1.��s���ո��"><input type="reset" name="reset" value="�^�_�즳���">
<input type="button" value="�^�Q�װϤ@��" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</form>
<hr>
</center>
<form name="handle" action="group_admin.php" method="post">
<table border=1 width=100%>
<tr bgcolor=#cdeffc><td width=50>���<td>�Q�װϼ��D<td>�Q�װϥD��<td width=120>����
<!-- BEGIN DYNAMIC BLOCK: discuss_list -->
<tr bgcolor=DISCOLOR>
<td width=50><input type="checkbox" name="DEL_NAME" value="DIS_ID" checked>
<td>DIS_NAME
<td>DIS_COMMENT
<td width=120>DIS_TYPE
<!-- END DYNAMIC BLOCK: discuss_list -->
</table>
<img src="/images/arrow_ltr.gif" border=0 width="38" height="22" alt="��ܪ��ʧ@" >
<input type="hidden" name="action" value="set">
<input type="submit" name="submit" value="2.�M�ΦܰQ�װ�" >
</form>

</body>
</html>
