<?
// 將訪客計數器加一
function kjFileCounter( $counter_file ) {
   // 要先建立 counter_file，並且設定其存取之權限
   $fd = fopen ( $counter_file, "r+");
   $counter = fgets( $fd, 80 );
   $counter = doubleval( $counter ) + 1;
   fseek($fd, 0);
   fputs( $fd, $counter );
   fclose($fd);
   echo $counter;
   //return $counter;
}

// 此一函數只是讀出訪客計數值, 不會將訪客計數器加一
function kjReadFileCounter( $counter_file ) {
   $fd = fopen ( $counter_file, "r");
   $counter = fgets( $fd, 80 );
   fclose($fd);

   return $counter;
}

// 圖形計數器函數
function GCounter( $counter ) {
   $S = (string)$counter;	// 先將數值轉成字串 $S

   // 逐一取字串 $S 的每一個字元, 然後串成 <IMG SRC=?.gif> 的圖形標示
   for ( $I=0; $I < strlen($S); $I++ ) {
      $G = "$G<IMG SRC=" . substr($S, $I, 1) . ".gif Align=TextTop>";
   }
   return $G;
}
?>
