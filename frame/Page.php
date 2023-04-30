<?php

namespace Frame;

class Page
{
    /**
     * 返回分页数据及分页信息
     * @param $db :数据库实例对象
     * @param $table :表名
     * @param int $page :当前点击的页数
     * @param int $per_page :当前页面显示多少条数据
     * @param int $show_page :当前页面显示多少个页码
     * @return array
     */
    public static function data($db, $table, int $page, int $per_page = 0, int $show_page = 6): array
    {
        //获取分页数据
        $data = $db->select('*')->from($table)->limit(($page - 1) * $per_page, $per_page)->run();
        //获取总条数
        $TableCount = $db->count($table);
        //获取总页数
        $pageCount = ceil($TableCount / $per_page);
        //获取起始页和结束页
        $start = max(1, $page - intval($show_page / 2));
        $end = min($start + $show_page - 1, $pageCount);
        $start = max(1, $end - $show_page + 1);
        //拼接数组
        $pageData['data'] = $data;              //分页数据
        $pageData['pre'] = $page - 1;           //上一页
        $pageData['cur'] = $page;               //当前页
        $pageData['next'] = $page + 1;          //下一页
        $pageData['start'] = $start;            //起始页
        $pageData['end'] = $end;                //结束页
        $pageData['show'] = $show_page;         //页码数
        $pageData['pageCount'] = $pageCount;    //总页数
        $pageData['tableCount'] = $TableCount;  //总条数
        return $pageData;
    }
}