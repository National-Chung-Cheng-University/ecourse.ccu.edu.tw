<?
// �N�X�ȭp�ƾ��[�@
function kjFileCounter( $counter_file ) {
   // �n���إ� counter_file�A�åB�]�w��s�����v��
   $fd = fopen ( $counter_file, "r+");
   $counter = fgets( $fd, 80 );
   $counter = doubleval( $counter ) + 1;
   fseek($fd, 0);
   fputs( $fd, $counter );
   fclose($fd);
   echo $counter;
   //return $counter;
}

// ���@��ƥu�OŪ�X�X�ȭp�ƭ�, ���|�N�X�ȭp�ƾ��[�@
function kjReadFileCounter( $counter_file ) {
   $fd = fopen ( $counter_file, "r");
   $counter = fgets( $fd, 80 );
   fclose($fd);

   return $counter;
}

// �ϧέp�ƾ����
function GCounter( $counter ) {
   $S = (string)$counter;	// ���N�ƭ��ন�r�� $S

   // �v�@���r�� $S ���C�@�Ӧr��, �M��ꦨ <IMG SRC=?.gif> ���ϧμХ�
   for ( $I=0; $I < strlen($S); $I++ ) {
      $G = "$G<IMG SRC=" . substr($S, $I, 1) . ".gif Align=TextTop>";
   }
   return $G;
}
?>
