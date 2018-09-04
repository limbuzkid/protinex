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
   *   id = "Home Product",
   *   admin_label = @Translation("Home Product"),
   * )
   */

  class CustomHomeProductBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
      $output = '<div class="productCarousel owl3D">';
      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', "product")
						->sort('weight', 'ASC');
			$tids = $query->execute();
      $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);

      foreach ($terms as $term) {
        $path = \Drupal::service('path.alias_manager')->getAliasByPath('/taxonomy/term/'.$term->tid->value);
        $product = '<li><a href="'. $path .'">'. $term->name->value .'</a></li>';
        $image = file_create_url($term->field_menu_image->entity->getFileUri());
        $splash_image = file_create_url($term->field_splash_image->entity->getFileUri());
        $output .= '<div class="productItem" data-productName="'.$term->name->value.'" data-productLink="'.$path.'" data-colorType="'.$term->field_color->value.'">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="553px"
							 height="461px" viewBox="0 0 553 461" enable-background="new 0 0 553 461" xml:space="preserve">
						<g class="productLayer">
								<image overflow="visible" width="291" height="374" class="productLayer_Image" xlink:href="'.$image.'"  transform="matrix(1 0 0 1 129 0)">
							</image>
						</g>';
				/*$output .= '<g class="gradientLayer">
								<image overflow="visible" width="266" height="87" class="gradientLayer_Image" xlink:href="/themes/himalaya/images/gradient.png"  transform="matrix(1 0 0 1 149 287)">
							</image>
						</g>';*/
				$output .= '<g class="splashLayer">
								<image overflow="visible" width="553" height="338" class="splashLayer_Image" xlink:href="'.$splash_image.'"  transform="matrix(1 0 0 1 0 123)">
							</image>
						</g>
					</svg>
			    </div>';
      }
      $output .= '</div>';
      return array(
        '#markup' => $this->t($output),
        /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
        '#cache' => array(
            'max-age' => 0,
        ),
      );
    }
  }