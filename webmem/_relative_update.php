<?php 
/*----------------------------------------------------------------------
 | 2016-10-04 | 編修時同步編修關聯物件 V 1.0
 |
 |Function:
 |
 |    1).get_relation() =>
 |      藉由$ObjID在HouseObject,及HouseObjectRec中找尋該物件的關聯子物件
 |
 |    2).opr_update()   =>
 |      在使用者提交表單時,針對關聯子物件做價格,坪數的修改
 |
 |    3).get_ma_relation() = >
 |      判斷本身是否為其他物件的子類別,再進階使用,EX.如果有母物件就禁制修
 |      改
 |
 */

class _relative_update{
    

    function get_relation($ObjID){

        $sql = "SELECT relationobj FROM HouseObject WHERE ObjID='$ObjID'";
        $res = mysql_query($sql);
        $row = mysql_fetch_assoc($res);
        
        $r_obj = array();
        
        if( $row['relationobj'] != NULL){

            $r_obj = explode(',',$row['relationobj']);

        }else{
            $sql = "SELECT relationobj FROM HouseObjectRec WHERE ObjID='$ObjID'";
            $res = mysql_query($sql);
            $row = mysql_fetch_assoc($res);

            if( $row['relationobj'] != NULL){
                $r_obj = explode(',',$row['relationobj']);
            }

        }

        
        return $r_obj;
    }
    
    function opr_update($obj_num_arr,$TradPrice,$sub_build,$isper,$OTradPrice){
        
        foreach($obj_num_arr as $obj_num){
             
            $sql = "UPDATE HouseObject SET TradPrice = '$TradPrice' ,isper = '$isper',OTradPrice = '$OTradPrice' WHERE ObjID = '$obj_num'";
            if( !mysql_query($sql) ){
                echo "<h3>". mysql_errno(). ": ".mysql_error(). "<br></h3>";
                exit(0);
            }

            $sql = "UPDATE HouseObjectRec SET TradPrice = '$TradPrice' ,isper = '$isper',OTradPrice = '$OTradPrice' WHERE ObjID = '$obj_num'";
            if( !mysql_query($sql) ){
                echo "<h3>". mysql_errno(). ": ".mysql_error(). "<br></h3>";
                exit(0);
            }

            $sql = "UPDATE HouseObjectData SET sub_build = '$sub_build' WHERE ObjID = '$obj_num'"; 
            if( !mysql_query($sql) ){
                echo "<h3>". mysql_errno(). ": ".mysql_error(). "<br></h3>";
                exit(0);
            }
      

        }
    }
    
    function get_ma_relation($ObjID){
        /*
        $sql = "SELECT ObjID FROM HouseObject WHERE relationobj like '%$ObjID%'";
        $res = mysql_query($sql);
        $row = mysql_fetch_assoc($res);
        */
        $sql = "SELECT EXISTS(SELECT ObjID FROM HouseObject WHERE relationobj like '%$ObjID%')";
        $res = mysql_query($sql);
        $row = mysql_fetch_row($res);
       
        if( $row[0] ==1){
            return true;
        }else{
            return false;
        }

    }

}

?>