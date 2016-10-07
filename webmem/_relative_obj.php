<?php
/*----------------------------------------------------------------------
 |2016-10-04 | 匯入物件時建立關聯 
 |
 |Function:
 |
 |    1)chk_grp_child() =>
 |      藉由傳入的母站ID,以及子站ID做判斷兩者是否為同個群組,且母站確實
 |      作為母站使用
 |
 |    2)relative_update() =>
 |      將關聯欄位取出,並且加入新的物件,排除空值(NULL)還有重複值,並且回
 |      傳
 |
 |
 */
class _relative_obj{
    
    function chk_grp_child($momID ,$MemID){
        
        $relative_switch = false;

        $sql = "SELECT HeadID3 FROM Trafficker WHERE MemID='$MemID'";
        $res = mysql_query($sql);
        $row = mysql_fetch_assoc($res);

        if( $row['HeadID3'] != 0){

            $sql = "SELECT HeadID3 FROM Trafficker WHERE MemID='$momID'";
            $res = mysql_query($sql);
            $mrow = mysql_fetch_assoc($res);
            
            if( $row['HeadID3'] == $mrow['HeadID3'] ){
            	
            	$sql = "SELECT mama_Num4 FROM TraffickerInfo WHERE MemID = '$momID'";
                $res = mysql_query($sql);
                $MNrow = mysql_fetch_assoc($res);

                if( $MNrow['mama_Num4'] == $momID){
                     $relative_switch = true;
                }
                
            }

        }

        return $relative_switch;
    }

    function relative_update($oobj,$newobj){

        $fobj_arr = explode(',',$oobj);
        $fobj_arr = array_filter($fobj_arr);
        array_push($fobj_arr,$newobj);
        $fobj_arr = array_unique($fobj_arr);
        $fobj_str = implode(",",$fobj_arr);

        return $fobj_str;

    }

}

?>