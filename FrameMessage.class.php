<?php
/** Frame Message class main 与 iframe 相互通讯类
*   Date:   2013-12-29
*   Author: fdipzone
*   Ver:    1.0
*
*   Func:
*   public  execute  根据参数调用方法
*   private returnJs 创建返回的javascript
*   private jsFormat 转义参数
*/

class FrameMessage{ // class start

    /* execute 根据参数调用方法
    * @param  String  $frame 要调用的方法的框架名称,为空则为parent
    * @param  String  $func  要调用的方法名
    * @param  JSONstr $args  要调用的方法的参数
    * @return String
    */
    public static function execute($frame, $func, $args=''){

        if(!is_string($frame) || !is_string($func) || !is_string($args)){
            return '';
        }

        // frame 与 func 限制只能是字母数字下划线
        if(($frame!='' && !preg_match('/^[A-Za-z0-9_]+$/',$frame)) || !preg_match('/^[A-Za-z0-9_]+$/',$func)){
            return '';
        }

        $params_str = '';

        if($args){
            $params = json_decode($args, true);

            if(is_array($params)){

                for($i=0,$len=count($params); $i<$len; $i++){ // 过滤参数,防止注入
                    $params[$i] = self::jsFormat($params[$i]);
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
    * @param  String  $str
    * @return String 
    */
    private static function returnJs($str){

        $ret = '<script type="text/javascript">'."\r\n";
        $ret .= $str."\r\n";
        $ret .= '</script>';

        return $ret;
    }


    /** 转义参数
    * @param  String $str
    * @return String
    */
    private static function jsFormat($str){

        $str = strip_tags(trim($str));  // 过滤html
        $str = str_replace('\\s\\s', '\\s', $str);
        $str = str_replace(chr(10), '', $str);
        $str = str_replace(chr(13), '', $str);
        $str = str_replace(' ', '', $str);
        $str = str_replace('\\', '\\\\', $str);
        $str = str_replace('"', '\\"', $str);
        $str = str_replace('\\\'', '\\\\\'', $str);
        $str = str_replace("'", "\'", $str);

        return $str;
    }

} // class end

?>