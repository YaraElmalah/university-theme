<?php

get_header();
pageBanner(array(
  'title' => get_the_title(),
  'subtitle' => 'Know Us More',
));

while (have_posts()) {
  the_post();

?>

  <?php
  $theParent = wp_get_post_parent_id(get_the_ID());
  ?>

  <div class="container container--narrow page-section">
    <?php if ($theParent) { ?>
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent) ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent) ?></a> <span class="metabox__main"><?php the_title() ?></span></p>
      </div>
    <?php } ?>


    <div class="page-links">
      <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent) ?>"><?php echo get_the_title($theParent) ?></a></h2>
      <ul class="min-list">
        <?php
        if ($theParent) {
          $findChildrenOf = $theParent;
        } else {
          $findChildrenOf = get_the_ID();
        }
        wp_list_pages(
          array(
            'title_li' => NULL,
            'child_of' => $findChildrenOf
          )
        );
        ?>
        <!--
        <li class="current_page_item"><a href="#">Our History</a></li>
        <li><a href="#">Our Goals</a></li>
      -->
      </ul>
    </div>


    <div class="generic-content">
      <?php the_content(); ?>
      <?php
      $skyColor = sanitize_text_field(get_query_var('skyColor'));
      if ($skyColor === 'blue') {
        echo '<p>Sky is blue today</p>';
      } ?>
    </div>
    <form method='get'>
      <input type="text" placeholder="sky color" name="skyColor">
      <input type="submit">
    </form>
  </div>

<?php }

get_footer();

?>