<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>�w���|ĳ�M��</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="{$tpl_path}/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/content.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/table.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>

</head>

<body>

    <h3 align="center">�w���|ĳ�M��</h3>
    <table border=1 align="center">
    <tr>
        <td bgcolor="#E6FFFC">�Ұ�W��</td>
        <td bgcolor="#E6FFFC">�½ұЮv</td>
        <td bgcolor="#E6FFFC">���D</td>
        <td bgcolor="#E6FFFC">�w������ɶ�</td>
        <td bgcolor="#E6FFFC">�|ĳ�̤j�H��</td>
        <td bgcolor="#E6FFFC">���v</td>
        <td bgcolor="#E6FFFC">�ǳƼҦ�</td>
        <td bgcolor="#E6FFFC">�����|ĳ</td>
    </tr>

    {foreach from=$meetingList item=meetingData}
      <tr>
          <td bgcolor="#F0FFEE">{$meetingData.courseName}</td>
          <td bgcolor="#F0FFEE">{$meetingData.teacherName}</td>
          <td bgcolor="#F0FFEE">{$meetingData.title}</td>
          <td bgcolor="#F0FFEE">{$meetingData.startTime}</td>
          <td bgcolor="#F0FFEE">{$meetingData.maxNumAttendee}</td>
          <td bgcolor="#F0FFEE">{if $meetingData.recording == 1}�O{else}�_{/if}</td>
          <td bgcolor="#F0FFEE"><a href='{$meetingData.preparaMeeting}'>�ǳ�</a></td>
          <td bgcolor="#F0FFEE"><a href='{$meetingData.cancelMeeting}'>����</a></td>
      </tr>
    {/foreach}
    </table>
    <caption style="font-size:14px">
    <p align="center">
    �|ĳ�w���ɶ��٨S����A�z�]�i�H��ܥ��i�J"�ǳƼҦ�"�A<br>
    �b"�ǳƼҦ�"���A�z�i�H���W�Ǥ@�ǱЧ����|ĳ�e���ǳơA��L�H���|�i�ӥ��Z�z�C<br>
    ���|ĳ�ɶ��@��A����"�ǳƼҦ�"�A���s�i�J�����줽�ǡA�|ĳ���䥦�ѻP�̴N�i�H�i�ӤF�C<br>
    <p align="center"><font color="red">�ݭn�`�N���O�A�b"�ǳƼҦ�"���A�Y��F�|ĳ�w���ɶ��A�@�w�n�O�o���s�i�J�����줽��<br>
    �_�h��L�|ĳ�ѻP�̵L�k�i�J�A���w���|ĳ�C</font><br>


</body>
</html>

