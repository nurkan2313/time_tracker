<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>test</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->url->get('img/favicon.ico')?>"/>
    {{ stylesheet_link('css/style.css') }}
    {{ stylesheet_link('css/select2.min.css') }}
    {{ stylesheet_link('css/perfect-scrollbar.css') }}
    {{ stylesheet_link('css/util.css') }}
    {{ stylesheet_link('css/main.css') }}
    {{ stylesheet_link('css/table.css') }}
</head>
<body>

{{ partial('layouts/menu') }}

<div class="container">
    <?php echo $this->getContent(); ?>
</div>

<!-- jQuery first, then Popper.js, and then Bootstrap's JavaScript -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"  crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"  crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
{{ javascript_include('js/select2.min.js') }}
{{ javascript_include('js/main.js') }}
</body>
</html>
