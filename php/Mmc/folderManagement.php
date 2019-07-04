<?php
  
  include_once("db_meeting.php");
  //所有有關floder的function都會放在這裡
  $folderList = array();
  $orderedList = array();
  $parentList = array();
  $parentindex = 0 ;

  // 建立目目錄的list
  function FolderStructCreate($ownerId) {

      global $folderList, $orderedList, $parentList, $parentindex; 

      $folderList = GetAllFolder($ownerId);
      $orderedList [] = $folderList[0];
      $parentList [] = $folderList[0]['folderId'];
      // 做DFSOrder先設定根目錄，為了有中止條件
      $folderList[0]['addToCreate'] = 1;
      DFSOrderFolder(1);
      return $orderedList;
       
  }

  // 對所有資料夾做DFS
  function DFSOrderFolder($folderIndex) {
      global $folderList, $orderedList, $parentList, $parentindex;
      for( $i=1; $i <= sizeof($folderList); $i++) {

          if($folderList[$folderIndex]['parentId'] == $parentList[$parentindex] && $folderList[$folderIndex]['addToCreate'] != 1) {
              $orderedList[] = $folderList[$folderIndex];
              $folderList[$folderIndex]['addToCreate'] = 1 ;
              $parentindex++;
              $parentList[$parentindex] = $folderList[$folderIndex]['folderId'];
              DFSOrderFolder((($folderIndex+1)%sizeof($folderList)));
              $parentindex--;

          }
          else
            ; 
          $folderIndex = (($folderIndex+1)%sizeof($folderList));
      }
  }

  // 增加資料夾到DB
  // 參數folderName:新增的資料夾名稱
  //     folderId:上層資料夾的id
  //     ownerId:使用者的id
  function CreateFolder($folderName,$folderId,$ownerId) {
      CreateFolderInDB($folderName,$folderId,$ownerId);
  }

  // 在DB中修改資料夾名稱
  // 參數folderName:要改的名稱
  //     folderId:要更改名稱的資料夾id
  function RenameFolder($folderName,$folderId) {
      RenameFolderInDB($folderName,$folderId);
  }

  // 在DB中刪除資料夾資訊
  // 參數folderId:要刪除的資料夾id
  function DeleteFolder($folderId,$ownerId) {
      global $folderList, $orderedList, $parentList, $parentindex;
      $folderList = GetAllFolder($ownerId);

      // 先找出符合最上層的folderId 
      for( $i=0; $i < sizeof($folderList); $i++) {
          if($folderList[$i]['folderId'] == $folderId) {
              $orderedList [] = $folderList[$i];
              $parentList [] = $folderList[$i]['folderId'];
              $folderList[0]['addToCreate'] = 1;
              $folderIndex = $i +1;
              $i = sizeof($folderList);
          }
          else
              ;
      }
      //將此資料夾下的所有資料夾都找出來並排序
      DFSOrderFolder($folderIndex);
      //刪除選定的資料夾及其所有子資料夾
      for ( $i=0; $i < sizeof($orderedList); $i++) {
         // 只刪除一筆
         DeleteFolderInDB($orderedList[$i]['folderId']);
      }
  }

?>

