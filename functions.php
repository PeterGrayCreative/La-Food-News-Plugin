<?php
/*
  Plugin Name: LA Food News Links
  Plugin URI: www.petergraycreative.com
  Description: Displays Syndicated News Links added by Admins
  Version: 1.0
  Author: Peter Gray
  Author URI: http://petergraycreative.com
 */

function news_link_shortcode($atts)
{
  $newsLinksLoop;
  // Set up item variables to make them easier to output inside strings
  $thePermalink = the_permalink();
  $postThumbnail = the_post_thumbnail();
  $theTitle = get_the_title();
  $newsOutlet = get_field('news_outlet');
  $postDate = get_field('post_date');
  $currentTime = time();

  $postTime = get_post_time('U', true);
  $timeDiff = $currentTime - $postTime;
  $links = new WP_Query(array('post_type' => 'news_posts'));

  if ($links->have_posts()) {
    while ($links->have_posts()) : $links->the_post();
    $newsLinksLoop .= '<div class="">';
    if (has_post_thumbnail()) {
      $newsLinksLoop .= <<<TEXT
      <div class="news-featured">
                        <a href="{$thePermalink}">{$postThumbnail}</a>
                    </div>
                    TEXT;
            }
    $newsLinksLoop .= <<<TEXT<div class="title">
                    <h2>
                    {$theTitle}
                    </h2>
                </div>
                <div class="meta">
                    <span>{$newsOutlet}</span>
                    <span>Time Since Posted: {$postDate} {$postTime}</span>
                </div>
            </div>
            TEXT;
    endwhile;
  }
  wp_reset_postdata();
  ob_start();
  return $newsLinksLoop;
}
add_shortcode('news-links', 'news_link_shortcode');
?>