<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table>
        <tr>
            <td>
                Querido Usuario {{$name}} por favor para completar su registro lo invitamos a seguir el siguiente link {{$id}} {{$code}}
            </td>
        </tr>
    </table>
    <a href="http://localhost:8080/#/email/{{$id.'/'.$code}}">click aqui para confirmar</a>
</body>
</html>