<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>管理者功能頁</title>
<style type="text/css">
<!--
.style1 {
	font-size: large;
	font-weight: bold;
}
.style2 {color: #FFFFFF}

body {
	background-image: url(/images/img/bg.gif);
}
-->
</style>
<script language="javascript">
	function HideAll() {
		var table_prefix = "table_";
		var index = 1;
		
		while (true) {
			var table_id = table_prefix + index.toString();
			var tmp = document.getElementById(table_id);
			if (tmp != null) {
				tmp.style.display = "none";
				index++;
			} else
				break;
		}
	}

	function showIt(tableid) {
		var t = document.getElementById(tableid);
		if (t != null) {
			HideAll();
			t.style.display = "";
		}
	}
	
	function ModifyPassword() {
		window.location = './Learner_Profile/chang_pass_admin.php';
	}
	
	function ViewUpdateLog(sel) {
		if (sel == 1)
			window.location = '../logs/course.log';
		else if (sel == 2)
			window.location = '../logs/takecourse.log';
		else if (sel == 3)
			window.location = '../logs/temptakecourse.log';
		else if (sel == 4)
			window.location = '../logs/update_student.log';
	}
</script>
</head>
<body>
<div align="center" class="style1">管理者功能頁</div>
<br/>
<table width="814" border="1" align="center" cellpadding="0" bordercolor="#999999">
  <tr bgcolor="#588ccc">

    <td width="110"><div align="center" class="style2">系統功能</div></td>
    <td width="692"><div align="center" class="style2">使用說明</div></td>
  </tr>
  <tr>
    <td><input type="button" value="設    定     學    期" style="width:120px" onclick="showIt('table_1');" /></td>
    <td>設定當下學期與學年。</td>
  </tr>

  <tr>
    <td>      <input name="button" type="button" value="開                    課" style="width:120px" onclick="showIt('table_2');" />    </td>
    <td>開課相關設定步驟。</td>
  </tr>
  <tr>
    <td>      <input name="button2" type="button" value="待 聘 教 師 維 護" style="width:120px" onclick="showIt('table_3');" />    </td>

    <td>每學期均有部份科目任課教師為〝待聘狀態〞，因應全面網路送繳成績，增設此項功能。</td>
  </tr>
  <tr>
    <td>      <input name="button" type="button" value="暫  時  性  選   課" style="width:120px" onclick="showIt('table_4');" />    </td>
    <td>設定暫時性選課資料步驟。</td>
  </tr>

  <tr>

    <td>      <input name="button" type="button" value="最  終  版  選   課" style="width:120px" onclick="showIt('table_5');" />    </td>
    <td>設定最終版選課資料步驟。</td>
  </tr>
  <tr>
    <td>      <input name="button" type="button" value="成    績    上     傳" style="width:120px" onclick="showIt('table_6');" />    </td>

    <td>設定成績上傳期限。</td>

  </tr>
  <tr>
    <td>      <input name="button" type="button" value="期    末    備     份" style="width:120px" onclick="showIt('table_7');" />    </td>
    <td>學期末需要備份的步驟。</td>
  </tr>

  <tr>
    <td>      <input name="button" type="button" value="期    中    管     理" style="width:120px" onclick="showIt('table_8');" />    </td>

    <td>當有學生休學或是退學，欲限制其在課程系統的使用權限時使用。</td>
  </tr>
  <tr>
    <td>      <input name="button" type="button" value="其                    他" style="width:120px" onclick="showIt('table_9');" />    </td>

    <td>其他。</td>
  </tr>
</table>

<br/>
<table width="480" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_1" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">學期設定步驟</div>      </td>
  </tr>

  <tr>
    <td colspan="2">(1) 設定當下學期</td>
  </tr>

  <tr>
  	<form action="/php/Courses_Admin/set_semester.php" method="post">
    <td width="94" height="23">
		<input name="button22" type="submit" value="設 定 當 下 學 期" style="width:120px" />
	</td>

    </form>
    <td width="374">更改目前所屬的學期年度之學年及學期值。</td>
  </tr>

</table>
<table width="480" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_2" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">開課設定步驟</div></td>
  </tr>

  <tr>
    <td colspan="2">(1) 更新教師</td>
  </tr>
  <tr>
    <td colspan="2">(2) 更新開課</td>
  </tr>
  <tr>

    <td colspan="2">(3) 觀看&quot;更新開課&quot;紀錄檔</td>
  </tr>
  <tr>

  	<!--form action="/php/ecdemo/update_tch_new.php" method="post"-->
  	<form action="/php/ecdemo/update_tch_new.postgre.php" method="post">
    <td width="77" height="23">
		<input name="button222" type="submit" value="更 新 教 師(New)" style="width:140px" />	</td>
	</form>

    <td width="391">更新教師資訊。</td>
  </tr>
  <tr>

  	<!--form action="/php/ecdemo/update_course_new.php" method="post"-->
  	<form action="/php/ecdemo/update_course_new.postgre.php" method="post">
    <td height="23">
		<input name="button222" type="submit" value="更 新 開 課(New)" style="width:140px" />	</td>
	</form>

    <td>更新新學期的開課資料。</td>
  </tr>
  <tr>
    <td height="23"><input name="button226" type="button" value="&quot;更新開課&quot;紀錄" style="width:140px" onclick="ViewUpdateLog(1);" /></td>
    <td>觀看&quot;更新開課&quot;紀錄檔。</td>
  </tr>
</table>

<table width="480" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_3" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">待聘教師開課維護</div></td>
  </tr>
  <tr>
    <td colspan="2">(1) 待聘教師－帳號新增維護</td>

  </tr>
  <tr>

    <td colspan="2">(2) 待聘教師－開課更新</td>
  </tr>
  <tr>
	<form action="/php/Learner_Profile/update_o_tch.php" method="post">
    <td width="77" height="23">
		<input name="button222" type="submit" value="帳  號  新  增  及  維  護" style="width:150px" />

	</td>
	</form>

    <td width="391"> 新增及維護待聘教師帳號。</td>
  </tr>
  <tr>
	<form action="/php/Courses_Admin/update_o_course.php" method="post">
    <td height="23">
		<input name="button222" type="submit" value="開課科目任課教師更新" style="width:150px" />

	</td>

	</form>
    <td> 更新待聘教師開課連結。</td>
  </tr>
</table>
<table width="561" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_4" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">暫時性選課設定步驟</div>      </td>
  </tr>
  <tr>
    <td colspan="2">(1) 更新學生</td>
  </tr>
  <tr>
    <td colspan="2">(2) 觀看&quot;更新學生&quot;紀錄檔</td>
  </tr>
  <tr>
    <td colspan="2">(3) 更新校際選課生選課</td>
  </tr>
  <tr>
    <td colspan="2">(4) 更新暫時性選課</td>
  </tr>
  <tr>
    <td colspan="2">(5) 觀看&quot;更新暫時性選課&quot;紀錄檔</td>
  </tr>
  <tr>
    <td colspan="2">(6) 同步學生與課程</td>
  </tr>
  <tr>
    <td colspan="2">(7) 同步學生與討論區訂閱名單</td>
  </tr>
  <tr>
    <form action="/php/ecdemo/update_stu_new.postgre.php" method="post">
      
      <td height="23"><input name="button222" type="submit" value="更 新 學 生(PG)" style="width:140px" />      </td>
      <!--td>更新學生資訊--為防止誤按先disable</td-->
    </form>
    <td>更新學生資訊。</td>
  </tr>
  <tr>
    <td height="23"><input name="button22" type="button" value="&quot;更新學生&quot;紀錄" style="width:140px" onclick="ViewUpdateLog(4);" />    </td>
    <td>觀看&quot;更新學生&quot;紀錄檔。</td>
  </tr>

  <tr>
  	<form action="/php/ecdemo/update_OtherStu_new.php" method="post">
    <td width="120" height="23">
		<input name="button223" type="submit" value="更新校際選課生選課" style="width:140px" />	</td>
	</form>
    <td width="429">更新新學期的校際選課生學生選課資料(由暫時選課資料取得)。</td>
  </tr>

  
  <tr>
  	<form action="/php/ecdemo/update_temptakecourse_new.postgre.php" method="post">
    <td width="120" height="23">
		<input name="button22" type="submit" value="更新暫時性選課(PG)" style="width:140px" />	</td>
	</form>
    <td width="429">更新新學期的學生選課資料(由暫時選課資料取得)。</td>
  </tr>
  <tr>
    <td height="23"><input name="button22" type="button" value="&quot;更新暫時性選課&quot;紀錄" style="width:140px" onclick="ViewUpdateLog(3);" /></td>
    <td>觀看&quot;更新暫時性選課&quot;紀錄檔。</td>
  </tr>
  <tr>
    <form action="/php/ecdemo/sync_data.php" method="post">
      
      <td height="23"><input name="button22" type="submit" value="同步學生與課程" style="width:140px" /></td>
    </form>
    <td>同步學生在課程中的測驗、作業、問卷資料。</td>
  </tr>
  <tr>
    <form action="/php/w60292/discuss_synchronization.php" method="post">
      <td height="23"><input name="button22" type="submit" value="同步學生與討論區" style="width:140px" /></td>
    </form>
    <td>同步學生在課程中的討論區訂閱資料。</td>
  </tr>
</table>
<table width="480" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_5" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">最終版選課設定步驟</div></td>
  </tr>
  <tr>
    <td colspan="2">(1) 更新學生</td>
  </tr>

  <tr>
    <td colspan="2">(2) 觀看&quot;更新學生&quot;紀錄檔</td>
  </tr>
  <tr>
    <td colspan="2">(3) 更新選課</td>
  </tr>
  <tr>
    <td colspan="2">(4) 觀看&quot;更新選課&quot;紀錄檔</td>
  </tr>
  <tr>
    <td colspan="2">(5) 同步學生與課程</td>
  </tr>
  <tr>
    <td colspan="2">(6) 同步學生與討論區訂閱名單</td>
  </tr>
  <tr>
	<form action="/php/ecdemo/update_stu_new.php" method="post">

    <td width="77" height="23">
		<input name="button222" type="submit" value="更 新 學 生" style="width:140px" />	</td>
	</form>
    <td width="391">更新學生資訊。</td>
  </tr>
  <tr>
    <td height="23"><input name="button22" type="button" value="&quot;更新學生&quot;紀錄" style="width:140px" onclick="ViewUpdateLog(4);" />    </td>
    <td>觀看&quot;更新學生&quot;紀錄檔。</td>
  </tr>

  <tr>
	<form action="/php/ecdemo/update_takecourse_new.php" method="post">

    <td height="23">
		<input name="button222" type="submit" value="更 新 選 課" style="width:140px" />	</td>
	</form>
    <td>更新新學期的學生選課資料。</td>
  </tr>
  <tr>
    <td height="23"><input name="button22" type="button" value="&quot;更新選課&quot;紀錄" style="width:140px" onclick="ViewUpdateLog(2);" />    </td>
    <td>觀看&quot;更新選課&quot;紀錄檔。</td>
  </tr>
  <tr>
    <form action="/php/ecdemo/sync_data.php" method="post">
      
      <td height="23"><input name="button22" type="submit" value="同步學生與課程" style="width:140px" /></td>
    </form>
    <td>同步學生在課程中的測驗、作業、問卷資料。</td>
  </tr>
  <form action="/php/w60292/discuss_synchronization.php" method="post">
      <td height="23"><input name="button22" type="submit" value="同步學生與討論區" style="width:140px" /></td>
    </form>
    <td>同步學生在課程中的討論區訂閱資料。</td>
  </tr>
</table>
<table width="374" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_6" style="display:none">
  <tr bgcolor="#588ccc">

    <td colspan="2"><div align="center" class="style2">成績上傳相關設定</div>      </td>
  </tr>
  <tr>
    <td colspan="2"> (1) 設定成績送交截止日</td>

  </tr>
  <tr>
	<form action="/php/Courses_Admin/deadline_setup.php" method="post">

    <td width="140" height="23">
		<input name="button22" type="submit" value="設定成績送交截止日" style="width:140px" />
	</td>
	</form>
    <td width="222"> 設定教師上傳成績的時間。</td>

  </tr>
</table>
<table width="421" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_7" style="display:none">
  <tr bgcolor="#588ccc">

    <td colspan="2"><div align="center" class="style2">期末備份相關設定</div>      </td>
  </tr>
  <tr>
    <td colspan="2">(1) 備份資料</td>

  </tr>
  <tr>
	<form action="/php/hist_backup/back_all.php" method="post">

    <td width="120" height="23">
		<input name="button22" type="submit" value="備 份 資 料" style="width:120px" />
	</td>
	</form>
    <td width="289">備份當學期資料進歷史區。</td>

  </tr>
</table>
<table width="694" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_8" style="display:none">
  <tr bgcolor="#588ccc">

    <td colspan="2"><div align="center" class="style2">期中管理相關設定</div>      </td>
  </tr>
  <tr>
    <td colspan="2"> (1) 更新學生名單</td>
  </tr>
  <tr>
    <td colspan="2"> (2) 搜尋教師資訊</td>
  </tr>

  <tr>
    <td colspan="2"> (3) 已上傳課程大綱列表</td>
  </tr>

  <tr>
    <td colspan="2"> (4) 已上傳教材列表</td>
  </tr>

  <tr>
    <td colspan="2"> (5) 發佈系統公告</td>
  </tr>
  <tr>

    <td colspan="2"> (6) 瀏覽次數統計</td>
  </tr>

  <tr>
    <td colspan="2"> (7) 期中問卷編輯    </td>
  </tr>
  
  <tr>
    <td colspan="2"> (8) 課程大綱稽核記錄</td>
  </tr>
  <tr>
	<form action="/php/ecdemo/update_stu_new.php" method="post">

    <td height="23">
		<input name="button22" type="submit" value="更新學生" style="width:140px" />	</td>
	</form>
    <td> 當學生名單有異動(如：入、休、退學等)時，請按此按鈕同步學生名單。</td>
  </tr>
  <tr>
	<form action="/php/Learner_Profile/search_teacher_info.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="搜尋教師資訊" style="width:140px" />	</td>
	</form>

    <td> 查詢教師的帳號、密碼。</td>
  </tr>
  
  <tr>
	<form action="/php/Courses_Admin/no_intro.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="已上傳課程大綱列表" style="width:140px" />	</td>
	</form>

    <td> 找出有上傳課程大網的課程。</td>
  </tr>

  <tr>
	<form action="/php/Courses_Admin/no_intro_college.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="已上傳大綱(New_Dep)" style="width:140px" />	</td>
	</form>

    <td> 找出有上傳課程大網的課程(New_依部門統計)。</td>
  </tr>
  
  <tr>
	<form action="/php/Courses_Admin/no_textbook.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="已上傳教材列表" style="width:140px" />	</td>
	</form>

    <td>顯示已上傳課程教材列表的統計資訊。</td>
  </tr>

  <tr>
	<form action="/php/Courses_Admin/no_textbook_college.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="已上傳教材(New_Dep)" style="width:140px" />	</td>
	</form>

    <td>顯示已上傳課程教材列表的統計資訊(New_依部門統計)。</td>
  </tr>  
  
  <tr>
	<form action="/php/sysnews.php" method="post">
    <td height="23">
		<input name="button223" type="submit" value="發佈系統公告" style="width:140px" />	</td>
	</form>

    <td> 發佈新的公告。</td>
  </tr>
  <tr>
	<form action="/php/Courses_Admin/templates/select.php" method="post">
    <td height="23">
		<input name="button225" type="submit" value="瀏覽次數統計" style="width:140px" />	</td>
	</form>

    <td>查看該學期各課程、各系所、各學院學生瀏覽教材次數。</td>
  </tr>
  <tr>
	<form action="/php/mid_questionary/onoff_questionary.php" method="post">
    <td width="140" height="23">
		<input name="button224" type="submit" value="期中問卷編輯" style="width:140px" />	</td>
	</form>

    <td width="542">新增、編輯、開啟或關閉該學期期中問卷。</td>
  </tr>
  <tr>
	<form action="/php/chiefboy1230/intro_audit.php" method="post">
    <td width="140" height="23">
		<input name="button226" type="submit" value="課程大綱稽核記錄" style="width:140px" />	</td>
	</form>

    <td width="542">查詢課程大綱的帳號、IP及時間點。</td>
  </tr>
</table>
<table width="553" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_9" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">其他</div>      </td>
  </tr>
  <tr>

    <td colspan="2">(1) 修改密碼</td>
  </tr>
  <tr>
    <td colspan="2">(2) 匯出辦公室資訊</td>
  </tr>
  <tr>
    <td colspan="2">(3) 清除已無效課程</td>
  </tr>
  <tr>

    <td colspan="2">(4) 教師使用紀錄查詢</td>
  </tr>
  <tr>
    <td colspan="2">(5) 匯出預警學生清單1</td>
  </tr>

  <tr>
    <td colspan="2">(6) 匯出預警學生清單2 </td>
  </tr>
  <tr>
  <!-- 100.11.9 教學組新增匯出沒有預警名單之課程-->
  <tr>
    <td colspan="2">(7) 匯出沒有預警名單之課程 </td>
  </tr>
  <tr>


    <td height="23">
		<input name="button22" type="button" value="修改密碼" style="width:140px" onclick="ModifyPassword();" />	</td>
    <td>修改管理者密碼。</td>
  </tr>
  <tr>

  	<form action="/php/Courses_Admin/export_office_time.php" method="post">
    <td height="23">

		<input name="button22" type="submit" value="匯出辦公室資訊" style="width:140px" />	</td>
	</form>
    <td>匯出當學期全校教師辦公室資訊。</td>
  </tr>
  <tr>
   	<form action="/php/ecdemo/del_unused_course.php" method="post">

    <td height="23">

		<input name="button22" type="submit" value="清除已無效課程" style="width:140px" />	</td>
	</form>
    <td>將一年之外沒有使用的課程資料庫刪除。</td>
  </tr>
  <tr>
   	<form action="/php/Learner_Profile/query_tch_log_index.php" method="post">
    <td height="23">

		<input name="button22" type="submit" value="教師使用紀錄查詢" style="width:140px" />	</td>
	</form>
    <td>查詢教師的使用紀錄。</td>
  </tr>
  <tr>
   	<form action="/php/Courses_Admin/export_earlywarning_jp.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="匯出預警學生清單1" style="width:140px" /></td>
	</form>
    <td>匯出預警學生清單－無表頭文字檔(供轉檔用)。</td>
  </tr>
  <tr>
   	<form action="/php/Trackin/export_earlywarning.php" method="post">
    <td width="140" height="23">
		<input name="button22" type="submit" value="匯出預警學生清單2" style="width:140px" />	</td>
	</form>

    <td width="401">匯出預警學生清單－Excel格式。</td>
  </tr>
  <tr>
   	<form action="/php/Courses_Admin/export_earlywarning_NoStu.php" method="post">
    <td width="140" height="23">
		<input name="button22" type="submit" value="無預警名單之課程" style="width:140px" />	</td>
	</form>

    <td width="401">匯出沒有預警名單之課程－無表頭文字檔。</td>
  </tr>
</table>
</body>
</html>
