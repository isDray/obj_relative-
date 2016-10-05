<?php
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


    	    $sql = "SELECT obj_to FROM Trafficker WHERE MemID = '$value'";
    	    $res = mysql_query($sql);
    	    $row = mysql_fetch_assoc($res);	
            

            $to_mem = explode(',',$row['obj_to']);
            array_push($to_mem, $MemID);
            $to_mem = array_filter($to_mem);
            $to_mem = array_unique($to_mem);
            $to_mem_str = implode(",",$to_mem);


            $sql = "UPDATE Trafficker SET obj_to='$to_mem_str' WHERE MemID='$value'";
    	    $rec = mysql_query($sql);
            if(!$rec){
                $obj_relative = 1;
            }


    	    $sql = "SELECT obj_from FROM Trafficker WHERE MemID = '$MemID'";
    	    $res = mysql_query($sql);
    	    $row = mysql_fetch_assoc($res);	
            

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