<?php
            		    	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){
                        		Error_handler( "�b sybase_connect �����~�o��" , $cnx );
                		}
                		$csd = @sybase_select_db("academic", $cnx);

				//�Ǥ���
				$Q001 = "select * from a31vcurriculum_tea where cour_cd = '1101113'";
                		$cur001 = sybase_query($Q001 , $cnx );
				$array001 = sybase_fetch_array($cur001);
				echo $array001['unitname'];
?>
