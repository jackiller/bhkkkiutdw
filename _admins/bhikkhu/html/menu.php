<!--<div id="logo"></div>-->
<div id="caption">ข้อมูลภิกขุและสามเณร วัดนาป่าพง</div>
<ul id="leftmenu">
<?php
	foreach($pagedata as $pk=>$pd){
?>
    <li class="<?php echo $page == $pk ? 'active' : '' ?>">
        <a href="index.php?page=<?php echo $pk ?>&theme=<?php echo $theme ?>&mode=<?php echo $pd['my_custom_param_1'] ?>"><?php echo $pd['title_1'] ?></a>
    </li>
<?php
	}
?>
</ul>