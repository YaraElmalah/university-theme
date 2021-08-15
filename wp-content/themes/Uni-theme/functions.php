<?php
//include the search route file
require_once get_theme_file_path('/inc/search-route.php');
require_once get_theme_file_path('/inc/like-route.php');
//Add New Query Var
add_filter('query_vars', 'marvel_university_query_vars');
function marvel_university_query_vars($vars)
{
	$vars[] = 'skyColor';
	return $vars;
}
//Page Banner function
function pageBanner($args = NULL)
{
	//title 
	if (!$args['title']) {
		$args['title'] = get_the_title();
	}
	//Subtitle
	if (!$args['subtitle']) {
		if (get_field('page_banner_subtitle')) {
			$args['subtitle'] = get_field('page_banner_subtitle');
		}
	}
	//Background
	if (!$args['photo']) {
		if (get_field('page_banner_background')) {
			$args['photo'] = get_field('page_banner_background')['sizes']['pageBanner'];
		} else {
			$args['photo'] = get_theme_file_uri('/images/ocean.jpg');
		}
	}
?>
	<div class="page-banner">
		<div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'] ?>);"></div>
		<div class="page-banner__content container container--narrow">
			<h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
			<div class="page-banner__intro">
				<p><?php echo $args['subtitle'] ?></p>
			</div>
		</div>
	</div>
<?php
}
add_action('wp_enqueue_scripts', 'university_files');
function university_files()
{
	wp_enqueue_style('my-main-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_style('universirty-main-style', get_stylesheet_uri(), null, microtime());
	wp_enqueue_script('my-main-js', get_theme_file_uri('js/scripts-bundled.js'), null, microtime(), true);
	wp_enqueue_script('search-js', get_theme_file_uri('js/search.js'), array('jquery'), microtime(), true);
	wp_localize_script('search-js', 'uni_info', array(
		'root_url' => get_site_url()
	));
	wp_enqueue_script('my-notes', get_theme_file_uri('js/my-notes.js'), array('jquery'), microtime(), true);
	wp_localize_script(
		'my-notes',
		'notes_obj',
		array(
			'nonce' => wp_create_nonce('wp_rest'),  //this should be written as it is
		)
	);
	wp_enqueue_script('like-js', get_theme_file_uri('js/like.js'), array('jquery'), microtime(), true);
	wp_localize_script('like-js', 'likes_info', array(
		'root_url' => get_site_url(),
		'nonce' => wp_create_nonce('wp_rest'),  //this should be written as it is

	));
}
add_action('after_setup_theme', 'university_features');
function university_features()
{
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_image_size('professorLandscape', 400, 260, true);
	add_image_size('professorPortrait', 480, 650, true);
	add_image_size('pageBanner', 1500, 350, true);
}
//manipulating the Query of Event Archive
add_action('pre_get_posts', 'university_adjust_event_queries');
function university_adjust_event_queries($query)
{
	//this action is so powerful that it affects the admin as well
	if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
		$today = date('Ymd');
		$query->set('meta_key', 'event_date');
		$query->set('orderby', 'meta_value_num');
		$meta_query_args = array(
			'key' => 'event_date',
			'compare' => '>=',
			'value' => $today,
			'type' => 'numeric' //type of sorting
		);
		$query->set('meta_query', $meta_query_args);
		$query->set('order', 'ASC');
	}
	//modify the Query of programs to order it Alphabetically
	if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
		$query->set('posts_per_page', -1);
	}
}
//Modify the WP API 
add_action('rest_api_init', 'university_custom_rest_api');
function university_custom_rest_api()
{
	//used to register a field in the rest API
	register_rest_field('post', 'authorName', array(
		'get_callback' => function () {
			return get_the_author(); //get the author name
		}
	));
	register_rest_field('note', 'userNoteCount', array(
		'get_callback' => function () {
			return count_user_posts(get_current_user_id(), 'note'); //get number of notes that the user have
		}
	));
}
//Redirect subscriber account out of the admin and onto homepage
add_action('admin_init', 'redirect_subs_frontend');
function redirect_subs_frontend()
{
	$current_user = wp_get_current_user();
	//make sure that the subscriber do not reach the admin dashboard
	if (count($current_user->roles) === 1 && $current_user->roles[0] === 'subscriber') {
		wp_redirect(esc_url(site_url('/')));
		exit;
	}
}
//hide top admin bar
add_action('wp_loaded', 'no_admin_bar');
function no_admin_bar()
{
	$current_user = wp_get_current_user();
	//target the normal subscriber
	if (count($current_user->roles) === 1 && $current_user->roles[0] === 'subscriber') {
		show_admin_bar(false); //hide the admin bar
	}
}
//Customize Login Screen
//change the branding logo url
add_filter('login_headerurl', 'university_header_url');
function university_header_url()
{
	return esc_url(site_url('/'));
}
//load css style for the login page
add_action('login_enqueue_scripts', 'university_header_log');
function university_header_log()
{
	wp_enqueue_style('my-main-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
	wp_enqueue_style('universirty-main-style', get_stylesheet_uri(), null, microtime());
}
//change the branding title
add_filter('login_headertitle', 'university_branding_title');
function university_branding_title()
{
	return get_bloginfo('name');
}
//Force Notes Privacy to be private
/**
 * This filter trigger everytime a post is created or updated
 * Filter from Serverside
 */
add_filter('wp_insert_post_data', 'make_note_private', 10, 2);
function make_note_private($data, $postArr)
{
	if ($data['post_type'] === "note") {
		if (count_user_posts(get_current_user_id(), 'note') >= 5 && !$postArr['ID']) {
			die("You have Reached Your Note Limit");
		}
	}
	if ($data['post_type'] === "note" && $data['post_status'] !== "trash") {
		$data['post_status'] = 'private';
		$data['post_title'] = sanitize_text_field($data['post_title']);
		$data['post_content'] = sanitize_textarea_field($data['post_content']);
	}
	return $data;
}
