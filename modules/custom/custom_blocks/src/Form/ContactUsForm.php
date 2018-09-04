<?php
  /**
    * @file
    * Contains \Drupal\custom_blocks\Form\ContactUsForm.
    */
  namespace Drupal\custom_blocks\Form;
  
  use Drupal\Core\Ajax\AjaxResponse;
  use Drupal\Core\Ajax\ChangedCommand;
  use Drupal\Core\Ajax\CssCommand;
  use Drupal\Core\Ajax\HtmlCommand;
  use Drupal\Core\Ajax\InvokeCommand;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\Core\Site\Settings;
  
  
  class ContactUsForm extends FormBase {
    /**
    * {@inheritdoc}
    */
    public function getFormId() {
      return 'contactForm';
    }
    
    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state) {
      
      $options = array('0' => 'Age');
      for($i=2;$i<=100;$i++) {
        $options[$i] = $i;
      }
      
      $form['age'] = array(
        '#type' => 'textfield',
        '#prefix' => '<div class="formBox"><div class="fieldBox wid100 requiredField"><div class="inputBox ageField">',
        '#suffix' => '<span id="ageErr" class="error"></span></div></div>',
        '#placeholder' => t('Age'),
        '#maxlength'  => '2',
        '#attributes' => array(
              'id' => 'ageofkid',
              'data-label' => "true",
        ),
      );
      
      $form['name'] = array(
        '#type' => 'textfield',
        '#prefix' => '<div class="fieldBox wid100 requiredField"><div class="inputBox nameField">',
        '#suffix' => '<span id="nameErr" class="error"></span></div></div>',
        '#placeholder' => t('Name'),
        '#attributes' => array(
              'id' => 'name',
              'data-label' => "true",
              //'data-validation' => "required,minLength3,alphaOnly" 
        ),
      );
      
      $form['email'] = array(
        '#type' => 'textfield',
        '#prefix' => '<div class="fieldBox wid100 requiredField"><div class="inputBox emailField">',
        '#suffix' => '<span id="mailErr" class="error"></span></div></div>',
        '#placeholder' => t('Email'),
        '#attributes' => array(
              'id' => 'email',
              'data-label' => "true",
              //'data-validation' => "required,email" 
        ),
      );
      
      $form['query'] = array(
        '#type' => 'textarea',
        '#prefix' => '<div class="fieldBox wid100 requiredField"><div class="textareaBox descField">',
        '#suffix' => '<span id="quesErr" class="error"></span></div></div>',
        '#placeholder' => t('Query'),
        '#attributes' => array(
              'id' => 'question',
              'data-label' => "true",
              //'data-validation' => "required,maxlength"
        ),
      );
      
      $form['submit'] = array(
        '#type' => 'button',
        '#value' => 'Submit',
        '#prefix' => '<div class="fieldBox wid100">',
        '#suffix' => '</div></div>',
        '#attributes' => array(
              'class' => array('btn', 'blue', 'center', 'fr'),
        ),
        '#ajax' => array(
          'callback' => '::contactCallback',
          //'callback' => 'Drupal\custom_blocks\Form\AskTheXpertForm::ajaxCallback',
          'effect' => 'fade',
          'event' => 'click',
          'progress' => array(
            'type' => 'throbber',
            'message' => 'Please wait',
          ),
        ),
        //'#id' => 'askFormSubmit',

      );
      
      return $form;
    }
    
    public function contactCallback(array &$form, FormStateInterface $form_state) {
      $error      = false;
      $age_err    = false;
      $name_err   = false;
      $mail_err   = false;
      $query_err  = false;
      $ajax_response = new AjaxResponse();
      
      if(trim($form_state->getValue('age')) == '') {
        $err_txt = 'Age is required';
        $age_err = true;
        $error = true;
      } else {
        if(!preg_match("/^[0-9]|[0-9]{2}$/", $form_state->getValue('age')) || $form_state->getValue('age') < 1) {
          $err_txt = 'Invalid Age';
          $age_err = true;
          $error = true;
        } 
      }
      
      if($age_err) {
        $css = ['opacity' => '1'];
        $ajax_response->addCommand(new CssCommand('.ageField .error', $css));
        $ajax_response->addCommand(new HtmlCommand('#ageErr', $err_txt));
      } else {
        $css = ['opacity' => '0'];
        $ajax_response->addCommand(new CssCommand('.ageField .error', $css));
        $ajax_response->addCommand(new HtmlCommand('#ageErr', ''));
      }
      
      
      if(trim($form_state->getValue('name')) == '') {
        $err_txt = 'Name is required';
        $name_err = true;
        $error = true;
      } else {
        if(!preg_match("/^([a-zA-Z.\']+\s?)*$/", $form_state->getValue('name'))) {
          $err_txt = 'Invalid Name';
          $name_err = true;
          $error = true;
        } 
      }
      if($name_err) {
        $css = ['opacity' => '1'];
        $ajax_response->addCommand(new CssCommand('.nameField .error', $css));
        $ajax_response->addCommand(new HtmlCommand('#nameErr', $err_txt));
      } else {
        $ajax_response->addCommand(new HtmlCommand('#nameErr', ''));
      }
      
      if(trim($form_state->getValue('email')) == '') {
        $err_txt = 'Email is required';
        $mail_err = true;
        $error = true;
      } else {
        //if (!\Drupal::service('email.validator')->isValid(trim($form_state->getValue('email')))) {
        if (filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL) === false) {  
          $err_txt = 'Invalid Email';
          $mail_err = true;
          $error = true;
        }
      }
      
      if($mail_err) {
        $css = ['opacity' => '1'];
        $ajax_response->addCommand(new CssCommand('.emailField .error', $css));
        $ajax_response->addCommand(new HtmlCommand('#mailErr', $err_txt));
      } else {
        $ajax_response->addCommand(new HtmlCommand('#mailErr', ''));
      }
      
      
      
      if(trim($form_state->getValue('query')) == '') {
        $err_txt = 'Query is required';
        $query_err = true;
        $error = true;
      } else {
        if(!preg_match("/^[a-zA-Z0-9,.!?#\-:& *'\"\/\\\ ()\[\]]*$/", $form_state->getValue('query'))) {
          $err_txt = 'Invalid characters in Query';
          $query_err = true;
          $error = true;
        }
      }
      
      if($query_err) {
        $css = ['opacity' => '1'];
        $ajax_response->addCommand(new CssCommand('.descField .error', $css));
        $ajax_response->addCommand(new HtmlCommand('#quesErr', $err_txt));
      } else {
        $ajax_response->addCommand(new HtmlCommand('#quesErr', ''));
      }
      
      if(!$error) {
        $name = $form_state->getValue('name');
        $desc = $form_state->getValue('query');
        $mail = $form_state->getValue('email');
        $age  = $form_state->getValue('age');
        
        $request_time = \Drupal::time()->getRequestTime();
        
        $query = \Drupal::database()->insert('contact_us');
        $query->fields(['name','email', 'age', 'feedback','created']);
        $query->values([$name, $mail, $age, $desc, $request_time]);
        $query->execute();
        
        $msg = '<div class="mssage">Thank you <span>for the query. We will get back to you soon</span></div>';
        
        $send_mail = new \Drupal\Core\Mail\Plugin\Mail\PhpMail(); // this is used to send HTML emails
        $from = 'from@gmail.com';
        //$to = $mail;
        $to = Settings::get('admin_email', NULL);
        $message['headers'] = array(
          'content-type' => 'text/html',
          'MIME-Version' => '1.0',
          'reply-to' => $from,
          'from' => 'Protinex Team <'.$from.'>'
        );
        $message['to'] = $to;
        $message['subject'] = $name . " has posted a feedback on protinex.com";
         
        $message['body'] = 'Hello, Thank you for reading this blog.';
         
        $send_mail->mail($message);
        
        
        $ajax_response->addCommand(new HtmlCommand('.contactform', $msg));
      }
      
      //print_r($ajax_response);
      
      // ValCommand does not exist, so we can use InvokeCommand.
      //$ajax_response->addCommand(new InvokeCommand('#edit-user-name', 'val' , array($random_user->get('name')->getString())));
      
      // ChangedCommand did not work.
      //$ajax_response->addCommand(new ChangedCommand('#edit-user-name', '#edit-user-name'));
      
      // We can still invoke the change command on #edit-user-name so it triggers Ajax on that element to validate username.
      //$ajax_response->addCommand(new InvokeCommand('#edit-user-name', 'change'));
      
      // Return the AjaxResponse Object.
      return $ajax_response;
    }
    
    public function ajaxCallback(array &$form, FormStateInterface $form_state) {
      return $form;
    }
    
    public function submitForm(array &$form, FormStateInterface $form_state) {
      /*$name = $form_state->getValue('name');
      $description = $form_state->getValue('description');
      $email = $form_state->getValue('email');*/
      
      //$message = t('Your information has been successfully submitted.') ;
      //drupal_set_message($msg);
    }
    

  
  }
    
  