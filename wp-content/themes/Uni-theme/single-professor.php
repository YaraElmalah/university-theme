<?php

get_header();

while (have_posts()) {
  the_post();
  pageBanner();
?>
  <div class="container container--narrow page-section">
    <div class="generic-content">
      <div class="row group">
        <div class="one-third">
          <?php the_post_thumbnail('professorPortrait') ?>
        </div>
        <div class="two-thirds">
          <?php
          $existsStatus = 'no';
          $existsQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
              array(
                'key' => 'liked_professor_id',
                'compare' => '=',
                'value' => get_the_ID()
              )
            )
          ));
          if ($existsQuery->found_posts) {
            $existsStatus = 'yes';
          }
          $likeCount = new WP_Query(array(
            'post_type' => 'like',
            'meta_query' => array(
              array(
                'key' => 'liked_professor_id',
                'compare' => '=',
                'value' => get_the_ID()
              )
            )
          ));
          ?>
          <span class="like-box" data-exists="<?php echo $existsStatus ?>" data-professor="<?php the_ID(); ?>">
            <i class="fa fa-heart-o" aria-hidden="true"></i>
            <i class="fa fa-heart" aria-hidden="true"></i>
            <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
          </span>
          <?php the_content(); ?>
        </div>
      </div>


    </div>
    <?php
    $related_programs = get_field('related_programs');
    if ($related_programs) { ?>
      <hr class="section-break">
      <ul class="link-list min-list">
        <h2 class="headline headline--medium">Subject(s) Taught</h2>
        <?php
        foreach ($related_programs as $program) {
        ?>
          <li><a href="<?php the_permalink($program) ?>"><?php echo get_the_title($program) ?></a></li>
      <?php

        }
        echo "</ul>";
      }
      ?>


  </div>

<?php }

get_footer();

?>