<?php get_header(); 
  pageBanner();

?>
  <div class="container container--narrow page-section">
    <?php
    if(have_posts()){
      while (have_posts()) {
        the_post();
        get_template_part('template-parts/event');
      }
    }
    echo paginate_links();
    ?>
     <hr class="section-break">
      <p>Looking for a recap for our past events <a href="<?php echo site_url('/past-events') ?>">Check our past events archive</a></p>
  </div>
    <?php get_footer(); ?>