<?php
add_action('rest_api_init', 'university_register_search');
function university_register_search(){
	register_rest_route('university/v1', 'search', array(
		'methods' => WP_REST_Server::READABLE, //CRUD (we can pass GET)
		'callback' => 'universitySearchResults'
	));
}
//As the hook "rest_api_init" pass a parameter of the link
function universitySearchResults($data){
	$main_query = new WP_Query(array(
		'post_type' => array('post', 'page', 'professor', 'event', 'program'),
		's' => sanitize_text_field($data['term']) //the search result
	));
	$results = array(
		'general' => array(),
		'profs' => array(),
		'programs' => array(),
		'events' => array()
	);
	while($main_query->have_posts()){
		$main_query->the_post();
		if(get_post_type() === 'post' || get_post_type() === 'page'){
		array_push($results['general'], array(
		'title' => get_the_title(),
		'permalink' => get_the_permalink(),
		'type' => get_post_type(),
		'authorName' => get_the_author()
		));
	}

	if(get_post_type() === 'professor'){
		array_push($results['profs'], array(
		'title' => get_the_title(),
		'permalink' => get_the_permalink(),
		'thumbnail' => get_the_post_thumbnail_url(0, 'professorLandscape')
		));
	}	
	if(get_post_type() === 'program'){
		array_push($results['programs'], array(
		'title' => get_the_title(),
		'permalink' => get_the_permalink(),
		'id' => get_the_id()
		));
	}	
	if(get_post_type() === 'event'){
		$description = NULL;
	     if(has_excerpt()){
           $description =  get_the_excerpt();
            } else{
            $description =  wp_trim_words(get_the_content(), 8); 
            }

		$event_date = get_field('event_date');
		array_push($results['events'], array(
		'title' => get_the_title(),
		'permalink' => get_the_permalink(),
		'month' => date('M' , strtotime($event_date)),
		'day' => date('d' , strtotime($event_date)),
		'desc' => $description
		));
	}
	}
	//Relationships
	$program_meta_query = array(
		'relation' => 'OR',
		'meta_query' => array(),
	);
	if($results['programs']){
	foreach ($results['programs'] as $item) {
		array_push($program_meta_query, array(
				array(
				'key' => 'related_programs',
				'compare' => 'LIKE',
				'value' => '"' .  $item['id'] . '"'
			)
		));
	}
}
	$program_relationship_query = new WP_Query(array(
		'post_type' => array('professor', 'event'),
		'meta_query' => $program_meta_query
	));
	while($program_relationship_query->have_posts()){
		$program_relationship_query->the_post();
		if(get_post_type() === 'professor'){
		array_push($results['profs'], array(
		'title' => get_the_title(),
		'permalink' => get_the_permalink(),
		'thumbnail' => get_the_post_thumbnail_url(0, 'professorLandscape')
		));
	}
		if(get_post_type() === 'event'){
		$description = NULL;
	     if(has_excerpt()){
           $description =  get_the_excerpt();
            } else{
            $description =  wp_trim_words(get_the_content(), 8); 
            }

		$event_date = get_field('event_date');
		array_push($results['events'], array(
		'title' => get_the_title(),
		'permalink' => get_the_permalink(),
		'month' => date('M' , strtotime($event_date)),
		'day' => date('d' , strtotime($event_date)),
		'desc' => $description
		));
	}

	}	
	$results['profs'] = array_values(array_unique($results['profs'], SORT_REGULAR));
	$results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
	
	
	return $results;
}