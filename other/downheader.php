<?php
/**
 * User: HC
 * Date: 2017/10/24
 * Time: 16:27
 * Function:下载功能头部说明
 */
function down(){

    header ( 'Content-type: application/octet-stream' );//二进制提交
    header ( "Accept-Ranges: bytes" );//多线程，断点续传
    header ( "Accept-Length:" . filesize ( "." . @download_url ) );//长度
    header ( "Content-Disposition: attachment; filename=" . @filename );//下载名称
    
}
