<?php
/*
 *  用户个人所有喜欢
 *
 *  URL形式为： /profile/用户id/子类信息
 *  子类信息包括：评论（notes）、喜欢（likes）、关注（following）和
 *  图像（个人主页默认显示所有保存的图片）
 */

require_once('functions/settings.php');
require_once(ABSPATH . '/wp-load.php');

/*======================================
获取当前 被 浏览的用户信息
======================================*/
$uid = preg_replace('/^.*?\/(\d+)\/likes\/?$/', '$1', $_SERVER['REQUEST_URI']);
$user = get_user_by('id', $uid);
if ( $user == false) {
  wp_redirect('/');
  exit();
}
else{
  $name = $user->display_name;
  $avatar = get_user_meta($uid, 'avatar', true);
}

/*======================================
构建WP_Query，选出当前被浏览用户喜欢的所有图片
======================================*/
global $query;
global $wpdb;
$like_record = $wpdb->get_col("
    SELECT pic_id FROM pic_like
    WHERE user_id = {$uid}
  ");
if (count($like_record) > 0) {
  $args = array("post__in" => $like_record);
  $query = new WP_Query($args);
  $post_count = $query->found_posts;
}
else{
  $query = null;
  $post_count = 0;
}

get_header();

?>

<script type="text/javascript">
var nonce = '<?php echo wp_create_nonce("user_pic_action_".get_current_user_id()); ?>';
</script>


<div id="luka">
  <div class="hamburger"> </div>
  <p><a href="/"> <?php echo $name .' 的所有喜欢 - '. get_bloginfo(); ?> </a> </p>
</div>

<div id="page">
  <div class="headerSpacer"> </div>
  <div id="images">
    <!-- 用户信息 -->
    <div class="tile" id="userInfo">
      <h1><b><a href="/profile/<?php echo $uid; ?>"><?php echo $name; ?></a></b></h1>
      <div class="plain-stat">
        共喜欢 <?php echo $post_count;?> 张图片
      </div>
    </div>

<?php require('functions/get_pic_grid.php'); ?>

    <div class="clear">
    </div>

  </div>
  <div class="clear">
  </div>
</div>
<div id="loader">
</div>

<?php get_footer();?>