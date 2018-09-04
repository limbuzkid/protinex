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
   *   id = "recipes",
   *   admin_label = @Translation("Recipes"),
   * )
   */

  class CustomRecipes extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
			$output = '';
			
			$qry = \Drupal::entityQuery('node')
							->condition('status', 1)				
							->condition('type', "recipes");
			$node_count = $qry->count()->execute();
			
			$query = \Drupal::entityQuery('node')
						->condition('status', 1)
						->condition('type', 'recipes')
						->sort('created', 'DESC')
						->range(0,4);
			$nids = $query->execute();
			$nodes = entity_load_multiple('node', $nids);
			$first = true;
			$index = 1;
			//$count = 0;
			
			foreach($nodes as $node) {

					$image = file_create_url($node->field_image->entity->getFileUri());
					if($node->field_youtube_video_id->value) {
						$video = 'data-svg-video="http://www.youtube.com/embed/'. $node->field_youtube_video_id->value .'"';
					} else {
						$video = '';
					}
					//$image = '';
					if($first) {
						$output .= '<div class="commonListSection" data-scroll="active">';
						$first = false;
					} else {
						$output .= '<div class="commonListSection" data-scroll="">';
					}
					
					$output .= '<div class="columnMaster">
											<div class="column wid40">
													<div class="imgBox svgBox" data-svg="/themes/himalaya/images/Recipes'.$index.'.svg" '.$video.'">
															<img src="'. $image .'" alt="'. $node->title->value .'">
													</div>
											</div>
											<div class="column wid60 contentSec">
													<div class="topSec">
															<div class="titleSec">
																	<h2>'. $node->title->value .'</h2>
																	<em>Recipe submitted by <strong>'. $node->field_blogger_s_name->value .'</strong></em>
															</div>
															
															<h4 class="serving">Yields '. $node->field_yields->value .'</h4>
													</div>
													<div class="btmCont">'. $node->body->value .'</div>
											</div>
									</div>
							</div>';
					$index++;

			}
			
			if($node_count > 4) {
				$output .= '<div class="loadMoreSec">
											<a class="loadMore" href="javascript:;" rel="4" id="'.$node_count.'">Load More Recipes </a><span class="cntRcp">4 of '.$node_count.'</span>
										</div>';
			}
			return array(
				'#title' => $this->t('Recipes'),
				'#markup' => $this->t($output),
			);
	}
  }