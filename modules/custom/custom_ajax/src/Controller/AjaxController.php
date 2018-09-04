<?php
  /**
  @file
  Contains \Drupal\custom_ajax\Controller\AjaxController.
   */
  namespace Drupal\custom_ajax\Controller;
  
  use Drupal\Core\Controller\ControllerBase;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\JsonResponse;
  use Drupal\image\Entity\ImageStyle;
  use Drupal\node\Entity\Node;
  use Drupal\user\Entity\User;
  use Drupal\Core\Url;
  use Drupal\Core\Link;
  use Drupal\file\Entity\File;
  use Drupal\Core\Mail\MailManagerInterface;
  use Drupal\Component\Utility\SafeMarkup;
  use Drupal\Component\Utility\Html;
  use Drupal\Core\Password\PhpassHashedPassword;
  use \DateTime;
  use \Drupal\custom_mail\Controller\MailController;
  use Drupal\Core\Session\AccountInterface;
  use Drupal\Core\DependencyInjection\ContainerInjectionInterface; 
  use Symfony\Component\DependencyInjection\ContainerInterface;
  use Drupal\Core\Password\PasswordInterface;
  use Drupal\Component\Serialization\Json;


  class AjaxController extends ControllerBase {
		public function recipes_load_more() {
      $start = $_POST['loadNo'];
      $upper = (int)$start + 5;
      $output = '';
			$query = \Drupal::entityQuery('node')
						->condition('status', 1)
						->condition('type', 'recipes')
						->sort('created', 'DESC')
						->range($start, $upper);
			$nids = $query->execute();
			$nodes = entity_load_multiple('node', $nids);
			$first = true;
			$index = 1;
			$count = 0;
      foreach($nodes as $node) {
				$count++;
				//echo '<pre>'; print_r($node); echo '</pre>';
				if($count <= 4) {
					$image = file_create_url($node->field_image->entity->getFileUri());
					//$image = '';
					if($first) {
						$output .= '<div class="commonListSection" data-scroll="active">';
						$first = false;
					} else {
						$output .= '<div class="commonListSection" data-scroll="">';
					}
					
					$output .= '<div class="columnMaster">
											<div class="column wid40">
													<div class="imgBox svgBox" data-svg="/themes/himalaya/images/Recipes'.$index.'.svg">
															<img src="'. $image .'" alt="'. $node->title->value .'">
													</div>
											</div>
											<div class="column wid60 contentSec">
													<div class="topSec">
															<div class="titleSec">
																	<h2>'. $node->title->value .'</h2>
																	<em>Recipe submitted by <strong>'. $node->field_blogger_s_name->value .'</strong></em>
															</div>
															
															<h4 class="serving">Yields '. $node->field_yields->value .'</h4>
													</div>
													<div class="btmCont">'. $node->body->value .'</div>
											</div>
									</div>
							</div>';
					$index++;
				}
			}
      
      if($count > 4) {
        $load_more = '1';
      } else {
        $load_more = '0';
      }
      
      return new JsonResponse(['up' => $upper-1, 'lm' => $load_more, 'res' => $output]);
    }
    public function testimonial_load_more() {
      $start = $_POST['loadNo'];
      $upper = (int)$start + 5;
      $output = '';
			$query = \Drupal::entityQuery('node')
						->condition('status', 1)
						->condition('type', 'testimonials')
						->sort('created', 'DESC')
						->range($start, $upper);
			$nids = $query->execute();
			$nodes = entity_load_multiple('node', $nids);
			$first = true;
			$index = 1;
			$count = 0;
      foreach($nodes as $node) {
				$count++;
				if($count <= 4) {
					if($index > 4) {
						$index = 1;
					}
					if($first) {
						$active = 'active';
						$first = false;
					} else {
						$active = '';
					}
					if($node->field_image->target_id) {
						$image = file_create_url($node->field_image->entity->getFileUri());
					} else {
						$image = '/themes/himalaya/images/default.png';
					}
					
					$output .= '<div class="column wid50 tCenter"  data-scroll="'.$active.'">
												<div class="testimonialContent">
													<div class="testimonialData">
														<div class="imgBox svgBox" data-svg="/themes/himalaya/images/Recipes'.$index.'.svg"><img src="'.$image.'" alt="Testimonial"></div>
														<h3>'.$node->title->value.'</h3>
														<div class="content"><p>'. $node->body->value .'</p></div>
													</div>
												</div>
											</div>';
					$index++;
				}
			}
      
      if($count > 4) {
        $load_more = '1';
      } else {
        $load_more = '0';
      }
      
      return new JsonResponse(['up' => $upper-1, 'lm' => $load_more, 'res' => $output]);
    }
		
		public function get_token() {
			$random_str					= '';
			$salt 							= md5('xenitorp');
			$leadtime 					= time();
			$random_str 				= md5(uniqid($leadtime));
			$token 							= sha1(md5($random_str . $salt));
			$_SESSION['ask']['token'] 	= $token;
			return new JsonResponse(['token' => $random_str]);
		}
		
		public function ask_the_experts() {
			$err = array();
			$error = false;
			$msg = '';
			$original_token = $_SESSION['ask']['token'];
			$salt 		= md5('xenitorp');
			$leadtime = time();
			$random_str = md5(uniqid($leadtime));
			$token 		= sha1(md5($random_str . $salt));
			$_SESSION['ask']['token'] 	= $token;
			
			$name 		= $_POST['name'];
			$mail 		= $_POST['mail'];
			$desc  		= $_POST['query'];
			$rnd_str 	= $_POST['token'];
			$chk_token = sha1(md5($rnd_str . $salt));
			
			if($original_token == $chk_token) {
				if(trim($name) == '') {
					$err[] = 'name-Name is required';
					$error = true;
				} else {
					if(!preg_match("/^([a-zA-Z.\']+\s?)*$/", $name)) {
						$err[] = 'name-Invalid Name';
						$error = true;
					} 
				}
				
				if(trim($mail) == '') {
					$err[] = 'email-Email is required';
					$error = true;
				} else {
					if (filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {  
						$err[] = 'email-Invalid Email';;
						$error = true;
					}
				}
				
				if(trim($desc) == '') {
					$err[] = 'query-Query is required';
					$error = true;
				} else {
					if(!preg_match("/^[a-zA-Z0-9,.!?#\-:& *'\"\/\\\ ()\[\]]*$/", $desc)) {
						$err[] = 'question-Invalid characters in Query';
						$error = true;
					}
				}
				
				if(!$error) {
					
					
					$mailManager = \Drupal::service('plugin.manager.mail');
					$module = 'custom_ajax';
					$key = 'askExpert'; // Replace with Your key
					$to = 'limbuzkid@gmail.com';
					$params['message'] = '<p>This is the message</p>';
					$params['title'] = 'The title';
					$langcode = \Drupal::currentUser()->getPreferredLangcode();
					$send = true;
				
					$result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
					if ($result['result'] != true) {
						$message = t('There was a problem sending your email notification to @email.', array('@email' => $to));
						drupal_set_message($message, 'error');
						\Drupal::logger('mail-log')->error($message);
						return;
					}
					
					exit;
				
					$message = t('An email notification has been sent to @email ', array('@email' => $to));
					drupal_set_message($message);
					\Drupal::logger('mail-log')->notice($message);
					$is_error = '0';
					$mobile = '';
					$gender = '';
					$age    = 0;
					$city   = '';
					$request_time = \Drupal::time()->getRequestTime();
					
					$query = \Drupal::database()->insert('ask_the_expert');
					$query->fields(['name','email','query', 'age', 'gender', 'city', 'mobile', 'created']);
					$query->values([$name, $mail, $desc, $age, $gender, $city, $mobile, $request_time]);
					$query->execute();
					
					
					$mail_msg = 'Dear Admin, <br><br> '. $name . ' has posted a query. Please find the details below. <br><br>';
					$mail_body .= '<table><tr><td>Name</td><td>:</td><td>'.$name. '</td></tr>';
					$mail_body .= '<tr><td>Email</td><td>:</td><td>'.$mail.'</td></tr><tr><td>Query</td><td>:</td><td>'.$desc.'</td></tr></table>';
									
					$mail_msg = '<table width="563" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr><td style="line-height:10px;"><img src="http://protinex.com/themes/himalaya/images/logo.png" alt="" border="0"></td></tr>
						<tr><td valign="top"><table width="563" border="0" cellspacing="0" cellpadding="0"><tr><td width="15"></td>
						<td width="533" valign="top"><table width="533" border="0" cellspacing="0" cellpadding="0"><tr>
						<td style="font-family:Arial; font-size:14px; color:#000;"><p>'.$mail_msg.'</p></td></tr></table></td></tr>
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
					
					
				} else {
					$is_error = '1';
				}
				
				return new JsonResponse(['error' => $is_error, 'errArr' => $err, 'msg' => $msg, 'tkn' => $random_str]);
			} else {
				$is_error = '1';
				return new JsonResponse(['error' => $is_error, 'errArr' => '0', 'msg' => 'Token mismatch.', 'tkn' => $random_str]);
			}
			
		}
		
		public function calculator_food_items() {
			$query = \Drupal::database()->select('calculator_data', 'cd');
			$query->fields('cd', ['food_item', 'portion_quantity', 'portion', 'protein']);
			$query->orderBy('food_item', 'ASC');
			$result = $query->execute();
			foreach($result as $row) {
				//$options .= '<option data-protein="'.$row->protein.'" data-portion="'.strtolower($row->portion).'" value="'. $row->food_item .'">'.$row->food_item.'</option>'; 
				$options[] = array(
					'name'	=> $row->food_item,
					'protein' => $row->protein,
					'portion' => strtolower($row->portion),
				); 
			}
			return new JsonResponse(['data' => $options]);
		}
		
		public function get_calci_result() {
			$name 			= $_POST['name'];
			$age 				= $_POST['age'];
			$gender 		= strtolower($_POST['gender']);
			$height 		= $_POST['height'];
			$weight 		= $_POST['weight'];
			$diabetic 	= $_POST['diabetic'];
			$pregnant 	= $_POST['pregnant'];
			$breast_feeding = '0';
			$breakfast 	= round($_POST['breakfast'],2);
			$lunch			= round($_POST['lunch'],2);
			$snacks			= round($_POST['snacks'],2);
			$dinner			= round($_POST['dinner'],2);
			$total			= round((int)$_POST['total']);
			$bmi				= round((float)$_POST['bmi'],2);
			$option			= '';
			$protein_intake = '';
			$mobile  =  $_POST['mobile'];
			$email = $_POST['email'];
			
			$ideal_body_weight	= round((float)$_POST['IdealBodyWeight'],2);
			$daily_protein_requirement = round((int)$_POST['dailyProteinReq']);
			
			if($age >= 2 && $age <= 8) {
				$age_range = '2-8';
			} elseif($age > 8 && $age <= 17) {
				$age_range = '9-17';
			} elseif($age >= 18) {
				$age_range = '18+';
			}
			
			if($daily_protein_requirement == 0) {
				$protein_intake = 'Adequate Protein';
				$heading = 'Your protein intake is adequate';
			} elseif($total < $daily_protein_requirement) {
				$protein_intake = 'Protein Deficient';
				$heading = 'Your protein intake is inadequate';
			} elseif($total > $daily_protein_requirement) {
				$protein_intake = 'Excess Protein';
				$heading = 'Your protein intake is excess';
			}	else {
				$protein_intake = 'Adequate Protein';
				$heading = 'Your protein intake is adequate';
			}
			
			//echo $age . '- '. $total . ' - '. $protein_intake;
			//exit;
			
			$query = \Drupal::entityQuery('node')
						->condition('type', 'calculator_messages')
						->condition('field_result_type', $protein_intake);
						
			/*if($diabetic == '1') {
				echo 'dia';
						
			} else {
				echo 'nodia';
			}
			if($gender == 'male') {
				echo 'male';
						
			} else {
				echo 'fem';
			}
			if($age == '18+') {
				echo '18+';
						
			} else {
				echo '0';
			}
			
			if($diabetic == '1' && $gender == 'male' && $age == '18+') {
				echo 'true';
			} else {
				echo 'false';
			}*/
			if($gender == 'male') {
				if($diabetic == '1' && $age_range == '18+') {
					$query->condition('field_options', 'Diabetic');
				} else {
					$query->condition('field_select_age', $age_range);
				}
			} else {
				if($pregnant == '1' && $age_range == '18+') {
					$query->condition('field_options', 'Pregnant');
				} elseif($breast_feeding == '1' && $age_range == '18+') {
					$query->condition('field_options', 'Breast feeding Mothers (20 - 40 Years)');
				} elseif($age_range == '18+' && $diabetic == '1') {
					$query->condition('field_options', 'Diabetic');
				} else {
					$query->condition('field_select_age', $age_range);
				}
			}
			
			$query->range(0,1);
			$nids = $query->execute();

			if(!empty($nids)) {
				$msg = 'success';
				$nodes = entity_load_multiple('node', $nids);
				$protinex_gap = round($daily_protein_requirement - $total);
				
				$images = '';
				$html = '';
				$buy_now = '';
				foreach($nodes as $node) {
					//print_r($node);
					$message = str_replace('/*req*/', round($daily_protein_requirement), $node->body->value);
					$message = str_replace('/*intake*/', round($total), $message);
					$message = str_replace('/*gap*/', $protinex_gap, $message);
					$buy_now = '';
					if(isset($node->field_product_images) && $protein_intake == 'Protein Deficient') {
						if(isset($node->field_product_images[0])) {
							$img =  file_create_url($node->field_product_images[0]->entity->field_content_image->entity->getFileUri());
							$prod =  $node->field_product_images[0]->entity->field_title->value;
							$link =  $node->field_product_images[0]->entity->field_amazon_link->value;
							$buy_now .= '<a href="'.$link.'" target="_blank" class="btn red">Buy Now</a>';
							$html .= '<li><div class="imgSec"><img src="'.$img.'" alt=""><h4>'.$prod.'</h4></div</li>';
						}
					
						if(isset($node->field_product_images[1])) {
							$img =  file_create_url($node->field_product_images[1]->entity->field_content_image->entity->getFileUri());
							$prod =  $node->field_product_images[1]->entity->field_title->value;
							$link = $node->field_product_images[1]->entity->field_amazon_link->value;
							$buy_now .= '<a href="'.$link.'" class="btn red" target="_blank">Buy Now</a>';
							$html .= '<li><div class="imgSec"><img src="'.$img.'" alt=""><h4>'.$prod.'</h4></div</li>';
						}

						$images = '<div class="fillGapSec"><p><strong>Fill the protein gap: Go for</strong></p><ul>'. $html. '</ul></div>'.$buy_now.'<div class="disclaimer note">
											<strong>Disclaimer:</strong>
											The recommended product is for normal healthy adults. Persons with health conditions should consult doctor before consuming this product.
									</div>';
					}
					
					$message .=  $images;
				}
			
				if($daily_protein_requirement == 0){
					$protein_intake_percentage = 0;
					$protein_percent = '';
					$total = 0;
				} else {
					$protein_intake_percentage = round(($total/$daily_protein_requirement) * 100);
				
					if($protein_intake_percentage > 100) {
						$protein_percent = $protein_intake_percentage - 100 .'% MORE';
					} else {
						$protein_percent = 100 - $protein_intake_percentage;
						if($protein_percent > 0) {
							$protein_percent = 100 - $protein_intake_percentage .'% LESS';
						} else {
							$protein_percent = '';
						}
						
					}
				}
				
				$data = array(
					'daily_intake' => '('. round($total) .'gms)',
					'daily_requirement' => round($daily_protein_requirement),
					'protein_intake' => round($protein_intake_percentage).'%',
					'protein_less'	=> $protein_percent,
					'message'				=> $message,
					'heading'				=> $heading,
				);
				
				
				$query = \Drupal::database()->insert('calculator_results');
				$query->fields(['name','age','gender','height', 'weight', 'email','mobile','diabetic','pregnant','breastfeeding','protein_intake','protein_requirement','protein_status']);
				$query->values([$name, $age, $gender, $height, $weight, $email, $mobile, $diabetic, $pregnant, $breast_feeding, $total, $daily_protein_requirement, $protein_intake]);
				$query->execute();
				
				$mail_msg = 'Dear Admin, <br><br> '. $name . ' has posted a query. Please find the details below. <br><br>';
				$mail_body .= '<table><tr><td>Name</td><td>:</td><td>'.$name. '</td></tr>';
				$mail_body .= '<tr><td>Email</td><td>:</td><td>'.$mail.'</td></tr><tr><td>Query</td><td>:</td><td>'.$protein_intake.'</td></tr></table>';
									
				$mail_msg = '<table width="563" border="0" align="center" cellpadding="0" cellspacing="0">
											<tr><td style="line-height:10px;"><img src="http://protinex.com/themes/himalaya/images/logo.png" alt="" border="0"></td></tr>
						<tr><td valign="top"><table width="563" border="0" cellspacing="0" cellpadding="0"><tr><td width="15"></td>
						<td width="533" valign="top"><table width="533" border="0" cellspacing="0" cellpadding="0"><tr>
						<td style="font-family:Arial; font-size:14px; color:#000;"><p>'.$total.'</p></td></tr></table></td></tr>
						<tr><td width="15"></td><td width="533" valign="top"><table width="533" border="0" cellspacing="0" cellpadding="0">
						<tr><td height="15"></td></tr><tr><td style="font-family:Arial; font-size:14px; color:#000;"><p>'.$total.'</p></td></tr>
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
					$message['subject'] = "Protein Calculator Results";
					 
					$message['body'] = $mail_msg;
					 
					//$send_mail->mail($message);
		
			} else {
				$msg = 'failed';
				$data = $nids;
			}
			return new JsonResponse(['msg' => $msg, 'data' => $data, 'head' => $heading, 'age' => $age, ]);
		}
    
  }