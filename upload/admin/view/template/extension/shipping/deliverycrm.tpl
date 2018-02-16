<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (!$deliverycrm_status) { ?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> После установки модуля зайдите в Менеджер дополнений и нажмите кнопку "Обновить"
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-status">Статус:</label>
            <div class="col-sm-7">
              <select name="deliverycrm_status" id="input-status" class="form-control">			
                <?php if ($deliverycrm_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		  
		  <div class="form-group">
			<label class="col-sm-3 control-label" for="deliverycrm_ignore_security"><span data-toggle="tooltip" title="Отключить проверку доступа к API при добавлении трек-номера">Не могу настроить доступ к API:</span></label>
			<div class="col-sm-7">
				<div class="checkbox">
					<label><input class="_form_flag" type="checkbox" name="deliverycrm_ignore_security" value="1" <?php echo ($deliverycrm_ignore_security ? ' checked="checked"' : ''); ?>/>
					<span style="color: red;">Используйте с осторожностью, в качестве временной меры!</span></label>
				</div>
			</div>
		  </div>
		  
		  <div class="form-group">
			<div class="col-sm-12"><h3>Что делать при добавлении трек-номера к заказу:</h3></div>
		  </div>
		  
		  <div class="form-group">
			<label class="col-sm-3 control-label" for="deliverycrm_change_status">Менять статус заказа:</label>
			<div class="col-sm-7">
				<div class="checkbox">
					<label><input class="_form_flag" rel="_change_status" type="checkbox" name="deliverycrm_change_status" value="1" <?php echo ($deliverycrm_change_status ? ' checked="checked"' : ''); ?>/></label>
					</div>
			</div>
		  </div>
		  <div class="form-group _change_status">
			<label class="col-sm-3 control-label" for="deliverycrm_order_status">Устанавливать статус:</label>
			<div class="col-sm-7">
			  <select name="deliverycrm_order_status" class="form-control">			
				<?php foreach ($order_statuses as $order_status) { ?>
				<option value="<?php echo $order_status['order_status_id']; ?>"<?php echo ($order_status['order_status_id'] == $deliverycrm_order_status ? ' selected="selected"' : ''); ?>><?php echo $order_status['name']; ?></option>
				<?php } ?>
              </select>
			</div>
		  </div>
		  
		  <div class="form-group">
			<label class="col-sm-3 control-label" for="deliverycrm_email_notify"><span data-toggle="tooltip" title="При помощи добавления комментария в историю заказа">Уведомлять покупателя по E-mail:</span></label>
			<div class="col-sm-7">
				<div class="checkbox"><label><input class="_form_flag" rel="_email_notify" type="checkbox" name="deliverycrm_email_notify" value="1" <?php echo ($deliverycrm_email_notify ? ' checked="checked"' : ''); ?>/></div>
			  </div>
		  </div>
		  
		  <div class="form-group _email_notify">
			<label class="col-sm-3 control-label" for="deliverycrm_email_text"><span data-toggle="tooltip" title="Подстановка в письмо: {order_id} - номер заказа, {track_no} - трек-номер, {shipping_firstname} и {shipping_lastname} - имя и фамилия покупателя.">Текст письма:</span></label>
			<div class="col-sm-7">
				<textarea name="deliverycrm_email_text" rows="10" class="form-control"><?php echo $deliverycrm_email_text; ?></textarea>
			</div>
		  </div>

		  <div class="form-group">
			<label class="col-sm-3 control-label" for="deliverycrm_sms_notify"><span data-toggle="tooltip" title="Настройте SMS-шлюз">Уведомлять покупателя по SMS:</span></label>
			<div class="col-sm-7">
				<div class="checkbox"><label><input class="_form_flag" rel="_sms_notify" type="checkbox" name="deliverycrm_sms_notify" value="1" <?php echo ($deliverycrm_sms_notify ? ' checked="checked"' : ''); ?>/>
				</div>
			</div>
		  </div>
          
            <div class="form-group _sms_notify">
              <label class="col-sm-2 control-label" for="input-template">SMS шлюз:</label>
              <div class="col-sm-10">
                <select name="deliverycrm_sms_gatename" id="select-smsgate" class="form-control">
                  <?php foreach ($sms_gatenames as $sms_gatename) { ?>
                  <?php if ($deliverycrm_sms_gatename == $sms_gatename) { ?>
                  <option value="<?php echo $sms_gatename; ?>" selected="selected"><?php echo $sms_gatename; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $sms_gatename; ?>"><?php echo $sms_gatename; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <br />
                <span id="sms_gate_link"></span>
              </div>
            </div>
            <div class="form-group _sms_notify">
              <label class="col-sm-2 control-label" for="input-sms-gate-username">Логин или ID на SMS шлюзе:</label>
              <div class="col-sm-10">
                <input type="text" name="deliverycrm_sms_gate_username" value="<?php echo $deliverycrm_sms_gate_username; ?>" id="input-sms-gate-username" class="form-control" />
              </div>
            </div>
            <div class="form-group _sms_notify">
              <label class="col-sm-2 control-label" for="input-config-sms-gate-password">Пароль,token или API key на SMS шлюзе:</label>
              <div class="col-sm-10">
                <input type="password" name="deliverycrm_sms_gate_password" value="<?php echo $deliverycrm_sms_gate_password; ?>" id="input-sms-gate-password" class="form-control" />
              </div>
            </div>	
            <div class="form-group _sms_notify">
              <label class="col-sm-2 control-label" for="input-config-sms-gate-from">Отправитель SMS:</label>
              <div class="col-sm-10">
                <input type="text" name="deliverycrm_sms_gate_from" value="<?php echo $deliverycrm_sms_gate_from; ?>" id="input-sms-gate-from" class="form-control" />
              </div>
            </div>          
		  
		  <div class="form-group _sms_notify">
			<label class="col-sm-3 control-label" for="deliverycrm_sms_text"><span data-toggle="tooltip" title="Подстановка в SMS: {order_id} - номер заказа, {track_no} - трек-номер, {shipping_firstname} и {shipping_lastname} - имя и фамилия покупателя.">Текст SMS:</span></label>
			<div class="col-sm-7">
				<textarea name="deliverycrm_sms_text" rows="5" class="form-control"><?php echo $deliverycrm_sms_text; ?></textarea>
			</div>
		  </div>
		  
		  <div class="form-group">
			<label class="col-sm-3 control-label" for="deliverycrm_export"><span data-toggle="tooltip" title="DeliveryCRM - сервис отслеживания статусов доставки.">Экспортировать в <a href="http://deliverycrm.ru" target="_blank">DeliveryCRM</a>:</span></label>
			<div class="col-sm-7">
				<div class="checkbox"><label><input class="_form_flag" rel="_export_deliverycrm" type="checkbox" name="deliverycrm_export" value="1" <?php echo ($deliverycrm_export ? ' checked="checked"' : ''); ?>/>
				</div>
			</div>
		  </div>
		  
		  <div class="form-group _export_deliverycrm">
			<label class="col-sm-3 control-label" for="deliverycrm_user">ID пользователя:</label>
			<div class="col-sm-7">
				<input type="text" name="deliverycrm_user" value="<?php echo $deliverycrm_user; ?>" class="form-control input-small" />
				<a href="https://deliverycrm.ru/client/profile/index" target="_blank">получить ID пользователя</a>
			</div>
		  </div>
              
		  <div class="form-group _export_deliverycrm">
			<label class="col-sm-3 control-label" for="deliverycrm_api_id">Ключ API:</label>
			<div class="col-sm-7">
				<input type="text" name="deliverycrm_api_id" value="<?php echo $deliverycrm_api_id; ?>" class="form-control input-small" />
				<a href="https://deliverycrm.ru/client/profile/index" target="_blank">получить ключ API</a>
			</div>
		  </div>
		  
		  <div class="form-group _export_deliverycrm">
			<label class="col-sm-3 control-label" for="deliverycrm_test_mode">Тестовый режим:</label>
			<div class="col-sm-7">
              <select name="deliverycrm_test_mode" class="form-control">			
                <?php if ($deliverycrm_test_mode == '1') { ?>
                <option value="1" selected="selected">Да</option>
                <option value="0">Нет</option>
                <?php } else { ?>
                <option value="1">Да</option>
                <option value="0" selected="selected">Нет</option>
                <?php } ?>
              </select>
			</div>
		  </div>
              
		  <div class="form-group _export_deliverycrm">
            <label class="col-sm-3 control-label" for="deliverycrm-carriers">Используемые перевозчики:</label>
            <div class="col-sm-7">
              <select name="deliverycrm_carriers[]" id="deliverycrm-carriers" size="17" multiple class="form-control">			
				<?php foreach ($all_carriers as $num=>$carrier) { ?>
				<option value="<?php echo $num; ?>"<?php echo (isset($deliverycrm_carriers[$num]) ? ' selected="selected"' : ''); ?>><?php echo $carrier; ?></option>
				<?php } ?>
              </select>
            </div>
		  </div>
		  
		<input type="hidden" name="deliverycrm_set" value="1" />
      </form>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {

var smsGates = {
    bytehand: 'https://www.bytehand.com/?r=3ddf1b2421770b0c',
    bytehandcom: 'https://www.bytehand.com/?r=3ddf1b2421770b0c',
	smsc: 'https://smsc.ru/?ppOpenCart',
    smscru: 'https://smsc.ru/?ppOpenCart',
    smscua: 'https://smsc.ru/?ppOpenCart',
	epochtasms: 'http://www.epochta.ru/#a_aid=opencart',
	epochta: 'http://www.epochta.ru/#a_aid=opencart',
    epochtasmscomua: 'http://www.epochta.ru/#a_aid=opencart',
	unisender: 'http://www.unisender.com/?a=opencart',
    unisenderru: 'http://www.unisender.com/?a=opencart'
};
$('#select-smsgate').change(function() {
	var gate = $(this).val();
	if (smsGates[gate]) {
		$('#sms_gate_link').html('<a href="'+smsGates[gate]+'" target="_blank">Получить доступ</a>');
	}
	else {
		$('#sms_gate_link').html('');
	}
});
$('#select-smsgate').trigger('change');


$('._form_flag').change(function() {
	if ($(this).is(':checked')) {
		$('.'+$(this).attr('rel')).show();
	}
	else {
		$('.'+$(this).attr('rel')).hide();
	}
});
$('._form_flag').trigger('change');

var token = '';
// Login to the API
$.ajax({
	url: '<?php echo $store; ?>index.php?route=api/login',
	type: 'post',
	dataType: 'json',
	data: 'key=<?php echo $api_key; ?>',
	crossDomain: true,
	success: function(json) {
		$('.alert-api').remove();

        if (json['error']) {
    		if (json['error']['key']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger alert-api"><i class="fa fa-exclamation-circle"></i> ' + json['error']['key'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
    		}

            if (json['error']['ip']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger alert-api"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i> <?php echo $button_ip_add; ?></button></div>');
    		}
        }

        if (json['token']) {
			token = json['token'];
		}
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
});

$(document).delegate('#button-ip-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=user/api/addip&token=<?php echo $token; ?>&api_id=<?php echo $api_id; ?>',
		type: 'post',
		data: 'ip=<?php echo $api_ip; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-ip-add').button('loading');
		},
		complete: function() {
			$('#button-ip-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

})
</script>
<?php echo $footer; ?>
