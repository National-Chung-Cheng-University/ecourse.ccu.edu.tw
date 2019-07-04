<html>
<head>
<title> TITLE </title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<style type="text/css">
<!--
body {  background-attachment: fixed; background-image: url(/images/skin1/bbg.gif); font-size: 10pt}
a {  font-size: 10pt; color: #000066}
.bfont {  font-size: 10pt}
table {  font-size: 10pt}
font {  font-size: 10pt}
a:hover {  color: #CC0000; text-decoration: none; font-size: 10pt}
-->
</style>
<SCRIPT LANGUAGE="JavaScript1.2"> 
<!-- 
if (document.layers) { 
	document.writeln('<SCRIPT SRC="/js/course_tree_02.js"><\/SCRIPT>'); 
} else if (document.all) { 
	document.writeln('<SCRIPT SRC="/js/course_tree_01.js"><\/SCRIPT>'); 
}
function detail () {
	window.open('./messager/detail.php?PHPSESSID=PHPID','','resizable=1,scrollbars=1,width=300,height=400');
}
function note () {
	window.open('./note/note.php?PHPSESSID=PHPID','','resizable=1,scrollbars=1,width=400,height=400');
}
function chat () {
	window.open('./chat/chat_int.php?PHPSESSID=PHPID', '', 'width=750,height=480,resizable=1,scrollbars=1');
}

function loadTrees() {

window.JTree1 = new Tree("<a href='#' target='menu'>組員資料</a>");
JTree1.folderIcons = new Array("/images/coursefolder.gif","/images/coursefolder_h.gif","/images/coursefolder_s.gif","/images/coursefolder_s.gif");
JTree1.itemIcons = new Array("/images/courseitem.gif","/images/courseitem_h.gif","/images/courseitem.gif","/images/courseitem_h.gif");
JTree1.addTreeItem( "<a href='./manager/manager.php' target='main'>任務指派</a>" );
JTree1.addTreeItem( "<a href='./Contact.php' target='main'>聯絡簿</a>" );
JTree1.addTreeItem( "<a href='#' onClick='detail();'>即時訊息</a>" );
JTree1.protoTree = JTree1;

window.JTree2 = new Tree("<a href='#' target='menu'>討論及成果</a>");
JTree2.folderIcons = new Array("/images/coursefolder.gif","/images/coursefolder_h.gif","/images/coursefolder_s.gif","/images/coursefolder_s.gif");
JTree2.itemIcons = new Array("/images/courseitem.gif","/images/courseitem_h.gif","/images/courseitem.gif","/images/courseitem_h.gif");
JTree2.addTreeItem( "<a href='#' onClick='chat();' >聊天室</a>" );
JTree2.addTreeItem( "<a href='./discuss/discuss.php' target='main'>討論區</a>" );
JTree2.addTreeItem( "<a href='./guestbook/guestbookm.php' target='main'>留言版</a>" );
JTree2.addTreeItem( "<a href='./news/news.php' target='main' onClick='parent.msgwin2()'>公佈欄</a>" );
JTree2.addTreeItem( "<a href='./schedule/show_sched.php' target='main'>時程表</a>" );
JTree2.addTreeItem( "<a href='./result/result.php' target='main'>成果預覽</a>" );
JTree2.addTreeItem( "<a href='./result/result.php?action=modify' target='main'>成果發表</a>" );
JTree2.protoTree = JTree2;

window.JTree3 = new Tree("<a href='#' target='menu'>個人工具</a>");
JTree3.folderIcons = new Array("/images/coursefolder.gif","/images/coursefolder_h.gif","/images/coursefolder_s.gif","/images/coursefolder_s.gif");
JTree3.itemIcons = new Array("/images/courseitem.gif","/images/courseitem_h.gif","/images/courseitem.gif","/images/courseitem_h.gif");
JTree3.addTreeItem( "<a href='./source/source_main.php' target='main'>資源分享</a>" );
JTree3.addTreeItem( "<a href='#' onClick='note();'>個人記事本</a>" );
JTree3.addTreeItem( "<a href='./memo/memo.php' target='main'>個人行事曆</a>" );
JTree3.protoTree = JTree3;

window.JTree4 = new Tree("<a href='#' target='menu'>評分工具</a>");
JTree4.folderIcons = new Array("/images/coursefolder.gif","/images/coursefolder_h.gif","/images/coursefolder_s.gif","/images/coursefolder_s.gif");
JTree4.itemIcons = new Array("/images/courseitem.gif","/images/courseitem_h.gif","/images/courseitem.gif","/images/courseitem_h.gif");
JTree4.addTreeItem( "<a href='./Trackin/GIGrade.php' target='main'>組內自評</a>" );
JTree4.addTreeItem( "<a href='./Trackin/GBGrade.php' target='main'>組間互評</a>" );
JTree4.addTreeItem( "<a href='./Trackin/ShowStudent.php' target='main'>參予歷程</a>" );
JTree4.protoTree = JTree4;

window.JTree5 = new Tree("<a href='#' target='menu'>小組說明</a>");
JTree5.folderIcons = new Array("/images/coursefolder.gif","/images/coursefolder_h.gif","/images/coursefolder_s.gif","/images/coursefolder_s.gif");
JTree5.itemIcons = new Array("/images/courseitem.gif","/images/courseitem_h.gif","/images/courseitem.gif","/images/courseitem_h.gif");
JTree5.addTreeItem( "<a href='info.php' target='main'>小組說明預覽</a>" );
JTree5.addTreeItem( "<a href='info.php?action=modify' target='main'>小組說明編輯</a>" );
JTree5.protoTree = JTree5;

window.tRoot = new Tree("tRoot");
tRoot.addTreeItem(JTree5);
tRoot.addTreeItem(JTree1);
tRoot.addTreeItem(JTree2);
tRoot.addTreeItem(JTree3);
tRoot.addTreeItem(JTree4);

tRoot.folderIcons = new Array("/images/coursefolder.gif","/images/coursefolder_h.gif","/images/coursefolder_s.gif","/images/coursefolder_s.gif");
tRoot.itemIcons = new Array("/images/courseitem.gif","/images/courseitem_h.gif","/images/courseitem.gif","/images/courseitem_h.gif");
tRoot.protoTree = tRoot;
showTree(window.tRoot,0,0);
} 
//--> 
</SCRIPT> 
</head>
<body background="/images/img/bg.gif" onload = "loadTrees();">

</body>
</html>