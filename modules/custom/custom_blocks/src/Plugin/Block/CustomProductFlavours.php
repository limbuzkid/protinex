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
   *   id = "product_flavours",
   *   admin_label = @Translation("Product-Available Flavours"),
   * )
   */

  class CustomProductFlavours extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
			$query = \Drupal::database()->delete('taxonomy_term__field_brand_tvcs');
			$query->condition('field_brand_tvcs_value', '', '=');
			$query->execute();
			$term_id = \Drupal::routeMatch()->getRawParameter('taxonomy_term');
			$term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term_id);
			$flavour = $term->field_flavour->value;
			$output = '';
			$query = \Drupal::entityQuery('node')
						->condition('status', 1)
						->condition('type', 'product')
						->condition('field_product_category', $term_id)
						->sort('created', 'DESC');
			$nids = $query->execute();
			$nodes = entity_load_multiple('node', $nids);
			$first = true;
			$index = 1;
			$count = 0;
			$item = '';
			
			/*foreach($nodes as $node) {
				echo '<div class="dnone"><pre>';
				print_r($node);
				echo '</div></pre>';
			}*/

			foreach($nodes as $node) {
				if(trim($flavour) == trim($node->title->value)) {
					$image_url = $term->field_menu_image->entity->getFileUri();
					$image = file_create_url($image_url);
					$style = ImageStyle::load('thumbnail');  // Load the image style configuration entity.
					$uri = $style->buildUri($image_url);
					$url = $style->buildUrl($image_url);
					/*$output .= '<div class="productItem" data-productName="'.$term->field_flavour->value.'" data-productLink="'.$term->field_amazon_link->value.'" data-colorType="'.$node->field_wave_color->value.'">
									<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="553px" height="461px" viewBox="0 0 553 461" enable-background="new 0 0 553 461" xml:space="preserve">
									<g class="productLayer"><image overflow="visible" width="291" height="374" class="productLayer_Image" xlink:href="'.$image.'"  transform="matrix(1 0 0 1 129 0)"></image></g>
									<g class="gradientLayer"><image overflow="visible" width="266" height="87" class="gradientLayer_Image" xlink:href="/themes/himalaya/images/gradient.png"  transform="matrix(1 0 0 1 149 287)"></image></g>
									<g class="splashLayer"><image overflow="visible" width="553" height="338" class="splashLayer_Image" xlink:href="'.$splash_image.'"  transform="matrix(1 0 0 1 0 123)"></image></g>
									</svg></div>';*/
					$first_item = '<div class="item active"><a href="javascript:;"><img src="'.$url.'" alt="'.$term->field_flavour->value.'">
										<h4>'. $term->field_flavour->value .'</h4></a></div>';
				} else {
					$image_url = $node->field_image->entity->getFileUri();
					$image = file_create_url($image_url);
					$splash_image = file_create_url($node->field_wrapper_image->entity->getFileUri());
					$style = ImageStyle::load('thumbnail');  // Load the image style configuration entity.
					$uri = $style->buildUri($image_url);
					$url = $style->buildUrl($image_url);
					$output .= '<div class="productItem" data-productName="'.$node->title->value.'" data-productLink="'.$node->field_amazon->value.'" data-colorType="'.$node->field_wave_color->value.'">
									<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="553px" height="461px" viewBox="0 0 553 461" enable-background="new 0 0 553 461" xml:space="preserve">
									<g class="productLayer"><image overflow="visible" width="291" height="374" class="productLayer_Image" xlink:href="'.$image.'"  transform="matrix(1 0 0 1 129 0)"></image></g>';
									
					//$output .= '<g class="gradientLayer"><image overflow="visible" width="266" height="87" class="gradientLayer_Image" xlink:href="/themes/himalaya/images/gradient.png"  transform="matrix(1 0 0 1 149 287)"></image></g>';
					$output .= '<g class="splashLayer"><image overflow="visible" width="553" height="338" class="splashLayer_Image" xlink:href="'.$splash_image.'"  transform="matrix(1 0 0 1 0 123)"></image></g>
									</svg></div>';
					$item .= '<div class="item"><a href="javascript:;"><img src="'.$url.'" alt="'.$node->title->value.'">
										<h4>'. $node->title->value .'</h4></a></div>';
				}
			}
			

			$output .= '</div><div class="productNameLink">
							<h2 style="color: rgb(120, 51, 129);">'.$term->field_flavour->value.'</h2>
							<a href="javascript:;" class="btn" style="background-color: rgb(120, 51, 129);" target="_blank">Buy on <img src="/themes/himalaya/images/amazon.png" alt=""></a>
						</div>
					</div>
				</div>';
			$output .= '<div class="column wid60 contentSec">
									<div class="btmCont">
									<h3>Available Flavours</h3>
									<div class="availFlavCarousel owl-carousel">'.$first_item . $item.'</div>';
									
			//echo $output;
			return array(
				'#title' => $this->t('Product'),
				'#markup' => $this->t($output),
				'#cache' => array(
            'max-age' => 0,
        ),
			);
	}
  }
	

	