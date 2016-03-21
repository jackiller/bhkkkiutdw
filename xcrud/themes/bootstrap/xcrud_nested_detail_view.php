<?php echo $this->render_table_name($mode); ?>
<div class="xcrud-view">
<div class="xcrud-top-actions btn-group"></div>
<?php echo $mode == 'view' ? $this->render_fields_list($mode,array('tag'=>'table','class'=>'table')) : $this->render_fields_list($mode,'div','div','label','div'); ?>
<div class="xcrud-top-actions btn-group">
    <?php
    //echo $this->render_button('save_return','save','list','btn btn-primary','','create,edit');
	// เปลี่ยนชื่อปุ่ม save_return เป็น save_nested_return จากนั้นไปที่ไฟล์ xcrud/languages/en.ini เพิ่ม save_nested_return แล้วกำหนด wording ที่ต้องการให้ตัวแปรนี้
    echo $this->render_button('save_nested_return','save','list','btn btn-primary','','create,edit');

    echo $this->render_button('save_new','save','create','btn btn-default','','create,edit');
    echo $this->render_button('save_edit','save','edit','btn btn-default','','create,edit');

    //echo $this->render_button('return','list','','btn btn-warning');
	// เปลี่ยนชื่อปุ่ม return เป็น return_nested จากนั้นไปที่ไฟล์ xcrud/languages/en.ini เพิ่ม return_nested แล้วกำหนด wording ที่ต้องการให้ตัวแปรนี้
    echo $this->render_button('return_nested','list','','btn btn-warning');

	?>

</div>
</div>
<div class="xcrud-nav">
    <?php echo $this->render_benchmark(); ?>
</div>
