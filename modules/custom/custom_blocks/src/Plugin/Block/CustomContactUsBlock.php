<?php
	/**
	 * @file
	 * Contains \Drupal\custom_blocks\Plugin\Block\CustomContactUsBlock.
	 */
	
	namespace Drupal\custom_blocks\Plugin\Block;
	
	use Drupal\Core\Block\BlockBase;
	use Drupal\Core\Form\FormInterface;
	
	/**
	 * Provides a 'Custom' block.
	 *
	 * @Block(
	 *   id = "contact_us",
	 *   admin_label = @Translation("Contact Us"),
	 * )
	 */
	class CustomContactUsBlock extends BlockBase {
	
		/**
		 * {@inheritdoc}
		 */
		public function build() {
			$form = \Drupal::formBuilder()->getForm('Drupal\custom_blocks\Form\ContactUsForm');
			return $form;
		 }
	}