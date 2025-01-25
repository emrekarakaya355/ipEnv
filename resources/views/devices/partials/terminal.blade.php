<!-- Xterm.js kütüphanesini dahil ediyoruz -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/xterm@4.9.0/css/xterm.css">
<script src="https://cdn.jsdelivr.net/npm/xterm@4.9.0/lib/xterm.js"></script>


<x-layout>
    @section('title', 'SSH Interaktif Terminal')
    <div id="terminal"></div>
</x-layout>

@vite('resources/js/client.js')


<style>
    #terminal {
        font-family: 'Courier New', monospace;
        font-size: 14px;
        padding-left: 5px;
    }
</style>
