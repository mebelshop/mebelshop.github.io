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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td>Доступные методы доставки:</td>
            <td>
              <?php if($digicalc_delivery_type_parcel) { ?>
              <input type="checkbox" name="digicalc_delivery_type_parcel"  checked="checked" />Обычная посылка<br />
              <?php } else { ?>
              <input type="checkbox" name="digicalc_delivery_type_parcel" />Обычная посылка<br />
              <?php } ?>
              <?php if($digicalc_delivery_type_bookpost_1class) { ?>
              <input type="checkbox" name="digicalc_delivery_type_bookpost_1class" checked="checked" />Бандероль 1й класс<br />
              <?php } else { ?>
             <input type="checkbox" name="digicalc_delivery_type_bookpost_1class" />Бандероль 1й класс<br />
              <?php } ?>
              <?php if($digicalc_delivery_type_valued_bookpost) { ?>
              <input type="checkbox" name="digicalc_delivery_type_valued_bookpost" checked="checked" />Бандероль с объявленной ценностью<br />
              <?php } else { ?>
             <input type="checkbox" name="digicalc_delivery_type_valued_bookpost" />Бандероль с объявленной ценностью<br />
              <?php } ?>
              <?php if($digicalc_delivery_type_bookpost) { ?>
              <input type="checkbox" name="digicalc_delivery_type_bookpost" checked="checked" />Обычная бандероль<br />
              <?php } else { ?>
              <input type="checkbox" name="digicalc_delivery_type_bookpost" />Обычная бандероль<br />
              <?php } ?>
              <?php if($digicalc_delivery_type_ems) { ?>
              <input type="checkbox" name="digicalc_delivery_type_ems" checked="checked"/>ЕМС<br />
              <?php } else { ?>
              <input type="checkbox" name="digicalc_delivery_type_ems" />ЕМС<br />
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td>Ваш индекс:</td>
            <td>
              <input type="text" name="digicalc_postcode" value="<?php echo $digicalc_postcode; ?>" size="6" /><br>
              <span class="help" style="display:inline">Индекс места отправления (откуда вы отправляете посылки)</span>
            </td>
          </tr>
          <tr>
            <td>Надбавка:</td>
            <td>
              <input type="text" name="digicalc_addcost" value="<?php echo $digicalc_addcost; ?>" size="10" /> руб. <br>
             <span class="help" style="display:inline">добавлять эту сумму к стоимости расчета (например стоимость упаковки)</span>
            </td>
          </tr>
          <tr>
            <td>Бесплатная доставка:</td>
            <td>
              <input type="text" name="digicalc_cost_free" value="<?php echo $digicalc_cost_free; ?>" size="10" /> руб.<br>
             <span class="help" style="display:inline">бесплатная доставка, начиная от этой суммы</span>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_weight_class; ?></td>
            <td><select name="digicalc_weight_class_id">
                <?php foreach ($weight_classes as $weight_class) { ?>
                <?php if ($weight_class['weight_class_id'] == $digicalc_weight_class_id) { ?>
                <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_display_time ?></td>
            <td><select name="digicalc_display_time">
                <?php if ($digicalc_display_time) { ?>
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
              </select></td>
          </tr>
           <tr>
            <td>Округлять копейки?</td>
            <td><select name="digicalc_round_summ">
                <?php if ($digicalc_round_summ) { ?>
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
              </select><br><span class="help" style="display:inline">доставка считается с точностью до копеек, округлять результат?</span></td>
          </tr>
          <tr>
            <td>Все отправления ценные</td>
            <td><select name="digicalc_all_valued">
                <?php if ($digicalc_all_valued) { ?>
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
              </select><br><span class="help" style="display:inline">если все отправления оцениваются при отправке в стоимость заказа, то сумма за доставку будет на 4% больше</span></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="digicalc_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $digicalc_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status ?></td>
            <td><select name="digicalc_status">
                <?php if ($digicalc_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="digicalc_sort_order" value="<?php echo $digicalc_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
        
        

      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>