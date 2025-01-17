<x-layout>
    @section('title', 'SSH Interaktif Terminal')
    <div class="flex-1">
        <div>
            @foreach($device->deviceType->scripts as $script)
            {{$script->script}}
            @endforeach
        </div>
    <div class="container">
        <h1>SSH Interaktif Terminal</h1>
        <div id="terminal" style="background: black; color: white; padding: 10px; font-family: monospace; height: 300px; overflow-y: auto;">
            <div id="output"></div>
            <div>
                <span style="color: lime;">$ </span>
                <input id="command-input" type="text"
                       style="background: black; color: white; border: none; outline: none; width: 90%;"
                       autofocus />
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const terminal = document.getElementById('terminal');
        const output = document.getElementById('output');
        const commandInput = document.getElementById('command-input');

        // Cihaz ID'sini PHP'den alınan id değişkeni ile ayarlayın
        const deviceId = {{ $id }};

        // Enter tuşuna basıldığında komutu gönder
        commandInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                const command = commandInput.value.trim();
                commandInput.value = '';

                if (command === '') return;

                // Komutu terminalde göster
                output.innerHTML += `<div><span style="color: lime;">$</span> ${command}</div>`;
                terminal.scrollTop = terminal.scrollHeight;

                // Komut gönder ve sonucu al
                axios.post('/devices/executeCommand', {
                    command: command,
                    id: deviceId
                })
                    .then(response => {
                        // Backend'den dönen çıktıyı terminale yazdır
                        const responseOutput = response.data.message || response.data;
                        output.innerHTML += `<div>${responseOutput.replace(/\n/g, '<br>')}</div>`;
                        terminal.scrollTop = terminal.scrollHeight;
                    })
                    .catch(error => {
                        // Hata mesajını terminale yazdır
                        const errorMessage = error.response?.data?.message || 'Bir hata oluştu.';
                        output.innerHTML += `<div style="color: red;">Hata: ${errorMessage}</div>`;
                        terminal.scrollTop = terminal.scrollHeight;
                    });
            }
        });
    </script>

</x-layout>
