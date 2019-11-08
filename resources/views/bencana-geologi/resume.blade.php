@if ($contents->isEmpty())
    Belum ada laporan masuk
@else
    <textarea id="whatsaap">{{ str_replace('&deg;C','°C',implode("\n\n", $contents->toArray())) }}</textarea>
    <button id="copy">Copy</button>
    <script>
        function copy() {
            var copyText = document.querySelector("#whatsaap");
            copyText.select();
            document.execCommand("copy");
        }

        document.querySelector("#copy").addEventListener("click", copy);
    </script> 
@endifnav