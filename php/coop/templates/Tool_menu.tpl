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

window.JTree1 = new Tree("<a href='#' target='menu'>�խ����</a>");
JTree1.folderIcons = new Array("/images/coursefolder.gif","/images/coursefolder_h.gif","/images/coursefolder_s.gif","/images/coursefolder_s.gif");
JTree1.itemIcons = new Array("/images/courseitem.gif","/images/courseitem_h.gif","/images/courseitem.gif","/images/courseitem_h.gif");
JTree1.addTreeItem( "<a href='./manager/manager.php' target='main'>���ȫ���</a>" );
JTree1.addTreeItem( "<a href='./Contact.php' target='main'>�p��ï</a>" );
JTree1.addTreeItem( "<a href='#' onClick='detail();'>�Y�ɰT��</a>" );
JTree1.protoTree = JTree1;

window.JTree2 = new Tree("<a href='#' target='menu'>�Q�פΦ��G</a>");
JTree2.folderIcons = new Array("/images/coursefolder.gif","/images/coursefolder_h.gif","/images/coursefolder_s.gif","/images/coursefolder_s.gif");
JTree2.itemIcons = new Array("/images/courseitem.gif","/images/courseitem_h.gif","/images/courseitem.gif","/images/courseitem_h.gif");
JTree2.addTreeItem( "<a href='#' onClick='chat();' >��ѫ�</a>" );
JTree2.addTreeItem( "<a href='./discuss/discuss.php' target='main'>�Q�װ�</a>" );
JTree2.addTreeItem( "<a href='./guestbook/guestbookm.php' target='main'>�d����</a>" );
JTree2.addTreeItem( "<a href='./news/news.php' target='main' onClick='parent.msgwin2()'>���G��</a>" );
JTree2.addTreeItem( "<a href='./schedule/show_sched.php' target='main'>�ɵ{��</a>" );
JTree2.addTreeItem( "<a href='./result/result.php' target='main'>���G�w��</a>" );
JTree2.addTreeItem( "<a href='./result/result.php?action=modify' target='main'>���G�o��</a>" );
JTree2.protoTree = JTree2;

window.JTree3 = new Tree("<a href='#' target='menu'>�ӤH�u��</a>");
JTree3.folderIcons = new Array("/images/coursefolder.gif","/images/coursefolder_h.gif","/images/coursefolder_s.gif","/images/coursefolder_s.gif");
JTree3.itemIcons = new Array("/images/courseitem.gif","/images/courseitem_h.gif","/images/courseitem.gif","/images/courseitem_h.gif");
JTree3.addTreeItem( "<a href='./source/source_main.php' target='main'>�귽����</a>" );
JTree3.addTreeItem( "<a href='#' onClick='note();'>�ӤH�O�ƥ�</a>" );
JTree3.addTreeItem( "<a href='./memo/memo.php' target='main'>�ӤH��ƾ�</a>" );
JTree3.protoTree = JTree3;

window.JTree4 = new Tree("<a href='#' target='menu'>�����u��</a>");
JTree4.folderIcons = new Array("/images/coursefolder.gif","/images/coursefolder_h.gif","/images/coursefolder_s.gif","/images/coursefolder_s.gif");
JTree4.itemIcons = new Array("/images/courseitem.gif","/images/courseitem_h.gif","/images/courseitem.gif","/images/courseitem_h.gif");
JTree4.addTreeItem( "<a href='./Trackin/GIGrade.php' target='main'>�դ��۵�</a>" );
JTree4.addTreeItem( "<a href='./Trackin/GBGrade.php' target='main'>�ն�����</a>" );
JTree4.addTreeItem( "<a href='./Trackin/ShowStudent.php' target='main'>�Ѥ����{</a>" );
JTree4.protoTree = JTree4;

window.JTree5 = new Tree("<a href='#' target='menu'>�p�ջ���</a>");
JTree5.folderIcons = new Array("/images/coursefolder.gif","/images/coursefolder_h.gif","/images/coursefolder_s.gif","/images/coursefolder_s.gif");
JTree5.itemIcons = new Array("/images/courseitem.gif","/images/courseitem_h.gif","/images/courseitem.gif","/images/courseitem_h.gif");
JTree5.addTreeItem( "<a href='info.php' target='main'>�p�ջ����w��</a>" );
JTree5.addTreeItem( "<a href='info.php?action=modify' target='main'>�p�ջ����s��</a>" );
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