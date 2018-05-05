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
    $output .=
      '<div class="title">
                    <h2>';
    $output .= get_the_title();
    $output .= '</h2>
                </div>
                <div class="meta">
                    <span>';
    $output .= get_field('news_outlet');
    $output .= '</span>
                    <span>Time Since Posted: ';
    $output .= the_field('post_date');
    $output .= '</span>
                </div>
            </div>';

    endwhile;
  }
  wp_reset_postdata();
  return $output;
}
add_shortcode('news-links', 'news_link_shortcode');
?>