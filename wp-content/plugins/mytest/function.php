<?php
/**
 * [add_copyright_info 输出内容后连上要显示的版权信息，简单设置增加版权信息的内容的样式]
 * @param [type] $content [string]
 */
function add_copyright_info ($content){
    $content .= '<div style="clear:both; border-top:1px dashed #e0e0e0; padding:10px 0 10px 0; font-size:12px;">版权所有©转载必须以链接形式注明作者和原始出处：<a href="'.bloginfo("url").'" title="点击去首页">'.bloginfo().'</a> » <a title="本文地址" href="'.get_permalink().'">'.get_the_title().'</a></div>' ;
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
    echo plugin_dir_path(__FILE__);
    echo __FILE__;
}



// 当wp后台的头部加载时，执行的 PHP函数 my_custom_admin_head
// add_action('admin_head', 'my_custom_admin_head');
// 输出一个css样式，改变body的背景颜色
// function my_custom_admin_head() {
//     echo '<style>body {background-color: #4AAF48 !important;}</style>';
// }


register_activation_hook(__FILE__, 'so_mytest_activation');
function so_mytest_activation() {
// 启用时要做的事情
	// echo "<script>alert('插件启用成功！')</script>";
}


add_action( 'pre_get_posts', 'boj_randomly_order_blog_posts');
function boj_randomly_order_blog_posts( $query ) {
    if($query-> is_home &&empty($query-> query_vars['suppress_filters']))
        $query-> set('orderby','rand');
}


add_action( 'plugins_loaded','so_mytest_activation');



// add_filter( 'wp_title', 'boj_add_site_name_to_title', 10, 2 );
 
// function boj_add_site_name_to_title( $title,$seq) {
//      得到网站名称 
//     $name = get_bloginfo('name');
//     /* 附加到 $title 变量。 */
//     $title.=$sqp.' '.$name;
//     /* 返回 $title */
//     return '111';
// }


// add_filter( 'posts_result', 'boj_custom_home_page_posts');
 
// function boj_cumstom_home_page_posts( $result ) {

//     global$wpdb,$wp_query;
//     /* 检查是否在首页 */
//     if( is_home() ) {
//         /* 每页的 post 个数 */
//         $per_page= get_option('posts_per_page');
//         /* 得到当前页 */
//         $paged= get_query_var('paged');
//         /* 设置 $page 变量 */
//         $page= ( ( 0 ==$paged|| 1 ==$paged ) ? 1 : absint($paged ) );
//         /* 设置偏移的 posts 的个数 */
//         $offset= ($page- 1 ) * $per_page.',';
//          通过 $offset 和 要显示的 posts 数量来设置 limit 
//         $limits='LIMIT'.$offset.$per_page;
//         /* 从数据库查询结果 */
//         $result=$wpdb-> get_results("
//                             SELECT SQL_CALC_FOUND_ROWS $wpdb->posts. *
//                             FROM $wpdb-> posts
//                             WHERE 1 = 1
//                             AND post_type ='page'
//                             AND post_status ='publish'
//                             ORDER BY post_title ASC$limits"
//                         );
//     }
//     return $result;
// }



add_filter( 'the_content', 'boj_add_related_posts_to_content');
 
function boj_add_related_posts_to_content( $content) {
    /* 如果不是单篇文章，直接返回 content */
    if( !is_singular('post') )
        return $content;
    /* 得到当前 post 的分类 */
    $terms= get_the_terms( get_the_ID(),'category');
    /* 循环分类，并将它们的 ID 放到一个数组中 */
    $categories=array();
    foreach($terms as $term )
        $categories[] =$term->term_id;
    /* 从数据库查询相同分类的 posts */
    $loop=new WP_Query(
       array(
            'cat__in'=>$categories,
            'posts_per_page'=> 5,
            'post__not_in'=>array( get_the_ID() ),
            'orderby'=>'rand'
        )
    );
 
    /* 是否有相关 posts 存在 */
    if($loop-> have_posts() ) {
        /* 开始 ul */
        $content.='<ul class="related-posts">';
        while($loop-> have_posts() ){
            $loop-> the_post();
            /* 添加 post 标题 */
            $content.= the_title (
                '<li><a href="'.get_permalink().'">',
                '</a></li>',
                false
            );
        }
        /* 结束 ul */
        $content.='</ul>';
        /* 重置 query */
        wp_reset_query();
    }
    /* 返回 content */
    return $content;
}



// boj_widgetexample_widget_my_info class
    class boj_widgetexample_widget_my_info extends WP_Widget {
 		// process the new widget
	    function __construct() {
	        $widget_ops = array(
	            'classname' => 'boj_widgetexample_widget_class',
	            'description' => 'Display a user\'s favorite movie and song.'
	        );
	        parent::__construct( 'boj_widgetexample_widget_my_info', 'My Info Widget', $widget_ops );
	    }

	    function form($instance) {
	        $defaults = array( 'title' => 'My Info', 'movie' => '', 'song' => '' );
	        $instance = wp_parse_args( (array) $instance, $defaults );
	        $title = $instance['title'];
	        $movie = $instance['movie'];
	        $song = $instance['song'];
	        ?>
	 
	        <p>Title: <input id="<?php echo $this->get_field_id('title');?>" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" type="text"
	            value="<?php echo $title; ?>" /></p>
	        <p> Favorite Movie: <input class="widefat" name="<?php echo $this->get_field_name('movie'); ?> "type="text"
	            value="<?php echo esc_attr( $movie ); ?> " /> </p>
	        <p> Favorite Song: <textarea class="widefat" name="<?php echo $this->get_field_name('song'); ?> " />
	            <?php echo esc_attr( $song ); ?> </textarea> </p>
	        <?php
	    }

	    // 保存小工具设置
	    function update( $new_instance, $old_instance ) {
	        $instance = $old_instance;
	        $instance['title'] = strip_tags( $new_instance['title'] );
	        $instance['movie'] = strip_tags( $new_instance['movie'] );
	        $instance['song'] = strip_tags( $new_instance['song'] );
	 
	        return $instance;
	    }

	    // 显示小工具
	    function widget( $args, $instance ) {
	        extract( $args );
	 
	        echo $before_widget;
	        $title = apply_filters( 'widget_title', $instance['title'] );
	        $movie = empty( $instance['movie'] ) ? '&nbsp;' : $instance['movie'];
	        $song = empty( $instance['song'] ) ? '&nbsp;' : $instance['song'];
	 
	        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
	        echo '<p> Fav Movie: ' . $movie . '</p>';
	        echo '<p> Fav Song: ' . $song . '</p>';
	        echo $after_widget;
	    }

    }



    // boj_awe_widget class
    class boj_awe_widget extends WP_Widget {
        // process the new widget
        function __construct() {
            $widget_ops = array(
                'classname' => 'boj_awe_widget_class',
                'description' => 'Display an RSS feed with options.'
            );
            parent::__construct( 'boj_awe_widget', 'Advaced RSS Widget', $widget_ops );
        }

        // 创建小工具的设置表单
        function form( $instance ) {
            $defaults = array(
                'title' => 'RSS Feed',
                'rss_feed' => 'http://strangework.com/feed',
                'rss_items' => '2'
            );
            $instance = wp_parse_args( (array) $instance, $defaults );
            $title = $instance['title'];
            $rss_feed = $instance['rss_feed'];
            $rss_items = $instance['rss_items'];
            $rss_date = $instance['rss_date'];
            $rss_summary = $instance['rss_summary'];
            ?>
                <p>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>"
                    type = "text" value="<?php echo esc_attr( $title ); ?>" /></p>
                <p>RSS Feed: <input class="widefat" name="<?php echo $this->get_field_name( 'rss_feed' ); ?>"
                    type="text" value="<?php echo esc_attr( $rss_feed ); ?>" /></p>
                <p>Items to Display:
                    <select name="<?php echo $this->get_field_name( 'rss_items' ); ?>">
                        <option value="1" <?php selected( $rss_items, 1 ); ?>>1</option>
                        <option value="2" <?php selected( $rss_items, 2 ); ?>>2</option>
                        <option value="3" <?php selected( $rss_items, 3 ); ?>>3</option>
                        <option value="4" <?php selected( $rss_items, 4 ); ?>>4</option>
                        <option value="5" <?php selected( $rss_items, 5 ); ?>>5</option>
                    </select>
                </p>
                <p>Show Date?: <input name="<?php echo $this->get_field_name( 'rss_date' ); ?>"
                    type="checkbox" <?php checked( $rss_date, 'on' ); ?> /></p>
                <p>Show Summary?: <input name="<?php echo $this->get_field_name( 'rss_summary' ); ?>"
                    type="checkbox" <?php checked( $rss_summary, 'on' ); ?> /></p>
                <?php
        }
        // 保存小工具设置
	    function update( $new_instance, $old_instance ) {
	        $instance = $old_instance;
	        $instance['title'] = strip_tags( $new_instance['title'] );
	        $instance['rss_feed'] = strip_tags( $new_instance['rss_feed'] );
	        $instance['rss_items'] = strip_tags( $new_instance['rss_items'] );
	        $instance['rss_date'] = strip_tags( $new_instance['rss_date'] );
	        $instance['rss_summary'] = strip_tags( $new_instance['rss_summary'] );
	 
	        return $instance;
	    }

	    // 显示小工具
	    function widget( $args, $instance ) {
	        extract( $args );
	 
	        echo $before_widget;
	 
	        // load the widget settings
	        $title = apply_filters( 'widget_title', $instance['title'] );
	        $rss_feed = empty( $instance['rss_feed'] ) ? '' : $instance['rss_feed'];
	        $rss_items = empty( $instance['rss_items'] ) ? 2 : $instance['rss_items'];
	        $rss_date = empty( $instance['rss_date'] ) ? 0 : 1;
	        $rss_summary = empty( $instance['rss_summary'] ) ? 0 : 1;
	 
	        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
	 
	        if ( $rss_feed ) {
	            // display the RSS feed
	            wp_widget_rss_output( array(
	                'url' => $rss_feed,
	                'title' => $title,
	                'items' => $rss_items,
	                'show_summary' => $rss_summary,
	                'show_author' => 0,
	                'show_date' => $rss_date
	            ) );
	        }
	 
	         echo $after_widget;
	    }
	}