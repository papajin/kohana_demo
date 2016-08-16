<?php
    $g = [ 'm' => __( 'male' ), 'f' => __( 'female' ) ];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?= $title; ?></title>
    <style type="text/css">
        body {
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        }
        table {
            width: 100%;
        }
    </style>
</head>
<body>
<h1><?= $title; ?></h1>
<table border="1">
    <tr>
        <td>Звертання:</td>
        <td><?= $data[ 'name' ]; ?></td>
    </tr>
    <tr>
        <td>Дата і час народження:</td>
        <td><?= sprintf( '%s о %s з точністю %s', $data[ 'date' ], (($data[ 'time' ])?$data[ 'time' ]:'-:-'), $time_delta[ $data[ 'time_delta' ] ] ); ?></td>
    </tr>
    <tr>
        <td>Місце народження:</td>
        <td><?= $data[ 'born_at' ]; ?></td>
    </tr>
    <tr>
        <td>Теперішнє місцезнаходження:</td>
        <td><?= $data[ 'live_at' ]; ?></td>
    </tr>
    <tr>
        <td>Стать:</td>
        <td><?= $g[ $data[ 'gender' ] ]; ?></td>
    </tr>
    <tr>
        <td>Зріст:</td>
        <td><?= sprintf( '%d см', $data[ 'tall' ] ); ?></td>
    </tr>
    <tr>
        <td>Вага:</td>
        <td><?= sprintf( '%d кг', $data[ 'weight' ] ); ?></td>
    </tr>
    <tr>
        <td>Повідомлення:</td>
        <td><?= $data['mes']; ?></td>
    </tr>
    <tr>
        <td>Електронна адреса:</td>
        <td><?= $data['email']; ?></td>
    </tr>
</table>
<body/>
</html>
