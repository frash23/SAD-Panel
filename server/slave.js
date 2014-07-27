var net = require('net');

var HOST = '127.0.0.1';
var PORT = 21300;

var client = new net.Socket();
client.connect(PORT, HOST, function() {

    client.write('SLAVE_CONNECT;RAM:64G;CC:US;IP:');

});

// Add a 'data' event handler for the client socket
// data is what the server sent to this socket
client.on('data', function(data) {
    
    console.log('Data was recieved from master: '+data);
    // Close the client socket completely
    
});

// Add a 'close' event handler for the client socket
client.on('close', function() {
    console.log('Connection closed');
});
