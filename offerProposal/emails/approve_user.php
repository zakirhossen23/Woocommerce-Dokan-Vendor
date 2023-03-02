<?php

function send_approve_user($user_numebr)
{
    $user = (object)get_user_by('id', $user_numebr);
    $to = $user->user_email;
    $subject = 'Your account has been approved!';
    // $message = "Test";
    ob_start();
?>
    <div id="m_-1362742744568104215wrapper" dir="ltr" style="background-color:#f7f7f7;margin:0;padding:70px 0;width:100%">
        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
            <tbody>
                <tr>
                    <td align="center" valign="top">
                        <div id="m_-1362742744568104215template_header_image">
                        </div>
                        <table border="0" cellpadding="0" cellspacing="0" width="600" id="m_-1362742744568104215template_container" style="background-color:#ffffff;border:1px solid #dedede;border-radius:3px">
                            <tbody>
                                <tr>
                                    <td align="center" valign="top">

                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="m_-1362742744568104215template_header" style="background-color:#96588a;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0">
                                            <tbody>
                                                <tr>
                                                    <td id="m_-1362742744568104215header_wrapper" style="padding:36px 48px;display:block">
                                                        <h1 style="font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#ffffff;background-color:inherit">Hi <?= $user->user_nicename ?>,</h1>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top">

                                        <table border="0" cellpadding="0" cellspacing="0" width="600" id="m_-1362742744568104215template_body">
                                            <tbody>
                                                <tr>
                                                    <td valign="top" id="m_-1362742744568104215body_content" style="background-color:#ffffff">

                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td valign="top" style="padding:48px 48px 32px">
                                                                        <div id="m_-1362742744568104215body_content_inner" style="color:#636363;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">

                                                                            <p style="margin:0 0 16px">Your account has been approved!</p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">

                        <table border="0" cellpadding="10" cellspacing="0" width="600" id="m_-1362742744568104215template_footer">
                            <tbody>
                                <tr>
                                    <td valign="top" style="padding:0;border-radius:6px">
                                        <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" valign="middle" id="m_-1362742744568104215credit" style="border-radius:6px;border:0;color:#8a8a8a;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:12px;line-height:150%;text-align:center;padding:24px 0">
                                                        <p style="margin:0 0 16px">Tekoa </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
            </tbody>
        </table>
        <div class="yj6qo"></div>
        <div class="adL">
        </div>
    </div>
<?php
    $message = ob_get_contents();
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
    $headers .= 'From: <support@tekoa.co.ke>' . "\r\n";
    return     wp_mail($to, $subject, $message, $headers);
}
