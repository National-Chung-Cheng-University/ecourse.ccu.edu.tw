<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>�|ĳ�M��</title>

<link rel="stylesheet" href="/images/skin1/css/main-body.css" type="text/css">
<link href="{$tpl_path}/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/content.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/table.css" rel="stylesheet" type="text/css" />
<link href="{$tpl_path}/css/form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.7.2.custom.min.js"></script>

</head>

<body>

    <h3 align=center>�w���|ĳ�|ĳ�M��</h3>
    {foreach from=$meetingList item=meetingData name=temp}
      {if $smarty.foreach.temp.first}
        <table class="datatable" border=1 width=100%>
          <tr>
          <td>�s��</td>
          <td>���D</td>
          <td>�|ĳ�ɶ�</td>
          <td>�|ĳ�H��</td>
          <td>�D���H</td>
        </tr>
      {/if}
      <tr>
          <td>{$meetingData.index_num}</td>
          <td>{$meetingData.title}</td>
          <td>{$meetingData.reservationTime}{if $meetingData.end == 1}<br>[�w����]{/if}</td>
          <td>{$meetingData.maxNumAttendee}</td>
          <td>{$meetingData.nativeName}</td>
      </tr>
      {if $smarty.foreach.temp.last}
        </table>
      {/if}

    {foreachelse}
       <br>����Ƨ��L������v��
    {/foreach}


</body>
</html>

