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

    <h1>Рекламные объявления</h1>

    <div class="row mb-3">
        <div class="col">
            <p>Рекламный кабинет: [<?= $getAccounts[0]['account_id'];?>] <?= $getAccounts[0]['account_name'];?></p>
        </div>
        <div class="col">
            <p>Рекламная кампания: [<?= $getCampaigns[0]['id'];?>] <?= $getCampaigns[0]['name'];?></p>
        </div>
        <div class="col">
            <p><a href="<?= base_url('/home/account/'.$getAccounts[0]['account_id']);?>">К списку кабинетов</a></p>
        </div>
    </div>
    
    <div class="row mb-3">
      <div class="col themed-grid-col">
        <div class="row mb-3">
            <?foreach($newArray as $item){?>
            <div class="col-sm-4 themed-container">
                <p><a href="<?= base_url('/home/delete/'.$item['id']);?>">Удалить объявление</a></p>
                <table class="table">
                    <tbody>
                    <?foreach($item as $key => $val){?>
                    <tr>
                        <th scope="row"><?= $key;?></th>
                        <td><?print_r($val);?></td>
                    </tr>
                    <?}?>
                    <tr>
                        <th scope="row">Примечание</td>
                        <td>
                            <textarea id="note" data-adid="<?= $item['id'];?>" data-campaignid="<?= $getCampaigns[0]['id'];?>"><?= (array_key_exists($item['id'], $notes))?$notes[$item['id']]:'';?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><button type="button" class="btn btn-primary btn-blue btn-save">Сохранить</button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <?}?>
        </div>
      </div>
    </div>

  </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $(".btn-save").click(function(){
            var note = $("#note").val();
            var ad_id = $("#note").data("adid");
            var campaign_id = $("#note").data("campaignid");
            
            $.ajax({
                type: "POST",
                url: "/home/save",
                data: {note:note, ad_id:ad_id, campaign_id:campaign_id},
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    if (data['success'] == false) {
                        $('#error_message').html(data['error']);
                    }
                    else{
                        document.location.reload();
                    }
                }
            });
        });
    });
</script>

  </body>
</html>
