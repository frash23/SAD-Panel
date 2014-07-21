domain = require('domain'),
d = domain.create();

d.on('error', function(err) {
  console.error(err);
});

var stdin = process.openStdin(); 
stdin.setEncoding('utf8');
stdin.on( 'data', function(key){eval(key);});

String.prototype.containsString = function(it) { return this.indexOf(it) !== -1; };
function removeSubDir(a){
	var thing="";
	var spliced=a.split("/");
	for(var i=0;i<spliced.length-1;i++){
		if(spliced[i] !== spliced[spliced.length-1]){
			thing+=spliced[i];
		}
	}
}

function checkfType(a,callback){
  exec("file '"+a+"'",function(err,stdout,stderr){
    callback(stdout);
  });
}
  		
var noStart = false;
var prc = null;
var closeListener;
var users,http = require('http'),
	fs = require('fs');

var app = http.createServer(function(request,response){}).listen(8970,"0.0.0.0");

var prc;
var io = require('socket.io').listen(app);
var undef = "undefined";
var split = require('split');
var spawn = require('child_process').spawn;
var exec = require('child_process').exec;
console.log(typeof prc);
setInterval(function(){if(prc === null && noStart === false){console.log('poopy');
prc = spawn('java',  ['-jar', '-Xmx512M', '-Dfile.encoding=utf8', 'spigot.jar']);
prc.stdout.setEncoding('utf8');prc.stdin.setEncoding = 'utf-8';
prc.stdout.on('data', function(data){io.sockets.emit("logUpdate",{message:data});});}
if(prc !== null){
  if(!prc._events.close){
    prc.on('close',function(code){prc=null});
  }
}
},500);

io.sockets.on('connection',function(socket){
  socket.on('sendConsoleReq',function(data){
    if(prc === null){
      if(data['message'] == "start"){
        noStart = false;
      }
    }else if(data['message'] == "stop"){
      noStart = true;
      prc.stdin.write(data['message']+"\n");
    }else if(data['message'] == "restart"){
      noStart = false;
      prc.stdin.write("stop\n");
    }else{
      prc.stdin.write(data['message']+"\n");
    }
  });
  
  socket.on('sendDirReq',function(data){
	checkfType(data['message'],function(resp){
		if (resp.containsString("directory")===true){
			subdir = subdir
	  		exec("ls "+data['message'],function(err,stdout,stderr){console.log(stdout);io.sockets.emit("dirResp",{message:stdout,subDir:data['message']})});
	  	}else if(resp.containsString("text")===true){
	  		console.log("Is text");
	  		exec("cat "+data['message'],function(err,stdout,stderr){console.log(stdout);io.sockets.emit("fileResp",{message:stdout})});
  		}else{console.log("is binary data")}
	});
  });
});


setInterval(function(){if(prc !== null){us=spawn('ps',['-p',prc.pid,'-o','%cpu,vsz']);us.stdout.setEncoding('utf8');us.stdout.on('data',function(data){io.sockets.emit("resUpdate",{message:data.split('\n')[1]})});}},2000);
