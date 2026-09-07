<?php
	//Clean Url
	function clean_url($title_string,$url_string='',$act,$post_id = ''){
		global $db;
		$str_string = $title_string;
		$str_string = trim($str_string);
	    $str_string = html_entity_decode($str_string);
	    $str_string = strip_tags($str_string);
	    $str_string = strtolower($str_string);
	    $str_string = preg_replace('~[^ a-z0-9_.]~', ' ', $str_string);
	    $str_string = preg_replace('~ ~', '-', $str_string);
	    $str_string = preg_replace('~-+~', '-', $str_string);
		$last_val = substr($str_string,-1);
		if($last_val == '-'){
			$str_string = substr($str_string,0,-1);
			}
		//If add new post
		if($act == 'add-new'){
			$str_string = create_post_url($str_string);
			}////If add new post
			
		//If update exists post
			if($act == 'update'){
				//If post id not given
				if(empty($post_id)){die('On update third parameter should be post id');}
				
				//Check if url not same
				$get_url = $db->query("select post_url,post_title from ad_posts where ID=$post_id ");
				if($get_url->post_title != $title_string){
					$str_string = create_post_url($str_string);
					}elseif($get_url->post_url != $url_string){
					$str_string = create_post_url($url_string);
					}
				}//If update exists post
			
	    return $str_string;
		}
		
	//Create URL
	function create_post_url($str_string){
		global $db;
		$count = $db->query("select COUNT(post_url) as total from ad_posts where post_url like '$str_string%'");
		 if($count->total>0){
			 $str_string = $str_string.'-'.($count->total+1);
			 }
			 return $str_string;
		}
		
	// Save new post
	function add_post($param){
		global $current_user, $db;
		$post_title = mysql_real_escape_string($param['post_title']);
		$post_content = mysql_real_escape_string($param['post_content']);
		$post_url = clean_url($param['post_title'],'','add-new'); //Create URL
		$post_image = $param['post_image'];
		//Post Categories
		if(isset($param['post_cat'])){
			$post_cat = $param['post_cat'];
			}else{
				$post_cat = '';
				}
		if(is_numeric($param['user_id'])){
			//Sql Query
			$sql  = "insert into ad_posts ";
			$sql .= "(user_id,post_date,post_cat,post_title,post_content,post_status,post_url,post_type,img,code,heading,duration,cost,award) ";
			$sql .= " VALUES ";
			$sql .= " (".$param['user_id'].",'".$param['post_date']."','$post_cat','$post_title','$post_content','".$param['post_status']."','$post_url','".$param['post_type']."','".$post_image."','".$param['post_code']."','".$param['post_heading']."','".$param['post_duration']."','".$param['post_cost']."','".$param['post_award']."')";
			$post_id = $db->sql_query($sql);
				//return $post_id;
				ad_redirect('edit-page.php?type='.$param['post_type'].'&post_id='.$post_id);
				exit;
			}else{
				die('User id should be numaric');
				}
		}
	
	// Update ost
	function update_post($param){
		global $db;
		$post_title = mysql_real_escape_string($param['post_title']);
		$post_content = mysql_real_escape_string($param['post_content']);
		$post_url = clean_url($param['post_title'],$param['post_url'],'update',$param['post_id']); //Create URL
		$post_code = $param['post_code'];
		$post_heading = $param['post_heading'];
		$post_duration = $param['post_duration'];
		$post_cost = $param['post_cost'];
		$post_award = $param['post_award'];
		$post_image = $param['post_image'];
		//Post Categories
		if(isset($param['post_cat'])){
			$post_cat = $param['post_cat'];
			}else{
				$post_cat = '';
				}
		//Sql Query
		$sql  = "update ad_posts set ";
		$sql .= " post_date='".$param['post_date']."', post_cat='$post_cat', ";
		$sql .= " post_title='$post_title', post_content='$post_content', ";
		$sql .= " post_status='".$param['post_status']."', post_url='$post_url', img='$post_image', code='$post_code', heading='$post_heading', duration='$post_duration', cost='$post_cost', award='$post_award'";
		$sql .= " where ID=".$param['post_id'];
		//Update Query
		$db->sql_query($sql);
	}
	//Get Posts
	function get_post($ID = ''){
		global $db;
					if(!empty($ID) && is_numeric($ID)){
						$this_query = " ID = ".$ID;
					}elseif(empty($ID) && isset($_GET['post_id']) &&  is_numeric($_GET['post_id'])){
					$this_query = " ID = ".$_GET['post_id'];
					}else{
					$this_query = " post_url = '$ID'";
					}
					
			//Get post by id
			$get_post = $db->query("select * from ad_posts where ".$this_query );
			return $get_post;
		}
		
		//Get Posts
	function get_post_img($ID = ''){
		global $db;
					if(!empty($ID) && is_numeric($ID)){
						$this_query = " ID = ".$ID;
					}elseif(empty($ID) && isset($_GET['post_id']) &&  is_numeric($_GET['post_id'])){
					$this_query = " ID = ".$_GET['post_id'];
					}else{
					$this_query = " post_url = '$ID'";
					}
					
			//Get post by id
			$get_post = $db->query("select img from ad_posts where ".$this_query );
			return $get_post;
		}
		
		
		//get Attachment
			function get_img_src($ID,$size,$querystring='',$remove_attr=false){
				global $media;
				$imagesrc = get_post_img($ID);
				if($remove_attr){
					$widht = '';
					$height='';
					}else{
					$widht = 'width="'.$media[$size][0].'"';
					$height ='height="'.$media[$size][1].'"';	
						}
				if($imagesrc){
				echo '<img '.$querystring.' src="'.SITE_URL.'ad-content/uploads/'.$media[$size][0].'x'.$media[$size][1].'-'.$imagesrc->img.'" '.$widht.' '.$height.' " />';
				}else{
				echo '<img '.$querystring.' src="'.SITE_URL.'ad-content/uploads/thumb.png"  '.$widht.' '.$height.' />';
					}
				}
		
		
	//Get multipal posts
	function get_posts($param){
		global $db;
		//Get post param
		if(!is_array($param) && empty($param)){
			$param = array(
			'per_page' 		=> '',
			'category' 		=> '',
			'post_type'		=> 'post',
			'order_by'	 	=> '',
			'order' 		=> 'DESC',
			'post_status' 	=> 'publish',
				);
			}//End array
			
			$sql_query  =  "select * from ad_posts where ";
			$sql_query .=  "post_type ='$param[post_type]' ";
			//Category id
			if(!empty($param['category']) && isset($param['category'])){
			$sql_query .=  " and post_cat ='$param[category]'";
			}
			//Post status
			if(!empty($param['post_status']) && isset($param['post_status']) ){
			$sql_query .=  " and post_status ='$param[post_status]'";
			}else{
				$sql_query .=  " and post_status ='publish' ";
				}
			//Order by
			if(!empty($param['order_by']) && isset($param['order_by'])){
				$sql_query .=  " order by ". $param['order_by'] ;
			}else{
				$sql_query .=  " order by ID ";
				}
			//order and limit
			$limit = ($param['per_page'])?' limit '. $param['per_page']:' ';
			$sql_query .=  $param['order'] . $limit ;
			
			return $db->sql_query($sql_query);
		}
		
			//Get multipal posts
	function get_posts_rand($param){
		global $db;
		//Get post param
		if(!is_array($param) && empty($param)){
			$param = array(
			'per_page' 		=> '',
			'category' 		=> '',
			'post_type'		=> 'post',
			'order_by'	 	=> '',
			'order' 		=> 'DESC',
			'feild' 		=> '',
			'post_status' 	=> 'publish',
				);
			}//End array
			
			$sql_query  =  "select $param[feild] from ad_posts where ";
			$sql_query .=  "post_type ='$param[post_type]' ";
			//Category id
			if(!empty($param['category']) && isset($param['category'])){
			$sql_query .=  " and post_cat ='$param[category]'";
			}
			//Post status
			if(!empty($param['post_status']) && isset($param['post_status']) ){
			$sql_query .=  " and post_status ='$param[post_status]'";
			}else{
				$sql_query .=  " and post_status ='publish' ";
				}
			//Order by
			if(!empty($param['order_by']) && isset($param['order_by'])){
				$sql_query .=  " order by ". $param['order_by'] ;
			}else{
				$sql_query .=  " order by ID ";
				}
			//order and limit
			$limit = ($param['per_page'])?' limit '. $param['per_page']:' ';
			$sql_query .=  $param['order'] . $limit ;
			return $db->sql_query($sql_query);
		}
		
		//Delete Post
			function del_post($post_id = '',$type){
				global $db;
				if(!empty($post_id)){
					$return = $db->sql_query("delete from ad_posts where ID = ".$post_id);
					}
					if($type != ""){
						ad_redirect('pages.php?type='.$type);
					}else{
						ad_redirect('pages.php');
					}
				
				}
				
				
				
				//Delete Post
			function del_cat($post_id = '',$type){
				global $db;
				if(!empty($post_id)){
					$return = $db->sql_query("delete from ad_categories where ID = ".$post_id);
					}
				ad_redirect('category.php?type='.$type);
				}
				