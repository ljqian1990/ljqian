var express = require('express');
var app = express();
var server = require('http').createServer(app);
var SkyRTC = require('skyrtc').listen(server);
var path = require("path");

var port = process.env.PORT || 3000;
server.listen(port);

app.use(express.static(path.join(__dirname, 'public')));

app.get('/', function(req, res) {
	res.sendfile(__dirname + '/index.html');
});

SkyRTC.rtc.on('new_connect', function(socket) {
	console.log('create new conncet');
});

SkyRTC.rtc.on('remove_peer', function(socketId) {
	console.log(socketId + "member leaved");
});

SkyRTC.rtc.on('new_peer', function(socket, room) {
	console.log("new member " + socket.id + " join the room:" + room);
});

SkyRTC.rtc.on('socket_message', function(socket, msg) {
	console.log("recept the new message:" + msg + " from：" + socket.id);
});

SkyRTC.rtc.on('ice_candidate', function(socket, ice_candidate) {
	console.log("recept the ICE Candidate from:" + socket.id);
});

SkyRTC.rtc.on('offer', function(socket, offer) {
	console.log("recept the offer from:" + socket.id);
});

SkyRTC.rtc.on('answer', function(socket, answer) {
	console.log("recept the answer from:" + socket.id);
});

SkyRTC.rtc.on('error', function(error) {
	console.log("error：" + error.message);
});