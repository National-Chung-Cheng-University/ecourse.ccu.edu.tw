<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>�޲z�̥\�୶</title>
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
<div align="center" class="style1">�޲z�̥\�୶</div>
<br/>
<table width="814" border="1" align="center" cellpadding="0" bordercolor="#999999">
  <tr bgcolor="#588ccc">

    <td width="110"><div align="center" class="style2">�t�Υ\��</div></td>
    <td width="692"><div align="center" class="style2">�ϥλ���</div></td>
  </tr>
  <tr>
    <td><input type="button" value="�]    �w     ��    ��" style="width:120px" onclick="showIt('table_1');" /></td>
    <td>�]�w��U�Ǵ��P�Ǧ~�C</td>
  </tr>

  <tr>
    <td>      <input name="button" type="button" value="�}                    ��" style="width:120px" onclick="showIt('table_2');" />    </td>
    <td>�}�Ҭ����]�w�B�J�C</td>
  </tr>
  <tr>
    <td>      <input name="button2" type="button" value="�� �u �� �v �� �@" style="width:120px" onclick="showIt('table_3');" />    </td>

    <td>�C�Ǵ�����������إ��ұЮv�����ݸu���A���A�]�����������eú���Z�A�W�]�����\��C</td>
  </tr>
  <tr>
    <td>      <input name="button" type="button" value="��  ��  ��  ��   ��" style="width:120px" onclick="showIt('table_4');" />    </td>
    <td>�]�w�Ȯɩʿ�Ҹ�ƨB�J�C</td>
  </tr>

  <tr>

    <td>      <input name="button" type="button" value="��  ��  ��  ��   ��" style="width:120px" onclick="showIt('table_5');" />    </td>
    <td>�]�w�̲ת���Ҹ�ƨB�J�C</td>
  </tr>
  <tr>
    <td>      <input name="button" type="button" value="��    �Z    �W     ��" style="width:120px" onclick="showIt('table_6');" />    </td>

    <td>�]�w���Z�W�Ǵ����C</td>

  </tr>
  <tr>
    <td>      <input name="button" type="button" value="��    ��    ��     ��" style="width:120px" onclick="showIt('table_7');" />    </td>
    <td>�Ǵ����ݭn�ƥ����B�J�C</td>
  </tr>

  <tr>
    <td>      <input name="button" type="button" value="��    ��    ��     �z" style="width:120px" onclick="showIt('table_8');" />    </td>

    <td>���ǥͥ�ǩάO�h�ǡA�������b�ҵ{�t�Ϊ��ϥ��v���ɨϥΡC</td>
  </tr>
  <tr>
    <td>      <input name="button" type="button" value="��                    �L" style="width:120px" onclick="showIt('table_9');" />    </td>

    <td>��L�C</td>
  </tr>
</table>

<br/>
<table width="480" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_1" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">�Ǵ��]�w�B�J</div>      </td>
  </tr>

  <tr>
    <td colspan="2">(1) �]�w��U�Ǵ�</td>
  </tr>

  <tr>
  	<form action="/php/Courses_Admin/set_semester.php" method="post">
    <td width="94" height="23">
		<input name="button22" type="submit" value="�] �w �� �U �� ��" style="width:120px" />
	</td>

    </form>
    <td width="374">���ثe���ݪ��Ǵ��~�פ��Ǧ~�ξǴ��ȡC</td>
  </tr>

</table>
<table width="480" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_2" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">�}�ҳ]�w�B�J</div></td>
  </tr>

  <tr>
    <td colspan="2">(1) ��s�Юv</td>
  </tr>
  <tr>
    <td colspan="2">(2) ��s�}��</td>
  </tr>
  <tr>

    <td colspan="2">(3) �[��&quot;��s�}��&quot;������</td>
  </tr>
  <tr>

  	<!--form action="/php/ecdemo/update_tch_new.php" method="post"-->
  	<form action="/php/ecdemo/update_tch_new.postgre.php" method="post">
    <td width="77" height="23">
		<input name="button222" type="submit" value="�� �s �� �v(New)" style="width:140px" />	</td>
	</form>

    <td width="391">��s�Юv��T�C</td>
  </tr>
  <tr>

  	<!--form action="/php/ecdemo/update_course_new.php" method="post"-->
  	<form action="/php/ecdemo/update_course_new.postgre.php" method="post">
    <td height="23">
		<input name="button222" type="submit" value="�� �s �} ��(New)" style="width:140px" />	</td>
	</form>

    <td>��s�s�Ǵ����}�Ҹ�ơC</td>
  </tr>
  <tr>
    <td height="23"><input name="button226" type="button" value="&quot;��s�}��&quot;����" style="width:140px" onclick="ViewUpdateLog(1);" /></td>
    <td>�[��&quot;��s�}��&quot;�����ɡC</td>
  </tr>
</table>

<table width="480" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_3" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">�ݸu�Юv�}�Һ��@</div></td>
  </tr>
  <tr>
    <td colspan="2">(1) �ݸu�Юv�бb���s�W���@</td>

  </tr>
  <tr>

    <td colspan="2">(2) �ݸu�Юv�ж}�ҧ�s</td>
  </tr>
  <tr>
	<form action="/php/Learner_Profile/update_o_tch.php" method="post">
    <td width="77" height="23">
		<input name="button222" type="submit" value="�b  ��  �s  �W  ��  ��  �@" style="width:150px" />

	</td>
	</form>

    <td width="391"> �s�W�κ��@�ݸu�Юv�b���C</td>
  </tr>
  <tr>
	<form action="/php/Courses_Admin/update_o_course.php" method="post">
    <td height="23">
		<input name="button222" type="submit" value="�}�Ҭ�إ��ұЮv��s" style="width:150px" />

	</td>

	</form>
    <td> ��s�ݸu�Юv�}�ҳs���C</td>
  </tr>
</table>
<table width="561" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_4" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">�Ȯɩʿ�ҳ]�w�B�J</div>      </td>
  </tr>
  <tr>
    <td colspan="2">(1) ��s�ǥ�</td>
  </tr>
  <tr>
    <td colspan="2">(2) �[��&quot;��s�ǥ�&quot;������</td>
  </tr>
  <tr>
    <td colspan="2">(3) ��s�ջڿ�ҥͿ��</td>
  </tr>
  <tr>
    <td colspan="2">(4) ��s�Ȯɩʿ��</td>
  </tr>
  <tr>
    <td colspan="2">(5) �[��&quot;��s�Ȯɩʿ��&quot;������</td>
  </tr>
  <tr>
    <td colspan="2">(6) �P�B�ǥͻP�ҵ{</td>
  </tr>
  <tr>
    <td colspan="2">(7) �P�B�ǥͻP�Q�װϭq�\�W��</td>
  </tr>
  <tr>
    <form action="/php/ecdemo/update_stu_new.postgre.php" method="post">
      
      <td height="23"><input name="button222" type="submit" value="�� �s �� ��(PG)" style="width:140px" />      </td>
      <!--td>��s�ǥ͸�T--������~����disable</td-->
    </form>
    <td>��s�ǥ͸�T�C</td>
  </tr>
  <tr>
    <td height="23"><input name="button22" type="button" value="&quot;��s�ǥ�&quot;����" style="width:140px" onclick="ViewUpdateLog(4);" />    </td>
    <td>�[��&quot;��s�ǥ�&quot;�����ɡC</td>
  </tr>

  <tr>
  	<form action="/php/ecdemo/update_OtherStu_new.php" method="post">
    <td width="120" height="23">
		<input name="button223" type="submit" value="��s�ջڿ�ҥͿ��" style="width:140px" />	</td>
	</form>
    <td width="429">��s�s�Ǵ����ջڿ�ҥ;ǥͿ�Ҹ��(�ѼȮɿ�Ҹ�ƨ��o)�C</td>
  </tr>

  
  <tr>
  	<form action="/php/ecdemo/update_temptakecourse_new.postgre.php" method="post">
    <td width="120" height="23">
		<input name="button22" type="submit" value="��s�Ȯɩʿ��(PG)" style="width:140px" />	</td>
	</form>
    <td width="429">��s�s�Ǵ����ǥͿ�Ҹ��(�ѼȮɿ�Ҹ�ƨ��o)�C</td>
  </tr>
  <tr>
    <td height="23"><input name="button22" type="button" value="&quot;��s�Ȯɩʿ��&quot;����" style="width:140px" onclick="ViewUpdateLog(3);" /></td>
    <td>�[��&quot;��s�Ȯɩʿ��&quot;�����ɡC</td>
  </tr>
  <tr>
    <form action="/php/ecdemo/sync_data.php" method="post">
      
      <td height="23"><input name="button22" type="submit" value="�P�B�ǥͻP�ҵ{" style="width:140px" /></td>
    </form>
    <td>�P�B�ǥͦb�ҵ{��������B�@�~�B�ݨ���ơC</td>
  </tr>
  <tr>
    <form action="/php/w60292/discuss_synchronization.php" method="post">
      <td height="23"><input name="button22" type="submit" value="�P�B�ǥͻP�Q�װ�" style="width:140px" /></td>
    </form>
    <td>�P�B�ǥͦb�ҵ{�����Q�װϭq�\��ơC</td>
  </tr>
</table>
<table width="480" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_5" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">�̲ת���ҳ]�w�B�J</div></td>
  </tr>
  <tr>
    <td colspan="2">(1) ��s�ǥ�</td>
  </tr>

  <tr>
    <td colspan="2">(2) �[��&quot;��s�ǥ�&quot;������</td>
  </tr>
  <tr>
    <td colspan="2">(3) ��s���</td>
  </tr>
  <tr>
    <td colspan="2">(4) �[��&quot;��s���&quot;������</td>
  </tr>
  <tr>
    <td colspan="2">(5) �P�B�ǥͻP�ҵ{</td>
  </tr>
  <tr>
    <td colspan="2">(6) �P�B�ǥͻP�Q�װϭq�\�W��</td>
  </tr>
  <tr>
	<form action="/php/ecdemo/update_stu_new.php" method="post">

    <td width="77" height="23">
		<input name="button222" type="submit" value="�� �s �� ��" style="width:140px" />	</td>
	</form>
    <td width="391">��s�ǥ͸�T�C</td>
  </tr>
  <tr>
    <td height="23"><input name="button22" type="button" value="&quot;��s�ǥ�&quot;����" style="width:140px" onclick="ViewUpdateLog(4);" />    </td>
    <td>�[��&quot;��s�ǥ�&quot;�����ɡC</td>
  </tr>

  <tr>
	<form action="/php/ecdemo/update_takecourse_new.php" method="post">

    <td height="23">
		<input name="button222" type="submit" value="�� �s �� ��" style="width:140px" />	</td>
	</form>
    <td>��s�s�Ǵ����ǥͿ�Ҹ�ơC</td>
  </tr>
  <tr>
    <td height="23"><input name="button22" type="button" value="&quot;��s���&quot;����" style="width:140px" onclick="ViewUpdateLog(2);" />    </td>
    <td>�[��&quot;��s���&quot;�����ɡC</td>
  </tr>
  <tr>
    <form action="/php/ecdemo/sync_data.php" method="post">
      
      <td height="23"><input name="button22" type="submit" value="�P�B�ǥͻP�ҵ{" style="width:140px" /></td>
    </form>
    <td>�P�B�ǥͦb�ҵ{��������B�@�~�B�ݨ���ơC</td>
  </tr>
  <form action="/php/w60292/discuss_synchronization.php" method="post">
      <td height="23"><input name="button22" type="submit" value="�P�B�ǥͻP�Q�װ�" style="width:140px" /></td>
    </form>
    <td>�P�B�ǥͦb�ҵ{�����Q�װϭq�\��ơC</td>
  </tr>
</table>
<table width="374" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_6" style="display:none">
  <tr bgcolor="#588ccc">

    <td colspan="2"><div align="center" class="style2">���Z�W�Ǭ����]�w</div>      </td>
  </tr>
  <tr>
    <td colspan="2"> (1) �]�w���Z�e��I���</td>

  </tr>
  <tr>
	<form action="/php/Courses_Admin/deadline_setup.php" method="post">

    <td width="140" height="23">
		<input name="button22" type="submit" value="�]�w���Z�e��I���" style="width:140px" />
	</td>
	</form>
    <td width="222"> �]�w�Юv�W�Ǧ��Z���ɶ��C</td>

  </tr>
</table>
<table width="421" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_7" style="display:none">
  <tr bgcolor="#588ccc">

    <td colspan="2"><div align="center" class="style2">�����ƥ������]�w</div>      </td>
  </tr>
  <tr>
    <td colspan="2">(1) �ƥ����</td>

  </tr>
  <tr>
	<form action="/php/hist_backup/back_all.php" method="post">

    <td width="120" height="23">
		<input name="button22" type="submit" value="�� �� �� ��" style="width:120px" />
	</td>
	</form>
    <td width="289">�ƥ���Ǵ���ƶi���v�ϡC</td>

  </tr>
</table>
<table width="694" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_8" style="display:none">
  <tr bgcolor="#588ccc">

    <td colspan="2"><div align="center" class="style2">�����޲z�����]�w</div>      </td>
  </tr>
  <tr>
    <td colspan="2"> (1) ��s�ǥͦW��</td>
  </tr>
  <tr>
    <td colspan="2"> (2) �j�M�Юv��T</td>
  </tr>

  <tr>
    <td colspan="2"> (3) �w�W�ǽҵ{�j���C��</td>
  </tr>

  <tr>
    <td colspan="2"> (4) �w�W�ǱЧ��C��</td>
  </tr>

  <tr>
    <td colspan="2"> (5) �o�G�t�Τ��i</td>
  </tr>
  <tr>

    <td colspan="2"> (6) �s�����Ʋέp</td>
  </tr>

  <tr>
    <td colspan="2"> (7) �����ݨ��s��    </td>
  </tr>
  
  <tr>
    <td colspan="2"> (8) �ҵ{�j���]�ְO��</td>
  </tr>
  <tr>
	<form action="/php/ecdemo/update_stu_new.php" method="post">

    <td height="23">
		<input name="button22" type="submit" value="��s�ǥ�" style="width:140px" />	</td>
	</form>
    <td> ��ǥͦW�榳����(�p�G�J�B��B�h�ǵ�)�ɡA�Ы������s�P�B�ǥͦW��C</td>
  </tr>
  <tr>
	<form action="/php/Learner_Profile/search_teacher_info.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="�j�M�Юv��T" style="width:140px" />	</td>
	</form>

    <td> �d�߱Юv���b���B�K�X�C</td>
  </tr>
  
  <tr>
	<form action="/php/Courses_Admin/no_intro.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="�w�W�ǽҵ{�j���C��" style="width:140px" />	</td>
	</form>

    <td> ��X���W�ǽҵ{�j�����ҵ{�C</td>
  </tr>

  <tr>
	<form action="/php/Courses_Admin/no_intro_college.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="�w�W�Ǥj��(New_Dep)" style="width:140px" />	</td>
	</form>

    <td> ��X���W�ǽҵ{�j�����ҵ{(New_�̳����έp)�C</td>
  </tr>
  
  <tr>
	<form action="/php/Courses_Admin/no_textbook.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="�w�W�ǱЧ��C��" style="width:140px" />	</td>
	</form>

    <td>��ܤw�W�ǽҵ{�Ч��C���έp��T�C</td>
  </tr>

  <tr>
	<form action="/php/Courses_Admin/no_textbook_college.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="�w�W�ǱЧ�(New_Dep)" style="width:140px" />	</td>
	</form>

    <td>��ܤw�W�ǽҵ{�Ч��C���έp��T(New_�̳����έp)�C</td>
  </tr>  
  
  <tr>
	<form action="/php/sysnews.php" method="post">
    <td height="23">
		<input name="button223" type="submit" value="�o�G�t�Τ��i" style="width:140px" />	</td>
	</form>

    <td> �o�G�s�����i�C</td>
  </tr>
  <tr>
	<form action="/php/Courses_Admin/templates/select.php" method="post">
    <td height="23">
		<input name="button225" type="submit" value="�s�����Ʋέp" style="width:140px" />	</td>
	</form>

    <td>�d�ݸӾǴ��U�ҵ{�B�U�t�ҡB�U�ǰ|�ǥ��s���Ч����ơC</td>
  </tr>
  <tr>
	<form action="/php/mid_questionary/onoff_questionary.php" method="post">
    <td width="140" height="23">
		<input name="button224" type="submit" value="�����ݨ��s��" style="width:140px" />	</td>
	</form>

    <td width="542">�s�W�B�s��B�}�ҩ������ӾǴ������ݨ��C</td>
  </tr>
  <tr>
	<form action="/php/chiefboy1230/intro_audit.php" method="post">
    <td width="140" height="23">
		<input name="button226" type="submit" value="�ҵ{�j���]�ְO��" style="width:140px" />	</td>
	</form>

    <td width="542">�d�߽ҵ{�j�����b���BIP�ήɶ��I�C</td>
  </tr>
</table>
<table width="553" border="1" align="center" cellpadding="0" bordercolor="#999999" id="table_9" style="display:none">
  <tr bgcolor="#588ccc">
    <td colspan="2"><div align="center" class="style2">��L</div>      </td>
  </tr>
  <tr>

    <td colspan="2">(1) �ק�K�X</td>
  </tr>
  <tr>
    <td colspan="2">(2) �ץX�줽�Ǹ�T</td>
  </tr>
  <tr>
    <td colspan="2">(3) �M���w�L�Ľҵ{</td>
  </tr>
  <tr>

    <td colspan="2">(4) �Юv�ϥά����d��</td>
  </tr>
  <tr>
    <td colspan="2">(5) �ץX�wĵ�ǥͲM��1</td>
  </tr>

  <tr>
    <td colspan="2">(6) �ץX�wĵ�ǥͲM��2 </td>
  </tr>
  <tr>
  <!-- 100.11.9 �оǲշs�W�ץX�S���wĵ�W�椧�ҵ{-->
  <tr>
    <td colspan="2">(7) �ץX�S���wĵ�W�椧�ҵ{ </td>
  </tr>
  <tr>


    <td height="23">
		<input name="button22" type="button" value="�ק�K�X" style="width:140px" onclick="ModifyPassword();" />	</td>
    <td>�ק�޲z�̱K�X�C</td>
  </tr>
  <tr>

  	<form action="/php/Courses_Admin/export_office_time.php" method="post">
    <td height="23">

		<input name="button22" type="submit" value="�ץX�줽�Ǹ�T" style="width:140px" />	</td>
	</form>
    <td>�ץX��Ǵ����ձЮv�줽�Ǹ�T�C</td>
  </tr>
  <tr>
   	<form action="/php/ecdemo/del_unused_course.php" method="post">

    <td height="23">

		<input name="button22" type="submit" value="�M���w�L�Ľҵ{" style="width:140px" />	</td>
	</form>
    <td>�N�@�~���~�S���ϥΪ��ҵ{��Ʈw�R���C</td>
  </tr>
  <tr>
   	<form action="/php/Learner_Profile/query_tch_log_index.php" method="post">
    <td height="23">

		<input name="button22" type="submit" value="�Юv�ϥά����d��" style="width:140px" />	</td>
	</form>
    <td>�d�߱Юv���ϥά����C</td>
  </tr>
  <tr>
   	<form action="/php/Courses_Admin/export_earlywarning_jp.php" method="post">
    <td height="23">
		<input name="button22" type="submit" value="�ץX�wĵ�ǥͲM��1" style="width:140px" /></td>
	</form>
    <td>�ץX�wĵ�ǥͲM��еL���Y��r��(�����ɥ�)�C</td>
  </tr>
  <tr>
   	<form action="/php/Trackin/export_earlywarning.php" method="post">
    <td width="140" height="23">
		<input name="button22" type="submit" value="�ץX�wĵ�ǥͲM��2" style="width:140px" />	</td>
	</form>

    <td width="401">�ץX�wĵ�ǥͲM���Excel�榡�C</td>
  </tr>
  <tr>
   	<form action="/php/Courses_Admin/export_earlywarning_NoStu.php" method="post">
    <td width="140" height="23">
		<input name="button22" type="submit" value="�L�wĵ�W�椧�ҵ{" style="width:140px" />	</td>
	</form>

    <td width="401">�ץX�S���wĵ�W�椧�ҵ{�еL���Y��r�ɡC</td>
  </tr>
</table>
</body>
</html>
