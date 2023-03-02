<!-- products  -->

	<h4 style="padding: 0;margin: 0;">Requests Quota Products</h4>
	<br>
	<div class="main-quiz-user">
        <?php
        $arr_of_prods = get_user_meta(get_current_user_id(),'multi_vendor_request');
		$arr_of_prods_for = $arr_of_prods;
		if (count($arr_of_prods) == 0) $arr_of_prods[]=-1;
		foreach($arr_of_prods_for as $parent_prod){
			$handle=new WC_Product_Variable($parent_prod);
			$variations1=$handle->get_children();
			foreach ($variations1 as $value) {
				$single_variation=new WC_Product_Variation($value);
				$arr_of_prods[] = $single_variation->get_ID();
			}
			foreach(get_children($parent_prod) as $child)
				$arr_of_prods[] =  $child->ID;
		}
        
        $arr_of_query = array(
                    'post_type' => 'rfq_request',
                    'post_status' => 'publish',
                    'posts_per_page'=>-1,
                    'meta_query'=>array(
                                         array('key' => 'product',
                                                'value' => $arr_of_prods ,
                                                'compare' => 'IN'),
                                        array('key' => 'status',
                                                'value' => 'Approved',
                                                'compare' => '='),
                                        ),
                );
        $query_quiz_total = new WP_Query($arr_of_query);
        

         $total_requests = $query_quiz_total->post_count;
                
        $key = 0 ;
                $page_num = isset($_GET['page_num']) ? $_GET['page_num'] : 1;
                $array_of_search=array();
                if (count($arr_of_prods) == 0) $arr_of_prods[]=-1;
                if(isset($_GET['s_product'])){
					$arr_of_prods_search = array();
					$arr_of_prods_search[] = $_GET['s_product'];
					$handle=new WC_Product_Variable($_GET['s_product']);
					$variations1=$handle->get_children();
					
					foreach ($variations1 as $value) {
						$single_variation=new WC_Product_Variation($value);
						$arr_of_prods_search[] = $single_variation->get_ID();
					}
					
					foreach(get_children($_GET['s_product']) as $child)
						$arr_of_prods_search[] =  $child->ID;
						$array_of_search = array('key' => 'product',
						   'value' => $arr_of_prods_search,
						   'compare' => 'IN');
                    
                } else $array_of_search = array('key' => 'product',
                                                'value' => $arr_of_prods ,
                                                'compare' => 'IN');
				
                $arr_of_query = array(
                    'post_type' => 'rfq_request',
                    'post_status' => 'publish',
                    'posts_per_page'=>-1,
                    'meta_query'=>array( $array_of_search
                                         ,
                                        array('key' => 'status',
                                                'value' => 'Approved',
                                                'compare' => '='),
                                        ),
                );
                // search user
                
                $query_quiz_total = new WP_Query($arr_of_query);
                //var_dump( $query_quiz_total->post_count);
                $arr_of_query ['posts_per_page']=20;
                $arr_of_query ['paged']=$page_num;
                
                
                $query_quiz = new WP_Query($arr_of_query);
                $count_res = $query_quiz_total->post_count;
                //var_dump($query_quiz);
        ?>
        Total : <?=$total_requests?>  <br><br>
        <div class=" my-4">
        <?php
            $s_status = $_GET['status']??'';
            $s_user = $_GET['user']??'';
            $s_product = $_GET['s_product']??'';
            
        ?>
        <div class="row">
            
            <div class="col-md-4">
                <select class="select2 form-select select_product" style="width:200px" name="product">
                    <option value="-1">Select Product</option>
                    <?php
                            $args = array(
                                'post_type'      => 'product',
                                'posts_per_page' => -1,
                                'post__in' => $arr_of_prods,
                            );
                        
                            $loop = new WP_Query( $args );
                        
                            while ( $loop->have_posts() ) : $loop->the_post();
                                global $product;
                                echo '<option value="'.get_the_ID().'" '.
                            (($s_product==get_the_ID())?'selected':'').'>'.get_the_title().'</option>';
                            endwhile;
                        
                            wp_reset_query(); ?>
                    
                    
                    
                     
                    
                  </select>
            </div>
            
        </div>
        <span> showing <?=$query_quiz->post_count?> out of <?=$count_res?> </span>
        
        </div>
        <table>
            <tr>
              <th class="d-none d-sm-block">#</th>
              <th>Product</th>
              <th>Quantity</th>
              <th>Status</th>
              
              <th></th>
            </tr>
            <?php
                

                while ($query_quiz->have_posts()) {
                    $key++;
                    $query_quiz->the_post();
                    $product = get_post_meta(get_the_ID(), 'product', true);
                    $user    = get_post_meta(get_the_ID(), 'quantity', true);
                    if (!$product) continue ;
                    
                    $status = get_post_meta(get_the_ID(), 'status', true);
                    //$status = $status?$status:'waiting'
					// get vendor proposal
					$proposal = get_posts(array(
											   'post_type'=>'rfq_proposal',
											   'author'=>get_current_user_id() ,
											   'meta_query'=>array(
																   array('key' => 'request',
																	'value' => get_the_ID(),
																	'compare' => '='),
															),
											   ));
					$proposal = $proposal[0]??false ;
					//var_dump($proposal);
					$proposal_status =($proposal)?get_post_meta($proposal->ID, 'status', true):'Waiting';
					$proposal_price =($proposal)?get_post_meta($proposal->ID, 'price', true):'';
					$proposal_notes =($proposal)?$proposal->post_content:'';
					$proposal_id =($proposal)?$proposal->ID:'';
			?>
			
			<tr>
                <td class="d-none d-sm-block"><?=(($page_num-1)*20)+$key?></td>
                <td><a href="<?=get_the_permalink($product)?>" target="_blank"><?=get_the_title($product)?></a></td>
                <td><?=$user?></td>
                <td class="proposal_<?=$proposal_status?>"><?=$proposal_status?></td>
                
                <td class="delete_access" data-class="<?=$product?>" data-user="<?=$product?>">
                    <span>
                        <div id="modal_<?=get_the_ID()?>" class="modal modal-request">
                        <!-- show proposal edits  -->
						<div class="container p-4">
                        <div>
                            <label style="font-size: 1.4rem;">Product Request</label>  </div><div>
                            <label>Product </label> <span><?=get_the_title($product)?></span></div><div>
							<?php
								$user_info = get_userdata($user);
								$is_paid = get_the_author_meta( 'is_paid', get_current_user_id() );
							?>
                            <label>User </label> <span><?= ($is_paid==1)?$user_info->user_login :'*****'; ?></span></div><div>
                            <label>Quantity </label> <span><?=get_post_meta(get_the_ID(), 'quantity', true)?></span></div><div>
                            <label>Company </label> <span><?=($is_paid==1)?get_post_meta(get_the_ID(), 'company', true) :'*****'; ?></span></div><div>
                            <label>Address </label> <span><?=($is_paid==1)?get_post_meta(get_the_ID(), 'address', true):'*****'; ?></span></div><div>
                            <label>Status </label><span class="status_<?=$status?>"><?=$status?></span></div><div>
                            <label>Date </label><span ><?=date('Y-m-d h:i:s A',get_post_time()) ?></span></div><div>
                            <label>last update</label><span ><?=date('Y-m-d h:i:s A',get_post_modified_time())?></span></div><div>
                            <label>Notes </label> <span><?=get_post_meta(get_the_ID(), 'notes', true)?></span></div>
							<hr><div>
							
							<label>Seller Price </label> <span>
							<input type="number" class="xoo-aff-required xoo-aff-text form-control" name="price_<?=get_the_ID()?>"
							placeholder="Seller Price(*)" value="<?=$proposal_price?>"
                autocomplete="quantity" required="	">
							</span></div><div>
							<label>details of the offer </label> <span style="width: 100%;">
							<textarea name="notes_<?=get_the_ID()?>" placeholder="details of the offer ..."
							 style="width: 100%;border-color: #ccc;
            box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);padding: 10px;resize: none;border-radius: 0px 10px 10px 0;" ><?=$proposal_notes?></textarea>
       
							</span></div>
							
                            <br>
                            <?php if ($proposal_status == 'Waiting' ) { ?>
                            <button class="btn btn-primary btn-submit-proposal" data-class="<?=get_the_ID()?>">Submit</button>
                            <?php } else if ($proposal_status == 'Submitted') { ?>
                            <button class="btn btn-danger  btn-remove-proposal" data-class="<?=$proposal_id?>">Remove</button>
                            <?php } ?>
                            <a href="#" onclick="$.fancybox.close();jQuery('body').removeClass('modal-open');return false;" style="float: right;"
							   class="btn btn-danger">Close</a>
                        <!-- show proposal edits  -->
                        </div>
    </div>
                        <!-- Link to open the modal -->
                        
                            <a href="#modal_<?=get_the_ID()?>" class="fancybox" style="" rel="modal:open">
                                <i class="fa fa-eye" style="color:blue;"></i>
                            </a>
                        
                    </span>
                    
                    <span></span>
                </td>
              </tr>
			<?php
		
		}
		
		wp_reset_query();
		
	?>
	</table>
		<div class="pagination1">
	  
			<?php  for($i=1;$i<(ceil($count_res/20))+1;$i++){ ?>
			<a href="?page_num=<?=$i?>" <?= ($i==$page_num)?'class="active"':''?>><?=$i?></a>
			<?php } ?>	
		
		
	  </div>
	</table>
	
	</div>
	
	<!-- products  -->
    
