<?php
/*
  Plugin Name: LA Food News Links
  Plugin URI: www.petergraycreative.com
  Description: Displays Syndicated News Links added by Admins
  Version: 1.0
  Author: Peter Gray
  Author URI: http://petergraycreative.com
 */

function add_stylesheet() {
	wp_enqueue_style( 'CSS', plugins_url( '/style.css', __FILE__ ) );
}

add_action( 'wp_enqueue_scripts', 'add_stylesheet' );

function news_link_shortcode($atts)
{
  $output;
  $links = new WP_Query(array('post_type' => 'news_posts'));

  if ($links->have_posts()) {
    while ($links->have_posts()) : $links->the_post();
    $output .= sprintf('<div class="panel-news_article">');
    if (has_post_thumbnail()) {
      $output .= sprintf('<div class="news-featured"><a href="%s">%s</a></div>', the_permalink(), the_post_thumbnail());
    }
    $output .= sprintf('<div class="title"><h2>%s</h2></div>', get_the_title());
    $output .= sprintf('<div class="meta"><span>%s</span>', get_field('news_outlet'));
    $output .= sprintf('<span>Time Since Posted: %d</span></div></div>', get_post_time('post_date'));
    $output .= 'posttime' . get_post_time('U', 'gmt', get_the_ID()) . 'currenttime' . time();
    $output .= 'timediff' . round(abs(time() - get_post_time('U', 'gmt', get_the_ID())) / 60,2);
    endwhile;
  }
  wp_reset_postdata();
  return $output;
}
add_shortcode('news-links', 'news_link_shortcode');
?>