<?php
// cf7 extend
add_action( 'wp_ajax_send_rfq', 'send_rfq_fn' );
add_action( 'wp_ajax_nopriv_send_rfq', 'send_rfq_fn' );

function send_rfq_fn(){
    if (isset($_POST['quantity'])){
        // save rfq post 
        $post_id = wp_insert_post(array (
                    'post_type' => 'rfq_request',
                    'post_title' => 'request for qouta',
                    'post_content' => '',
                    'post_status' => 'publish',
                    'comment_status' => 'closed',   // if you prefer
                    'ping_status' => 'closed',      // if you prefer
                    'post_author'=> get_current_user_id(),
                    
                ));
                
        if ($post_id) {
            // insert post meta
            add_post_meta($post_id, 'quantity', $_POST['quantity']);
            add_post_meta($post_id, 'brand', $_POST['brand']);
            add_post_meta($post_id, 'country', $_POST['country']);
            add_post_meta($post_id, 'notes', $_POST['notes']);
            add_post_meta($post_id, 'product', $_POST['product']);
            add_post_meta($post_id, 'status', 'Waiting');
            // notify admin by mail
            // notify vendors by mail
            
            echo 'success';
            die ;
            
        }
           
    }
}

// approve_request
add_action( 'wp_ajax_approve_request', 'approve_request_fn' );
add_action( 'wp_ajax_nopriv_approve_request', 'approve_request_fn' );

function approve_request_fn(){
    update_post_meta($_POST['request_id'], 'status', 'Approved');
    $post_request = get_post($_POST['request_id']);
    wp_update_post($post_request);
    // send mail to user including view
    // send email to product vendors 
    die ;
}

add_action( 'wp_ajax_refuse_request', 'refuse_request_fn' );
add_action( 'wp_ajax_nopriv_refuse_request', 'refuse_request_fn' );

function refuse_request_fn(){
    update_post_meta($_POST['request_id'], 'status', 'Refused');
    $post_request = get_post($_POST['request_id']);
    wp_update_post($post_request);
    die ;
}


// approve_user
add_action( 'wp_ajax_approve_user', 'approve_user_fn' );
add_action( 'wp_ajax_nopriv_approve_user', 'approve_user_fn' );

function approve_user_fn(){
    global $wpdb;

    $user_id = (int) $_POST['user_id'];	
    $wpdb->update($wpdb->users, array('user_status' => 0), array('ID' => $user_id));

    // send mail to admin / seller
    include  plugin_dir_path( __FILE__ )  . 'emails/approve_user.php';
   
    echo  var_export(send_approve_user( $user_id),true);
    die ;
}

add_action( 'wp_ajax_reject_user', 'reject_user_fn' );
add_action( 'wp_ajax_nopriv_reject_user', 'reject_user_fn' );

function reject_user_fn(){
    $user               = new stdClass();
    $user->ID           = $_POST['user_id'];
    $user->user_status  = 2;
    wp_update_user( $user );

    die ;
}



// submit_proposal
add_action( 'wp_ajax_submit_proposal', 'submit_proposal_fn' );
add_action( 'wp_ajax_nopriv_submit_proposal', 'submit_proposal_fn' );

function submit_proposal_fn(){
    $req_id = $_POST['request_id'];
    $price = $_POST['price'];
    $notes = $_POST['notes'];
    
    $post_id = wp_insert_post(array (
                    'post_type' => 'rfq_proposal',
                    'post_title' => 'proposal for request',
                    'post_content' => $notes,
                    'post_status' => 'publish',
                    'comment_status' => 'closed',   // if you prefer
                    'ping_status' => 'closed',      // if you prefer
                    'post_author'=> get_current_user_id(),
                    
                ));
                
    if ($post_id) {
        // insert post meta
        update_post_meta($post_id, 'request', $req_id );
        update_post_meta($post_id, 'price', $price);
        update_post_meta($post_id, 'status', 'Submitted');
        // notify user by mail
        
        echo 'success';
        die ;
        
    }
}

// remove_proposal
add_action( 'wp_ajax_remove_proposal', 'remove_proposal_fn' );
add_action( 'wp_ajax_nopriv_remove_proposal', 'remove_proposal_fn' );

function remove_proposal_fn(){
    $proposal = get_post($_POST['proposal_id']);
    if($proposal && $proposal->post_author == get_current_user_id() ){
        delete_post_meta($post_id, 'request' );
        delete_post_meta($post_id, 'price');
        delete_post_meta($post_id, 'status');
        wp_delete_post($_POST['proposal_id']);
    }
    die;
}

//approve_proposal
add_action( 'wp_ajax_approve_proposal', 'approve_proposal_fn' );
add_action( 'wp_ajax_nopriv_approve_proposal', 'approve_proposal_fn' );

function approve_proposal_fn(){
    //echo 'send mail';
    //include  plugin_dir_path( __FILE__ )  . 'emails/approve_request.php';
    //echo send_approve();
    //die ;
    $proposal = get_post($_POST['proposal_id']);
    if ($proposal){
        $request_id = get_post_meta($proposal->ID, 'request',true );
        $request  = get_post($request_id);
        if ($request->post_author == get_current_user_id()){
            // ignor all proposla
            $proposals = get_posts(
                            array(
                                    'post_type'=>'rfq_proposal',
                                    'meta_query'=>array(
                                                        array('key' => 'request',
                                                         'value' => $request->ID,
                                                         'compare' => '='),
                                                 ),
                                    ));
            foreach($proposals as $prop) {
                update_post_meta($prop->ID, 'status','Ignored');
            }
            update_post_meta($proposal->ID, 'status','Approved');
            update_post_meta($request_id, 'chosen',$proposal->ID);
            // send mail to admin / seller
            include  plugin_dir_path( __FILE__ )  . 'emails/approve_request.php';
            echo send_approve($request_id);
        }
    }
    die ; 
}

// add user meta
add_action( 'show_user_profile', 'erweb_add_extra_social_links' );
add_action( 'edit_user_profile', 'erweb_add_extra_social_links' );
 
function erweb_add_extra_social_links( $user )
{
    $roles = ( array ) $user->roles;
    if(!in_array('seller',$roles))
        return ; 
    ?>
        <h3>Vendor Paid Status</h3>
 
        <table class="form-table">
            <tr>
                <th><label for="is_paid">Is this vendor paid ?</label></th>
                <td><input type="checkbox" name="is_paid" 
                value="1"
                <?= (get_the_author_meta( 'is_paid', $user->ID )==1)?'checked="checked"':''; ?>
                class="regular-text" /></td>
            </tr>
 
        </table>
    <?php
}
function tm_save_profile_fields( $user_id ) {

    if ( ! current_user_can( 'edit_user', $user_id ) ) {
   	 return false;
    }

    if ( empty( $_POST['is_paid'] ) ) {
        update_usermeta( $user_id, 'is_paid', 0 );
   	 return false;
    }

    update_usermeta( $user_id, 'is_paid', $_POST['is_paid'] );
}

add_action( 'personal_options_update', 'tm_save_profile_fields' );
add_action( 'edit_user_profile_update', 'tm_save_profile_fields' );

