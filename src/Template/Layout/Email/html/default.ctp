<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <style type="text/css">
        p {
            border:0;
            margin:0;
        }
        a:link {
            color: #015da2;
            text-decoration: none;
        }
        a:visited {
            text-decoration: none;
            color: #015da2;
        }
        a:hover {
            text-decoration: underline;
            color: #01487C;
        }
        a:active {
            text-decoration: none;
            color: #015da2;
        }
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
            color: #595959;
            font-family: Tahoma;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div style="margin:auto;text-align:left">
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#FFFFFF; color:#666666; font-size:12px; font-family:Tahoma;">
        <tr>
            <td height="5" style="background-color:#005285; height:5px"></td>
        </tr>
        <tr>
            <td height="1" align="left" style="margin:auto; padding:10px; padding-left:20px"><img src="http://public.qisat.com.br/temp/aprovacao/moodle/logoQiSat_slogan_horizontal.png" width="400" height="39" border="0"></td>
        </tr>
        <tr>
            <td height="1" style="height:1px; border-bottom:solid 1px #dedede"></td>
        </tr>
        <tr>
            <td style="padding:20px 20px 8px 20px; line-height:125%; vertical-align:top;">
                <?= $this->fetch('content') ?>
            </td>
        <tr>
            <td style=" padding:20px 20px 16px 20px; line-height:120%">Atenciosamente,<br/><br/>
                <b>Equipe QiSat</b><br/>
                Central de Inscrições: (48) 3332-5000 <br/>
                E-mail:<span style="color:#015da2;"> <a href="mailto:qisat@qisat.com.br">qisat@qisat.com.br</a><br/>
                    <i><a href="https://www.qisat.com.br" target="_blank">https://www.qisat.com.br</a></i></span></td>
        </tr>
        <tr>
            <td style="margin:auto; background-color:#4e4e4e; padding:6px; font-family:Tahoma; font-size:11px; color:#979797; margin:auto; text-align:center; height:14px;">© 2003 - 2017 - Todos os Direitos Reservados à MN Tecnologia e Treinamento Ltda. | Para mais informações entre em <a style="color:#cccccc" href="mailto:qisat@qisat.com.br">contato</a>.</td>
        </tr>
    </table>
</div>
</body>
</html>
