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
   *   id = "mainmenu",
   *   admin_label = @Translation("Main Menu"),
   * )
   */

  class CustomMenuBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
			$output = '';
      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', "product")
						->sort('weight', 'ASC');
      $tids = $query->execute();
      $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
      $first = true;
      $product = '';
			$desc = '';
      foreach($terms as $term) {
        $path = \Drupal::service('path.alias_manager')->getAliasByPath('/taxonomy/term/'.$term->tid->value);
        $product .= '<li><a href="'. $path .'">'. $term->name->value .'</a></li>';
        $original_image = $term->field_menu_image->entity->getFileUri();
        $style = ImageStyle::load('prod_thumb');  // Load the image style configuration entity.
        $uri = $style->buildUri($original_image);
        $url = $style->buildUrl($original_image);
        if($first) {
          $first = false;
          $desc .= '<div class="productItem show"><div class="imgBox withShadow">
											<img src="'.$url.'" alt="'.$term->name->value.'" /></div>'.$term->field_short->value.'</div>';
        } else {
          $desc .= '<div class="productItem"><div class="imgBox withShadow">
											<img src="'.$url.'" alt="'.$term->name->value.'" /></div>'.$term->field_short->value.'</div>';
        }
      }
			$output .= '<ul class="mainNavigation productList">
										<li>
											<a href="javascript:;">Products</a>
											<div class="subNav">
												<div class="productView tcenter vCenter imgCarousel">'. $desc .'</div>
												<ul>'.$product.'</ul>
											</div>
										</li>
									</ul>';
			
      $output .= '<ul class="mainNavigation">';
      $tree = \Drupal::menuTree()->load('main', new \Drupal\Core\Menu\MenuTreeParameters());
      foreach ($tree as $item) {
        if($item->link->isEnabled()) {
          $title = $item->link->getTitle();
          $url_obj = $item->link->getUrlObject();
          $url_string = $url_obj->toString();
          if($item->hasChildren) {
            $output .= '<li><a href="javascript:;">'.$title.'</a><ul class="subNav">';
            foreach($item->subtree as $child) {
              if($child->link->isEnabled()) {
                $level_next_title = $child->link->getTitle();
                $level_next_url = $child->link->getUrlObject()->toString();
                if($child->hasChildren) {
                  $output .= '<li><a href="javascript:;">'.$level_next_title.'</a><ul class="subNav">';
                  foreach($child->subtree as $gchild) {
                    if($gchild->link->isEnabled()) {
                      $gchild_title = $gchild->link->getTitle();
                      $gchild_link = $gchild->link->getUrlObject()->toString();
                      $output .= '<li><a href="'.$gchild_link.'">'.$gchild_title.'</a></li>';
                    }
                  }
                  $output .= '</ul></li>';
                } else {
                  $output .= '<li><a href="'.$level_next_url.'">'.$level_next_title.'</a></li>';
                }
              }
            }
            $output .= '</ul></li>';
          } else {
            $output .= '<li><a href="'.$url_string.'">'.$title.'</a></li>';
          }
        }
      }
      $output .= '</ul>';
			
      return array(
        '#markup' => $this->t($output),
        /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
        '#cache' => array(
            'max-age' => 0,
        ),
      );
    }
  }