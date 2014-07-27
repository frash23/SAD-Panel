var net = require('net');

// Create a server instance, and chain the listen function to it
// The function passed to net.createServer() becomes the event handler for the 'connection' event
// The sock object the callback function receives UNIQUE for each connection
net.createServer(function(sock) {
    
    // We have a connection - a socket object is assigned to the connection automatically
    console.log(sock.remoteAddress +' has connected');
    
    // Add a 'data' event handler to this instance of socket
    sock.on('data', function(data) {
        
        console.log('Data was recieved from '+sock.remoteAddress+': '+data);
	switch(String(data).split(';')[0]){
		case 'SRV_CREATE'  : console.log('Recieved SRV_CREATE  command with arguments: '+String(data).split(';').slice(1).join(';'));break;
		case 'SRV_START'   : console.log('Recieved SRV_START   command with arguments: '+String(data).split(';').slice(1).join(';'));break;
		case 'SRV_STOP'    : console.log('Recieved SRV_STOP    command with arguments: '+String(data).split(';').slice(1).join(';'));break;
		case 'SRV_RESTART' : console.log('Recieved SRV_RESTART command with arguments: '+String(data).split(';').slice(1).join(';'));break;
		default            : console.log('Recieved invalid socket message: '+data);
	}
      
    });
    
    // Add a 'close' event handler to this instance of socket
    sock.on('close', function(data) {
        console.log(sock.remoteAddress +' has disconnected');
    });
    
}).listen(21300,'127.0.0.1');

console.log('Server started');
var fs = require('fs');
eval(fs.readFileSync('master.cfg')+'');
console.log(cf.master_password);
