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

    <h1>Рекламные кампании</h1>

    <div class="row mb-3">
        <div class="col">
            <p>Рекламный кабинет: [<?= $getAccounts[0]['account_id'];?>] <?= $getAccounts[0]['account_name'];?></p>
        </div>
        <div class="col">
            <p><a href="<?= base_url('/');?>">К списку кабинетов</a></p>
        </div>
    </div>

    <div class="row mb-3">
      <div class="col themed-grid-col">
        <div class="row mb-3">
            <?foreach($getCampaigns as $item){?>
            <div class="col-sm-4 themed-container"><a href="<?= base_url('/home/campaign/'.$getAccounts[0]['account_id'].'/'.$item['id']);?>">[<?= $item['id'];?>] <?= $item['name'];?></a></div>
            <?}?>
        </div>
      </div>
    </div>

  </div>
</main>
    
  </body>
</html>
