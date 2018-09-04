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
   *   id = "danone_logo",
   *   admin_label = @Translation("Danone Logo"),
   * )
   */

  class CustomDanoneBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
      $output = '';
      $query = \Drupal::database()->select('danone','d');
      $query->addField('d', 'fid');
      $query->range(0, 1);
      $fid = $query->execute()->fetchField();
      
      if($fid) {
        $query = \Drupal::database()->select('file_managed', 'fm');
        $query->addField('fm', 'uri');
        $query->condition('fm.fid', $fid);
        $query->range(0, 1);
        $uri = $query->execute()->fetchField();
        $url = file_create_url($uri);
        $output .= '<a class="logo fr" href="http://www.danone.in" target="_blank"><img src="'.$url.'" alt="Danone"/></a>';
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