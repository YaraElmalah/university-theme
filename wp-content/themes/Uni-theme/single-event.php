<?php

  get_header();

  while(have_posts()) {
    the_post(); 
    pageBanner();

    ?>

  <div class="container container--narrow page-section">
    <?php ?>
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo site_url('/blog') ?>"><i class="fa fa-home" aria-hidden="true"></i>Back to Blog</a> <span class="metabox__main"><?php the_title() ?></span></p>
    </div>
    <div class="generic-content">
      <?php the_content(); ?>
    </div>
    <?php 
    $related_programs = get_field('related_programs');
    if($related_programs){ ?>
      <hr class="section-break">
    <ul class="link-list min-list">
      <h2 class="headline headline--medium">Related Programs</h2>
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