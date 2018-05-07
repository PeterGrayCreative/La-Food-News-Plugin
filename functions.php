<?php
/*
  Plugin Name: LA Food News Links
  Plugin URI: www.petergraycreative.com
  Description: Displays Syndicated News Links added by Admins
  Version: 1.0
  Author: Peter Gray
  Author URI: http://petergraycreative.com
 */

function add_stylesheet()
{
  wp_enqueue_style('CSS', plugins_url('/style.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'add_stylesheet');
function is_new_item($postTime)
{
  $time = round(abs(time() - $postTime) / 60 / 60);
  return $time < 72;
}
function time_since_post($postTime)
{
  $time = round(abs(time() - $postTime));
  if ($time < 60) $formattedTime = round($time / 60) . ' s';
  elseif ($time / 60 < 60) $formattedTime = round($time / 60) . ' min';
  elseif ($time / 60 / 60 < 24) {
    $formattedTime = round($time / 60 / 60);
    $formattedTime .= (round($time / 60 / 60) > 1 ? ' hrs' : ' hr');
  }
  else $formattedTime = round($time / 60 / 60 / 24) . ' days';
  return $formattedTime;
}
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
    $isNewPost = is_new_item(get_post_time('U', 'gmt', get_the_ID())) ? ' new-link' : '';
    $output .= sprintf('<div class="title"><a href="%s"><h2>%s<span class="label new">%s</span></h2></a></div>', get_field('article_link'), get_the_title(), ($isNewPost ? 'new' : ''));
    $output .= sprintf('<div class="meta"><span>%s</span>', get_field('news_outlet'));
    $output .= sprintf('<span>%s</span></div>', time_since_post(get_post_time('U', 'gmt', get_the_ID())));
    $output .= sprintf('<a href="" class="summary-link">Summary</a><p class="summary">%s</p></div>', strip_tags(get_the_excerpt()));
    endwhile;
  }
  wp_reset_postdata();
  return $output;
}
add_shortcode('news-links', 'news_link_shortcode');
?>