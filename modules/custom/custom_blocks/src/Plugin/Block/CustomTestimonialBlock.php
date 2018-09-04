<?php
	/**
	 * @file
	 * Contains \Drupal\custom_blocks\Plugin\Block\CustomTestimonialBlock.
	 */
	
	namespace Drupal\custom_blocks\Plugin\Block;
	
	use Drupal\Core\Block\BlockBase;
	use Drupal\Core\Form\FormInterface;
	
	/**
	 * Provides a 'Custom' block.
	 *
	 * @Block(
	 *   id = "testimonial_form",
	 *   admin_label = @Translation("Testimonial Form"),
	 * )
	 */
	class CustomTestimonialBlock extends BlockBase {
	
		/**
		 * {@inheritdoc}
		 */
		public function build() {
			
			$qry = \Drupal::entityQuery('node')
							->condition('status', 1)				
							->condition('type', "testimonials");
			$node_count = $qry->count()->execute();
			
			$first = true;
      $output = '<section class="section testimonial inner">
        <div class="container">
            <div class="columnMaster">';
      $query = \Drupal::entityQuery('node')
						->condition('status', 1)
						->condition('type', 'testimonials')
						->sort('created', 'DESC')
						->range(0,4);
			$nids = $query->execute();
			$nodes = entity_load_multiple('node', $nids);
			$index = 1;
      foreach ($nodes as $node) {
					if($index > 4) {
						$index = 1;
					}
					if($first) {
						$active = 'active';
						$first = false;
					} else {
						$active = '';
					}
					if($node->field_image->target_id) {
						$image = file_create_url($node->field_image->entity->getFileUri());
					} else {
						$image = '/themes/himalaya/images/default.png';
					}
					
					$output .= '<div class="column wid50 tCenter"  data-scroll="'.$active.'">
												<div class="testimonialContent">
													<div class="testimonialData">
														<div class="imgBox svgBox" data-svg="/themes/himalaya/images/Recipes'.$index.'.svg"><img src="'.$image.'" alt="Testimonial"></div>
														<h3>'.$node->title->value.'</h3>
														<div class="content"><p>'. $node->body->value .'</p></div>
													</div>
												</div>
											</div>';
					$index++;
      }
			if($node_count > 4) {
				$output .= '<div class="loadMoreSec"><a class="loadMoreTest" href="javascript:;" rel="4" id="'.$node_count.'">Load More</a><span class="cntRcp">4 of '.$node_count.'</span></div>';
			}
			$output .= '</div></div></section>';
			
			//$form = \Drupal::formBuilder()->getForm('Drupal\custom_blocks\Form\TestimonialForm');
			//return $form;

			$build['result'] = array(
					'#markup' => $this->t($output),
			);
			/*return array(
        '#markup' => $this->t($output),
         If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line 
        '#cache' => array(
            'max-age' => 0,
        ),
      );*/
			
			$build['form'] = \Drupal::formBuilder()->getForm('Drupal\custom_blocks\Form\TestimonialForm');
			return $build;
		}
	}