<?php
	/**
	 * @file
	 * Contains \Drupal\custom_blocks\Plugin\Block\CustomAskTheXpertBlock.
	 */
	
	namespace Drupal\custom_blocks\Plugin\Block;
	
	use Drupal\Core\Block\BlockBase;
	use Drupal\Core\Form\FormInterface;
	
	/**
	 * Provides a 'Custom' block.
	 *
	 * @Block(
	 *   id = "ask_the_expert",
	 *   admin_label = @Translation("Ask the Expert"),
	 * )
	 */
	class CustomAskTheXpertBlock extends BlockBase {
	
		/**
		 * {@inheritdoc}
		 */
		public function build() {
			$form = \Drupal::formBuilder()->getForm('Drupal\custom_blocks\Form\AskTheXpertForm');
			return $form;
		}
	}