<?php
/*
Plugin Name: MYTEST plugin
Plugin URI: http://redream.cn
Description: 我是个漂亮的测试插件
Version:  1.0
Author: So
Author URI: http://redream.cn
*/
include 'function.php';

// 当加载文章内容的时候，执行添加版权信息的方法
add_action('the_content', 'add_copyright_info');

// 显示主菜单和子菜单
add_action('admin_menu','add_settings_menu');


// 在WordPress后台评论处添加一个子菜单
add_action('admin_menu', 'comments_submenu');
function comments_submenu() {
    add_comments_page(__('数据保存'), __('数据保存'), 'read', 'my-unique-identifier-datasave', 'add_comments_submenu');
}

// WordPress后台评论处菜单page
function add_comments_submenu(){
   if($_POST['test_hidden'] == 'y') {
       update_option('test_input_c',$_POST['test_insert_options']); //更新你添加的数据库
?>
     <div id="message" style="background-color: green; color: #ffffff;">保存成功 !</div>
<?php
   }
?>

<div>
      <?php screen_icon(); //显示图标  ?>
      <h2>添加数据</h2>
      <form action="" method="post" id="my_plugin_test_form">
          <h3>
              <label for="test_insert_options">输入测试数据:</label>
              <input type="text" id="test_insert_options" name="test_insert_options" value="<?php  echo esc_attr(get_option('test_input_c')); ?>"  />
          </h3>
          <p>
              <input type="submit" name="submit" value="保存" class="button button-primary" />
              <input type="hidden" name="test_hidden" value="y"  />
          </p>
      </form>
  </div>
<?php
}
// 通过get_option()来显示存在数据库中的信息。
// 以上填写的信息都存在了数据库中的wp_options表里面。