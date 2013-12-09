<?php

get_header();

?>

<script type="text/javascript">
var nonce = '<?php echo wp_create_nonce("user_pic_action_".get_current_user_id()); ?>';
</script>

<div id="luka">
  <div class="hamburger"> </div>
  <p> <a href="#"> <?php single_cat_title();?> - <?php echo get_bloginfo(); ?> </a> </p>
</div>

<div id="page">
    <div class="headerSpacer"></div>
    <div id="images">
        <div class="tile" id="categoryInfo">
            <h1>主题<br>
            <b><?php single_cat_title();?></b></h1>
            <p class="description"><?php echo strip_tags(category_description()); ?></p>
        </div>

<?php global $query;
      $cat_id = get_cat_ID(single_cat_title('', false));
      $query = new WP_Query('cat='.$cat_id);
      require_once('functions/get_pic_grid.php');
?>
    </div>
</div>
</div>

<?php get_footer(); ?>