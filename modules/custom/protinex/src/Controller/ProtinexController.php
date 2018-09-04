<?php

  namespace Drupal\protinex\Controller;
  
  use Drupal\Core\Controller\ControllerBase;
  
  class ProtinexController extends ControllerBase {
    /**
     * Display the markup.
     *
     * @return array
     */
    public function content() {
      return array(
        '#type' => 'markup',
        '#markup' => $this->t('Good to Go!'),
      );
    }
    
    public function askTheExperts() {
      $html = "<table><tr><th>Sl.No</th><th>Name</th><th>Age</th><th>Gender</th><th>Email</th><th>Mobile</th><th>Query</th><th>Date</th></tr>";
      $index = 1;
      $query = \Drupal::database()->select('ask_the_expert', 'a');
      $query->fields('a', ['name','age','gender','email','mobile','query','created']);
      $query->orderBy('a.created', 'DESC');
      $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
            ->limit(10);
      $rows = $pager->execute();
      //$users = $query->execute()->fetchAllAssoc('uid');
      foreach($rows as $row) {
        $html .= '<tr>
                    <td>'.$index.'</td>
                    <td>'.ucwords($row->name).'</td>
										<td>'.$row->age.'</td>
										<td>'.$row->gender.'</td>
                    <td>'.$row->email.'</td>
										<td>'.$row->mobile.'</td>
                    <td>'.$row->query.'</td>
                    <td>'.date("d/m/Y", $row->created).'</td>
                  </tr>';
          $index++;
      }
      $html .= '</table><br><a href="/admin/protinex/downloads?q=askTheXperts">Download</a>';
      $output = array(
          '#markup' => $html,
      );
      $build['result'] = $output;
      $build['pager'] = [
        '#type' => 'pager',
      ];
      return $build;
    }
    
    public function contactUs() {
      $html = "<table><tr><th>Sl.No</th><th>Name</th><th>Age</th><th>Email</th><th>Feedback</th><th>Date</th></tr>";
      $index = 1;
      $query = \Drupal::database()->select('contact_us', 'a');
      $query->fields('a', ['name','email','feedback','age', 'created']);
      $query->orderBy('a.created', 'DESC');
      $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
            ->limit(10);
      $rows = $pager->execute();
      //$users = $query->execute()->fetchAllAssoc('uid');
      foreach($rows as $row) {
        $html .= '<tr>
                    <td>'.$index.'</td>
                    <td>'.ucwords($row->name).'</td>
                    <td>'.$row->age.'</td>
                    <td>'.$row->email.'</td>
                    <td>'.$row->feedback.'</td>
                    <td>'.date("d/m/Y", $row->created).'</td>
                  </tr>';
          $index++;
      }
      $html .= '</table><br><a href="/admin/protinex/downloads?q=contactUs">Download</a>';
      $output = array(
          '#markup' => $html,
      );
      $build['result'] = $output;
      $build['pager'] = [
        '#type' => 'pager',
      ];
      return $build;
    }
    
    public function testimonials() {
      $html = "<table><tr><th>Sl.No</th><th>Name</th><th>Contact</th><th>Email</th><th>Testimony</th><th>Date</th><th>Action</th></tr>";
			$query = \Drupal::entityQuery('node')
						->condition('status', 0)
						->condition('type', 'testimonials')
						->sort('created', 'DESC')
            ->pager(10);
			$nids = $query->execute();
			$nodes = entity_load_multiple('node', $nids);
			$index = 1;
      foreach($nodes as $node) {
        $html .= '<tr>
                    <td>'.$index.'</td>
                    <td>'.ucwords($node->title->value).'</td>
                    <td>'.$node->field_contact->value.'</td>
                    <td>'.$node->field_email->value.'</td>
                    <td>'.$node->body->value.'</td>
                    <td>'.date("d/m/Y", $node->created->value).'</td>
                    <td><a href="/node/'.$node->nid->value.'/edit?destination=/admin/content">Approve</a></td>
                  </tr>';
        $index++;
      }
      $html .= '</table><br><a href="/admin/protinex/downloads?q=testimonials">Download</a>';
      $output = array(
          '#markup' => $html,
      );
      $build['result'] = $output;
      $build['pager'] = [
        '#type' => 'pager',
      ];
      return $build;
    }
		
		public function recipes() {
      $html = "<table><tr><th>Sl.No</th><th>Submitted By</th><th>Contact</th><th>Email</th><th>Recipe</th><th>Date</th><th>Action</th></tr>";
			$query = \Drupal::entityQuery('node')
						->condition('status', 0)
						->condition('type', 'recipes')
						->sort('created', 'DESC')
            ->pager(10);
			$nids = $query->execute();
			$nodes = entity_load_multiple('node', $nids);
			$index = 1;
      foreach($nodes as $node) {
        $html .= '<tr>
                    <td>'.$index.'</td>
                    <td>'.ucwords($node->title->value).'</td>
                    <td>'.$node->field_contact->value.'</td>
                    <td>'.$node->field_email->value.'</td>
                    <td>'.$node->body->value.'</td>
                    <td>'.date("d/m/Y", $node->created->value).'</td>
                    <td><a href="/node/'.$node->nid->value.'/edit?destination=/admin/content">View</a></td>
                  </tr>';
        $index++;
      }
      $html .= '</table><br><a href="/admin/protinex/downloads?q=recipes">Download</a>';
      $output = array(
          '#markup' => $html,
      );
      $build['result'] = $output;
      $build['pager'] = [
        '#type' => 'pager',
      ];
      return $build;
    }
    
    
    public function downloads() {
      $csv_output = "";
      $arg = $_GET['q'];
      if($arg == 'askTheXperts') {
        $csv_output .= "Name,Age,Gender,Email,Mobile,Query,Date\n";
        $sql = 'SELECT * FROM prt_ask_the_expert';
        $db = \Drupal::database();
        $rows = $db->query($sql);
        $rows->allowRowCount = TRUE;
        foreach($rows as $row) {
          $date_created = date('d/m/Y', $row->created);
          $csv_output .=  $row->name .",\"".$row->age."\",\"".$row->gender."\",\"".$row->email ."\",\"".$row->mobile."\",\"".$row->query."\",\"".$date_created."\"\n";
        }
      }
      if($arg == 'contactUs') {
        $csv_output .= "Name,Age,Email,Feedback,Date\n";
        $sql = 'SELECT * FROM prt_contact_us';
        $db = \Drupal::database();
        $rows = $db->query($sql);
        $rows->allowRowCount = TRUE;
        foreach($rows as $row) {
          $date_created = date('d/m/Y', $row->created);
          $csv_output .=  $row->name .",\"".$row->age."\",\"".$row->email ."\",\"".$row->feedback."\",\"".$date_created."\"\n";
        }
      }
			if($arg == 'testimonials') {
        $csv_output .= "Name,Contact,Email,testimony,Published,Date\n";
        $query = \Drupal::entityQuery('node')
								->condition('type', 'testimonials')
								->sort('created', 'DESC');
				$nids = $query->execute();
				$nodes = entity_load_multiple('node', $nids);
				foreach($nodes as $node) {
          $date_created = date('d/m/Y', $node->created->value);
					if($node->status->value == 1) {
						$published = 'Yes';
					} else {
						$published = 'No';
					}
          $csv_output .=  $node->title->value .",\"".$node->field_contact->value."\",\"".$node->field_email->value ."\",\"".$node->body->value."\",\"". $published ."\",\"".$date_created."\"\n";
        }
      }
      $filename = $arg."_".date("Y-m-d_H-i",time());
      header("Content-type: application/vnd.ms-excel");
      header("Content-disposition: csv" . date("Y-m-d") . ".csv");
      header("Content-disposition: filename=".$filename.".csv");
      print $csv_output;
      exit;
    }
		
		
		public function search() {
			$srch_result = array();
			$srch_html = '';
      $output = '';
			if(isset($_POST['search'])) {
				$srch_txt = trim($_POST['search']);
				if($srch_txt != '') {
					$query = \Drupal::entityQuery('taxonomy_term');
					$group = $query->orConditionGroup()
								->condition('name', '%'.$srch_txt.'%', 'LIKE')
								->condition('field_details', '%'.$srch_txt.'%', 'LIKE')
								->condition('field_ingredients', '%'.$srch_txt.'%', 'LIKE')
								->condition('field_how_to_use', '%'.$srch_txt.'%', 'LIKE')
								->condition('field_dosage.value', '%'.$srch_txt.'%', 'LIKE');
					$qry = $query
								->condition($group);
					$tids = $qry->execute();
					$terms = entity_load_multiple('taxonomy_term', $tids);
	
					foreach($terms as $term) {
						$url = \Drupal::service('path.alias_manager')->getAliasByPath('/taxonomy/term/'.$term->tid->value);
						if($term->description->value != '') {
							$desc = strip_tags(substr($term->description->value, 0, 300)).'...';
							$srch_result[] = array(
								'title' => $term->name->value,
								'desc'  => $desc,
								'url'  => $url
							);
						}
					}
					
					$query = \Drupal::entityQuery('node')
								->condition('type', 'calculator_messages', '<>');
					$group = $query->orConditionGroup()
								->condition('title', '%'.$srch_txt.'%', 'LIKE')
								->condition('body.value', '%'.$srch_txt.'%', 'LIKE');
					$qry = $query
								->condition('status', 1)
								->condition($group);
					$nids = $qry->execute();
					$nodes = entity_load_multiple('node', $nids);
					foreach($nodes as $node) {
						$content_type = $node->type->entity->label();
						$alias = $node->toUrl()->toString();
						if(!preg_match('/node/', $alias)) {
							$url = $alias;
						} else {
							if($content_type == 'Testimonials') {
								$url = '/testimonials';
							}
							if($content_type == 'Article') {
								$url = '/media';
							}
							if($content_type == 'Blogs') {
								$url = '/blog';
							}
							if($content_type == 'FAQs') {
								$url = '/faqs';
							}
							if($content_type == 'Recipes') {
								$url = '/recipes';
							}
							if($content_type == 'World of Proteins') {
								$url = '/discover-protein/what-is-protein/the-world-of-proteins';
							}
							if($content_type == 'Product') {
								$tid = $node->field_product_category[0]->target_id;
								$url = \Drupal::service('path.alias_manager')->getAliasByPath('/taxonomy/term/'.$tid);
							}
						}
						$desc = strip_tags(substr($node->body->value, 0, 300)).'...';
						if(!$node->body->value) {
							$srch_result[] = array(
								'title' => $node->title->value,
								'desc'  => $desc,
								'url'  => $url
							);
						}
					}
					$sql = "SELECT a.info, b.body_value
									FROM prt_block_content_field_data a
									LEFT JOIN prt_block_content__body b ON b.entity_id = a.id
									WHERE a.info LIKE '%".$srch_txt."%' OR b.body_value LIKE '%".$srch_txt."%'";
					$db = \Drupal::database();
					$rows = $db->query($sql);
					$rows->allowRowCount = TRUE;
					foreach($rows as $row) {
						if(strpos($row->info, '-') !== 0) {
							$temp = explode('-', $row->info);
							if($row->body_value != '') {
								if($temp[0] == 'Home') {
									$url = '/';
								}
								if($temp[0] == 'About') {
									$url = '/what-is-protinex/about-protinex';
								}
								$desc = strip_tags(substr($row->body_value, 0, 300)).'...';
								$srch_result[] = array(
										'title' => $temp[1],
										'desc'  => $desc,
										'url'  => $url
								);
							}
						}
					}
					$sql = "SELECT a.parent_id, b.field_title_value, c.field_content_body_value
									FROM prt_paragraphs_item_field_data a
									LEFT JOIN prt_paragraph__field_title b ON b.entity_id = a.id
									LEFT JOIN prt_paragraph__field_content_body c ON c.entity_id = a.id
									WHERE b.field_title_value LIKE '%".$srch_txt."%' OR c.field_content_body_value LIKE '%".$srch_txt."%'";
					$db = \Drupal::database();
					$rows = $db->query($sql);
					$rows->allowRowCount = TRUE;
					foreach($rows as $row) {
						$url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$row->parent_id);
						$desc = strip_tags(substr($row->field_content_body_value, 0, 300)).'...';
						$node = \Drupal\node\Entity\Node::load($row->parent_id);
						if($row->field_content_body_value != '') {
							$srch_result[] = array(
								'title' => $node->title->value,
								'desc'  => $desc,
								'url'  => $url
							);
						}
					}
					$res_count = count($srch_result);
					$page_count = ceil($res_count/10);
					$count = 1;
					$first = true;
					$page = 1;
					$output .= '<div class="srchResBox"><h1>Search Results</h1><ul id="example">';
					$row_count = 1;
					foreach($srch_result as $item) {
						if($row_count > 80) { exit; }
						if(trim($item['url']) != '' ||  trim($item['title']) != '' || trim($item['desc']) != '') {
							$link 	= $item['url'];
							$title 	= $item['title'];
							$desc 	= $item['desc'];
							$output .= '<li class="item"><h3><a href="'.$link.'">'.$title.'</a></h3><p>'.$desc.'</p></li>';
						}
						$row_count++;
					}
					$output .= '</ul></div>';
	
					//$srch_html = '<h1>Search Results</h1><ul class="list" rel="'.$page_count.'">'.$output.'</ul><nav class="pager pagination fr"><ul class="srchPager pagination"></ul></nav>';          
				}
			if(!$res_count) {
				$output .= '<h2>No results found</h2>';
			}
			}
      $element = array(
        '#title'  => t('Search Results'),  
        '#markup' => $output,
      );
      return $element;
		}
		
  }