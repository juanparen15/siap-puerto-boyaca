<x-filament-widgets::widget>
    <x-filament::section heading="Mapa de Elementos">
        <div id="mapa-dashboard" style="height: 350px;" wire:ignore></div>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof L === 'undefined') return;
            var map = L.map('mapa-dashboard').setView([5.977, -74.579], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            fetch('/api/mapa/elementos')
                .then(r => r.json())
                .then(items => {
                    var colors = { operativa: '#16a34a', no_operativa: '#dc2626', desinstalada: '#6b7280' };
                    items.forEach(function(el) {
                        if (el.latitud && el.longitud) {
                            L.circleMarker([el.latitud, el.longitud], {
                                radius: 4, fillColor: colors[el.estado] || '#6b7280',
                                color: '#fff', weight: 1, fillOpacity: 0.8
                            }).addTo(map);
                        }
                    });
                });
        });
        </script>
    </x-filament::section>
</x-filament-widgets::widget>
