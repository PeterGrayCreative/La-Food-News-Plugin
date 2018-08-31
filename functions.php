<?php
/*
  Plugin Name: LA Food News Links
  Plugin URI: www.petergraycreative.com
  Description: Displays Syndicated News Links added by Admins
  Version: 1.0
  Author: Peter Gray
  Author URI: http://petergraycreative.com
 */

function init_plugin()
{
  // Register Custom Post Type for News Links
  function custom_news_post_type()
  {

    $labels = array(
      'name' => _x('News Links', 'Post Type General Name', 'news_link'),
      'singular_name' => _x('News Link', 'Post Type Singular Name', 'news_link'),
      'menu_name' => __('News Links', 'news_link'),
      'name_admin_bar' => __('News Links', 'news_link'),
      'archives' => __('News Archives', 'news_link'),
      'attributes' => __('', 'news_link'),
      'parent_item_colon' => __('', 'news_link'),
      'all_items' => __('All News Links', 'news_link'),
      'add_new_item' => __('Add News Link', 'news_link'),
      'add_new' => __('Add New', 'news_link'),
      'new_item' => __('New Link', 'news_link'),
      'edit_item' => __('Edit Link', 'news_link'),
      'update_item' => __('Update Link', 'news_link'),
      'view_item' => __('View Link', 'news_link'),
      'view_items' => __('View Links', 'news_link'),
      'search_items' => __('Search Links', 'news_link'),
      'not_found' => __('Not found', 'news_link'),
      'not_found_in_trash' => __('Not found in Trash', 'news_link'),
      'insert_into_item' => __('Insert into item', 'news_link'),
      'uploaded_to_this_item' => __('Uploaded to this Link', 'news_link'),
      'items_list' => __('Links List', 'news_link'),
      'items_list_navigation' => __('Links list navigation', 'news_link'),
      'filter_items_list' => __('Filter Links list', 'news_link'),
    );
    $args = array(
      'label' => __('News Link', 'news_link'),
      'description' => __('Syndicated News Post Links', 'news_link'),
      'labels' => $labels,
      'supports' => array('title', 'editor', 'thumbnail', 'trackbacks', 'page-attributes', 'excerpt'),
      'taxonomies' => array('news_tag'), //'post_category'
      'hierarchical' => false,
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'menu_position' => 5,
      'menu_icon' => 'dashicons-format-aside',
      'show_in_admin_bar' => true,
      'show_in_nav_menus' => true,
      'can_export' => true,
      'has_archive' => true,
      'exclude_from_search' => false,
      'publicly_queryable' => true,
      'capability_type' => 'page',
      'show_in_rest' => true,
      'rest_base' => 'news',
    );
    register_post_type('news', $args);

  }
  add_action('init', 'custom_news_post_type', 0);

  add_action('init', 'create_news_tag_taxonomies', 0);
// Add custom tag taxonomy for News Links
  function create_news_tag_taxonomies()
  {
    $labels = array(
      'name' => _x('News Tags', 'taxonomy general name'),
      'singular_name' => _x('Tag', 'taxonomy singular name'),
      'search_items' => __('Search Tags'),
      'popular_items' => __('Popular Tags'),
      'all_items' => __('All Tags'),
      'parent_item' => null,
      'parent_item_colon' => null,
      'edit_item' => __('Edit Tag'),
      'update_item' => __('Update Tag'),
      'add_new_item' => __('Add New Tag'),
      'new_item_name' => __('New Tag Name'),
      'separate_items_with_commas' => __('Separate tags with commas'),
      'add_or_remove_items' => __('Add or remove tags'),
      'choose_from_most_used' => __('Choose from the most used tags'),
      'menu_name' => __('News Tags'),
    );

    register_taxonomy('news_tag', 'news', array(
      'hierarchical' => false,
      'labels' => $labels,
      'show_ui' => true,
      'update_count_callback' => '_update_post_term_count',
      'query_var' => true,
      'rewrite' => array('slug' => 'news-tag'),
    ));
  }

  add_action('init', 'create_news_cat_taxonomies', 0);

  function create_news_cat_taxonomies()
  {
    $labels = array(
      'name' => _x('Categories', 'taxonomy general name'),
      'singular_name' => _x('Category', 'taxonomy singular name'),
      'search_items' => __('Search Categories'),
      'popular_items' => __('Popular Categories'),
      'all_items' => __('All Categories'),
      'parent_item' => null,
      'parent_item_colon' => null,
      'edit_item' => __('Edit Category'),
      'update_item' => __('Update Category'),
      'add_new_item' => __('Add New Category'),
      'new_item_name' => __('New Category Name'),
      'separate_items_with_commas' => __('Separate tags with commas'),
      'add_or_remove_items' => __('Add or remove tags'),
      'choose_from_most_used' => __('Choose from the most used tags'),
      'menu_name' => __('News Cats'),
    );

    register_taxonomy('news_category', 'news_posts', array(
      'hierarchical' => false,
      'labels' => $labels,
      'show_ui' => true,
      'update_count_callback' => '_update_post_term_count',
      'query_var' => true,
      'rewrite' => array('slug' => 'news-category'),
    ));
  }
  wp_register_style('CSS', plugins_url('/style.css', __FILE__), null, null, null);
  wp_register_script('index', plugins_url('/index.js', __FILE__), '', '', true);
  wp_enqueue_style('CSS');
  wp_enqueue_script('index');
}

add_action('wp_enqueue_scripts', 'init_plugin');

// Borrowed from https://www.binarymoon.co.uk/2017/04/fixing-typographic-widows-wordpress/
function fix_widows($title)
{

	// Strip spaces.
  $title = trim($title);
	// Find the last space.
  $space = strrpos($title, ' ');

	// If there's a space then replace the last on with a non breaking space.
  if (false !== $space) {
    $str = substr($title, 0, $space) . '&nbsp;' . substr($title, $space + 1);
  }

	// Return the string.
  return $str;

}
function is_new_item($postTime)
{
  $time = round(abs(time() - $postTime) / 60 / 60);
  return $time < 72;
}
function time_since_post($postTime)
{
  // Possibly rewrite to switch statement later. This is a bit confusing to keep track of.
  $time = round(abs(time() - $postTime));
  if ($time < 60) $formattedTime = round($time / 60) . ' s';
  elseif ($time / 60 < 60) $formattedTime = round($time / 60) . ' min';
  elseif ($time / 60 / 60 < 24) {
    $formattedTime = round($time / 60 / 60);
    $formattedTime .= (round($time / 60 / 60) > 1 ? ' hrs' : ' hr');
  } else {
    $days = round($time / 60 / 60 / 24);
    if ($days > 1) {
      $formattedTime = $days . ' days';
    } else $formattedTime = $days . ' day';
  }
  return $formattedTime;
}
function news_link_shortcode($atts)
{
  $args = array(
    'post_type' => 'news',
    'tax_query' => array(
      array(
        'label' => 'News',
        'taxonomy' => 'news_category',
        'field' => 'slug',
        'terms' => strtolower(esc_attr($atts['category'])),
        'public' => true,
        'show_in_rest' => true,
        'rest_controller_class' => 'WP_REST_Posts_Controller',
      ),
    ),
  );
  var_dump($args);
  $links = new WP_Query($args);
  $output = '';
  if ($links->have_posts()) {
    while ($links->have_posts()) : $links->the_post();
    $output .= sprintf('<div class="panel-news_article">');
    if (has_post_thumbnail()) {
      $output .= sprintf('<div class="news-featured"><a href="%s">%s</a></div>', the_permalink(), the_post_thumbnail());
    }
    $article_link = get_field('article_link');

    preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,8})*\/*[^\/]/', $article_link, $rootLink);
    $base_url = $rootLink[0];

    $btn = do_shortcode('[boombox_button url="' . $article_link . '" tag_type="a" size="small" type="primary"]' . 'Read Full Article' . '[/boombox_button]');
    $isNewPost = is_new_item(get_post_time('U', 'gmt', get_the_ID())) ? ' new-link' : '';
    $output .= sprintf('<span class="news-title">%s<span class="label %s">%s</span></span>', fix_widows(get_the_title()), ($isNewPost ? 'new' : ''), ($isNewPost ? 'new' : ''));
    $output .= sprintf('<div class="summary display-none"><p>%s</p>%s</div>', strip_tags(get_the_excerpt()), $btn);
    $output .= sprintf('<div class="meta"><a href="%s"><span>%s</span></a>', isset($base_url) ? $base_url : '', get_field('news_outlet'));
    $output .= sprintf('<span>&#183</span><span>%s</span></div></div>', time_since_post(get_post_time('U', 'gmt', get_the_ID())));
    endwhile;
  }
  wp_reset_postdata();
  return $output;
}
add_shortcode('news-links', 'news_link_shortcode');
?>