<?php
/*
 *  管理员前端管理界面
 *  功能：审核待发布内容，查看内容发布统计
 *
 *  @param {string} type stat:内容发布统计  默认:审核图片
 */

require_once('functions/settings.php');
require_once(ABSPATH . '/wp-load.php');

$type = $_GET['type'];

global $wpdb;
$stat_class = "";
$default_class = "";

//内容发布统计
if ($type === "stat") {

  $stat_class = " class='active'";
}

//审核图片
else{
  $args = array("post_status" => "draft");
  $query = new WP_Query($args);
  $review_count = $query->found_posts;

  $default_class = " class='active'";
}


get_header();

?>

<script type="text/javascript">
var nonce = '<?php echo wp_create_nonce("admin_".get_current_user_id()); ?>';
</script>

<div id="luka">
  <div class="hamburger"> </div>
  <p><a href="/"> 管理后台 - <?php echo get_bloginfo(); ?> </a> </p>
</div>

<div id="page">
  <div class="headerSpacer"> </div>
  <div id="images">
    <div class="tile" id="intro">
      <h1>当前操作<br>
      <b><?php echo $term; ?></b></h1>
      <ol class="nav">
        <li<?php echo $default_class;?>>
          <a href="/admin-front/">审核图片（<span id="reviewCount"><?php echo $review_count;?></span>）</a>
        </li>
      <!--   <li<?php echo $stat_class;?>>
          <a href="/admin-front?type=stat">查看统计</a>
        </li> -->
      </ol>
    </div>

<?php if ($type == "stat"): ?>

<?php else: require_once('functions/get_pic_grid.php');?>
<?php endif ?>

    <div class="clear">
    </div>

  </div>
  <div class="clear">
  </div>
</div>
<div id="loader">
</div>

<?php get_footer();?>