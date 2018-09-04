<?php
  /**
    * @file
    * Contains \Drupal\custom_blocks\Form\AskTheXpertForm.
    */
  namespace Drupal\custom_blocks\Form;
  
  use Drupal\Core\Ajax\AjaxResponse;
  use Drupal\Core\Ajax\ChangedCommand;
  use Drupal\Core\Ajax\CssCommand;
  use Drupal\Core\Ajax\HtmlCommand;
  use Drupal\Core\Ajax\InvokeCommand;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;
  
  
  class AskTheXpertForm extends FormBase {
    /**
    * {@inheritdoc}
    */
    public function getFormId() {
      return 'askForm';
    }
    
    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $is_home_page = \Drupal::service('path.matcher')->isFrontPage();
      if($is_home_page) {
        $class = 'dnone';
      } else {
        $class = '';
      }
      
      
      $form['name'] = array(
        '#type' => 'textfield',
        '#prefix' => '<div class="formBox"><div class="fieldBox wid33 requiredField"><div class="inputBox nameField">',
        '#suffix' => '<span id="nameErr" class="error">This field is required.</span></div></div>',
        '#placeholder' => t('Name'),
        '#attributes' => array(
              'id' => 'name',
              'data-label' => "true",
              //'data-validation' => "required,minLength3,alphaOnly" 
        ),
      );
      
      $form['email'] = array(
        '#type' => 'textfield',
        '#prefix' => '<div class="fieldBox wid33 requiredField"><div class="inputBox emailField">',
        '#suffix' => '<span id="mailErr" class="error">This field is required.</span></div></div>',
        '#placeholder' => t('Email'),
        '#attributes' => array(
              'id' => 'email',
              'data-label' => "true",
             // 'data-validation' => "required,email" 
        ),
      );
      
      $form['mobile'] = array(
        '#type' => 'textfield',
        '#prefix' => '<div class="fieldBox wid33 '.$class.' requiredField"><div class="inputBox mobField">',
        '#suffix' => '<span id="mobErr" class="error">This field is required.</span></div></div>',
        '#placeholder' => t('Mobile'),
        '#maxlength'  => '15',
        '#attributes' => array(
              'id' => 'mobile',
              'data-label' => "true",
              
              //'data-validation' => "required" 
        ),
      );
      
      $form['age'] = array(
        '#type' => 'textfield',
        '#prefix' => '<div class="fieldBox wid33 '.$class.' requiredField"><div class="inputBox ageField">',
        '#suffix' => '<span id="ageErr" class="error">This field is required.</span></div></div>',
        '#placeholder' => t('Age'),
        '#maxlength'  => '2',
        '#attributes' => array(
              'id' => 'ageofkid',
              'data-label' => "true",
        ),
      );
      
      $form['gender'] = array(
        '#type' => 'select',
        '#prefix' => '<div class="fieldBox wid33 '.$class.' requiredField"><div class="selectBox genderField"><div class="selectedValue">Gender</div>',
        '#suffix' => '<span id="genderErr" class="error">This field is required.</span></div></div>',
        '#options' => array(
          '' => 'Gender',
          'male' => t('Male'),
          'female' => t('Female')
        ),
        '#attributes' => array(
              'id' => 'gender',
        ),
      );
      
      $form['city'] = array(
        '#type' => 'textfield',
        '#prefix' => '<div class="fieldBox wid33 '.$class.' requiredField"><div class="inputBox cityField">',
        '#suffix' => '<span id="cityErr" class="error">This field is required.</span></div></div>',
        '#placeholder' => t('City'),
        '#attributes' => array(
              'id' => 'city',
              'data-label' => "true",
        ),
      );
      
      
      
      $form['query'] = array(
        '#type' => 'textarea',
        '#prefix' => '<div class="fieldBox wid66 requiredField"><div class="textareaBox descField">',
        '#suffix' => '<span id="quesErr" class="error">This field is required.</span></div></div>',
        '#placeholder' => t('Query'),
        '#attributes' => array(
              'id' => 'question',
              'data-label' => "true",
              //'data-validation' => "required"
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
          'callback' => '::expertCallback',
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
    
    public function expertCallback(array &$form, FormStateInterface $form_state) {
      $is_home_page = \Drupal::service('path.matcher')->isFrontPage();
      $error    = false;
      $name_err = false;
      $age_err  = false;
      $gen_err  = false;
      $mob_err  = false;
      $cty_err  = false;
      $mail_err = false;
      $query_err = false;
      $ajax_response = new AjaxResponse();
      
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
      
      if(!$is_home_page) {
        if(trim($form_state->getValue('age')) == '') {
          $err_txt = 'Age is required';
          $age_err = true;
          $error = true;
        } else {
          if(!preg_match("/^[0-9]+$/", $form_state->getValue('age'))) {
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
          $ajax_response->addCommand(new HtmlCommand('#ageErr', ''));
        }
      
        if(trim($form_state->getValue('gender')) == '') {
          $err_txt = 'Gender is required';
          $gen_err = true;
          $error = true;
        } else {
          if($form_state->getValue('gender') != 'male' && $form_state->getValue('gender') != 'female') {
            $err_txt = 'Invalid Gender';
            $gen_err = true;
            $error = true;
          } 
        }
        if($gen_err) {
          $css = ['opacity' => '1'];
          $ajax_response->addCommand(new CssCommand('.genderField .error', $css));
          $ajax_response->addCommand(new HtmlCommand('#genderErr', $err_txt));
        } else {
          $ajax_response->addCommand(new HtmlCommand('#genderErr', ''));
        }
        
        if(trim($form_state->getValue('mobile')) == '') {
          $err_txt = 'Mobile Number is required';
          $mob_err = true;
          $error = true;
        } else {
          $mob = (int)$form_state->getValue('mobile');
          if(!preg_match("/^[0-9]+$/", $form_state->getValue('mobile'))) {
            $err_txt = 'Invalid Mobile Number';
            $mob_err = true;
            $error = true;
          }
          if($mob < 1000000000 || $mob > 999999999999999) {
            $err_txt = 'Invalid Mobile Number';
            $mob_err = true;
            $error = true;
          }
        }
        
        if($mob_err) {
          $css = ['opacity' => '1'];
          $ajax_response->addCommand(new CssCommand('.mobField .error', $css));
          $ajax_response->addCommand(new HtmlCommand('#mobErr', $err_txt));
        } else {
          $ajax_response->addCommand(new HtmlCommand('#mobErr', ''));
        }
        
        if(trim($form_state->getValue('city')) == '') {
          $err_txt = 'City is required';
          $cty_err = true;
          $error = true;
        } else {
          if(!preg_match("/^[A-Za-z .'-]+$/", $form_state->getValue('city'))) {
            $err_txt = 'Invalid City';
            $cty_err = true;
            $error = true;
          } 
        }
        
        if($cty_err) {
          $css = ['opacity' => '1'];
          $ajax_response->addCommand(new CssCommand('.cityField .error', $css));
          $ajax_response->addCommand(new HtmlCommand('#cityErr', $err_txt));
        } else {
          $ajax_response->addCommand(new HtmlCommand('#cityErr', ''));
        }
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
        $name   = $form_state->getValue('name');
        $desc   = $form_state->getValue('query');
        $mail   = $form_state->getValue('email');
        
        if($is_home_page) {
          $mobile = '';
          $gender = '';
          $age    = 0;
          $city   = '';
        } else {
          $mobile = $form_state->getValue('mobile');
          $gender = $form_state->getValue('gender');
          $age    = $form_state->getValue('age');
          $city   = $form_state->getValue('city');
        }
        
        $request_time = \Drupal::time()->getRequestTime();
        
        $query = \Drupal::database()->insert('ask_the_expert');
        $query->fields(['name','email','query', 'age', 'gender', 'city', 'mobile', 'created']);
        $query->values([$name, $mail, $desc, $age, $gender, $city, $mobile, $request_time]);
        $query->execute();
        
        $mail_body = '';
        $mail_msg = 'Dear Admin, <br> '. $name . ' has posted a query.<br>Please find the details below. <br>';
        $mail_body .= '<table><tr><td>Name</td><td>:</td><td>'.$name. '</td></tr>';
        if($is_home_page) {
          $mail_body .= '<tr><td>Email</td><td>:</td><td>'.$mail.'</td></tr><tr><td>Query</td><td>:</td><td>'.$desc.'</td></tr></table>';
        } else {
          $mail_body .= '<tr><td>Age</td><td>:</td><td>'.$age.'</td></tr>
                          <tr><td>Gender</td><td>:</td><td>'.$gender.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>';
        }
        
                
        $mail_msg .= '<table width="563" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr><td style="line-height:10px;"><img src="'.$base_url.'/themes/himalaya/files/logo.png" alt="" border="0"></td></tr>
          <tr><td valign="top"><table width="563" border="0" cellspacing="0" cellpadding="0"><tr><td width="15"></td>
          <td width="533" valign="top"><table width="533" border="0" cellspacing="0" cellpadding="0"><tr>
          <td style="font-family:Arial; font-size:14px; color:#000;"><p> '.$mail_msg.'</p></td></tr></table></td></tr>
          <tr><td width="15"></td><td width="533" valign="top"><table width="533" border="0" cellspacing="0" cellpadding="0">
          <tr><td height="15"></td></tr><tr><td style="font-family:Arial; font-size:14px; color:#000;"><p>'.$mail_body.'</p></td></tr>
          <tr><td height="15"></td></tr></table></td><td width="15"></td></tr><tr><td width="15"></td><td width="533" valign="top">
          <table width="533" border="0" cellspacing="0" cellpadding="0">
            <tr><td style="font-family:Arial; font-size:14px; color:#000;">Thanks,</td></tr>
            <tr><td style="font-family:Arial; font-size:14px; color:#000;"><p>ProtinexTeam</p></td></tr>
            <tr><td height="15"></td></tr>
          </table></td><td width="15"></td></tr></table></td></tr><tr>
          <td valign="top" bgcolor="#b30608">
          <table width="563" border="0" cellspacing="0" cellpadding="0"><tr><td width="15"></td><td width="533" valign="top">
          <table width="533" border="0" cellspacing="0" cellpadding="0"><tr><td height="15"></td></tr><tr><td height="2" bgcolor="#a8a8a8"></td></tr>
          <tr><td height="15"></td></tr><tr>
          <td align="center" style="font-family:Arial; font-size:12px; color:#000; text-decoration:underline;">
          This email is an automated notification and does not require a reply.</td></tr><tr><td height="15"></td></tr>
          </table></td><td width="15"></td></tr></table></td></tr></table>';        
        $msg = '<div class="mssage">Thank you <span>for the query. We will get back to you soon</span></div>';
        
        $send_mail = new \Drupal\Core\Mail\Plugin\Mail\PhpMail(); // this is used to send HTML emails
        $from = 'team@protinex.com';
        $to = 'sunil.limboo@indigo.co.in';
        $message['headers'] = array(
          'content-type' => 'text/html',
          'MIME-Version' => '1.0',
          'reply-to' => $from,
          'from' => 'Protinex Team <'.$from.'>'
        );
        $message['to'] = $to;
        $message['subject'] = "Ask the expert";
         
        $message['body'] = $mail_msg;
         
        $send_mail->mail($message);
        
        
        $ajax_response->addCommand(new HtmlCommand('.askform', $msg));
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
    
  