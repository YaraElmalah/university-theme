<?php get_header();
    pageBanner(array(
    'title' => 'Past Events',
    'subtitle' => 'Recap of our Past Events'
   ));
 ?>

  <div class="container container--narrow page-section">
    <?php
     $today = date('Ymd');
        $args = array(
          'paged' => get_query_var('paged'),
          'post_type' => 'event',
          'order' => 'DESC',
          'orderby' => 'meta_value_num',
          'meta_key' => 'event_date',
          'meta_query' => array(
            'key' => 'event_date',
            'compare' => '<',
            'value' => $today,
            'type' => 'numeric' //type of sorting
          )
        );
    $past_events = new WP_Query($args);
    if($past_events->have_posts()){
      while ($past_events->have_posts()) {
        $past_events->the_post();
        get_template_part('template-parts/event');
      }
    }
    echo paginate_links(array(
      'total' => $past_events->max_num_pages,
    ));
    ?>
     

  </div>
    <?php get_footer(); ?>