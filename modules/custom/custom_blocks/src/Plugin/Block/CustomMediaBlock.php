<?php
	/**
	 * @file
	 * Contains \Drupal\custom_blocks\Plugin\Block\CustomMediaBlock.
	 */
	
	namespace Drupal\custom_blocks\Plugin\Block;
	
	use Drupal\Core\Block\BlockBase;
	use Drupal\Core\Form\FormInterface;
	
	/**
	 * Provides a 'Custom' block.
	 *
	 * @Block(
	 *   id = "media",
	 *   admin_label = @Translation("Media"),
	 * )
	 */
	class CustomMediaBlock extends BlockBase {
	
		/**
		 * {@inheritdoc}
		 */
		public function build() {
			$output = '<div class="media-wrap"><ul>';
			
			$query = \Drupal::entityQuery('node')
						->condition('status', 1)
						->condition('type', 'article')
						->sort('field_published_date', 'DESC')
						->pager(16);
						
			$nids = $query->execute();
			$nodes = entity_load_multiple('node', $nids);

			foreach($nodes as $node) {			
				//$shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 25));
				$published = date('d F Y', strtotime($node->field_published_date->value));
				$output .= '<li><p class="article">'. $node->field_tags->entity->name->value .'</p>';
				//$output .= '<img src="'. file_create_url($node->field_image->entity->uri->value) .'" alt="'. $node->body->value .'" srcset="">';
				$output .= '<h3>'. $node->title->value .'</h3><p class="mediaName">'. $node->body->value .'</p>
										<p class="date">'. $published.'</p><a href="'. $node->field_blog_url->value .'" class="readmore" target="_blank">Read More</a></li>';
			}
			$output .= '</ul></div>';
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