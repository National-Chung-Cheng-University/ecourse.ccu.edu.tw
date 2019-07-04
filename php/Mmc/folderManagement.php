<?php
  
  include_once("db_meeting.php");
  //�Ҧ�����floder��function���|��b�o��
  $folderList = array();
  $orderedList = array();
  $parentList = array();
  $parentindex = 0 ;

  // �إߥإؿ���list
  function FolderStructCreate($ownerId) {

      global $folderList, $orderedList, $parentList, $parentindex; 

      $folderList = GetAllFolder($ownerId);
      $orderedList [] = $folderList[0];
      $parentList [] = $folderList[0]['folderId'];
      // ��DFSOrder���]�w�ڥؿ��A���F���������
      $folderList[0]['addToCreate'] = 1;
      DFSOrderFolder(1);
      return $orderedList;
       
  }

  // ��Ҧ���Ƨ���DFS
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

  // �W�[��Ƨ���DB
  // �Ѽ�folderName:�s�W����Ƨ��W��
  //     folderId:�W�h��Ƨ���id
  //     ownerId:�ϥΪ̪�id
  function CreateFolder($folderName,$folderId,$ownerId) {
      CreateFolderInDB($folderName,$folderId,$ownerId);
  }

  // �bDB���ק��Ƨ��W��
  // �Ѽ�folderName:�n�諸�W��
  //     folderId:�n���W�٪���Ƨ�id
  function RenameFolder($folderName,$folderId) {
      RenameFolderInDB($folderName,$folderId);
  }

  // �bDB���R����Ƨ���T
  // �Ѽ�folderId:�n�R������Ƨ�id
  function DeleteFolder($folderId,$ownerId) {
      global $folderList, $orderedList, $parentList, $parentindex;
      $folderList = GetAllFolder($ownerId);

      // ����X�ŦX�̤W�h��folderId 
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
      //�N����Ƨ��U���Ҧ���Ƨ�����X�ӨñƧ�
      DFSOrderFolder($folderIndex);
      //�R����w����Ƨ��Ψ�Ҧ��l��Ƨ�
      for ( $i=0; $i < sizeof($orderedList); $i++) {
         // �u�R���@��
         DeleteFolderInDB($orderedList[$i]['folderId']);
      }
  }

?>

