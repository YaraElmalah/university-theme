<?php
if (!is_user_logged_in()) {
  wp_redirect(esc_url(site_url('/')));
  exit;
}
get_header();
pageBanner(array(
  'title' => get_the_title(),
));
?>
<div class="container container--narrow page-section">
  <div class="create-note">
    <h2 class="headline headline--medium">Create New Note</h2>
    <input class="new-note-title" type="text" placeholder="Title">
    <textarea class="new-note-body" placeholder="Write your note here .."></textarea>
    <span class="submit-note">Create Note</span>
    <span class="note-limit-message">Note: Limit reached delete an existing note to make a room for a new one</span>
  </div>
  <ul id="my-notes" class="min-list link-list">

    <?php
    $user_notes = new wp_Query(array(
      'post_type' => 'note',
      'posts_per_page' => -1,
      'author' => get_current_user_id() //to get the notes that belongs to the current logged in user
    ));
    if ($user_notes->have_posts()) {
      while ($user_notes->have_posts()) {
        $user_notes->the_post();
    ?>
        <li data-id="<?php the_ID(); ?>">
          <input type="text" class="note-title-field" value="<?php echo str_replace('Private: ', "", esc_attr(get_the_title())); ?>" readonly>
          <span class="edit-note">
            <i class="fa fa-pencil" aria-hidden="true"></i> Edit
          </span>
          <span class="delete-note">
            <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
          </span>
          <textarea class="note-body-field" readonly><?php echo esc_html(esc_textarea(wp_strip_all_tags(get_the_content()))); ?></textarea>
          <span class="update-note btn btn--blue btn--small">
            <i class="fa fa-arrow-right"></i>
          </span>

        </li>
    <?php
      }
    }
    ?>



  </ul>
</div>

<?php get_footer();
?>