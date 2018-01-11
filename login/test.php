<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>

</head>
<body>
<a href="javascript:void(0);" id="test1">test1</a>
<a href="javascript:void(0);" id="test2">test2</a>
<a href="javascript:void(0);" id="test3" class="class1">test3</a>
<a href="javascript:void(0);" id="test4" class="class2">test4</a>
</body>
<script src="./jquery-1.7.2.min.js"></script>
<script src="http://common.dev.ztgame.com:9451/login/login.js"></script>
<script>
(function(){
	this.custom = function(user) {
		console.log(user.uid);
		console.log(user.account);
		console.log(user.showaccount);
	},
	this.bindClick = function(){
		return {'ids':[{name:'test1', func:function(user){console.log(user.uid)}}, {name:'test2', func:function(user){console.log(user.account)}}], 'classes':[{name:'class1'}]};
	}
}).call(Login.prototype);

var login = new Login();
login.init();

</script>
</html>