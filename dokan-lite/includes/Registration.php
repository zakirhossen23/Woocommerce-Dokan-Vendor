<?php

namespace WeDevs\Dokan;

use WP_Error;

/**
 * Vendor Registration
 *
 * @since 2.8
 */
class Registration {

    public function __construct() {
        // validate registration
        add_filter( 'woocommerce_process_registration_errors', array( $this, 'validate_registration' ) );
        add_filter( 'woocommerce_registration_errors', array( $this, 'validate_registration' ) );

        // after registration
        add_filter( 'woocommerce_new_customer_data', array( $this, 'set_new_vendor_names' ) );
        add_action( 'woocommerce_created_customer', array( $this, 'save_vendor_info' ), 10, 2 );
    }

    /**
     * Validate vendor registration
     *
     * @param  \WP_Error $error
     *
     * @return \WP_Error
     */
    public function validate_registration( $error ) {
        if ( is_checkout() ) {
            return $error;
        }

        if ( defined( 'WP_CLI' ) || defined( 'REST_REQUEST' ) ) {
            return $error;
        }

        $post_data   = wp_unslash( $_POST );
        $nonce_check = apply_filters( 'dokan_register_nonce_check', true );

        if ( $nonce_check ) {
            $nonce_value = isset( $post_data['_wpnonce'] ) ? sanitize_key( $post_data['_wpnonce'] ) : '';
            $nonce_value = isset( $post_data['woocommerce-register-nonce'] ) ? sanitize_key( $post_data['woocommerce-register-nonce'] ) : $nonce_value;

            if ( empty( $nonce_value ) || ! wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
                return new WP_Error( 'nonce_verification_failed', __( 'Nonce verification failed', 'dokan-lite' ) );
            }
        }

        $allowed_roles = apply_filters( 'dokan_register_user_role', array( 'customer', 'seller','hospital' ) );

        // is the role name allowed or user is trying to manipulate?
        if ( isset( $post_data['role'] ) && ! in_array( $post_data['role'], $allowed_roles, true ) ) {
            return new WP_Error( 'role-error', __( 'Cheating, eh?', 'dokan-lite' ) );
        }

        $role = $post_data['role'];

        $required_fields_hospital = apply_filters(
            'dokan_seller_registration_required_fields', array(
				'hospital-name'    => __( 'Please enter hospital name.', 'dokan-lite' ),
				'person-incharge'    => __( 'Please enter person incharge.', 'dokan-lite' ),
				'hospital-phone'    => __( 'Please enter phone number.', 'dokan-lite' ),
				'hospital-address' => __( 'Please provide hospital address.', 'dokan-lite' ),
            )
        );


        $required_fields = apply_filters(
            'dokan_seller_registration_required_fields', array(
				'company-name'    => __( 'Please enter your company name.', 'dokan-lite' ),
				'contact-person'    => __( 'Please enter contact person.', 'dokan-lite' ),
				'seller-phone'    => __( 'Please enter your phone number.', 'dokan-lite' ),
				'seller-address'    => __( 'Please enter your address.', 'dokan-lite' ),
            )
        );

        if ( $role === 'hospital' ) {
            foreach ( $required_fields_hospital as $field => $msg ) {
                if ( empty( trim( $post_data[ $field ] ) ) ) {
                    return new WP_Error( "$field-error", $msg );
                }
            }
        }

        if ( $role === 'seller' ) {
            foreach ( $required_fields as $field => $msg ) {
                if ( empty( trim( $post_data[ $field ] ) ) ) {
                    return new WP_Error( "$field-error", $msg );
                }
            }
        }

        return $error;
    }

    /**
     * Inject first and last name to WooCommerce for new vendor registraion
     *
     * @param array $data
     * @return array
     */
    public function set_new_vendor_names( $data ) {
        $post_data = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification

    
        $allowed_roles = apply_filters( 'dokan_register_user_role', array('customer', 'seller','hospital') );
        $role          = ( isset( $post_data['role'] ) && in_array( $post_data['role'], $allowed_roles, true ) ) ? $post_data['role'] : 'customer';

        $data['role'] = $role;

        if ( $role === 'seller' ) {
            $data['company_name']           = wp_strip_all_tags( $post_data['company-name'] );
            $data['contact_person']         = wp_strip_all_tags( $post_data['contact-person'] );
            $data['seller_phone']           = wp_strip_all_tags( $post_data['seller-phone'] );
            $data['seller_address']         = wp_strip_all_tags( $post_data['seller-address'] );
            $data['seller_categories']      = $post_data['seller-categories'];
    
        }else if  ( $role === 'hospital' ) {
            $data['hospital_name']          = wp_strip_all_tags( $post_data['hospital-name'] );
            $data['person_incharge']        = wp_strip_all_tags( $post_data['person-incharge'] );
            $data['hospital_phone']         = wp_strip_all_tags( $post_data['hospital-phone'] );
            $data['hospital_address']       = wp_strip_all_tags( $post_data['hospital-address'] );
    
        }else if ( $role === 'customer' ) {
            $data['customer_name']          = wp_strip_all_tags( $post_data['customer-name'] );
            $data['customer_phone']         = wp_strip_all_tags( $post_data['customer-phone'] );
            $data['customer_address']       = wp_strip_all_tags( $post_data['customer-address'] );
          
        }

       
        return $data;
    }

    
    /**
     * Adds default dokan store settings when a new vendor registers
     *
     * @param int $user_id
     * @param array $data
     *
     * @return void
     */
    public function save_vendor_info( $user_id, $data ) {
        $post_data = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification
        global $wpdb;
     
        $user   = new \stdClass();
        $user->ID     = (int)$user_id;

        //$data = _ and $post_data = -
         
        if ( isset( $data['role'] ) && $data['role'] == 'hospital' ) {
           
            $my_custom_filename = time() . $_FILES['logo']['name'];
            $output = wp_upload_bits( $my_custom_filename , null, file_get_contents($_FILES['logo']['tmp_name']));
           
            $user_details = array(
                'hospital_name'     => sanitize_text_field( wp_unslash( $data['hospital_name'] ) ),     
                'person_incharge'     => sanitize_text_field( wp_unslash( $data['person_incharge'] ) ),
                'hospital_phone'     => sanitize_text_field( wp_unslash( $data['hospital_phone'] ) ),
                'hospital_logo'     => sanitize_text_field( wp_unslash( $output['url']) ),
                'hospital_address'     => sanitize_text_field( wp_unslash( $data['hospital_address'] ) )
            );

            update_user_meta( $user_id, 'user_details', $user_details );
            update_user_meta( $user_id, 'first_name', ((object)$user_details)->hospital_name );
            update_user_meta( $user_id, 'nickname',((object)$user_details)->hospital_name);
            $user->display_name = ((object)$user_details)->hospital_name;
            wp_update_user( $user );
            return;
        }else if ( isset( $data['role'] ) && $data['role'] == 'customer' ) {
            $user_details = array(
                'customer_name'     => sanitize_text_field( wp_unslash( $data['customer_name'] ) ),     
                'customer_phone'     => sanitize_text_field( wp_unslash( $data['customer_phone'] ) ),
                'customer_address'     => sanitize_text_field( wp_unslash( $data['customer_address'] ) ),
            );

            update_user_meta( $user_id, 'user_details', $user_details );
            update_user_meta( $user_id, 'first_name', ((object)$user_details)->customer_name );
            update_user_meta( $user_id, 'nickname',((object)$user_details)->customer_name);
            $user->display_name = ((object)$user_details)->customer_name;
            wp_update_user( $user );
            return;
        }




        if ( ! isset( $data['role'] ) || $data['role'] !== 'seller' ) {
            return;
        }

        $user_details = array(
            'company_name'     => sanitize_text_field( wp_unslash( $data['company_name'] ) ),     
            'contact_person'     => sanitize_text_field( wp_unslash( $data['contact_person'] ) ),
            'seller_phone'     => sanitize_text_field( wp_unslash( $data['seller_phone'] ) ),
            'seller_address'     => sanitize_text_field( wp_unslash( $data['seller_address'] ) ),
            'seller_categories'     => $data['seller_categories']  
        );

        update_user_meta( $user_id, 'user_details', $user_details );
        
        $social_profiles = array();

        foreach ( dokan_get_social_profile_fields() as $key => $item ) {
            $social_profiles[ $key ] = '';
        }

        $dokan_settings = array(
            'store_name'     => sanitize_text_field( wp_unslash( $post_data['shopname'] ) ),
            'social'         => $social_profiles,
            'payment'        => array(),
            'phone'          => sanitize_text_field( wp_unslash( $post_data['phone'] ) ),
            'show_email'     => 'no',
            'location'       => '',
            'find_address'   => '',
            'dokan_category' => '',
            'banner'         => 0,
        );

        // Intially add values on profile completion progress bar
        $dokan_settings['profile_completion']['store_name']     = 10;
        $dokan_settings['profile_completion']['phone']          = 10;
        $dokan_settings['profile_completion']['next_todo']      = 'banner_val';
        $dokan_settings['profile_completion']['progress']       = 20;
        $dokan_settings['profile_completion']['progress_vals']  = array(
            'banner_val'            => 15,
            'profile_picture_val'   => 15,
            'store_name_val'        => 10,
            'address_val'           => 10,
            'phone_val'             => 10,
            'map_val'               => 15,
            'payment_method_val'    => 15,
            'social_val' => array(
                'fb'        => 4,
                'twitter'   => 2,
                'youtube'   => 2,
                'linkedin'  => 2,
            ),
        );

        update_user_meta( $user_id, 'dokan_profile_settings', $dokan_settings );
        update_user_meta( $user_id, 'dokan_store_name', $dokan_settings['store_name'] );

        do_action( 'dokan_new_seller_created', $user_id, $dokan_settings );
    }
}
