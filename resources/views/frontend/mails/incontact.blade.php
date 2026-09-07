<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="initial-scale=1.0"/>
    <meta name="format-detection" content="telephone=no"/>
    <title></title>
    <style type="text/css">
        body {
            width: 100%;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        
    </style>
</head>
<body style="font-family: Arial, sans-serif; font-size:13px; color: #444444; min-height: 200px;" bgcolor="#E4E6E9"
      leftmargin="0" topmargin="0" marginheight="0" marginwidth="0">
<table width="100%" height="100%" bgcolor="#E4E6E9" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="100%" align="center" valign="top" bgcolor="#E4E6E9"
            style="background-color:#E4E6E9; min-height: 200px;">
            <table>
                <tr>
                    <td class="table-td-wrap" align="center" width="458">
                             Below is message form client<br><br>

                             Name: {{ request()->input('name') }} 
                             <br>
                             Email: {{ request()->input('email') }} 
                             <br>
                             Message: {{ request()->input('message') }}         
                    </td>
                </tr>
            </table>
</body>
</html>