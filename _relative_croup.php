<?php
/* 2016-10-03 | 連動功能群組
 * 
 * Function:
 *
 *     1).check_grpma()
 *         取得連動群組的所有母站,如果自己就是母站則排除,接著呼叫grpobj_share()
 *
 *     2).grpobj_share()
 *         將取得的母站ID在Trafficker資料表中的obj_to欄位添加自己的MemID進去,接著
 *         把母站ID加進自己的obj_from欄位達到可以匯入的功能
 *
 */
class _relative_croup{

    function check_grpma($HeadID3,$MemID){
    
        $sql = "SELECT Trafficker.MemID
                FROM Trafficker LEFT JOIN TraffickerInfo
                ON Trafficker.MemID =  TraffickerInfo.MemID
                WHERE Trafficker.HeadID3 = '$HeadID3'
                AND TraffickerInfo.mama_Num4 IS NOT NULL";
        
        $res = mysql_query( $sql );

        $grpma_arr = array();
        
        foreach ( mysql_fetch_assoc($res) as $value) {
        	
        	if($value!=$MemID){

        	    array_push( $grpma_arr, $value );
            }
        }
        
        self::grpobj_share($grpma_arr,$MemID);
    }

    function grpobj_share($grpma_arr,$MemID){
    	
    	foreach ($grpma_arr as $key => $value) {
            
            // $value = 母站MemID
            // $MemID = 要加入群組的會員

    	    $sql = "SELECT obj_to FROM Trafficker WHERE MemID = '$value'";
    	    $res = mysql_query($sql);
    	    $row = mysql_fetch_assoc($res);	
            
            // 本來是可以將母站的obj_to直接添加會員ID就好,但是為了避免格式錯誤
            // 就拆開再重組一次
            $to_mem = explode(',',$row['obj_to']);
            array_push($to_mem, $MemID);
            $to_mem = array_filter($to_mem);
            $to_mem = array_unique($to_mem);
            $to_mem_str = implode(",",$to_mem);


            // 執行改變母站的obj_to
            $sql = "UPDATE Trafficker SET obj_to='$to_mem_str' WHERE MemID='$value'";
    	    $rec = mysql_query($sql);
            if(!$rec){
                $obj_relative = 1;
            }
            // 抓出加入群組會員的MemID
    	    $sql = "SELECT obj_from FROM Trafficker WHERE MemID = '$MemID'";
    	    $res = mysql_query($sql);
    	    $row = mysql_fetch_assoc($res);	
            
            // 重組
            $from_mem = explode(',',$row['obj_from']);
            array_push($from_mem, $value);
            $from_mem = array_filter($from_mem);
            $from_mem = array_unique($from_mem);
            $from_mem_str = implode(",",$from_mem);
           
            $sql = "UPDATE Trafficker SET obj_from='$from_mem_str' WHERE MemID='$MemID'";
            $rec = mysql_query($sql);
            if(!$rec){
                $obj_relative = 1;
            }
    
    	}

        if($obj_relative !=1){
             $obj_relative = 0;
        }
        return $obj_relative;

    }

}

?>