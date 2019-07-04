<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="/images/skinSKINNUM/css/main-body.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
var first = true;

function Fun_round(objnum, decimal) { //將objnum四捨五入 ， decimal = 0 => 小數點第0位(個位數)
  	var tNum = 0;
  	tNum = Math.round( objnum * Math.pow(10,decimal) ) / Math.pow(10,decimal)
  	return tNum;
}

function autogroup() {
	var errormsg = "請輸入\n";
	var errorflag = false;
	var total = 0;
	var each = 0;
	var total_student = 0;
	var method = 3;
	var base = 6;
	var other = 9;

	// error checking part START.
	if( (groupform.totalgroup.value.length > 0) && (isNaN( parseInt( groupform.totalgroup.value ) )) ) { //分為幾組
		errorflag = true;
		errormsg = errormsg + "  正確的分組數目\n";
	}

	if( (groupform.eachgroup.value.length > 0) && (isNaN( parseInt( groupform.eachgroup.value ) )) ) {   //每組幾人
		errorflag = true;
		errormsg = errormsg + "  正確的每組人數\n";
	}

	if( (groupform.totalgroup.value.length == 0) && (groupform.eachgroup.value.length == 0) ){
		errorflag = true;
		errormsg = errormsg + "  至少一項分組條件\n";
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
			case 1:  // 只照人數分
				for( i=0;i<total_student;i++ ) {
					groupform.elements[(base-1)+(3*i)].value = Math.floor(i/each) + 1;
				}
				break;
			case 2: // 只照組別分
				for( i=0;i<each*total;i++ ) {
					groupform.elements[(base-1)+(3*i)].value = Math.floor(i/each) + 1;
				}
				
				j = 1;
				for( ;i<total_student;i++ ) {
					groupform.elements[(base-1)+(3*i)].value = j;
					j++;
				}
				break;
			case 3: // 兩種都有. 沒分到的依原來的值.
				for( i=0;(i<each*total) && (i<total_student);i++ ) {
					groupform.elements[(base-1)+(3*i)].value = Math.floor(i/each) + 1;
				}

				if( each*total < total_student ) {
					message = "尚有 " + (total_student-each*total) + " 位學生未被自動分組\n未被分組的學生其組別為原來值";
					alert(message);
				}
				break;
			default: // ??????
				alert("發生不明錯誤, 請聯絡系統管理員.");
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
			case 1:  // 只照人數分
				for( i=0;i<total_student;i++ ) {
					k = (base-1)+(3*id[i]);
					groupform.elements[k].value = Math.floor(i/each) + 1;
				}
				break;
			case 2: // 只照組別分
				
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
			case 3: // 兩種都有. 沒分到的依原來的值.
				for( i=0;(i<each*total) && (i<total_student);i++ ) {
					k = (base-1)+(3*id[i]);
					groupform.elements[k].value = Math.floor(i/each) + 1;
				}

				if( each*total < total_student ) {
					message = "尚有 " + (total_student-each*total) + " 位學生未被自動分組\n未被分組的學生其小組編號為原來值";
					alert(message);
				}
				break;
			default: // ??????
				alert("發生不明錯誤, 請聯絡系統管理員.");
				break;
		}
	}
	// processing part END.

	if(first) {
		alert("自動分組完後請記得按 \"1.更新分組資料\" 以及頁面下方的 \"2.套用至討論區\" 按鈕, 對資料庫資料作更新.");
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
<font color='red' size=-1>請記得按下面的 "1.更新分組資料" 以及頁面下方的 "2.套用至討論區" 按鈕 以套用更新至討論區</font>
<table border=1>
<tr>
	<td bgcolor="#4d6be2"><font color="#ffffff">分組條件</font>
	<td>分為<input type="text" name="totalgroup" size=3>組
	<td>每組<input type="text" name="eachgroup" size=3>人
<tr>
	<td bgcolor="#4d6be2"><font color="#ffffff">分組方式</font>
	<td><input type="radio" name="a" value="1" checked>依學號順序
	<td><input type="radio" name="a" value="2">亂數分組
</table><br>
<input type="button" onClick="autogroup();" value="自動分組">
<hr>
<table border=1>
<caption>請輸入各個學生的小組編號, <br>未分組的學生編號為"<B>-1</B>"</caption>
<br>
<font color='red' size=-1>請記得按 "1.更新分組資料" 以及頁面下方的 "2.套用至討論區" 按鈕 以套用更新至討論區</font>
<tr bgcolor="#4d6be2"><td align="center"><font color=#ffffff>學號</font>
                      <td align="center"><font color=#ffffff>姓名</font>
                      <td align="center"><font color=#ffffff>新小組編號</font>
					  <td align="center"><font color=#ffffff>原小組編號</font>
<!-- BEGIN DYNAMIC BLOCK: user_list -->
<tr bgcolor=GRCOLOR><td>STU_ID<td>STU_NAME
    <td><input type="text" name="GRP_INPUT" size=10 maxlength=4 value="GRP_NUM">
        <input type="hidden" name="SID_INPUT" value="STU_ID">
		<input type="hidden" name="SID_STAT" value="STATUS">
    <td>GRP_NUM
<!-- END DYNAMIC BLOCK: user_list -->
</table><br>
<input type="hidden" name="action" value="update">
<input type="submit" name="submit" value="1.更新分組資料"><input type="reset" name="reset" value="回復原有資料">
<input type="button" value="回討論區一覽" onClick="location.href='dis_list.php?PHPSESSID=PHP_ID'">
</form>
<hr>
</center>
<form name="handle" action="group_admin.php" method="post">
<table border=1 width=100%>
<tr bgcolor=#cdeffc><td width=50>選取<td>討論區標題<td>討論區主旨<td width=120>種類
<!-- BEGIN DYNAMIC BLOCK: discuss_list -->
<tr bgcolor=DISCOLOR>
<td width=50><input type="checkbox" name="DEL_NAME" value="DIS_ID" checked>
<td>DIS_NAME
<td>DIS_COMMENT
<td width=120>DIS_TYPE
<!-- END DYNAMIC BLOCK: discuss_list -->
</table>
<img src="/images/arrow_ltr.gif" border=0 width="38" height="22" alt="選擇的動作" >
<input type="hidden" name="action" value="set">
<input type="submit" name="submit" value="2.套用至討論區" >
</form>

</body>
</html>
