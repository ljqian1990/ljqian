<?php
	require_once 'init.php';
	$site_arr = array();
	try {
		$siteControl = siteControl::getInstance();
		$siteControl->setAccount('ljqian');
		$site_arr = $siteControl->showSiteList();
	}catch (Exception $ex){
		echo 'ERROR';exit;
	}
	header("Content-type:text/html;charset=utf-8");
?>

<select id="sitehot" style="width: 158px; height: 20px;">
<option value="0">所有网站</option>
<?php 
foreach ($site_arr as $value){
?>
<option label="<?php echo $value['sitename']?>" value="<?php echo $value['siteid']?>"><?php echo $value['sitename']?></option>
<?php
}
?>
</select>
<input type="button" value="提交" id="submit"/>
<script src="../jquery/jquery-1.7.2.min.js"></script>
<script>
$("#submit").click(function(){
	var sitehot = $("#sitehot").val();
	$.ajax({
		url:"./action.php?act=sitehot&siteid="+sitehot+"&rand="+Math.random(),
		type:"get",
		dataType:"json",
		success:function(data){
			if(data.ret == 1){
				alert(1);
			}
		}
	});
});
</script>