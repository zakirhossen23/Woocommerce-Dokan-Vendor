<?php
/*
Plugin Name: Offer Proposal
*/

// cf7
require_once(plugin_dir_path(__FILE__) . 'cf7extend.php');
require_once(plugin_dir_path(__FILE__) . 'multivendor.php');

$var = 'F' . rand(2, 100) . rand(1, 100);

// seller redirect
add_filter('woocommerce_login_redirect', 'ckc_login_redirect', 10, 2);

function ckc_login_redirect($redirect_url, $user)
{


    if ($user->roles[0] == 'seller') {
        return 'https://tekoa.co.ke/index.php/my-account-2/';
    }

    return $redirect_url;
}

// add request offer instead off add to cart 
add_action('woocommerce_product_meta_end', 'add_text_after_excerpt_single_product', 30);
function add_text_after_excerpt_single_product()
{
    // Your custom text
    $post_excerpt = '';
    if (is_user_logged_in()) {
?>
        <div class="messagepop pop">
            <form class="xoo-el-action-form xoo-el-form-login save_request">
                <div class="xoo-aff-group xoo-el-username_cont">
                    <div class="xoo-aff-input-group d-flex flex-column ">
                        <label >
                            Quantity <span class="required">*</span>
                        </label>
                        <input type="number" class="xoo-aff-required xoo-aff-text" name="quantity" placeholder="Quantity(*)" value="" autocomplete="quantity" required>
                    </div>
                </div>
                <div class="xoo-aff-group xoo-el-username_cont">
                    <div class="xoo-aff-input-group d-flex flex-column ">
                        <label >
                           Preferred Brand or Leave as blank
                        </label>
                        <input type="text" class="xoo-aff-required xoo-aff-text" name="brand" placeholder="Brand Name" autocomplete="brand">
                    </div>
                </div>
                <div class="xoo-aff-group xoo-el-username_cont">
                    <div class="xoo-aff-input-group d-flex flex-column ">
                        <label >
                        Preferred Country of origin or Leave as blank
                        </label>
                        <input type="text" class="xoo-aff-required xoo-aff-text" name="country" placeholder="Country of origin"  autocomplete="country">
                    </div>
                </div>
                <div class="xoo-aff-group xoo-el-username_cont">
                    <div class="xoo-aff-input-group d-flex flex-column ">
                        <label >
                            Attach a photo or document
                        </label>
                        <input type="file" class="form-control" name="attachment"  required="required" />
                    </div>
                </div>

              
                <div class="xoo-aff-group xoo-el-username_cont">
                    <div class="xoo-aff-input-group d-flex flex-column ">
                        <label >
                            Describe
                        </label>
                        <textarea name="notes" placeholder="specific requests ..." style="width: 100%;border-color: #ccc;
            box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);padding: 10px;resize: none;border-radius: 0px 10px 10px 0;"></textarea>
                    </div>
                </div>
                <div class="req_confirm_message" style="display:none;background: #42ba96;color: #fff;margin: 10px;padding: 20px;
                text-align: justify;">thanks for submitting your request. You will be notified by mail with new proposal and you can check proposals any time at your <a href='<?= get_permalink( get_option('woocommerce_myaccount_page_id') ) ?>request-quota'>account > requests.</a></div>
                <input type="hidden" name="product" value='<?= get_the_ID() ?>'>
                <button type="submit" class="button btn xoo-el-action-btn xoo-el-login-btn save_request_btn">Request</button>
                <button type="button" class="button btn close" style="width: 100px;height: 40px;margin: 0 20px;background: #666;
    color: #fff;border: navajowhite;font-size: 16px;">Cancel</button>



            </form>
        </div>

        <a href="/contact" id="request_quota">ask for a quotation</a>
<?php
        $post_excerpt .= ob_get_contents();
        ob_end_clean();
    } else {
        $post_excerpt .= '<a id="request_quota" class="selected" onclick="return $(\'.customer-signinup\').click()" style="cursor: pointer;">ask for a quotation</a>';
    }

    echo $post_excerpt;
}

add_action('wp_enqueue_scripts', 'my_custom_enqueue_scripts', 10);
function my_custom_enqueue_scripts()
{
    wp_enqueue_style('jquery-datatables-css', '//cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css');
    wp_enqueue_script('jquery-datatables-js', '//cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js', array('jquery'));


    wp_enqueue_style('jquery-datatables-responsive-css', '//cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css');
    wp_enqueue_script('jquery-datatables-responsive-js', '//cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js', array('jquery'));
}

function load_external_jQuery()
{ // load external file  

    global $var;


    wp_register_script('modal-custom-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js', array('jquery'));
    wp_enqueue_script('modal-custom-js');

    wp_register_script('offer-proposal-js', plugin_dir_url(__FILE__)  . 'asset/custom.js?v=3.' . $var, array('jquery'));
    wp_localize_script(
        'ajax-script',
        'my_ajax_object',
        array('ajax_url' => admin_url('admin-ajax.php'))
    );
    wp_enqueue_script('offer-proposal-js');


    //Add the Select2 CSS file
    //wp_enqueue_style( 'select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0-rc.0');

    //Add the Select2 JavaScript file
    //wp_enqueue_script( 'select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', 'jquery', '4.1.0-rc.0');

    // style
    wp_enqueue_style("offer-proposal", plugin_dir_url(__FILE__)  . "asset/custom.css", '', '1.0.' . $var);
}
add_action('wp_enqueue_scripts', 'load_external_jQuery');

//wp_enqueue_script('offer-proposal-js', plugin_dir_url( __FILE__ )  . 'asset/custom.js',array('jQuery'));