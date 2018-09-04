<?php

  /**
   * @file
   * Contains Drupal\protinex\CalculatorDataForm
   */

  namespace Drupal\protinex\Form;

  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;

  class CalculatorDataForm extends FormBase {

    public function getFormId() {
      return 'calc_data_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
      $form['file'] = array(
        '#type' => 'managed_file',
        '#title' => t('Import Protein Calculator Data'),
        '#upload_validators' => array(
            'file_validate_extensions' => array('csv'),
            'file_validate_size' => array(25600000),
        ),
        '#upload_location' => 'public://protinex',
        '#required' => TRUE,
       );
      
      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Import',
      );
      return $form;
    }
  
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $file = $form_state->getValue('file');
      
      $file = \Drupal\file\Entity\File::load($file[0]);
      $fid = $file->fid->value;


      $temp = str_replace('public://protinex', '',  $file->uri->value);
      
      $file_path = getcwd(). '/sites/default/files/protinex'. $temp;
      
      if($handle = fopen($file_path, 'r')) {
        //$row = fgetcsv($handle);
        db_truncate('calculator_data')->execute();
        while ($row = fgetcsv($handle)) {
          if($row[0] != 'Number') {
            if($row[2] != '' && $row[3] != '' && $row[4] != '') {
              $query = \Drupal::database()->insert('calculator_data');
              $query->fields(['food_item', 'portion_quantity', 'portion', 'protein', 'type']);
              $query->values([$row[1], $row[2], $row[3], $row[4], 'Breakfast']);
              $query->execute();
            }
          }
        }
      }
      drupal_set_message('File uploaded');
    }
  
  }