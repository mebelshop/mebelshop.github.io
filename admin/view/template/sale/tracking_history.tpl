<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<table class="list">
  <thead>
    <tr>
      <td class="left"><b></b></td>
      <td class="left"><b></b></td>
      <td class="left"><b></b></td>
      <td class="left"><b></b></td>
      <td class="left"><b></b></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($tracking_history != 0) { ?>
    <?php foreach ($tracking_history as $track) { ?>
    <tr>
      <td class="left"><?php echo $track->operationIndex; ?></td>
      <td class="left"><?php echo $track->operationDescription; ?></td>
      <td class="left"><?php echo $track->operationTypeName; ?></td>
      <td class="left"><?php echo $track->operationAttrName; ?></td>
      <td class="left"><?php echo $track->operationDate; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
