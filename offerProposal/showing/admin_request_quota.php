<!-- products  -->


<h4 style="padding: 0;margin: 0;">Requests Quota Products</h4>
<br>
<div class="main-quiz-user">
    <?php

    $requests = new WP_Query();
    $requests->query('post_type=rfq_request');
    $total_requests = $requests->found_posts;

    $requests = new WP_Query();
    $requests->query('post_type=rfq_request&meta_key=status&meta_value=Approved');
    $total_approved = $requests->found_posts;
    $requests = new WP_Query();
    $requests->query('post_type=rfq_request&meta_key=status&meta_value=Refused');
    $total_refused = $requests->found_posts;
    $requests = new WP_Query();
    $requests->query('post_type=rfq_request&meta_key=status&meta_value=Waiting');
    $total_waiting = $requests->found_posts;

    $key = 0;
    $page_num = isset($_GET['page_num']) ? $_GET['page_num'] : 1;
    $array_of_search = array();
    if (isset($_GET['status'])) {
        $array_of_search[] = array(
            'key' => 'status',
            'value' => $_GET['status'],
            'compare' => '='
        );
    }
    if (isset($_GET['s_product'])) {
        /* $array_of_search[] = array('key' => 'product',
                        'value' => $_GET['s_product'],
                        'compare' => '=');*/
        $arr_of_prods_search = array();
        $arr_of_prods_search[] = $_GET['s_product'];
        $handle = new WC_Product_Variable($_GET['s_product']);
        $variations1 = $handle->get_children();

        foreach ($variations1 as $value) {
            $single_variation = new WC_Product_Variation($value);
            $arr_of_prods_search[] = $single_variation->get_ID();
        }
        foreach (get_children($_GET['s_product']) as $child)
            $arr_of_prods_search[] =  $child->ID;
        $array_of_search[] = array(
            'key' => 'product',
            'value' => $arr_of_prods_search,
            'compare' => 'IN'
        );
    }
    $arr_of_query = array(
        'post_type' => 'rfq_request',
        'post_status' => 'publish',
        'meta_query' => $array_of_search,
        'posts_per_page' => -1
    );
    // search user
    if (isset($_GET['user'])) {
        $arr_of_query['author'] = $_GET['user'];
    }

    $query_quiz_total = new WP_Query($arr_of_query);
    //var_dump( $query_quiz_total->post_count);
    $arr_of_query['posts_per_page'] = 20;
    $arr_of_query['paged'] = $page_num;


    $query_quiz = new WP_Query($arr_of_query);
    $count_res = $query_quiz_total->post_count;
    //var_dump($query_quiz);
    ?>
    Total : <?= $total_requests ?> <span class="status_Approved" style="margin:0 10px;">Approved : <?= $total_approved ?></span>
    <span class="status_Refused" style="margin:0 10px;">Refused : <?= $total_refused ?></span>
    <span class="" style="margin:0 10px;">waiting : <?= $total_waiting ?></span> <br><br>
    <div class=" my-4">

        <?php
        $s_status = $_GET['status'] ?? '';
        $s_user = $_GET['user'] ?? '';
        $s_product = $_GET['s_product'] ?? '';

        ?>
        <div class="row">
            <div class="col-md-4">
                <select class="s_status form-select" style="width:200px">
                    <option value="-1">Select Status</option>
                    <option vlaue="Approved" <?= $s_status == 'Approved' ? 'selected' : '' ?>>Approved</option>
                    <option vlaue="Refused" <?= $s_status == 'Refused' ? 'selected' : '' ?>>Refused</option>
                    <option vlaue="Waiting" <?= $s_status == 'Waiting' ? 'selected' : '' ?>>Waiting</option>

                </select>
            </div>
            <div class="col-md-4">
                <select class="select2 form-select select_user" style="width:200px" name="user">
                    <?php
                    $args = array(
                        'role'    => 'customer',
                        'orderby' => 'user_nicename',
                        'order'   => 'ASC'
                    );
                    $users = get_users($args);
                    ?>
                    <option value="-1">Select User</option>

                    <?php foreach ($users as $user) {
                        echo '<option value="' . $user->ID . '" ' .
                            (($s_user == $user->ID) ? 'selected' : '') . '>' . $user->display_name . '</option>';
                    } ?>


                </select>
            </div>

            <div class="col-md-4">
                <select class="select2 form-select select_product" style="width:200px" name="product">
                    <option value="-1">Select Product</option>
                    <?php
                    $args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => -1,

                    );

                    $loop = new WP_Query($args);

                    while ($loop->have_posts()) : $loop->the_post();
                        global $product;
                        echo '<option value="' . get_the_ID() . '" ' .
                            (($s_product == get_the_ID()) ? 'selected' : '') . '>' . get_the_title() . '</option>';
                    endwhile;

                    wp_reset_query(); ?>





                </select>
            </div>

        </div>
        <span> showing <?= $query_quiz->post_count ?> out of <?= $count_res ?> </span>

    </div>
    <table>
        <tr>
            <th class="d-none d-sm-block">#</th>
            <th>Product</th>
            <th>User</th>
            <th class="d-none d-sm-block">Status</th>
            <th>Proposal</th>

            <th></th>
        </tr>
        <?php


        while ($query_quiz->have_posts()) {
            $key++;
            $query_quiz->the_post();
            $product = get_post_meta(get_the_ID(), 'product', true);
            $user    = get_the_author();
            if (!$product) continue;

            $status = get_post_meta(get_the_ID(), 'status', true);
            //$status = $status?$status:'waiting'
            // get proposals
            $proposals = get_posts(array(
                'post_type' => 'rfq_proposal',
                'meta_query' => array(
                    array(
                        'key' => 'request',
                        'value' => get_the_ID(),
                        'compare' => '='
                    ),
                ),
            ));
            /*$proposals = $proposal[0]??false ;
					//var_dump($proposal);
					$proposal_status =($proposal)?get_post_meta($proposal->ID, 'status', true):'Waiting';
					$proposal_price =($proposal)?get_post_meta($proposal->ID, 'price', true):'';
					$proposal_notes =($proposal)?$proposal->post_content:'';
					$proposal_id =($proposal)?$proposal->ID:'';*/
        ?>

            <tr>
                <td class="d-none d-sm-block"><?= (($page_num - 1) * 20) + $key ?></td>
                <td><a href="<?= get_the_permalink($product) ?>" target="_blank"><?= get_the_title($product) ?>#<?= ($product) ?></a></td>
                <td><?= $user ?><span class="d-block d-sm-none status_<?= $status ?>"><?= $status ?></span></td>
                <td class="status_<?= $status ?> d-none d-sm-block"><?= $status ?></td>
                <td><?= ($chosen = get_post_meta(get_the_ID(), 'chosen', true)) ? '#' . $chosen : '' ?></td>
                <td class="delete_access" data-class="<?= $product ?>" data-user="<?= $product ?>">
                    <span>
                        <div id="modal_<?= get_the_ID() ?>" class="modal modal-request" style="left: 24%;">
                            <!-- show proposal edits  -->
                            <!-- panel  -->
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Nav tabs -->
                                        <div class="card">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#home" class="f-home " data-class="home" aria-controls="home" role="tab" data-toggle="tab">Details</a>
                                                </li>
                                                <li role="presentation"><a href="#profile" data-class="profile" aria-controls="profile" role="tab" data-toggle="tab">Proposals (<?= count($proposals) ?>)</a></li>
                                            </ul>

                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active home">
                                                    <!-- ditals -->
                                                    <div class="row">
                                                        <div class="col-md-6"><label style="font-size: 1.4rem;">Product Request</label></div>
                                                        <div class="col-md-6"></div>
                                                        <div class="col-md-6"><label>Product </label></div>
                                                        <div class="col-md-6"><span><?= get_the_title($product) ?></span></div>
                                                        <div class="col-md-6"><label>User </label></div>
                                                        <div class="col-md-6"><span><?= $user ?></span></div>
                                                        <div class="col-md-6"><label>Quantity </label></div>
                                                        <div class="col-md-6"><span><?= get_post_meta(get_the_ID(), 'quantity', true) ?></span></div>
                                                        <div class="col-md-6"><label>Brand Name </label></div>
                                                        <div class="col-md-6"><span><?= get_post_meta(get_the_ID(), 'brand', true) ?></span></div>
                                                        <div class="col-md-6"><label>Country of origin </label></div>
                                                        <div class="col-md-6"><span><?= get_post_meta(get_the_ID(), 'country', true)  ?></span></div>
                                                        <div class="col-md-6"><label>Attachment</label></div>
                                                        <div class="col-md-6"><span><a target="_blank" style=" color: #5472d2; " href="<?= get_post_meta(get_the_ID(), 'attachment', true) ?>"> <?= get_post_meta(get_the_ID(), 'attachment_name', true) ?></a></span></div>


                                                        <div class="col-md-6"><label>Status </label></div>
                                                        <div class="col-md-6"><span class="status_<?= $status ?>"><?= $status ?></span></div>
                                                        <div class="col-md-6"><label>Date </label></div>
                                                        <div class="col-md-6"><span><?= date('Y-m-d h:i:s A', get_post_time()) ?></span></div>
                                                        <div class="col-md-6"><label>last update</label></div>
                                                        <div class="col-md-6"><span><?= date('Y-m-d h:i:s A', get_post_modified_time()) ?></span></div>
                                                        <div class="col-md-6"><label>Notes </label></div>
                                                        <div class="col-md-6"><span><?= get_post_meta(get_the_ID(), 'notes', true) ?></span></div>


                                                    </div>

                                                    <br>
                                                    <?php if ($status != 'Approved') { ?>
                                                        <button class="btn btn-primary btn-approve" data-class="<?= get_the_ID() ?>">Approve</button>
                                                    <?php } ?>
                                                    <?php if ($status != 'Refused') { ?>
                                                        <button class="btn btn-danger  btn-refuse" data-class="<?= get_the_ID() ?>">Decline</button>
                                                    <?php } ?>
                                                    <a href="#" rel="modal:close" onclick="$.fancybox.close();jQuery('body').removeClass('modal-open');return false;" style="float: right;" class="btn btn-danger">Close</a>
                                                    <!-- show proposal edits  -->
                                                </div>
                                                <!-- details -->

                                                <div role="tabpanel" class="tab-pane profile main-quiz-user">
                                                    <table>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Supplier</th>
                                                            <th>Price</th>
                                                            <th>Attachment</th>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                        </tr>
                                                        <?php foreach ($proposals as $proposal) { ?>
                                                            <tr>
                                                                <td><?= ($proposal->ID) ?></td>
                                                                <td><?= get_the_author_meta('display_name', $proposal->post_author) ?></td>
                                                                <td><?= get_post_meta($proposal->ID, 'price', true) ?></td>
                                                                <td>  <span ><a target="_blank" style=" color: #5472d2; " href="<?= get_post_meta($proposal->ID, 'attachment', true) ?>"> <?= get_post_meta($proposal->ID, 'attachment_name', true) ?></a></span></td>
                                                                <td><?= $proposal->post_date ?></td>
                                                                <td class="status_<?= get_post_meta($proposal->ID, 'status', true) ?>"><?= get_post_meta($proposal->ID, 'status', true) ?></td>

                                                            </tr>
                                                        <?php } ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- panel  -->


                        <!-- Link to open the modal -->

                        <a href="#modal_<?= get_the_ID() ?>" class="fancybox" style="" rel="modal:open" onclick="jQuery('.f-home').trigger('click');">
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

        <?php for ($i = 1; $i < (ceil($count_res / 20)) + 1; $i++) { ?>
            <a href="?page_num=<?= $i ?>" <?= ($i == $page_num) ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php } ?>


    </div>
    </table>

</div>

<!-- products  -->

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />-->