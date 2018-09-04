<?php

  /**
   * @file
   * Contains Drupal\protinex\UploadLogoForm
   */

  namespace Drupal\protinex\Form;

  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;

  class UploadLogoForm extends FormBase {

    public function getFormId() {
      return 'danone_logo_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
      $form['danone'] = array(
        '#type' => 'managed_file',
        '#title' => t('Danone Logo'),
        '#upload_validators' => array(
            'file_validate_extensions' => array('gif png jpg jpeg'),
            'file_validate_size' => array(25600000),
        ),
        '#upload_location' => 'public://protinex',
        '#required' => TRUE,
       );
      
      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Upload Danone Logo',
      );
      return $form;
    }
  
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $image = $form_state->getValue('danone');
      
      $file = \Drupal\file\Entity\File::load($image[0]);
      $fid = $file->fid->value;

      $file->setPermanent();
      $file->save();
      
      $query = \Drupal::database()->select('danone','d');
      $query->addField('d', 'fid');
      $query->range(0, 1);
      $found = $query->execute()->fetchField();

      if($found) {
        $query = \Drupal::database()->update('danone');
        $query->fields(['fid' => $fid]);
        $query->condition('fid', $found);
      } else {
        $query = \Drupal::database()->insert('danone');
        $query->fields(['fid']);
        $query->values([$fid]);
      }
      $query->execute();
      drupal_set_message('File uploaded');
    }
  
  }