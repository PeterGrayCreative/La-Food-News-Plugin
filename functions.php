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
    'post_type' => 'news_posts',
    'tax_query' => array(
      array(
        'public'       => true,
        'show_in_rest' => true,
        'label'        => 'News',
        'taxonomy' => 'news_category',
        'field' => 'slug',
        'terms' => strtolower(esc_attr($atts['category'])),
      ),
    ),
  );
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