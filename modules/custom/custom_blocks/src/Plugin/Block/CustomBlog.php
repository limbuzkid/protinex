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
   *   id = "blog",
   *   admin_label = @Translation("Blog"),
   * )
   */

  class CustomBlog extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
			$first = true;
			$output = '';
			$query = \Drupal::entityQuery('node')
						->condition('status', 1)
						->condition('type', 'blogs')
						->condition('field_featured_blog', 1, '=')
						->sort('field_published_date', 'DESC')
						->range(0,3);
			$nids = $query->execute();
			$nodes = entity_load_multiple('node', $nids);
			
			foreach($nodes as $node) {
				$term = \Drupal\taxonomy\Entity\Term::load($node->get('field_product_category')->target_id);
				if($node->field_image->target_id) {
					$image_url = file_create_url($node->field_image->entity->getFileUri());
				} else {
					$image_url = 'javascript:;';
				}
				$output .= '<div class="columnMaster blogBox vCenter" data-scroll="active">
											<div class="column wid30 tCenter">
												<div class="imgBox svgBox" data-svg="/themes/himalaya/images/Recipes2.svg"><img src="'. $image_url.'" alt="blog"> </div>
											</div>
											<div class="column wid70">
												<div class="blogContent">
													<h2>'.$term->name->value.'</h2>
													<h3>'. $node->title->value .'</h3>
													<p class="blogDetails">
														<span>Blogger</span>&nbsp; <strong>'.$node->field_blogger_s_name->value.' (from '.$node->field_blogger_s_website->value.')</strong> | '.$node->field_published_date->value.'
													</p>'.$node->body->value .'<a href="'.$node->field_blog_url->value.'" class="btn yellow" target="_blank">Read More</a>
												</div>
											</div>
										</div>';
			}
			
			$output .= '</div></div></section>
									<section class="section blogMaster twoColumn">
										<div class="container"><div class="blogArea">';
			
			$query = \Drupal::entityQuery('node')
						->condition('status', 1)
						->condition('type', 'blogs')
						->condition('field_featured_blog', 1, '<>')
						->sort('field_published_date', 'DESC')
						->pager(4);
			$nids = $query->execute();
			$nodes = entity_load_multiple('node', $nids);
			$index = 1;
			
			foreach($nodes as $node) {
				$term = \Drupal\taxonomy\Entity\Term::load($node->get('field_product_category')->target_id);
				if($node->field_image->target_id) {
					$image_url = file_create_url($node->field_image->entity->getFileUri());
				} else {
					$image_url = '';
				}
				if($index > 4) { $index = 1; }
				//if(strlen($node->title->value > 50)) {
					$shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 45)) . '...';
				//} else {
					//$shortdesc = $node->title->value;
				//}
				$output .= '<div class="columnMaster blogBox vCenter" data-scroll=""><div class="column wid40 tCenter">
										<div class="imgBox svgBox" data-svg="/themes/himalaya/images/Recipes'.$index.'.svg"><img src="'.$image_url.'" alt="blog"> </div></div>
										<div class="column wid60"><div class="blogContent">
										<h2>'.$term->name->value.'</h2><h3>'.$shortdesc.'</h3>
										<p class="blogDetails">
										<span>Blogger</span>&nbsp; <strong>'.$node->field_blogger_s_name->value.'('.$node->field_blogger_s_website->value.')</strong> | '.$node->field_published_date->value.'
										</p>'. $node->body->value .'<a href="'.$node->field_blog_url->value.'" class="btn red" target="_blank">Read More</a>
										</div></div></div>';			
				$index++;
			}

			$mark_up = array(
          '#markup' => $output,
      );
      $build['result'] = $mark_up;
      $build['pager'] = [
        '#type' => 'pager',
      ];
      return $build;
		}
  }