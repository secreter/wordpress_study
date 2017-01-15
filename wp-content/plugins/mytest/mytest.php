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
// add_action('the_content', 'add_copyright_info');

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


// 使用 widgets_init 动作钩子来执行自定义的函数
    add_action( 'widgets_init', 'boj_widgetexample_register_widgets' );
 
    // 注册小工具
    function boj_widgetexample_register_widgets() {
        register_widget( 'boj_widgetexample_widget_my_info' );
        register_widget( 'boj_awe_widget' );
    }

//控制板小工具
add_action( 'wp_dashboard_setup', 'boj_dashboard_example_widgets' );
 
    function boj_dashboard_example_widgets() {
        // 创建一个自定义的控制板小工具
        wp_add_dashboard_widget(
            'dashboard_custom_feed',
            'My Plugin Information',
            'boj_dashboard_example_display'
        );
    }
 
    function boj_dashboard_example_display() {
        echo '<p>Please contact support@example.ccom to report bugs.</p>';
    }

//下面向 post 页面添加一个自定义的元数据框。
    add_action( 'add_meta_boxes', 'boj_mbe_create' );
 
    function boj_mbe_create() {
        // 创建元数据框
        add_meta_box( 'boj-meta', 'My Custom Meta Box', 'boj_mbe_function', 'post', 'normal', 'high' );
    }
 
    function boj_mbe_function( $post ) {
        // 获取元数据的值如果存在
        $boj_mbe_name = get_post_meta( $post->ID, '_boj_mbe_name', true );
        $boj_mbe_costume = get_post_meta( $post->ID, '_boj_mbe_costume', true );
 
        echo '请填写下面的信息: ';
        ?>
        <p>Name: <input type="text" name="boj_mbe_name" value="
            <?php echo esc_attr( $boj_mbe_name ); ?>" /></p>
        <p>Costume:
            <select name="boj_mbe_costume">
                <option value="vampire" <?php selected( $boj_mbe_costume, 'vampire' ); ?>
                    >Vampire
                </option>
                <option value="zombie" <?php selected( $boj_mbe_costume, 'zombie' ); ?>
                    >Zombie
                </option>
                <option value="smurf" <?php selected( $boj_mbe_costume, 'smurf' ); ?>
                    >Smurf
                </option>
            </select>
            </p>
            <?php
    }
 
    // 用钩子来保存元数据
    add_action( 'save_post', 'boj_mbe_save_meta' );
 
    function boj_mbe_save_meta( $post_id ) {
        // 验证元数据存在
        if ( isset( $_POST['boj_mbe_name'] ) ) {
            // 保存元数据
            update_post_meta( $post_id, '_boj_mbe_name',
                strip_tags( $_POST['boj_mbe_name'] ) );
            update_post_meta( $post_id, '_boj_mbe_costume',
                strip_tags( $_POST['boj_mbe_costume'] ) );
        }
    }




/**
 * 如何在插件中使用 WordPress 提供的样式 
 */
add_action( 'admin_menu', 'boj_styling_create_menu' );
 
function boj_styling_create_menu() {
    // 创建顶级菜单
    add_menu_page( 'My Plugin Settings', 'Plugin Styling',
        'read', __FILE__, 'boj_styling_settings' );
}

function boj_styling_settings() {
        ?>
        <div class="wrap">
            <h2>My Plugin</h2>
            <h3>My Plugin</h3>
            <h4>My Plugin</h4>
            <h5>My Plugin</h5>
            <h6>My Plugin</h6>
        </div>

        <div class="wrap">
        	<?php screen_icon( 'plugins' ); ?>
            <h2>My Plugin</h2>
            <div id="message" class="updated">设置保存成功</div>
            <div id="message" class="error">保存出现错误</div>
        	<p>
			<input type="submit" name="Save" value="Save Options" />
			<input type="submit" name="Save" value="Save Options" class="button-primary" />
			</p>
			<p>
			<input type="submit" name="Secondary" value="Secondary Button" />
			<input type="submit" name="Secondary" value="Secondary Button" class="button-secondary " />
			</p>
			<p>
			<input type="submit" name="Secondary" value="Secondary Button" class="button-secondary " />
			<input type="submit" name="Highlighted" value="Button Highlighted" class="button-highlighted" />
			</p>

			<a href="#">Search</a>
			<a href="#" class="button-secondary">Search</a>
			<a href="#" class="button-highlighted">Search</a>
			<a href="#" class="button-primary">Search</a>
        </div>

        <div class="wrap">
		    <?php screen_icon( 'plugins' ); ?> <h2> My Plugin</h2>
		    <h2><a href-"#">Test</a>
		    <h3><a href="#">Test</a>
		    <h4><a href="#">Test</a>
		    <h5><a href="#">Test</a>
		    <a href="#">Test</a>
		</div>

		<!-- 表格 -->
		<div class="wrap">
        <?php screen_icon( 'plugins' ); ?>
        <h2>My Plugin</h2>
        <form method="POST" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="lname">Last Name</label></th>
                    <td><input id="lname" maxlength="45" size="25" name="lname" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="fname">First Name</label></th>
                    <td><input maxlength="45" size="25" name="fname" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="color">Favorite Color</label></th>
                    <td>
                        <select name="color">
                            <option value="orange">Orange</option>
                            <option value="black">Black</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="featured">Featured?</label></th>
                    <td><input type="checkbox" name="favorite" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="gender">Gender</label></th>
                    <td>
                        <input type="radio" name="gender" value="male" /> Male
                        <input type="radio" name="gender" value="female" /> Female
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="bio">Bio</label></th>
                    <td><input maxlength="45" size="25" name="fname" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="fname">First Name</label></th>
                    <td><textarea name="bio"></textarea></td>
                </tr>
                <tr valign="top">
                    <td>
                        <input type="submit" name="save" value="Save Options"
                            class="button-primary" />
                        <input type="submit" name="reset" value="Reset"
                            class="button-secondary" />
                    </td>
                </tr>
            </table>
        	</form>
    	</div>

    	<table class="widefat">
		    <thead>
		        <tr>
		            <th>Name</th>
		            <th>Favorite Holiday</th>
		        </tr>
		    </thead>
		    <tfoot>
		        <tr>
		            <th>Name</th>
		            <th>Favorite Holiday</th>
		        </tr>
		    </tfoot>
		    <tbody>
		        <tr>
		            <td>First</td>
		            <td>Second</td>
		        </tr>
		    </tbody>
		</table>
		<!-- 分页 -->
		<div class="tablenav">
		    <div class="tablenav-pages">
		        <span class="displaying-num">Displaying 1-20 of 66</span>
		        <span class="page-numbers current">1</span>
		        <a href="#" class="page-numbers">2</a>
		        <a href="#" class="page-numbers">3</a>
		        <a href="#" class="page-numbers">4</a>
		        <a href="#" class="next page-numbers">&raquo;</a>
		    </div>
		</div>
        <?php
    }



