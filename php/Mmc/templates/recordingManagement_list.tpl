<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>���v�ɲM��</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="{$tpl_path}/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/content.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/table.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>

</head>

<body>

    <h3 align="center" >���v�ɲM��</h3>

    <font >�w�ϥΪŶ��G{$totalused}MB�@�@�@�`�@�i�ΪŶ�:{$totalquota}MB �@�@�ϥζq:{$used}%</font><br>
    {if $used ge 95}
        <font color="red">�i�ΪŶ��Y�N����</font><br>
    {/if}

    <form action="moveRecording.php" method="post">
    <input type="hidden" name="currentFolderId" value="{$currentFolderId}">
    <input type="hidden" name="currentSeq" value="{$currentSeq}">
    <input type="submit" name="func" value="���ʨ�" class="btn">
    <select name="moveTofolderId">
    {foreach from=$folderList item=folderData}
       <option value="{$folderData.folderId}">{$folderData.folderCaption}</option> 
    {/foreach}
    </select>

    {foreach from=$recordingList item=meetingData name=temp}
      {if $smarty.foreach.temp.first}
        <table class="datatable" border=1>
          <tr>
          <td bgcolor="#E6FFFC"></td>
          <td bgcolor="#E6FFFC">�s��</td>
          <td bgcolor="#E6FFFC">�Ұ�W��</td>
          <td bgcolor="#E6FFFC">���D</td>
          <td bgcolor="#E6FFFC">�|ĳ�ɶ�</td>
          <td bgcolor="#E6FFFC">�|ĳ����</td>
          <td bgcolor="#E6FFFC">�o�G/�Ѱ�</td>
          <td bgcolor="#E6FFFC">�o�G</td>
          <td bgcolor="#E6FFFC">���v�ɾާ@</td>
          <td bgcolor="#E6FFFC">²���Y��</td>
          <td bgcolor="#E6FFFC">��ͰO��</td>
        </tr>
      {/if}
      <tr>
          <td bgcolor="#F0FFEE"><input type="checkbox" name="recordingId[]" value="{$meetingData.recordingId}"></td>
          <td bgcolor="#F0FFEE">{$meetingData.recordingIndex}</td>
          <td bgcolor="#F0FFEE">{$meetingData.courseName}</td>
          <td bgcolor="#F0FFEE">{$meetingData.title}</td>
          <td bgcolor="#F0FFEE">{$meetingData.actualStartTime}</td>
          <td bgcolor="#F0FFEE">{$meetingData.durationString}</td>
          <td bgcolor="#F0FFEE">{if $meetingData.isRead == 1}<a href='{$meetingData.cancelpublishUrl}'>�Ѱ�</a>{else}<a href='{$meetingData.publishUrl}'>�o�G</a>{/if}</td>
          <td bgcolor="#F0FFEE">{if $meetingData.isRead == 1}�O{else}�_{/if}</td>
          <td bgcolor="#F0FFEE"><a href='{$meetingData.operationUrl}'>���v�ɾާ@</a></td>
          <td bgcolor="#F0FFEE"><a href='{$meetingData.slidingshowUrl}' target=_blank>²���Y��</a></td>
          <td bgcolor="#F0FFEE"><a href='{$meetingData.chatUrl}' target=_blank>��ͤ�r</a></td>
      </tr>
      {if $smarty.foreach.temp.last}
        </table>
      {/if}

    {foreachelse}
       <br>����Ƨ��L������v��
    {/foreach}


</body>
</html>


