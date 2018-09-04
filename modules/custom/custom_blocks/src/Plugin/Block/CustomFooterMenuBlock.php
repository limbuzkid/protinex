<?php
  namespace Drupal\custom_blocks\Plugin\Block;
  
  use Drupal\Core\Block\BlockBase;
  use Drupal\Component\Annotation\Plugin;
  use Drupal\Core\Annotation\Translation;
  use Drupal\Core\Url;
  use Drupal\Core\Link;
  use Drupal\image\Entity\ImageStyle;

  /**
   * Provides a 'Custom' Block
   *
   * @Block(
   *   id = "footermenu",
   *   admin_label = @Translation("Footer Menu"),
   * )
   */

  class CustomFooterMenuBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
			$first = true;
      $output = '<div class="container"><ul class="links">';
      $tree = \Drupal::menuTree()->load('footer', new \Drupal\Core\Menu\MenuTreeParameters());
      foreach ($tree as $item) {
				$title = $item->link->getTitle();
        $url_obj = $item->link->getUrlObject();
        $url_string = $url_obj->toString();
				
        if($item->link->isEnabled()) {
          if($title == 'cp' || $title == 'fb' || $title == 'twit' || $title == 'yt' || $title == 'instagm') {
						if($title == 'cp') {
							$title = '&copy; '. date('Y') . ' Protinex';
							$output .= '</ul><ul class="sublink">';
							$output .= '<li><a href="javascript:;">'. $title .'</a></li>';
						} else {
							if($first) {
								$output .= '</ul><ul class="socialLinks">';
								$first = false;
							}
							$output .= '<li class="'.$title.'"><a href="'. $url_string .'"></a></li>';
						}
					} else {
						$output .= '<li><a href="'. $url_string .'">' .$title. '</a></li>';
					}
				}
      }
      $output .= '</ul></div>';
      return array(
        '#markup' => $this->t($output),
        /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
        '#cache' => array(
            'max-age' => 0,
        ),
      );
    }
  }