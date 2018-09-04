<?php
  namespace Drupal\custom_blocks\Plugin\Block;
  
  use Drupal\Core\Block\BlockBase;
  use Drupal\Component\Annotation\Plugin;
  use Drupal\Core\Annotation\Translation;
  use Drupal\Core\Url;
  use Drupal\Core\Link;
	use Drupal\Core\Entity\Query;


  /**
   * Provides a 'Custom' Block
   *
   * @Block(
   *   id = "faqs",
   *   admin_label = @Translation("FAQs"),
   * )
   */

  class CustomFaqsBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
			$output = '<div class="column wid33">';
			$qry = db_query('SELECT * FROM prt_node_field_data WHERE type=:arg1 AND status=:arg2', array(':arg1' => 'faqs', ':arg2' => '1'));
			$qry->allowRowCount = TRUE;
			$count = $qry->rowCount();
			
			
			$query = \Drupal::entityQuery('node');
			$query->condition('status', NODE_PUBLISHED)
						->condition('type', 'faqs')
						->sort('nid', 'ASC');
			//$count 	= $query->count()->execute();
			$nids 	= $query->execute();

			$nodes 	= entity_load_multiple('node', $nids);
			$row_count = ceil($count/3);
			$cnt = 1;
			foreach($nodes as $node) {
				if($cnt > $row_count) {
					$output .= '</div><div class="column wid33">';
					$cnt = 1;
				}
				$output .= '<div class="faq"><h2>'. $node->title->value .'</h2><div class="ans">'. $node->body->value .'</div></div>';
				$cnt++;
			}
			
			$output .= '</div>';
			
      //echo $count; exit;
      return array(
        '#markup' => $this->t($output),
        /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
        '#cache' => array(
            'max-age' => 0,
        ),
      );
    }
  }