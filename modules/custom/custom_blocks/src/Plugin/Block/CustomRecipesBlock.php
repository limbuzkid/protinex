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
	 *   id = "recipes_form",
	 *   admin_label = @Translation("Recipes Form"),
	 * )
	 */
	class CustomRecipesBlock extends BlockBase {
	
		/**
		 * {@inheritdoc}
		 */
		public function build() {
			$form = \Drupal::formBuilder()->getForm('Drupal\custom_blocks\Form\RecipesForm');
			return $form;
		}
	}