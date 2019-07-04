<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>預約會議清單</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="{$tpl_path}/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/content.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/table.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>

</head>

<body>

    <h3 align="center">預約會議清單</h3>
    <table border=1 align="center">
    <tr>
        <td bgcolor="#E6FFFC">課堂名稱</td>
        <td bgcolor="#E6FFFC">授課教師</td>
        <td bgcolor="#E6FFFC">標題</td>
        <td bgcolor="#E6FFFC">預約日期時間</td>
        <td bgcolor="#E6FFFC">會議最大人數</td>
        <td bgcolor="#E6FFFC">錄影</td>
        <td bgcolor="#E6FFFC">準備模式</td>
        <td bgcolor="#E6FFFC">取消會議</td>
    </tr>

    {foreach from=$meetingList item=meetingData}
      <tr>
          <td bgcolor="#F0FFEE">{$meetingData.courseName}</td>
          <td bgcolor="#F0FFEE">{$meetingData.teacherName}</td>
          <td bgcolor="#F0FFEE">{$meetingData.title}</td>
          <td bgcolor="#F0FFEE">{$meetingData.startTime}</td>
          <td bgcolor="#F0FFEE">{$meetingData.maxNumAttendee}</td>
          <td bgcolor="#F0FFEE">{if $meetingData.recording == 1}是{else}否{/if}</td>
          <td bgcolor="#F0FFEE"><a href='{$meetingData.preparaMeeting}'>準備</a></td>
          <td bgcolor="#F0FFEE"><a href='{$meetingData.cancelMeeting}'>取消</a></td>
      </tr>
    {/foreach}
    </table>
    <caption style="font-size:14px">
    <p align="center">
    會議預約時間還沒有到，您也可以選擇先進入"準備模式"，<br>
    在"準備模式"中，您可以先上傳一些教材做會議前的準備，其他人不會進來打擾您。<br>
    等會議時間一到，關掉"準備模式"再重新進入網路辦公室，會議的其它參與者就可以進來了。<br>
    <p align="center"><font color="red">需要注意的是，在"準備模式"中，若到了會議預約時間，一定要記得重新進入網路辦公室<br>
    否則其他會議參與者無法進入你的預約會議。</font><br>


</body>
</html>

