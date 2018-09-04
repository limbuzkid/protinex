<?php
  /**
    * @file
    * Contains \Drupal\custom_blocks\Form\RecipesForm.
    */
  namespace Drupal\custom_blocks\Form;
  
  use Drupal\Core\Ajax\AjaxResponse;
  use Drupal\Core\Ajax\ChangedCommand;
  use Drupal\Core\Ajax\CssCommand;
  use Drupal\Core\Ajax\HtmlCommand;
  use Drupal\Core\Ajax\InvokeCommand;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\file\Entity\File;
  use Drupal\node\Entity\Node;
  
  
  class RecipesForm extends FormBase {
    /**
    * {@inheritdoc}
    */
    public function getFormId() {
      return 'recipes_form';
    }
    
    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $form['Recipe'] = array(
        '#type' => 'textarea',
        '#prefix' => '<div class="formBox"><div class="fieldBox wid66 requiredField"><div class="textareaBox descFld">',
        '#suffix' => '<span class="txtErr error">Recipe is required.</span></div></div><div class="fieldBox wid33"><div class="upload-btn"><div class="btn gray">Image Upload +</div><p></p>',
        '#required' => TRUE,
        '#placeholder' => t('Recipe'),
        '#attributes' => array(
          'id' => 'name',
          'data-label' => 'true',
          'data-validation' => 'required'
        ),
      );
      
      $form['image'] = array(
        '#type' => 'managed_file',
        '#suffix' => '<span class="uploadErr error"></span></div></div>',
        '#placeholder' => t('Upload'),
        '#upload_location' => 'public://protinex/testimonials',
        '#attributes' => array(
          'id' => 'upload',
          'data-label' => 'true',
        ),
       );
      $form['Name'] = array(
        '#type' => 'textfield',
        '#required' => TRUE,
        '#prefix' => '<span class="uploadErr error"></span></div></div><i class="clear"></i><div class="fieldBox wid33 requiredField"><div class="inputBox nameFld">',
        '#suffix' => '<span class="nameErr error">Name is required.</span></div></div>',
        '#maxlength' => 30,
        '#placeholder' => t('Name'),
        '#attributes' => array(
          'id' => 'name',
          'data-label' => 'true',
          
        ),
        //<input name="Name" id="name" data-label="true" type="text" placeholder="Name" data-validation="required,minLength3" />

      );
      $form['Mobile'] = array(
        '#type' => 'textfield',
        '#required' => TRUE,
        '#maxlength' => 15,
        '#prefix' => '<div class="fieldBox wid33 requiredField"><div class="inputBox mobFld">',
        '#suffix' => '<span class="mobErr error">Mobile Number is required.</span></div></div>',
        '#placeholder' => t('Mobile Number'),
        '#attributes' => array(
          'id' => 'contact',
          'data-label' => 'true',
        ),
      );
      $form['Email'] = array(
        '#type' => 'textfield',
        '#required' => TRUE,
        '#maxlength' => 100,
        '#prefix' => '<div class="fieldBox wid33 requiredField"><div class="inputBox mailFld">',
        '#suffix' => '<span class="mailErr error">Email is required.</span></div></div>',
        '#placeholder' => t('Email ID'),
        '#attributes' => array(
          'id' => 'email',
          'data-label' => 'true',
        ),
      );
      
      
      $form['submit'] = array(
        '#type' => 'button',
        '#value' => 'Submit',
        '#prefix' => '<div class="fieldBox wid100 tCenter">',
        '#suffix' => '</div></div>',
        '#attributes' => array(
            'class' => ['btn', 'red', 'center']
        ),
        '#ajax' => array(
          'callback' => '::recipesCallback',
          'effect' => 'fade',
          'event' => 'click',
          'progress' => array(
            'type' => 'throbber',
            'message' => 'Please wait',
          ),
        ),
      );
      return $form;
    }
    
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $message = t('Your information has been successfully submitted.') ;
      drupal_set_message($message);
    }
    
    public function recipesCallback(array &$form, FormStateInterface $form_state) {
      $error    = false;
      $err_txt  = '';
      $name_err = false;
      $mail_err = false;
      $mob_err  = false;
      $desc_err = false;
      $name     = trim($form_state->getValue('Name'));
      $desc     = trim($form_state->getValue('Recipe'));
      $mail     = trim($form_state->getValue('Email'));
      $contact  = trim($form_state->getValue('Mobile'));
      if(isset($form_state->getValue('image')['fids'])) {
        $fid = trim($form_state->getValue('image')['fids'][0]);
      } else {
        $fid = '';
      }
      
      $ajax_response = new AjaxResponse();
      
      if($name == '') {
        $err_txt = 'Name is required.';
        $name_err = true;
        $error = true;
      } else {
        if(!preg_match("/^([a-zA-Z.\']+\s?)*$/", $name)) {
          $name_err = true;
          $err_txt = 'Invalid Name.';
          $error = true;
        }
      }
      if($name_err) {
        $css = ['opacity' => '1'];
        $ajax_response->addCommand(new CssCommand('.nameFld .error', $css));
        $ajax_response->addCommand(new HtmlCommand('.nameErr', $err_txt));
      } else {
        $css = ['opacity' => '0'];
        $ajax_response->addCommand(new CssCommand('.nameFld .error', $css));
        $ajax_response->addCommand(new HtmlCommand('.nameErr', ''));
      }
      
      if($contact == '') {
        $err_txt = 'aaaaMobile Number is required.';
        $mob_err = true;
        $error = true;
      } else {
        $contact = (int)$contact;
        if (!preg_match("/^[0-9]{7,15}$/", $contact) || ($contact < 1000000000 || $contact > 999999999999999)) {
          $err_txt = 'Invalid Mobile Number.';
          $mob_err = true;
          $error = true;
        }
      }
      
      if($mob_err) {
        $css = ['opacity' => '1'];
        $ajax_response->addCommand(new CssCommand('.mobFld .error', $css));
        $ajax_response->addCommand(new HtmlCommand('.mobErr', $err_txt));
      } else {
        $css = ['opacity' => '0'];
        $ajax_response->addCommand(new CssCommand('.mobFld .error', $css));
        $ajax_response->addCommand(new HtmlCommand('.mobErr', ''));
      }
      
      if($mail == '') {
        $err_txt = 'Email ID is required.';
        $mail_err = true;
        $error = true;
      } else {
        if (filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {
          $err_txt = 'Invalid Email ID.';
          $mail_err = true;
          $error = true;
        }
      }
      
      if($mail_err) {
        $css = ['opacity' => '1'];
        $ajax_response->addCommand(new CssCommand('.mailFld .error', $css));
        $ajax_response->addCommand(new HtmlCommand('.mailErr', $err_txt));
      } else {
        $css = ['opacity' => '0'];
        $ajax_response->addCommand(new CssCommand('.mailFld .error', $css));
        $ajax_response->addCommand(new HtmlCommand('.mailErr', ''));
      }
      
      if($desc == '') {
        $err_txt = 'Recipe is required.';
        $desc_err = true;
        $error = true;
      } else {
        if(!preg_match("/^[a-zA-Z0-9,.!?#\-:& *'\"\/\\\ ()\[\]]*$/", $form_state->getValue('$desc'))) {
          $err_txt = 'Invalid characters in string.';
          $desc_err = true;
          $error = true;
        }
      }
      
      if($desc_err) {
        $css = ['opacity' => '1'];
        $ajax_response->addCommand(new CssCommand('.descFld .error', $css));
        $ajax_response->addCommand(new HtmlCommand('.txtErr', $err_txt));
      } else {
        $css = ['opacity' => '0'];
        $ajax_response->addCommand(new CssCommand('.descFld .error', $css));
        $ajax_response->addCommand(new HtmlCommand('.txtErr', ''));      
      }
      if(!$error) {
        $node = Node::create(['type' => 'recipes']);
        $node->set('title', $name);
        $body = [
          'value' => $desc, 
          'format' => 'basic_html',
        ];
        $node->set('body', $body);
        $node->set('uid', 0);
        $node->set('field_blogger_s_name', $name);
        $node->set('field_contact', $contact);
        $node->set('field_email', $mail);
        if($fid) {
          $node->set('field_image', $fid);
        }
        $node->status = 0;
        $node->enforceIsNew();
        if($node->save()) {
          if($fid != '') {
            $image = $form_state->getValue('image');
            $file   = File::load($image[0]);
            if (!empty($file)) {
              $file->setPermanent();
              $file->save();
            }
          }
        }
        $msg = '<p class="mssage">Thank you <span>for the Recipe. Your Recipe is awaiting Admin\'s approval.</span></p>';
        
        $send_mail = new \Drupal\Core\Mail\Plugin\Mail\PhpMail(); // this is used to send HTML emails
        $from = 'from@gmail.com';
        $to = $mail;
        $message['headers'] = array(
          'content-type' => 'text/html',
          'MIME-Version' => '1.0',
          'reply-to' => $from,
          'from' => 'Protinex Team <'.$from.'>'
        );
        $message['to'] = $to;
        $message['subject'] = "User has submitted a recipe";
         
        $message['body'] = 'Hello, Thank you for reading this blog.';
         
        $send_mail->mail($message);
        
        
        $ajax_response->addCommand(new HtmlCommand('.recipes-form', $msg));
      }
      
      
      
      
      //$ajax_response->addCommand(new HtmlCommand('#edit-description', $form_state->getValue('photo["fids"]')));
      
      // ValCommand does not exist, so we can use InvokeCommand.
      //$ajax_response->addCommand(new InvokeCommand('#edit-user-name', 'val' , array($random_user->get('name')->getString())));
      
      // ChangedCommand did not work.
      //$ajax_response->addCommand(new ChangedCommand('#edit-user-name', '#edit-user-name'));
      
      // We can still invoke the change command on #edit-user-name so it triggers Ajax on that element to validate username.
      //$ajax_response->addCommand(new InvokeCommand('#edit-user-name', 'change'));
      
      // Return the AjaxResponse Object.
      return $ajax_response;
    }
  
  }
    
  