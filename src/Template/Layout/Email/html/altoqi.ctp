<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Confirmação de compra | QiSat</title>
        <link rel="shortcut icon" href="https://www.altoqi.com.br/wp-content/uploads/2017/10/cropped-favicon-01.png" type="image/x-icon">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <style type="text/css">
            @import url('https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i'); 
            body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;} 
            table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;} 
            img{-ms-interpolation-mode: bicubic;} 
            img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
            table{border-collapse: collapse !important;}
            body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }

            /* MOBILE STYLES */
            @media screen and (max-width: 525px) {
                *[class~=hide_on_mobile] { 
                    display: none !important;
                }       
                *[class~=show_on_mobile] {
                    display : block !important;
                    width : auto !important;
                    max-height: inherit !important;
                    overflow : visible !important;
                    float : none !important;
                }
                /* ALLOWS FOR FLUID TABLES */
                .rodape{
                    padding: 10px 0 10px 0 !important;
                    font-size: 14px !important;
                }
                .logo  {
                    margin: 0 auto !important;
                    padding: 25px 0 25px  0 !important;
                }
                .responsive-table {
                    width: 95% !important;
                }
                .padding-table {
                    padding: 10px 5% 10px 5% !important;
                    text-align: left !important;
                    font-size: 14px !important;
                }
                .titulo {
                    padding: 30px 5% 20px 5% !important;
                    text-align: left !important;
                    font-size: 18px !important;
                }
                .mobile-button {
                    text-align: center !important;
                    font-size: 16px !important;
                    padding-top: 20px !important;
                }
                .max {
                    max-width: 280px !important;
                }
                .wrapper {
                    max-width: 240px !important;
                }
                .padding-copy {
                    padding: 10px 5% 10px 5% !important;
                    text-align: left;
                }
                .no-padding {
                    padding: 0 !important;
                }
                .section-padding {
                    padding: 0 5% 30px 5% !important;
                    text-align: left !important;
                    font-size: 14px !important;
                }
                .section-padding2 {
                    padding: 30px 5% 30px 5% !important;
                    text-align: left !important;
                    font-size: 14px !important;
                }
                .pd {
                    padding: 33px 5% 33px 20% !important;
                }
            }
            /* ANDROID CENTER FIX */
            div[style*="margin: 16px 0;"] { margin: 0 !important; }
        </style>
</head>
<body>

<body style="margin: 0 !important; padding: 0 !important;" cz-shortcut-listen="true">
        <!-- HIDDEN PREHEADER TEXT -->
        <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
            
        </div>

        <!-- CORPO -->
        <table border="0" cellpadding="0" cellspacing="0" width="100%">

            <!-- LOGO -->
            <tbody><tr>
                <td bgcolor="#F6F6F6" align="center">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" class="responsive-table">
                        <tbody><tr align="center">
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 0 0 45px 0;">
                                    <tbody><tr align="center">
                                        <td>
                                            <?php echo $this->Html->image('logo-altoqi-qisat.png', array('fullBase' => true, 'style'=>'font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;')); ?>
                                        </td>
                                    </tr>
                                </tbody></table>
                            </td>
                        </tr>
                    </tbody></table>
                </td>
            </tr>
            <!-- //LOGO -->

            <!-- TEXTO --> 
            <tr>
                <td bgcolor="#F6F6F6 " align="center">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" class="responsive-table">
                        <tbody><tr>
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tbody><tr>
                                        <td>

                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #FFFFFF;  margin: 0 0 30px 0; text-align: left;  border-radius: 3px;">
                                                <tbody><tr>
                                                    <td align="left" style="padding: 40px 50px 40px 50px; font-size: 14.5px; line-height: 21px; font-family: &#39;Lato&#39;, Helvetica, Arial, sans-serif; color: #363F44;" class="section-padding2">
                                                         <?= $this->fetch('content') ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </td>
                                    </tr>
                                </tbody></table> 
                            </td>
                        </tr>
                    </tbody></table>
                </td>
            </tr>
            <!-- //TEXTO -->
            <!-- //CORPO -->
        </tbody></table>
    

</body>
</div>
</body>
</html>


