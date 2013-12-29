/** Main 与 Iframe 相互通讯类 支持同域与跨域通讯
*	Date:   2013-12-29
*   Author: fdipzone
*   Ver:    1.0
*/
var FrameMessage = (function(){

    /* 执行方法
    executor 执行的页面,为空则为同域
    frame    要调用的方法的框架名称,为空则为parent
    func     要调用的方法名
    args     要调用的方法的参数,必须为数组[arg1, arg2, arg3, argn...],方便apply调用
             元素为字符串格式,请不要使用html,考虑注入安全的问题会过滤
    */
    this.exec = function(executor, frame, func, args){

        this.executor = typeof(executor)!='undefined'? executor : '';
        this.frame = typeof(frame)!='undefined'? frame : '';
        this.func = typeof(func)!='undefined'? func : '';
        this.args = typeof(args)!='undefined'? (__fIsArray(args)? args : []) : []; // 必须是数组

        if(executor==''){
            __fSameDomainExec(); // same domain
        }else{
            __fCrossDomainExec(); // cross domain
        }

    }

    /* 同域执行 */
    function __fSameDomainExec(){
        if(this.frame==''){ // parent
            parent.window[this.func].apply(this, this.args);
        }else{
            window.frames[this.frame][this.func].apply(this, this.args);
        }
    }

    /* 跨域执行 */
    function __fCrossDomainExec(){
        if(typeof(oFrameMessageExec=='undefined')){
            var oFrameMessageExec = document.createElement('iframe');
            oFrameMessageExec.name = 'FrameMessage_tmp_frame';
            oFrameMessageExec.src = __fGetSrc();
            oFrameMessageExec.style.display = 'none';
            document.body.appendChild(oFrameMessageExec);
        }else{
            oFrameMessageExec.src = __fGetSrc();
        }
    }

    /* 获取执行的url */
    function __fGetSrc(){
        return this.executor + (this.executor.indexOf('?')==-1? '?' : '&') + 'frame=' + this.frame + '&func=' + this.func + '&args=' + JSON.stringify(this.args) + '&framemessage_rand=' + Math.random();
    }

    /* 判断是否数组 */
    function __fIsArray(obj){
        return Object.prototype.toString.call(obj) === '[object Array]';
    }

    return this;

}());