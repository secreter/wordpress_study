<?php
/**
 * [add_copyright_info 输出内容后连上要显示的版权信息，简单设置增加版权信息的内容的样式]
 * @param [type] $content [string]
 */
function add_copyright_info ($content){
    $content .= '<div style="clear:both; border-top:1px dashed #e0e0e0; padding:10px 0 10px 0; font-size:12px;">版权所有©转载必须以链接形式注明作者和原始出处：<a href="'.get_bloginfo("home").'" title="点击去首页">'.get_bloginfo("name").'</a> » <a title="本文地址" href="'.get_permalink().'">'.get_the_title().'</a></div>' ;
    return $content;
}



/*
添加设置菜单
*/
function add_settings_menu() {
    add_menu_page(__('自定义菜单标题'), __('测试菜单'), 'administrator',  __FILE__, 'my_function_menu', false, 100);
    add_submenu_page(__FILE__,'子菜单1','测试子菜单1', 'administrator', 'your-admin-sub-menu1', 'my_function_submenu1');
    add_submenu_page(__FILE__,'子菜单2','测试子菜单2', 'administrator', 'your-admin-sub-menu2', 'my_function_submenu2');
    add_submenu_page(__FILE__,'子菜单3','测试子菜单3', 'administrator', 'your-admin-sub-menu3', 'my_function_submenu3');
}
function my_function_menu() {
  echo "<h2>测试菜单设置</h2>";
}
function my_function_submenu1() {
   echo "<h2>测试子菜单设置一</h2>";
}
function my_function_submenu2() {
    echo "<h2>测试子菜单设置二</h2>";
}
function my_function_submenu3() {
    echo "<h2>测试子菜单设置三</h2>";
}



// 当wp后台的头部加载时，执行的 PHP函数 my_custom_admin_head
// add_action('admin_head', 'my_custom_admin_head');
// 输出一个css样式，改变body的背景颜色
// function my_custom_admin_head() {
//     echo '<style>body {background-color: #4AAF48 !important;}</style>';
// }