<!-- products  -->


<h4 style="padding: 0;margin: 0;">Users</h4>
<br>
<div class="main-quiz-user">
    <?php
    global $wpdb;


    $q = "SELECT ID FROM {$wpdb->users}";
    $requests = $wpdb->get_results($q, ARRAY_N);
    $total_users = count($requests);

    $q = "SELECT * FROM {$wpdb->users}  WHERE user_status = 2";
    $requests = $wpdb->get_results($q, ARRAY_N);
    $total_rejected = count($requests);

    $q = "SELECT * FROM {$wpdb->users}  WHERE user_status = 1";
    $requests = $wpdb->get_results($q, ARRAY_N);
    $total_pending = count($requests);

    $q = "SELECT * FROM {$wpdb->users}  WHERE user_status = 0";
    $requests = $wpdb->get_results($q, ARRAY_N);
    $total_approved = count($requests);


    $key = 0;
    $page_num = isset($_GET['page_num']) ? $_GET['page_num'] : 1;
    $user_status = "";
    if (isset($_GET['status'])) {
        $user_status = $_GET['status'];
    }

    $total_query_users = 0;
    function get_users_ordered_by_post_date($offs = 0, &$total_query_users, $user_status = "")
    {
        global $wpdb;


        $q = $user_status == "" ? "SELECT * FROM {$wpdb->users} LIMIT 20 OFFSET {$offs} " : "SELECT * FROM {$wpdb->users} WHERE user_status = {$user_status} LIMIT 20 OFFSET {$offs} ";
        $users = $wpdb->get_results($q, ARRAY_N);

        $q2 = $user_status == "" ? "SELECT * FROM {$wpdb->users}" : "SELECT * FROM {$wpdb->users}  WHERE user_status  = {$user_status} ";
        $requests = $wpdb->get_results($q2, ARRAY_N);
        $total_query_users = count($requests);

        foreach ($users as $i => $u) $users[$i] = get_userdata($users[$i][0]);

        return $users;
    }
    $users = get_users_ordered_by_post_date((($page_num - 1) * 20), $total_query_users, $user_status);


    ?>
    Total : <?= $total_users ?> <span class="status_Approved" style="margin:0 10px;">Approved : <?= $total_approved ?></span>
    <span class="status_Refused" style="margin:0 10px;">Rejected : <?= $total_rejected ?></span>
    <span class="" style="margin:0 10px;">Pending : <?= $total_pending ?></span> <br><br>
    <div class=" my-4">

        <?php
        $s_status = $_GET['status'] ?? -1;

        ?>
        <div class="row">
            <div class="col-md-4">
                <select class="s_status form-select" style="width:200px">
                    <option value="-1">Select Status</option>
                    <option value="0" <?= $s_status == 0 ? 'selected' : '' ?>>Approved</option>
                    <option value="2" <?= $s_status == 2 ? 'selected' : '' ?>>Rejected</option>
                    <option value="1" <?= $s_status == 1 ? 'selected' : '' ?>>Pending</option>
                </select>
            </div>

        </div>

    </div>
    <span> showing <?= count($users) ?> out of <?= $total_query_users ?> </span>
    <table>
        <tr>
            <th class="d-none d-sm-block">#</th>
            <th>User</th>
            <th>Role</th>
            <th>Registered Date</th>
            <th class="d-none d-sm-block">Status</th>
            <th></th>
        </tr>
        <?php
        for ($i = 0; $i <  count($users); $i++) {
            $key++;
            $user = (object)$users[$i];
            if ($user->user_status == 0) ($status = "Approved");
            if ($user->user_status == 1) ($status = "Pending");
            if ($user->user_status == 2) ($status = "Rejected");
            $role = array_keys($user->caps)[0];
            if ($role == "seller") $role = "Supplier";
            if ($role == "customer") $role = "Customer";
            if ($role == "hospital") $role = "Hospital";

            $user_details = (object)get_user_meta((int)$user->ID, 'user_details', true);


        ?>
            <tr>
                <td class="d-none d-sm-block"><?= (($page_num - 1) * 20) + $key ?></td>
                <td><?= $user->user_nicename ?></td>
                <td><?= $role  ?></td>
                <td><?= date("Y/m/d H:i:s", strtotime($user->user_registered)) ?></td>
                <td class="d-none d-sm-block"><?= $status ?></td>
                <td class="delete_access" data-class="<?= $product ?>" data-user="<?= $product ?>">
                    <span>
                        <div id="modal_<?= $user->ID ?>" class="modal modal-user">
                            <!-- show proposal edits  -->
                            <!-- panel  -->
                            <div class="container-modal">
                                <div class="row" style="background: white;">
                                    <div class="col-md-12" style="padding: 2rem;">
                                        <!-- ditals -->
                                        <div class="row">
                                            <div class="col-md-6"><label style="font-size: 1.4rem;"><?= $user->user_nicename ?>(<?= $role ?>)</label></div>
                                            <div class="col-md-6"></div>

                                            <?php if ($role == "Hospital") : ?>
                                                <div><img src="<?= $user_details->hospital_logo ?>" style=" width: 200px; "></img></div>


                                                <div class="col-md-6"><label>Hospital Name</label></div>
                                                <div class="col-md-6"><span><?= $user_details->hospital_name ?></span></div>

                                                <div class="col-md-6"><label>Person in charge</label></div>
                                                <div class="col-md-6"><span><?= $user_details->person_incharge ?></span></div>

                                                <div class="col-md-6"><label>Phone Number</label></div>
                                                <div class="col-md-6"><span><?= $user_details->hospital_phone ?></span></div>

                                                <div class="col-md-6"><label>Address </label></div>
                                                <div class="col-md-6"><span><?= $user_details->hospital_address ?></span></div>
                                            <?php endif; ?>

                                            <?php if ($role == "Supplier") : ?>
                                                <div class="col-md-6"><label>Company Name</label></div>
                                                <div class="col-md-6"><span><?= $user_details->company_name ?></span></div>

                                                <div class="col-md-6"><label>Contact Person</label></div>
                                                <div class="col-md-6"><span><?= $user_details->contact_person ?></span></div>

                                                <div class="col-md-6"><label>Phone Number</label></div>
                                                <div class="col-md-6"><span><?= $user_details->seller_phone ?></span></div>

                                                <div class="col-md-6"><label>Address </label></div>
                                                <div class="col-md-6"><span><?= $user_details->seller_address ?></span></div>

                                                <div>
                                                    <label>List of categories he supply: </label>

                                                    <?php foreach ($user_details->seller_categories as $key => $value) { ?>
                                                        <li><?= $value ?></li>
                                                    <?php } ?>

                                                </div>


                                            <?php endif; ?>


                                            <?php if ($role == "Customer") : ?>
                                                <div class="col-md-6"><label>Customer Name</label></div>
                                                <div class="col-md-6"><span><?= $user_details->customer_name ?></span></div>

                                                <div class="col-md-6"><label>Phone Number</label></div>
                                                <div class="col-md-6"><span><?= $user_details->customer_phone ?></span></div>

                                                <div class="col-md-6"><label>Address </label></div>
                                                <div class="col-md-6"><span><?= $user_details->customer_address ?></span></div>
                                            <?php endif; ?>


                                            <br>

                                            <div class="col-md-6"><label>Registered Date</label></div>
                                            <div class="col-md-6"><span><?= date("Y/m/d H:i:s", strtotime($user->user_registered)) ?></span></div>

                                            <div class="col-md-6"><label>Status</label></div>
                                            <div class="col-md-6"><span><?= $status ?></span></div>
                                        </div>

                                        <br>
                                        <?php if ($status != 'Approved') { ?>
                                            <button class="btn btn-primary btn-approve" data-class="<?= $user->ID ?>">Approve</button>
                                        <?php } ?>
                                        <?php if ($status != 'Rejected') { ?>
                                            <button class="btn btn-danger  btn-refuse" data-class="<?= $user->ID ?>">Reject</button>
                                        <?php } ?>
                                        <a href="#" rel="modal:close" onclick="$.fancybox.close();jQuery('body').removeClass('modal-open');return false;" style="float: right;" class="btn btn-danger">Close</a>
                                        <!-- show proposal edits  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- panel  -->


                        <!-- Link to open the modal -->

                        <a href="#modal_<?= $user->ID ?>" class="fancybox" style="" rel="modal:open" onclick="jQuery('.f-home').trigger('click');">
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

        <?php for ($i = 1; $i < (ceil($total_query_users   / 20)) + 1; $i++) { ?>
            <a href="?page_num=<?= $i ?>" <?= ($i == $page_num) ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php } ?>


    </div>
    </table>

</div>

<!-- products  -->

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />-->