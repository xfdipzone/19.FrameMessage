<?php
/** Frame Message class main 与 iframe 相互通讯类
*   Date:   2013-12-29
*   Author: fdipzone
*   Ver:    1.0
*/

class FrameMessage{ // class start

    /* execute
    * @param  String  $frame 要调用的方法的框架名称,为空则为parent
    * @param  String  $func  要调用的方法名
    * @param  JSONstr $args  要调用的方法的参数
    * @return String
    */
    public static function execute($frame, $func, $args=''){

        if(!is_string($frame) || !is_string($func) || !is_string($args)){
            return '';
        }

        $frame = strip_tags($frame);
        $func = strip_tags($func);
        $params_str = '';

        if($args){
            $params = json_decode($args, true);
            
            if(is_array($params)){

                for($i=0,$len=count($params); $i<$len; $i++){ // 过滤参数,防止注入
                    $params[$i] = strip_tags($params[$i]);
                }
                
                $params_str = "'".implode("','", $params)."'";
            }
        }

        if($frame==''){ // parent
            return self::returnJs("parent.parent.".$func."(".$params_str.");");
        }else{
            return self::returnJs("parent.window.".$frame.".".$func."(".$params_str.");");
        }

    }


    /** 创建返回的javascript
    * @param String  $str
    * @return String 
    */
    private static function returnJs($str){

        $ret = '<script type="text/javascript">'."\r\n";
        $ret .= $str."\r\n";
        $ret .= '</script>';

        return $ret;
    }

} // class end

?>