<?php
/*
  Plugin Name: LA Food News Links
  Plugin URI: www.petergraycreative.com
  Description: Displays Syndicated News Links added by Admins
  Version: 1.0
  Author: Peter Gray
  Author URI: http://petergraycreative.com
 */

function init_plugin_files()
{
  wp_register_style('CSS', plugins_url('/style.css', __FILE__));
  wp_register_script( 'index', plugins_url('/index.js', __FILE__) , '', '', true );
  wp_enqueue_style( 'CSS' );
  wp_enqueue_script( 'index');
}

add_action('wp_enqueue_scripts', 'init_plugin_files');

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
  // $a = shortcode_atts( $atts );

  $output;
  $output = sprintf(esc_attr($atts['category']));
  $links = new WP_Query(array('post_type' => 'news_posts', 'category' => esc_attr($atts['category'])));

  if ($links->have_posts()) {
    while ($links->have_posts()) : $links->the_post();
    $output .= sprintf('<div class="panel-news_article">');
    if (has_post_thumbnail()) {
      $output .= sprintf('<div class="news-featured"><a href="%s">%s</a></div>', the_permalink(), the_post_thumbnail());
    }
    $article_link = get_field('article_link');
    preg_match('/https?:\/\/[a-z]*[A-Z]*\..*(?=\/)\//', $article_link, $rootLink);
    print_r($rootLink);
    $btn = do_shortcode('[boombox_button url="' . $article_link . '" tag_type="a" size="small" type="primary"]' . 'Read Full Article' . '[/boombox_button]');
    $isNewPost = is_new_item(get_post_time('U', 'gmt', get_the_ID())) ? ' new-link' : '';
    $output .= sprintf('<div class="title"><a href="%s"><span class="news-title">%s<span class="label new">%s</span></span></a></div>', $article_link, get_the_title(), ($isNewPost ? 'new' : ''));
    $output .= sprintf('<div><a class="summary-link btn">Read Summary</a></div><div class="summary display-none"><p>%s</p>%s</div>', strip_tags(get_the_excerpt()), $btn);
    $output .= sprintf('<div class="meta"><a href="%s"><span>%s</span></a>', $rootLink, get_field('news_outlet'));
    $output .= sprintf('<span>&#183</span><span>%s</span></div></div>', time_since_post(get_post_time('U', 'gmt', get_the_ID())));
    endwhile;
  }
  wp_reset_postdata();
  return $output;
}
add_shortcode('news-links', 'news_link_shortcode');
?>