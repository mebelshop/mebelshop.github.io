<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
 <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>

<style>
html {
  position: relative;
  min-height: 100%;
}
body {
  margin-bottom: 60px;
}
.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 60px;
  background-color: #f5f5f5;
}

body > .container {
  padding: 40px 15px 0;
}
.container .text-muted {
  margin: 20px 0;
}

.footer > .container {
  padding-right: 15px;
  padding-left: 15px;
}
</style>
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $shop_link;?>">Печать бланков</a>
          
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li> <a href="<?php echo $F7;?>">Бланк Ф.7 <span id="badge" class="badge"></span></a> </li>
            <li><a href="<?php echo $F112;?>">Бланк Ф.112</a></li>
            <li><a href="<?php echo $F116;?>">Бланк Ф.116</a></li>
            <li class="active"><a href="<?php echo $F107;?>">Бланк Ф.107</a></li>
          </ul>
          <p class="navbar-text navbar-right">Сервис предоставлен: <a target="_blank" href="http://api.digi-post.ru" class="navbar-link">api.digi-post.ru</a></p>
        </div><!--/.nav-collapse -->
      </div>
    </div>

<!-- Begin page content -->
<div class="container">
 <div class="page-header"><h1>Печать бланка описи Ф.107 / заказ #<?php echo $order_id;?></h1></div>
 <div id="info"></div>

<div class="col-md-6">
<form role="form" id="blank">
  <div class="form-group">
    <label for="FIO">ФИО получателя</label>
    <input type="text" class="form-control" name="to_name" placeholder="Ивашов Евгений Петрович" value="<?php echo $order_info['shipping_firstname']." ".$order_info['shipping_lastname'];?>">
  </div>
  <div class="form-group">
    <label for="address1">Адрес 1</label>
    <input type="text" class="form-control" name="to_address_1" placeholder="г. Пермь, Пермский край" value="<?php echo $order_info['shipping_country'] .", ".$order_info['shipping_city'];?>">
  </div>
  <div class="form-group">
    <label for="address2">Адрес 2</label>
    <input type="text" class="form-control" name="to_address_2" placeholder="ул. Отрадная 8 кв. 32" value="<?php echo $order_info['shipping_address_1']." ".$order_info['shipping_address_2'];?>">
  </div>
  <div class="row">
  <div class="col-xs-3">
    <label for="postcode">Индекс</label>
    <input type="text" class="form-control" name="to_zip" placeholder="101000" value="<?php echo $order_info['shipping_postcode'];?>">
  </div>
  <div class="col-xs-4">
    <label for="order_amount">Сумма оценки</label>
    <input type="text" class="form-control" name="order_amount" placeholder="998" value="<?php echo $order_info['total'];?>">
  </div>
  <div class="col-xs-4">
    <label for="order_price">Сумма наложки</label>
    <input type="text" class="form-control" name="order_price" placeholder="899" value="<?php echo $order_info['total'];?>">
  </div>
</div><br>

<input type="hidden" name="blank_type" value="F107">
<input type="hidden" name="only_first" value="1">
<input type="hidden" name="order_id" value="<?php echo $order_id;?>">
<input type="hidden" name="from_name" value="<?php echo $this->config->get('digipost_from_name');?>">
<input type="hidden" name="from_address_1" value="<?php echo $this->config->get('digipost_from_address_1');?>">
<input type="hidden" name="from_address_2" value="<?php echo $this->config->get('digipost_from_address_2');?>">
<input type="hidden" name="from_zip" value="<?php echo $this->config->get('digipost_postindex');?>">

<input type="hidden" name="doc_type" value="<?php echo $this->config->get('digipost_doc_type');?>">
<input type="hidden" name="doc_serial" value="<?php echo $this->config->get('digipost_doc_serial');?>">
<input type="hidden" name="doc_num" value="<?php echo $this->config->get('digipost_doc_num');?>">
<input type="hidden" name="doc_date" value="<?php echo $this->config->get('digipost_doc_date');?>">
<input type="hidden" name="doc_received_by" value="<?php echo $this->config->get('digipost_doc_received_by');?>">

<?php foreach ($products as $key => $product) { ?>
<input type="hidden" name="products[<?php echo $key;?>][title]" value="<?php echo ($product['title']);?>">
<input type="hidden" name="products[<?php echo $key;?>][quantity]" value="<?php echo ($product['quantity']);?>">
<input type="hidden" name="products[<?php echo $key;?>][full_total_price]" value="<?php echo ($product['full_total_price']);?>">
<?php } ?>

<div class="well" style="max-width: 400px; margin: 0px auto 10px;"><a href="#" data-loading-text="Создаем бланк..." data-complete-text="Скачать бланк!" id="print-blank-btn" class="btn btn-primary btn-lg btn-block">Создать бланк Ф.107</a></div>

</form>
</div>
<div class="col-md-6 centerFlex">
	<table class="table table-striped" id="products">
	<tr><th>Товар</th><th>Кол-во</th><th>Стоимость</th><th>Сумма</th></tr>
	<?php foreach ($products as $product) { ?>
	<tr><td><?php echo $product['title'];?></td><td><?php echo $product['quantity'];?></td><td><?php echo $product['price'];?></td><td><?php echo $product['full_total_price'];?></td></tr>
	<?php } ?>
	</table>

	<table class="table table-striped" id="printed">
	<tr><th>Дата создания</th><th>Ссылка для загрузки</th></tr>
	</table>
</div>
</div>


    <div class="footer">
      <div class="container">
        <p class="text-muted"><span class="glyphicon glyphicon-copyright-mark"></span> ООО "ДиджиТекГрупп"</p>
      </div>
    </div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <!-- Latest compiled and minified JavaScript -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script>

$.ajax({
	type: "POST",
	data: { order_id: "<?php echo $order_id;?>", "blank_type": "F107"},
	url: "https://api.digi-post.ru/blanks/PrintedBlanks",
	beforeSend: function (xhr) {
		xhr.setRequestHeader ("Authorization", "Basic " + btoa('<?php echo $this->config->get("digipost_login");?>' + ":" + '<?php echo $this->config->get("digipost_api_key");?>'));
	},
	success: function(data) {
		var obj = jQuery.parseJSON(data);
		var tr;
		if (obj.message.length){
			for (var i = 0; i < obj.message.length; i++) {
				tr = $('<tr/>');
				tr.append("<td>" + obj.message[i].date_added + "</td>");
				tr.append("<td><a target=\"_blanks\" href=\"https://api.digi-post.ru/printed_blanks/" + obj.message[i].blank_url + "\">Скачать</a></td>");
				$("#printed").append(tr);
			}
		} else {
			tr = $('<tr/>');
			tr.append("<td colspan=\"2\">Бланк по этому заказу еще не печатался.</td>");
			$("#printed").append(tr);
		}
	},
	error: function(data){
		tr = $('<tr/>');
		tr.append("<td colspan=\"2\">Данные по этому заказу не были получены с сервера.</td>");
		$("#printed").append(tr);
	}
});


recalculate();

function recalculate(){
	$.ajax({
		type: "POST",
		url: "https://api.digi-post.ru/blanks/limits",
		beforeSend: function (xhr) {
			xhr.setRequestHeader ("Authorization", "Basic " + btoa('<?php echo $this->config->get("digipost_login");?>' + ":" + '<?php echo $this->config->get("digipost_api_key");?>'));
		},
		success: function(data) {
			var obj = jQuery.parseJSON(data);
			if (obj.message.plan_name == 'БЕСПЛ') {
			$("#info").html('<div id="alert" class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-info-sign"></span> Вам доступно для печати <strong><span id="blanks_count">'+obj.message.blanks_left+'</span> бланк(ов)</strong> за эти сутки. Осталось напечатать <strong><span id="blanks_left">'+obj.message.blanks_limit+'</span> бланка(ов)</strong>. Для того, чтобы убрать лимит необходимо <a target="_blank" href="http://api.digi-post.ru/invoice/index">оплатить лицензию</a> на api.digi-post.ru</div>');
			} else {
				$("#info").html('<div id="alert" class="alert alert-success" role="alert"><span class="glyphicon glyphicon-info-sign"></span> Благодарим за оплату лицензии на api.digi-post.ru. Лицензия активна.</div>');
			}
		},
		error: function(data){
			console.log(data);
		}
	});
}
$("#print-blank-btn").click(function(e) {
var href = $(this).attr('href');
if (href == "#"){
	e.preventDefault();
	var btn = $(this);
	btn.button('loading');
	$.ajax({
		type: "POST",
		url: "https://api.digi-post.ru/blanks",
		data: $("#blank").serialize(),
		beforeSend: function (xhr) {
			xhr.setRequestHeader ("Authorization", "Basic " + btoa('<?php echo $this->config->get("digipost_login");?>' + ":" + '<?php echo $this->config->get("digipost_api_key");?>'));
		},
		success: function(data) {
			var obj = jQuery.parseJSON(data);
			if (obj.error == 0){
				btn.button('complete');
				btn.removeClass('btn-primary');
				btn.addClass('btn-success');
				btn.prop("href", obj.message);
				btn.prop("download", obj.message);
				btn.attr("target","_blank");
				recalculate();
			} else {
				$("#alert").removeClass('alert-warning');
				$("#alert").addClass('alert-danger');
				$("#alert").html( "<span class=\"glyphicon glyphicon-info-sign\"></span> " + obj.message );
				$("html, body").animate({ scrollTop: 0 }, 600);
			}
		},
		error: function(data){
			console.log(data);
		}
	});
}
});
</script>
</body>
</html>