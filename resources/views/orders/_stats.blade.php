<div id="statsModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-sm relative">
        <button
            type="button"
            class="absolute top-2 right-2 text-gray-500 hover:text-black"
            onclick="closeStats()"
        >&times;</button>

        <h3 class="text-lg font-bold mb-4">Статистика заказов</h3>
        <ul class="space-y-1 text-sm">
            <li>Всего заказов (с учетом фильтров): {{ $stats['total'] ?? 0 }}</li>
            <li>Новых заказов: {{ $stats['new'] ?? 0 }}</li>
            <li>В работе: {{ $stats['in_progress'] ?? 0 }}</li>
            <li>Завершено: {{ $stats['done'] ?? 0 }}</li>
        </ul>
    </div>
</div>

<script>
    function openStats() {
        document.getElementById('statsModal')?.classList.remove('hidden');
    }

    function closeStats() {
        document.getElementById('statsModal')?.classList.add('hidden');
    }

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeStats();
        }
    });
</script>

