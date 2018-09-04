<?php
namespace Drupal\custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides a 'Custom' Block
 *
 * @Block(
 *   id = "home_banner",
 *   admin_label = @Translation("Home Banner"),
 * )
 */

class CustomHomeBannerBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    /* You can put any PHP code here if required */
    //print ('Hello World - PHP version');
    $output = '';
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'home_page_banner')
        ->sort('field_banner_order');
    $nids = $query->execute();

    $nodes = entity_load_multiple('node', $nids);
    /*echo '<pre>';
    foreach($nodes as $node) {
      echo 'AAA '. $node->field_image->target_id;
      //print_r($node);
    }
    echo '</pre>';
    exit;*/
    foreach($nodes as $node) {
      $wave_color = strip_tags($node->body->value);
      if($node->field_image->target_id) {
        $image = file_create_url($node->field_image->entity->getFileUri());
        $mob_image = file_create_url($node->field_mobile_banner_image->entity->getFileUri());
        if($node->field_blog_url->value) {
          $banner_link = $node->field_blog_url->value;
        } else {
          $banner_link = 'javascript:;';
        }
        $output .= '<div class="item" data-colors="'.$wave_color.'"><a href="'.$banner_link.'"><img src="" alt="banner" data-banner="'.$image.'" data-banner-mobile="'.$mob_image.'"></a></div>';
      } else {
        $wave_color = strip_tags($node->body->value);
        $utube_url = 'https://www.youtube.com/embed/'.$node->field_youtube_video_id->value.'?autoplay=1';
        $output .= '<div class="item" data-colors="'.$wave_color.'"><img src="" alt="banner" data-banner="/themes/himalaya/images/banner-default.jpg" data-banner-mobile="/themes/himalaya/images/banner-m-default.jpg"><a class="owl-video" href="'.$utube_url.'"></a></div>';
      }
    }

    return array(
      '#markup' => $this->t($output),
      /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
      '#cache' => array(
          'max-age' => 0,
      ),
    );
  }
}