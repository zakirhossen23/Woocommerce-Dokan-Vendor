function deselect(e) {
    jQuery('.pop').slideFadeToggle(function () {
        e.removeClass('selected');
    });
}

jQuery(function () {
    jQuery('#request_quota').on('click', function () {
        if (jQuery(this).hasClass('selected')) {
            deselect(jQuery(this));
        } else {
            jQuery(this).addClass('selected');
            jQuery('.pop').slideFadeToggle();
        }
        return false;
    });

    jQuery('.close').on('click', function () {
        deselect(jQuery('#request_quota'));
        return false;
    });
});

jQuery.fn.slideFadeToggle = function (easing, callback) {
    return this.animate({
        opacity: 'toggle',
        height: 'toggle'
    }, 'fast', easing, callback);
};

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

jQuery(document).ready(function ($) {

    // table
    //jQuery('#proposlas-table').DataTable({});
    var dt = $('#proposlastable').DataTable({
        columns: [
            { data: 'ID' },
            { data: 'Vendor' },
            { data: 'Price' },
            { data: 'Attachment' },
            { data: 'Date' },
            { data: 'Status' },
            { data: 'Options' },
        ],
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [0, 5] }
        ],
        pageLength: 25
    });

    //jQuery('.fancybox-close-small').click(function(){
    jQuery(document).on('click', '.fancybox-close-small', function () {
        jQuery('body').removeClass('modal-open');
    });


    if (getUrlParameter('rfq')) {
        console.log('triggered');
        jQuery('#request_quota').trigger('click');
    }

    //jQuery('.save_request').click(function(){
    jQuery(".save_request").submit(function (e) {

        //prevent Default functionality
        e.preventDefault();
        jQuery('.button').prop('disabled', true);
        // save request by ajax
        let formData = new FormData();           
        formData.append("action", 'send_rfq');
        formData.append("quantity", jQuery('input[name=quantity]').val());
        formData.append("brand", jQuery('input[name=brand]').val());
        formData.append("country", jQuery('input[name=country]').val());
        formData.append("attachment", jQuery('input[name=attachment]')[0].files[0]);
        formData.append("product", jQuery('input[name=product]').val());
        formData.append("notes", jQuery('textarea[name=notes]').val());
     
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.ajax({
            url: xoo_el_localize.adminurl,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                if (response == 'success') {
                    //jQuery('.messagepop form').fadeOut();
                    jQuery('.xoo-aff-group').fadeOut();
                    jQuery('.save_request_btn').fadeOut()
                    jQuery('.req_confirm_message').fadeIn();
                } else alert('error happpen');
    
                jQuery('.btn').prop('disabled', false);
            },
        });
       
        return false;
    });

    // approve
    jQuery('.modal-request .btn-approve').click(function () {
        var data = {
            'action': 'approve_request', //     
            'request_id': jQuery(this).data('class'),
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(xoo_el_localize.adminurl, data, function (response) {
            alert('status updated successfully.');
            location.reload();
        });


        return false;

    });

    jQuery('.modal-request .btn-refuse').click(function () {
        var data = {
            'action': 'refuse_request', //     
            'request_id': jQuery(this).data('class'),
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(xoo_el_localize.adminurl, data, function (response) {
            alert('status updated successfully.');
            location.reload();
        });

        return false;

    });



    // approve for user modaL
    jQuery('.modal-user .btn-approve').click(function () {
        var data = {
            'action': 'approve_user', //     
            'user_id': jQuery(this).data('class'),
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(xoo_el_localize.adminurl, data, function (response) {

            alert('status updated successfully.');
            location.reload();
        });


        return false;

    });
    // reject for user modaL
    jQuery('.modal-user .btn-refuse').click(function () {
        var data = {
            'action': 'reject_user', //     
            'user_id': jQuery(this).data('class')
        }

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(xoo_el_localize.adminurl, data, function (response) {
            alert('status updated successfully.');
            location.reload();
        });

        return false;

    });

    jQuery('.s_status').change(function () {
        return search();
    });
    jQuery('.select_user').on('change', function () {

        return search();
    });
    jQuery('.select_product').on('change', function () {

        return search();
    });

    jQuery('.select_request').on('change', function () {
        window.location.href = ('?s_request=' + jQuery(".select_request option:selected").val());
    });

    jQuery('.select2').select2({
        placeholder: "select option",
        allowClear: false,
        width: "resolve",
        height: "33px",
        containerCssClass: 'my-select'
    });

    // submit proposal 
    jQuery('.btn-submit-proposal').click(function () {
        var req_id = jQuery(this).data('class');

        if (jQuery('input[name=price_' + req_id + ']').val() == "") return alert('submitted price is not a number');
        if (jQuery('input[name=brand_' + req_id + ']').val() == "") return alert('submitted Brand is empty');
        if (jQuery('input[name=notes_' + req_id + ']').val() == "") return alert('submitted description is empty');
       
        let formData = new FormData();           
        formData.append("action", 'submit_proposal');
        formData.append("request_id",req_id);
        formData.append("price", jQuery('input[name=price_' + req_id + ']').val());
        formData.append("brand", jQuery('input[name=brand_' + req_id + ']').val());
        formData.append("country", jQuery('input[name=country_' + req_id + ']').val());
        formData.append("attachment", jQuery('input[name=attachment_' + req_id + ']')[0].files[0]);
        formData.append("notes", jQuery('textarea[name=notes_' + req_id + ']').val());
        formData.append("term", jQuery('textarea[name=term_' + req_id + ']:checked').val());
     
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.ajax({
            url: xoo_el_localize.adminurl,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                if (response == 'success') {
                    alert('proposal submitted succesfully.');
                    location.reload();
                } else alert('error happpen');
            },
        });

    });

    jQuery('.btn-approve-proposal').click(function () {
        var proposal_id = jQuery(this).data('class');
        var data = {
            'action': 'approve_proposal', //     
            'proposal_id': proposal_id,

        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(xoo_el_localize.adminurl, data, function (response) {
            alert('proposal Approved succesfully.');
            location.reload();
        });
    });

    jQuery('.btn-decline-proposal').click(function () {
        var proposal_id = jQuery(this).data('class');
        var data = {
            'action': 'decline_proposal', //     
            'proposal_id': proposal_id,

        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(xoo_el_localize.adminurl, data, function (response) {
            alert('proposal Declined succesfully.');
            location.reload();
        });
    });

    // btn-remove-proposal
    jQuery('.btn-remove-proposal').click(function () {
        var proposal_id = jQuery(this).data('class');


        var data = {
            'action': 'remove_proposal', //     
            'proposal_id': proposal_id,

        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(xoo_el_localize.adminurl, data, function (response) {
            alert('proposal removed succesfully.');
            location.reload();
        });
    });
    jQuery('.card a[role="tab"]').click(function (e) {
        e.preventDefault();
        jQuery('.tab-content .tab-pane').removeClass('active');
        jQuery('.card ul li').removeClass('active');
        jQuery(this).parent().addClass('active');
        jQuery('.tab-content .' + jQuery(this).data('class')).addClass('active');

        return false;
    });



    // end 
});

function search() {
    status_part = '';
    user_part = '';
    p_part = '';
    // check empty
    if (jQuery('.s_status').val() != -1 && jQuery(".s_status").val() != undefined) {
        search_status = jQuery('.s_status').val();
        status_part = '&status=' + search_status;
    }
    if (jQuery(".select_user option:selected").val() != -1 && jQuery(".select_user option:selected").val() != undefined) {
        search_user = jQuery(".select_user option:selected").val();
        user_part = '&user=' + search_user;
    }
    if (jQuery(".select_product option:selected").val() != -1 && jQuery(".select_product option:selected").val() != undefined) {
        search_product = jQuery(".select_product option:selected").val();
        p_part = '&s_product=' + search_product;
    }


    window.location.href = ('?' + status_part + user_part + p_part);
}