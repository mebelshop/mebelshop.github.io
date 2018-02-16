<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } else if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a><a href="#tab-about"><?php echo $tab_about; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
       <div id="tab-general">
       <h2>Основные настройки</h2>
        <table class="form">
          <tr>
            <td>Логин на api.Digi-Post:</td>
            <td>
              <input type="text" name="digipost_login" value="<?php echo $digipost_login; ?>" size="60" /><br>
              <span class="help" style="display:inline">Необходима регистрация на <a href="http://api.digi-post.ru/">api.digi-post.ru</a></span>
            </td>
          </tr>
          <tr>
            <td>API ключ на api.Digi-Post:</td>
            <td>
              <input type="text" name="digipost_api_key" value="<?php echo $digipost_api_key; ?>" size="60" /><br>
              <span class="help" style="display:inline">Выдается в <a href="http://api.digi-post.ru/user/profile">личном кабинете</a> после регистрации на api.digi-post.ru</span>
            </td>
          </tr>
          <tr>
            <td>Включить отслеживание?</td>
            <td><?php if ($enable_digi_tracking) { ?>
              <input type="radio" name="enable_digi_tracking" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="enable_digi_tracking" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="enable_digi_tracking" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="enable_digi_tracking" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          
        </table>
       
        
       <h2>Данные отправителя / для бланков</h2>
        <table class="form">
          <tr>
            <td><?php echo $entry_name; ?></td>
            <td>
              <input type="text" name="digipost_from_name" value="<?php echo $digipost_from_name; ?>" size="60" /><br><?php echo $text_from_name; ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_address_1; ?></td>
            <td>
              <input type="text" name="digipost_from_address_1" value="<?php echo $digipost_from_address_1; ?>" size="60" /><br><?php echo $text_from_address_1; ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_address_2; ?></td>
            <td>
              <input type="text" name="digipost_from_address_2" value="<?php echo $digipost_from_address_2; ?>" size="60" /><br><?php echo $text_from_address_2; ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_postindex; ?></td>
            <td>
              <input type="text" name="digipost_postindex" value="<?php echo $digipost_postindex; ?>" size="10" /><br><?php echo $text_postindex; ?>
            </td>
          </tr>
        </table>
        <h2>Предъявленный документ / для бланков</h2>
        <table class="form">
          <tr>
            <td><?php echo $entry_doc_type; ?></td>
            <td>
              <input type="text" name="digipost_doc_type" value="<?php echo $digipost_doc_type; ?>" size="30" /><br><?php echo $text_doc_type; ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_doc_serial; ?></td>
            <td>
              <input type="text" name="digipost_doc_serial" value="<?php echo $digipost_doc_serial; ?>" size="10" /><br><?php echo $text_doc_serial; ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_doc_num; ?></td>
            <td>
              <input type="text" name="digipost_doc_num" value="<?php echo $digipost_doc_num; ?>" size="10" /><br><?php echo $text_doc_num; ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_doc_date; ?></td>
            <td>
              <input type="text" name="digipost_doc_date" value="<?php echo $digipost_doc_date; ?>" size="10" /><br><?php echo $text_doc_date; ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_doc_received_by; ?></td>
            <td>
              <input type="text" name="digipost_doc_received_by" value="<?php echo $digipost_doc_received_by; ?>" size="50" /><br><?php echo $text_doc_received_by; ?>
            </td>
          </tr>
        </table>
        
        <h2>Наложка в адрес юр лица / для бланков</h2>
        <table class="form">
          <tr>
            <td><?php echo $entry_name; ?></td>
            <td>
              <input type="text" name="digipost_from_name_legal" value="<?php echo $digipost_from_name_legal; ?>" size="60" /><br><?php echo $text_from_name; ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_inn; ?></td>
            <td>
              <input type="text" name="digipost_inn" value="<?php echo $digipost_inn; ?>" size="30" /><br>
              <span class="help" style="display:inline">Ваш ИНН</span>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_bik; ?></td>
            <td>
              <input type="text" name="digipost_bik" value="<?php echo $digipost_bik; ?>" size="10" /><br>
              <span class="help" style="display:inline">БИК вашего банка</span>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_correspondent_account; ?></td>
            <td>
              <input type="text" name="digipost_correspondent_account" value="<?php echo $digipost_correspondent_account; ?>" size="50" /><br>
              <span class="help" style="display:inline">Номер вашего кор/счет</span>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_current_account; ?></td>
            <td>
              <input type="text" name="digipost_current_account" value="<?php echo $digipost_current_account; ?>" size="50" /><br>
              <span class="help" style="display:inline">Номер вашего рас/счета</span>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_bank_name; ?></td>
            <td>
              <input type="text" name="digipost_bank_name" value="<?php echo $digipost_bank_name; ?>" size="50" /><br>
              <span class="help" style="display:inline">Наименование вашего банка</span>
            </td>
          </tr>
        </table>

        
       </div>
       <div id="tab-about">
        <table class="form">
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="digipost_status">
                <?php if ($digipost_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
              <input type="hidden" name="digipost_installed" value="1" />
            </td>
          </tr>
          <tr>
            <td style="min-width:200px;"><?php echo $text_ext_name; ?></td>
            <td style="min-width:400px;"><?php echo $ext_name; ?></td>
            <td rowspan="7" style="border-bottom:0px;"><img style="margin-left:60px;" src="<?php echo $this->config->get('config_url')?>/image/digipost.png" /></td>
          </tr>
          <tr>
            <td><?php echo $text_ext_version; ?></td>
            <td><b><?php echo $ext_version; ?></b> [ <?php echo $ext_type; ?> ]</td>
          </tr>
          <tr>
            <td><?php echo $text_ext_compat; ?></td>
            <td><?php echo $ext_compatibility; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_ext_url; ?></td>
            <td><a target="_blank" href="http://api.digi-post.ru/site/page?view=opencart" target="_blank">http://api.digi-post.ru/site/page?view=opencart</a></td>
          </tr>
          <tr>
            <td><?php echo $text_ext_support; ?></td>
            <td>
              <a href="mailto:<?php echo $ext_support; ?>?subject=Opencart Запрос поддержки">Поддержка</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a href="<?php echo $ext_support_forum; ?>"><?php echo $text_forum; ?></a>
            </td>
          </tr>
          <tr>
            <td><?php echo $text_ext_legal; ?></td>
            <td>Copyright &copy; 2012 - <?php echo date('Y');?> api.Digi-Post.ru. </td>
          </tr>
          <tr>
            <td style="border-bottom:0px;"></td>
            <td style="border-bottom:0px;"></td>
          </tr>
        </table>
       </div>
      </form>
    </div>
  </div>
</div>
<div id="legal_text" style="display:none"></div>
<div id="help_text" style="display:none"></div>
<script type="text/javascript"><!--
$('#tabs a').tabs();
$("#legal_notice").click(function(e) {
    e.preventDefault();
    $("#legal_text").load(this.href, function() {
        $(this).dialog({
            title: '<?php echo $text_terms; ?>',
            width:  800,
            height:  600,
            minWidth:  500,
            minHeight:  400,
            modal: true,
        });
    });
    return false;
});
$("#help_notice").click(function(e) {
    e.preventDefault();
    $("#help_text").load(this.href, function() {
        $(this).dialog({
            title: '<?php echo $text_help_request; ?>',
            width:  800,
            height:  600,
            minWidth:  500,
            minHeight:  400,
            modal: true,
        });
    });
    return false;
});
//--></script>
<?php echo $footer; ?>