
const socket = new WebSocket("ws://localhost:8000/ws/ssh");

const terminal = new Terminal({
    cols: 80,   // Terminalin genişliği
    rows: 24,   // Terminalin yüksekliği
    convertEol: true // Satır sonlarını doğru şekilde işlemeye yarar
});
terminal.open(document.getElementById('terminal'));
// WebSocket bağlantısı kuruyoruz

// Xterm terminalini FastAPI'nin başlattığı terminal oturumuna bağlamak için attach kullanıyoruz
socket.onopen = function() {
    terminal.attach(socket); // attach ile WebSocket üzerinden terminali bağla
};

socket.onmessage = function(event) {
    // WebSocket'ten gelen verileri terminale yazdırıyoruz
    terminal.write(event.data);

};

// Terminalden gelen verileri WebSocket'e gönderiyoruz
let commandBuffer = ''; // Komutları toplamak için bir buffer

terminal.onData(data => {
    console.log("Gelen tuş kodu:", data);
    commandBuffer += data;           // Gelen karakteri buffer'a ekle
    terminal.write(data);            // Karakteri terminalde göster
    if (data === '\r' || data === '\n') { // Enter tuşu
        socket.send(commandBuffer);       // Buffer'daki komutu gönder
        terminal.writeln('');            // Yeni satıra geç
        commandBuffer = '';              // Buffer'ı temizle
    }
});





