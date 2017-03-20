<html>
<head>
<title>Upload Form</title>
</head>
<body>
    
<!--
<?php echo form_open_multipart('upload/do_upload');?>
    
    <?php foreach ($banksList as $bankId => $bankName): ?>
        <?php echo $bankName; ?> <input type="file" name="<?php echo $bankId; ?>" size="20" /> <br>
    <?php endforeach; ?>

<br /><br />

<input type="submit" value="upload" />

</form>
-->
<?php echo form_open_multipart('upload/do_upload1');?>
    
    <?php foreach ($banksList as $bank): ?>
        <?php echo $bank['name']; ?> <input type="file" name="<?php echo $bank['id']; ?>" size="20" /> <br>
    <?php endforeach; ?>

<br /><br />

<input type="submit" value="upload" />

</form>


    <br /><br />
    <a href="<?php echo base_url();?>index.php/banks/banks_management">Banks</a><br />
    <a href="<?php echo base_url();?>index.php/banks/maps_management/">Maps</a><br />
    <a href="<?php echo base_url();?>index.php/banks/skip_lines/">Skip Lines</a><br />
    <a href="<?php echo base_url();?>index.php/banks/payee_rules/">Payee rules</a><br />
    <a href="<?php echo base_url();?>index.php/banks/categories/">Categories</a><br />
    <a href="<?php echo base_url();?>index.php/banks/payees_categories/">Payee-Category</a><br />

</body>
</html>