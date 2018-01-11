<script src="../jquery/jquery-1.7.2.min.js"></script>
<script>
$.ajax({
	url : "http://sapi.dev.ztgame.com/yuyue/datasave",
	type:"post",
	data:{cid:6,phone_number:'18914923031',extra_data:{name:'ljqian'}},
	dataType:"jsonp",
	jsonp:"callback",
	success:function(data) {
		console.log(data);
	}
});
</script>