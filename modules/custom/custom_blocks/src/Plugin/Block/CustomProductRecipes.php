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
   *   id = "product_recipes",
   *   admin_label = @Translation("Product- Recipes"),
   * )
   */

  class CustomProductRecipes extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
			$term_id = \Drupal::routeMatch()->getRawParameter('taxonomy_term');
			$output = '<div class="btmCont chilCarsl owl-carousel vCenter">';
			$query = \Drupal::entityQuery('node')
						->condition('status', 1)
						->condition('type', 'recipes')
						->condition('field_product_category', $term_id)
						->sort('created', 'DESC');
						
			$nids = $query->execute();
			$nodes = entity_load_multiple('node', $nids);
			$first = true;

			foreach($nodes as $node) {
				$image_url = $node->field_image->entity->getFileUri();
				$image = file_create_url($image_url);
				//$splash_image = file_create_url($node->field_wrapper_image->entity->getFileUri());
				$output .= '<div class="item"><div class="column wid40">
										<div class="imgBox svgBox" data-svg="/themes/himalaya/images/Recipes1.svg">
										<img src="'.$image.'" alt="'.$node->title->value.'">
										</div>
										</div>
										<div class="column wid60 contentSec">
											<h4>' .$node->title->value .'</h4>
										</div>
										</div>'; 
			}
			//echo $output; exit;

			$output .= '</div>';

			return array(
				'#title' => $this->t('Product Recipes'),
				'#markup' => $this->t($output),
				'#cache' => array(
            'max-age' => 0,
        ),
			);
		}
  }
	
	