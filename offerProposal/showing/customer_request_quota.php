<!-- products  -->

<h4 style="padding: 0;margin: 0;">Requests Quota Products</h4>
<br>
<div class="main-quiz-user">
    <?php
    $requests = get_posts(array(
        'post_type' => 'rfq_request',
        'author' => get_current_user_id(),
    ));
    ?>
    Total : <?= count($requests) ?>
    <div class=" my-4">

        <div class="row">
            <?php
            if (isset($_GET['s_request']) && intval($_GET['s_request']))
                $s_request = get_post(intval($_GET['s_request']));
            else $s_request = 0;

            ?>
            <div class="col-md-8">
                <select class="select2 form-select select_request" style="width:100%" name="product">
                    <option value="-1">Select Product</option>
                    <?php
                    foreach ($requests as $req) {
                        $product = wc_get_product(get_post_meta($req->ID, 'product', 'true'));
                    ?>
                        <option value="<?= $req->ID ?>" <?= ($s_request && $s_request->ID == $req->ID) ? 'selected' : '' ?>>Request #<?= $req->ID ?> : <?= $product->get_name() ?></option>
                    <?php } ?>



                </select>
            </div>

        </div>
        <!-- request details  -->
        <?php
        if ($s_request && ($s_request->post_author == get_current_user_id())) {
            $s_roduct = wc_get_product(get_post_meta($s_request->ID, 'product', 'true'));
            $status = get_post_meta($s_request->ID, 'status', true);
        ?>
            <div class="row my-4 req_details">
                <div class="col-md-12"><label style="font-size: 1.4rem;">Request #<?= $s_request->ID ?> : <?= $s_roduct->get_name() ?></label></div>


                <div class="col-md-3"><label>Quantity </label></div>
                <div class="col-md-3"><span><?= get_post_meta($s_request->ID, 'quantity', true) ?></span></div>
                <div class="col-md-3"><label>Brand Name </label></div>
                <div class="col-md-3"><span><?= get_post_meta($s_request->ID, 'brand', true) ?></span></div>
                <div class="col-md-3"><label>Country of origin </label></div>
                <div class="col-md-3"><span><?= get_post_meta($s_request->ID, 'country', true) ?></span></div>
                <div class="col-md-3"><label>Status </label></div>
                <div class="col-md-3"><span class="status_<?= $status ?>"><?= $status ?></span></div>
                <div class="col-md-3"><label>Date </label></div>
                <div class="col-md-3"><span><?= ($s_request->post_date); ?></span></div>
                <div class="col-md-3"><label>last update</label></div>
                <div class="col-md-3"><span><?= $s_request->post_modified ?></span></div>
                <div class="col-md-3"><label>Describe </label></div>
                <div class="col-md-9"><span><?= get_post_meta($s_request->ID, 'notes', true) ?></span></div>
                <div class="col-md-3"><label>Attachment </label></div>
                <div class="col-md-9"><a target="_blank" style=" color: #5472d2; " href="<?= get_post_meta($s_request->ID, 'attachment', true) ?>"> <?= get_post_meta($s_request->ID, 'attachment_name', true) ?></a></div>



            </div>

            <div role="tabpanel" class="tab-pane profile main-quiz-user">
                <?php

                $proposals = get_posts(array(
                    'post_type' => 'rfq_proposal',
                    'meta_query' => array(
                        array(
                            'key' => 'request',
                            'value' => $s_request->ID,
                            'compare' => '='
                        ),
                    ),
                ));
                ?>
                <p>your request have <?= count($proposals) ?> proposals</p>

                <table id="proposlastable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th>Attachment</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proposals as $proposal) {
                            $vendor = dokan()->vendor->get($proposal->post_author);

                        ?>
                            <tr>
                                <td><?= ($proposal->ID) ?></td>

                                <td><a href="<?= $vendor->get_shop_url() ?>" target="_blank"><?= get_the_author_meta('display_name', $proposal->post_author) ?></a></td>
                                <td><?= get_post_meta($proposal->ID, 'price', true) ?></td>
                                <td> <span><a target="_blank" style=" color: #5472d2; " href="<?= get_post_meta($proposal->ID, 'attachment', true) ?>"> <?= get_post_meta($proposal->ID, 'attachment_name', true) ?></a></span></td>
                                <td><?= $proposal->post_date ?></td>
                                <td class="status_<?= get_post_meta($proposal->ID, 'status', true) ?>"><?= get_post_meta($proposal->ID, 'status', true) ?></td>
                                <td class="delete_access">
                                    <?php
                                    $statusproposal = get_post_meta($proposal->ID, 'status', true);
                                    $choosen = get_post_meta($s_request->ID, 'chosen', true);
                                    if (!$choosen) { ?>
                                        <span>
                                            <div id="modal_<?= $proposal->ID ?>" class="modal modal-request">
                                                <!-- show proposal edits  -->
                                                <div class="container-modal">
                                                    <div class="row" style="background: white;">
                                                        <div class="col-md-12" style="padding: 2rem;">
                                                            <div>
                                                                <label style="font-size: 1.4rem;width: 100%;">Product Request Approve</label>
                                                            </div>
                                                            <div>
                                                                <label>Product </label> <span><?= $s_roduct->get_name() ?></span>
                                                            </div>
                                                            <div>
                                                                <label>Quantity </label> <span><?= get_post_meta($s_request->ID, 'quantity', true) ?></span>
                                                            </div>
                                                            <div>
                                                                <label>Brand Name </label> <span><?= get_post_meta($s_request->ID, 'brand', true); ?></span>
                                                            </div>
                                                            <div>
                                                                <label>Country of origin </label> <span><?= get_post_meta($s_request->ID, 'country', true); ?></span>
                                                            </div>
                                                            <div>
                                                                <label>Attachment </label>
                                                                <span><a target="_blank" style=" color: #5472d2; " href="<?= get_post_meta($s_request->ID, 'attachment', true) ?>"> <?= get_post_meta($s_request->ID, 'attachment_name', true) ?></a></span>
                                                            </div>
                                                            <div>
                                                                <label>Status </label><span class="status_<?= $status ?>"><?= $status ?></span>
                                                            </div>
                                                            <div>
                                                                <label>Date </label><span><?= date('Y-m-d h:i:s A', $s_request->post_date) ?></span>
                                                            </div>
                                                            <div>
                                                                <label>last update</label><span><?= date('Y-m-d h:i:s A', $s_request->post_modified) ?></span>
                                                            </div>
                                                            <div>
                                                                <label>Notes </label> <span><?= get_post_meta($s_request->ID, 'notes', true) ?></span>
                                                            </div>
                                                            <hr>
                                                            <div>
                                                                <label>Proposal Details : </label>
                                                                <br>
                                                                <label>Supplier </label> <span><?= get_the_author_meta('display_name', $proposal->post_author) ?></span>
                                                            </div>
                                                            <div>
                                                                <label>Price </label> <span><?= get_post_meta($proposal->ID, 'price', true) ?></span>
                                                            </div>
                                                            <div>
                                                                <label>Proposal Notes </label> <span style="width: 100%;">
                                                                    <?= $proposal->post_content ?></span>
                                                            </div>
                                                            <div>
                                                                <label>Attachment </label>
                                                                <span><a target="_blank" style=" color: #5472d2; " href="<?= get_post_meta($proposal->ID, 'attachment', true) ?>"> <?= get_post_meta($proposal->ID, 'attachment_name', true) ?></a></span>
                                                            </div>
                                                            <br>
                                                            <span><label style="width: 100%;font-weight: 600;text-align: center;">Are you sure to Approve this Proposal?</label><br>
                                                                once you approve folowing will happen : <br>
                                                                1- this proposal status will change to approved<br>
                                                                2- other proposals will be ignored<br>
                                                                3- a notification will be sent to us<br>
                                                                <!--4- a new custom order will be created and you can find in my account -> my order--> <br><br><br></span>
                                                            <div class="100%">
                                                                <?php if ($statusproposal !== "Declined") : ?>
                                                                    <button class="btn btn-success btn-approve-proposal" data-class="<?= $proposal->ID ?>">Approve</button>

                                                                    <button class="btn btn-danger  btn-decline-proposal" data-class="<?= $proposal->ID  ?>">Decline</button>
                                                                <?php endif ?>
                                                                <a href="#" onclick="$.fancybox.close();return false;" style="float: right;" class="btn btn-danger">Close</a>
                                                            </div>
                                                            <!-- show proposal edits  -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Link to open the modal -->

                                            <a onclick=" $.fancybox.open({src: '#modal_<?= $proposal->ID ?>',touch: false}); return false;" href='#modal_<?= $proposal->ID ?>' style="" class="fancybox">
                                                <i class="fa fa-eye" style="color:blue;"></i>
                                            </a>

                                        </span>
                                    <?php } ?>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>


        <?php } ?>

        <!-- request details -->
    </div>


    </table>

</div>

<!-- products  -->