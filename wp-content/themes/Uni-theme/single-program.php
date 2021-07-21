<?php

  get_header();

  while(have_posts()) {
    the_post(); 
    pageBanner();
    ?>
    

  <div class="container container--narrow page-section">
    <?php ?>
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo site_url('/programs') ?>"><i class="fa fa-home" aria-hidden="true"></i>All Programs</a> <span class="metabox__main"><?php the_title() ?></span></p>
    </div>
    <div class="generic-content">
      <?php the_field('main_body_content') ?>
    </div>
    <?php
     //here we ordered by custom field (professors)
        $args = array(
          'posts_per_page' => -1,
          'post_type' => 'professor',
          'order' => 'ASC',
          'orderby' => 'title',
          'meta_query' => array( 
           array(
            'key' => 'related_programs',
            'compare' => 'LIKE',
            'value' => '"' . get_the_ID() . '"'
           )
            
          )
        );
          $related_professors = new WP_Query($args);
          if($related_professors->have_posts()){
            ?>
            <hr class="section-break">
            <h2 class="headline headline--medium"><?php echo get_the_title() ?>
             Professors</h2>
             <ul class="professor-cards">

            <?php
            while($related_professors->have_posts()){
             $related_professors->the_post();
                           ?>
              <li class="professor-card__list-item">
               <a href="<?php the_permalink() ?>" class="professor-card">
              <img class="professor-card__image" src="<?php echo the_post_thumbnail_url('professorLandscape') ?>">
              <span class="professor-card__name"><?php the_title() ?></span>
              </a>
            </li>
           <?php  }
           echo "</ul>";
          }
          wp_reset_postdata();
        ?>
    <hr class="section-break">
    <?php
     //here we ordered by custom field (Events)
        $today = date('Ymd');
        $args = array(
          'posts_per_page' => -1,
          'post_type' => 'event',
          'order' => 'ASC',
          'orderby' => 'meta_value_num',
          'meta_key' => 'event_date',
          'meta_query' => array(
           array(
            'key' => 'event_date',
            'compare' => '>=',
            'value' => $today,
            'type' => 'numeric' //type of sorting
          ), 
           array(
            'key' => 'related_programs',
            'compare' => 'LIKE',
            'value' => '"' . get_the_ID() . '"'
           )
            
          )
        );
          $homepage_events = new WP_Query($args);
          if($homepage_events->have_posts()){
            ?>
            <h2 class="headline headline--medium">Upcoming <?php echo get_the_title() ?> Events</h2>
            <?php
            while($homepage_events->have_posts()){
              $homepage_events->the_post();
              get_template_part('template-parts/event');
               }
          }
        ?>

  </div>
    
  <?php }
  get_footer();

?>