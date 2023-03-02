<?php

function send_approve($request_numebr){
    
    $to = get_bloginfo('admin_email');
    $subject = '#'.$request_numebr.' Approved By Customer';
    
    // get info
    $proposla_id = get_post_meta($request_numebr, 'chosen',true);
    $product = wc_get_product(get_post_meta($request_numebr,'product','true'));
    $s_request=get_post(intval($request_numebr));
    $s_proposal=get_post(intval($proposla_id));
    
$message = '<table class="m_-1032278250017986475full-width" style="width:800px;margin:0 auto;border:solid 1px #e3e7ec;
   border-top:0;border-bottom:0;padding-top:35px;padding-bottom:35px" width="800" cellspacing="0" cellpadding="0" border="0">
   <tbody>
      <tr>
         <td dir="ltr" align="center">
            <table class="m_-1032278250017986475almost-full-width" style="width:610px;margin:0 auto;padding:0" width="610" cellspacing="0" cellpadding="0" border="0">
               <tbody>
                  <tr>
                     <td style="padding:5px">
                        <h3 style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;
                        font-size:17px;font-weight:bold;line-height:1.24;letter-spacing:-0.1px;color:#4a4a4a;
                        margin:0">Congratulation</h3>
                     </td>
                  </tr>
                  <tr>
                     <td style="padding:5px">
                        <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:13px;
                        line-height:1.62;color:#404553;letter-spacing:normal">Proposal #'.$proposla_id.' for Request #'.$request_numebr.' has been approved by customer</p>
                     </td>
                  </tr>
                  <tr>
                     <td style="padding:19px 5px 5px">
                        <table style="margin:0 auto;width:100%" cellspacing="2.5">
                           <tbody>
                              <tr>
                                 <td style="width:8.23%;padding:0">
                                    <div style="width:100%;height:10px;background-color:#7ed321">
                                    </div>
                                 </td>
                                 <td style="width:20%;padding:0">
                                    <div style="width:100%;height:10px;background-color:#7ed321">
                                    </div>
                                 </td>
                                 <td style="width:49.67%;padding:0">
                                    <div style="width:100%;height:10px;background-color:#7ed321">
                                    </div>
                                 </td>
                                 <td style="width:22.1%;padding:0">
                                    <div style="width:100%;height:10px;background-color:#e2e5f1">
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td style="padding:5px">
                        <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:18px;line-height:1.22;color:#7e859b">
                        <span style="font-weight:bold;color:#7ed321">Approved</span></p>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <tr>
         <td dir="rtl" style="padding-top:30px" align="center">
            <table class="m_-1032278250017986475almost-full-width" style="width:610px;margin:0 auto;padding:0" dir="ltr" width="610" cellspacing="0" cellpadding="0" border="0">
               <tbody>
                  <tr>
                     <td style="background-color:#f8f9fb" class="m_-1032278250017986475full-width m_-1032278250017986475block-td" width="50%" valign="top" align="right">
                        <table class="m_-1032278250017986475full-width" valign="top" style="background-color:#f8f9fb;padding:16px 20px 6px 20px;box-sizing:border-box" align="left">
                           <tbody>
                              <tr>
                                 <td colspan="2">
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;
                                    font-size:12px;font-weight:bold;letter-spacing:0.2px;color:#4a4a4a;line-height:1.5;
                                    margin:0;text-transform:uppercase">Request Summery</p>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;
                                    font-size:12px;line-height:1.5;margin:0;color:#404553;min-width:55px">Product :</p>
                                 </td>
                                 <td style="padding-right:25px">
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;
                                    font-size:12px;line-height:1.5;color:#404553;margin:0;
                                    font-weight:600">'.$product->get_name().'</p>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:12px;line-height:1.5;margin:0;color:#404553;min-width:55px">Quantity:</p>
                                 </td>
                                 <td style="padding-right:25px">
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;
                                    font-size:12px;line-height:1.5;color:#404553;margin:0;
                                    font-weight:600">'.get_post_meta($request_numebr, 'quantity', true).'</p>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;
                                    font-size:12px;line-height:1.5;margin:0;color:#404553;min-width:55px">Company:</p>
                                 </td>
                                 <td style="padding-right:25px">
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;
                                    font-size:12px;line-height:1.5;color:#404553;margin:0;font-weight:600">'.get_post_meta($request_numebr, 'company', true).'</p>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:12px;line-height:1.5;margin:0;color:#404553;min-width:55px">Address:</p>
                                 </td>
                                 <td style="padding-right:25px">
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:12px;line-height:1.5;
                                    color:#404553;margin:0;font-weight:600">'.get_post_meta($request_numebr, 'address', true).'</p>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                     <td style="background-color:#f8f9fb" class="m_-1032278250017986475full-width m_-1032278250017986475block-td" width="50%" valign="top" align="left">
                        <table class="m_-1032278250017986475full-width" valign="top" style="box-sizing:border-box;background-color:#f8f9fb;padding:16px 20px" align="right">
                           <tbody>
                              <tr>
                                 <td>
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:12px;letter-spacing:0.2px;
                                    color:#4a4a4a;line-height:1.5;font-weight:bold;text-transform:uppercase;
                                    margin:0">Date '.$s_request->post_date.'</p>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;
                                    font-size:12px;line-height:1.5;color:#404553;font-weight:600;
                                    margin:0">last update '.$s_request->post_modified.'</p>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;
                                    font-size:12px;line-height:1.5;color:#404553;font-weight:600;
                                    margin:0">Seller : '.get_the_author_meta('display_name', $proposal->post_author).'</p>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;
                                    font-size:12px;line-height:1.5;color:#404553;font-weight:600;
                                    margin:0">Price : '.get_post_meta($proposal->ID, 'price', true).'</p>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        <table class="m_-1032278250017986475full-width" valign="top" style="box-sizing:border-box;
                        background-color:#f8f9fb;padding:0 20px 12px" align="right">
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <tr>
         <td dir="ltr" align="center">
            <table class="m_-1032278250017986475almost-full-width" style="width:610px;margin:0 auto;padding:15px 0;direction: ltr;text-align: left;" width="610" cellspacing="0" cellpadding="0" border="0">
               <tbody>
                  <tr>
                     <td style="padding-right:5px;padding-left:15px" valign="middle" align="left">
                        <div>
                        </div>
                     </td>
                     <td style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:12px;line-height:18px;letter-spacing:0.3px;text-align:left;color:#404553" align="left">
                        <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:12px;
                        line-height:1.5;color:#404553;margin:0;font-weight:600">Customer Notes : </p>
                        '.get_post_meta($proposal->ID, 'notes', true).'
                     </td>
                  </tr>
                  <tr>
                     <td style="padding-right:5px;padding-left:15px" valign="middle" align="left">
                        <div>
                        </div>
                     </td>
                     <td style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:12px;line-height:18px;letter-spacing:0.3px;text-align:left;color:#404553" align="left">
                        <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:12px;line-height:1.5;color:#404553;margin:0;font-weight:600">Admin Notes</p>
                         '.$proposal->post_content.'
                     </td>
                  </tr>
                  <tr>
                     <td style="padding-right:5px;padding-left:15px" valign="middle" align="left">
                        <div>
                        </div>
                     </td>
                     <td style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:12px;line-height:18px;letter-spacing:0.3px;text-align:left;color:#404553" align="left">
                        <p style="font-family:\'Proxima Nova\',Helvetica,\'Open Sans\',Corbel,Arial,sans-serif;font-size:12px;
                        line-height:1.5;color:#404553;margin:0;font-weight:600">seller ( '.get_the_author_meta('display_name', $proposal->post_author).' ) Notes :</p>
                        '.$proposal->post_content.'
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
$headers .= 'From: info@e-usine.com' . "\r\n";
return     $a =  wp_mail( $to, $subject, $message,$headers);

}