<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.80.0">
    <title>Grid Template · Bootstrap v5.0</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url('/assets/dist/css/bootstrap.min.css');?>" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="<?= base_url('/assets/dist/css/grid.css');?>" rel="stylesheet">
  </head>
  <body class="py-4">
    
<main>
  <div class="container">

    <h1>Рекламные кабинеты</h1>

    <div class="row mb-3">
      <div class="col-3 themed-grid-col">
        <div class="row mb-3">
            <img src="<?= $getUsers[0]['photo_100'];?>" />
        </div>
        
        <div class="row mb-3">
            <div class="col">
                <p><?= $getUsers[0]['first_name'];?></p>
            </div>
            <div class="col text-end">
                <p><a href="<?= base_url('/home/exit/');?>">Выход</a></p>
            </div>
        </div>
        
      </div>
      <div class="col-9 themed-grid-col">
        <div class="row mb-3">
            <?foreach($getAccounts as $item){?>
            <div class="col-sm-4 themed-container"><a href="<?= base_url('/home/account/'.$item['account_id']);?>">[<?= $item['account_id'];?>] <?= $item['account_name'];?></a></div>
            <?}?>
        </div>
      </div>
    </div>
  </div>
</main>


    
  </body>
</html>
