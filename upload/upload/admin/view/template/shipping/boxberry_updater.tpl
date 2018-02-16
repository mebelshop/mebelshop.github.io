<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general" class="vtabs-content">
			<div id="tabs" class="htabs">
				<a class="selected" href="#shipping">Параметры</a>
				<a href="#tracking">Отслеживание</a>
			</div>
				
			<div id="shipping">
			  <table class="form">
			  
				<tr>
				  <td>Статус:</td>
				  <td><select name="boxberry_updater_status">
					  <?php if (${'boxberry_updater_status'}) { ?>
					  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					  <option value="0"><?php echo $text_disabled; ?></option>
					  <?php } else { ?>
					  <option value="1"><?php echo $text_enabled; ?></option>
					  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					  <?php } ?>
					</select></td>
				</tr>
				
				<tr>
				  <td>Boxberry token:</td>
				  <td>
				  <input type="text" name="boxberryupd_token" value="<?php echo $boxberryupd_token; ?>" size="32" required="required" />
				  </td>
				</tr>
				<tr>
				  <td>Код доставки Boxberry в OpenCart:</td>
				  <td>
				  <input type="text" name="boxberryupd_shipping_code" value="<?php echo $boxberryupd_shipping_code; ?>" size="16" />
				  </td>
				</tr>
				<tr>
				  <td>Не обрабатывать заказы с этими статусами:</td>
				  <td><select name="boxberryupd_order_statuses[]" size="15" multiple>
					  <?php foreach ($order_statuses as $order_status) { ?>
					  <option value="<?php echo $order_status['order_status_id']; ?>"<?php echo (in_array($order_status['order_status_id'], $boxberryupd_order_statuses) ? ' selected="selected"' : ''); ?>><?php echo $order_status['name']; ?></option>
					  <?php } ?>
					</select></td>
				</tr>
			  </table>
			</div>
		  
			<div id="tracking">
			  <table class="form">
				<tr>
				  <td colspan="2">Подстановка данных в уведомления:</td>
				  <td colspan="3">
				  <span class="help">{order_id} - номер заказа, {track_no} - код отслеживания, {firstname},{lastname} - имя и фамилия покупателя, {address} - адрес доставки.</span>
				  <br/><span class="help">{DATE} - дата смены статуса, {COMMENT} - комментарий к статусу.</span>
				  </td>
				</tr>
				  
				<tr align="left">
				<th width="20%">Статус заказа в Boxberry</th>
				<th width="10%">Обраб.</th>
				<th width="20%">Статус заказа в OpenCart</th>
				<th width="35%">Писать в историю заказа / Текст SMS-уведомления</th>
				<th width="15%">Уведомлять покупателя</th>
				</tr>
				<?php foreach ($status_mapping as $code=>$vals) { ?>
			  
				<tr>
				  <td><?php echo $code.': '.$vals['name'] ?></td>
				  
				  <td>
				  <input type="checkbox" name="<?php echo 'boxberryupd_mapping'.$code.'_enabled';?>" value="1"<?php echo ($vals['enabled'] ? ' checked' : ''); ?>/>
				  </td>
				  
				  <td><select name="<?php echo 'boxberryupd_mapping'.$code.'_status';?>">
						<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $vals['status']) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				  </td>

				  <td>
				  <textarea name="<?php echo 'boxberryupd_mapping'.$code.'_text';?>" rows="3" cols="60"><?php echo $vals['text']; ?></textarea>
				  <?php if (isset($vals['sms'])) { ?>
				  <textarea name="<?php echo 'boxberryupd_mapping'.$code.'_sms';?>" rows="2" cols="60" placeholder="SMS-уведомление"><?php echo $vals['sms']; ?></textarea>
				  <?php } ?>
				  </td>
				  
				  <td>
				  <label><input type="checkbox" name="<?php echo 'boxberryupd_mapping'.$code.'_notify';?>" value="1"<?php echo ($vals['notify'] ? ' checked' : ''); ?>/>Email</label>
				  <?php if (isset($vals['sms'])) { ?>
				  <label><input type="checkbox" name="<?php echo 'boxberryupd_mapping'.$code.'_smsnotify';?>" value="1"<?php echo ($vals['smsnotify'] ? ' checked' : ''); ?>/>SMS</label>
				  <?php } ?>
				  </td>
				  
				  <?php } ?>
			  </table>
			</div>
			
        </div>
		<input type="hidden" name="boxberryupd_set" value="1" />
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 
<?php echo $footer; ?> 
