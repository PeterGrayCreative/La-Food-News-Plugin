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
  $newsLinksLoop;
  // Set up item variables to make them easier to output inside strings
  // $timeDiff = $currentTime - $postTime;
  $links = new WP_Query(array('post_type' => 'news_posts'));

  if ($links->have_posts()) {
    while ($links->have_posts()) : $links->the_post();
    $newsLinksLoop .= '<div class="panel-news_article">';
    if (has_post_thumbnail()) {

      $newsLinksLoop .= '<div class="news-featured"><a href="';
      $newsLinksLoop .= the_permalink();
      $newsLinksLoop .= '">';
      $newsLinksLoop .= the_post_thumbnail();
      $newsLinksLoop .= '</a>
      </div>';
    }
    $newsLinksLoop .=
      '<div class="title">
                    <h2>';
    $newsLinksLoop .= get_the_title();
    $newsLinksLoop .= '</h2>
                </div>
                <div class="meta">
                    <span>';
    $newsLinksLoop .= get_field('news_outlet');
    $newsLinksLoop .= '</span>
                    <span>Time Since Posted: ';
    $newsLinksLoop .= the_field('post_date');
    $newsLinksLoop .= '</span>
                </div>
            </div>';

    endwhile;
  }
  wp_reset_postdata();
  return $newsLinksLoop;
}
add_shortcode('news-links', 'news_link_shortcode');
?>