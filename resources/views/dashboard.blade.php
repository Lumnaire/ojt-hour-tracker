<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kamusta, {{ Auth::user()->name }}! 👋
        </h2>
    </x-slot>
    <div class="max-w-5xl mx-auto py-10 px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">🕒 OJT Hour Tracker</h2>
        <p class="text-gray-600 mb-8">Track your attendance and rendered hours with ease.</p>

        <!-- CARD START -->
        <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">

            <!-- Add Time In Button -->
            <div class="flex justify-start">
                <button onclick="document.getElementById('timeInModal').showModal()" class="bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 transition">
                    + Time In
                </button>

                <button onclick="exportToCSV()" class="bg-green-600 ml-3 text-white px-3 py-2 rounded-xl hover:bg-green-700 transition">
                    Download to Excel
                </button>
            </div>

            <!-- Time In Modal -->
            <dialog id="timeInModal" class="rounded-xl p-6 shadow-xl max-w-md w-full">
                <form method="POST" action="#" onsubmit="return false;">
                    <h3 class="text-xl font-semibold mb-4">Log Time In</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Date</label>
                        <input type="date" name="date" required value="{{ now()->format('Y-m-d') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Time In</label>
                        <input type="time" name="time_in" required class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="document.getElementById('timeInModal').close()" class="text-gray-500 px-4 py-2">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                    </div>
                </form>
            </dialog>

            <!-- Logs Table -->
            <div class="w-full overflow-x-auto max-h-96 border rounded-lg">
                <table class="min-w-[640px] w-full bg-white rounded-lg text-sm sm:text-base">
                    <thead class="bg-gray-100 text-gray-700 text-xs sm:text-sm whitespace-nowrap">
                        <tr>
                            <th class="py-3 px-4 text-left">Date</th>
                            <th class="py-3 px-4 text-left">Time In</th>
                            <th class="py-3 px-4 text-left">Time Out</th>
                            <th class="py-3 px-4 text-left">Hours</th>
                            <th class="py-3 px-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="logTable" class="text-sm">
                        <!-- Dynamic rows -->
                    </tbody>
                </table>
            </div>




            <!-- Total Hours Rendered, Needed, Remaining Section -->
<div class="mt-6 text-left space-y-4">
    <div class=" font-semibold text-gray-700 text-sm">
        Total Rendered Hours: <span id="totalHours">0</span> hrs
    </div>
    <div class="font-semibold text-gray-700 text-sm">
        Total Hours Needed: <span id="neededHours">0</span> hrs
    </div>
    <div class="font-semibold text-gray-700 text-sm">
        Remaining Hours: <span id="remainingHours">0</span> hrs
    </div>
    <button onclick="document.getElementById('editNeededModal').showModal()" class="mt-2 bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition">
        ✏️ Edit Total Hours Needed
    </button>
</div>

            <!-- Donate Section -->
<div class="bg-white rounded-2xl shadow-lg p-6 mt-8">
    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Support Developer 🙏</h3>
    <p class="text-gray-600 mb-4 text-center">If this tracker has been helpful, feel free to send a little love via GCash 💙</p>

    <div class="flex justify-center">
        <img
            src="{{ asset('images/gcash-qr.jpg') }}"
            alt="GCash QR Code"
            class="w-64 sm:w-72 md:w-80 lg:w-96 max-w-xs rounded-xl border"
        >
    </div>
    <p class="text-gray-600 mt-4 text-center">Developed by <a class="text-blue-600" href="https://ronald-portfolio-lumnaire.vercel.app/">Lumnaire</a> </p>
</div>
        </div>
        <!-- CARD END -->

        <!-- Edit Modal -->
<dialog id="editModal" class="rounded-xl p-6 shadow-xl max-w-md w-full">
    <form id="editForm" method="POST" action="#" onsubmit="return false;">
        <h3 class="text-xl font-semibold mb-4">Edit Time Log</h3>
        <input type="hidden" name="index">
        <div class="mb-4">
            <label class="block text-sm font-medium">Date</label>
            <input type="date" name="date" required class="mt-1 w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Time In</label>
            <input type="time" name="time_in" required class="mt-1 w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Time Out</label>
            <input type="time" name="time_out" class="mt-1 w-full border rounded px-3 py-2">
        </div>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('editModal').close()" class="text-gray-500 px-4 py-2">Cancel</button>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
        </div>
    </form>
</dialog>

<!-- Edit Needed Hours Modal -->
<dialog id="editNeededModal" class="rounded-xl p-6 shadow-xl max-w-md w-full">
    <form id="editNeededForm" method="POST" action="#" onsubmit="return false;">
        <h3 class="text-xl font-semibold mb-4">Set Total Hours Needed</h3>
        <div class="mb-4">
            <label class="block text-sm font-medium">Hours Needed</label>
            <input type="number" name="needed_hours" required min="0" class="mt-1 w-full border rounded px-3 py-2">
        </div>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('editNeededModal').close()" class="text-gray-500 px-4 py-2">Cancel</button>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save</button>
        </div>
    </form>
</dialog>
    </div>

    <script>
        const STORAGE_KEY = 'timelogs';

        function getLogs() {
            return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
        }

        function saveLogs(logs) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(logs));
        }

        function calculateHours(start, end) {
            const [sh, sm] = start.split(':').map(Number);
            const [eh, em] = end.split(':').map(Number);
            const diff = ((eh * 60 + em) - (sh * 60 + sm)) / 60;
            return diff > 0 ? diff.toFixed(2) : 0;
        }

        function renderLogs() {
            const logs = getLogs();
            const tbody = document.getElementById('logTable');
            const totalDisplay = document.getElementById('totalHours');
            tbody.innerHTML = '';
            let total = 0;

            logs.forEach((log, index) => {
                const row = document.createElement('tr');
                row.className = 'border-t';
                const hours = log.time_out ? calculateHours(log.time_in, log.time_out) : 0;
                total += parseFloat(hours);

                row.innerHTML = `
              <td class="py-2 px-2 sm:px-4 whitespace-nowrap">${log.date}</td>

                    <td class="py-2 px-4">${log.time_in}</td>
                    <td class="py-2 px-4">
                        ${log.time_out ? log.time_out : `
                            <form onsubmit="return false;" class="flex items-center space-x-2">
                                <input type="time" onchange="updateTimeOut(${index}, this.value)" class="border rounded px-2 py-1 text-sm">
                                <button class="text-sm text-blue-600 hover:underline">Save</button>
                            </form>
                        `}
                    </td>
                    <td class="py-2 px-4">${hours}</td>
                    <td class="py-2 px-4">
                        <button onclick="openEditModal(${index})" class="text-sm text-yellow-500 hover:underline mr-2">Edit</button>
                        <button onclick="deleteLog(${index})" class="text-sm text-red-500 hover:underline">Delete</button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            totalDisplay.textContent = total.toFixed(2);
        }

        function updateTimeOut(index, value) {
            const logs = getLogs();
            logs[index].time_out = value;
            saveLogs(logs);
            renderLogs();
        }

        function deleteLog(index) {
            const logs = getLogs();
            logs.splice(index, 1);
            saveLogs(logs);
            renderLogs();
        }

        function openEditModal(index) {
    const logs = getLogs();
    const log = logs[index];

    const form = document.getElementById('editForm');
    form.index.value = index;
    form.date.value = log.date;
    form.time_in.value = log.time_in;
    form.time_out.value = log.time_out || '';

    document.getElementById('editModal').showModal();
}

document.getElementById('editForm').addEventListener('submit', function () {
    const logs = getLogs();
    const index = parseInt(this.index.value);
    logs[index] = {
        date: this.date.value,
        time_in: this.time_in.value,
        time_out: this.time_out.value || null
    };
    saveLogs(logs);
    document.getElementById('editModal').close();
    renderLogs();
});

        document.addEventListener('DOMContentLoaded', () => {
            renderLogs();

            document.querySelector('#timeInModal form').addEventListener('submit', function () {
                const date = this.date.value;
                const timeIn = this.time_in.value;

                const logs = getLogs();
                logs.push({ date, time_in: timeIn });
                saveLogs(logs);

                this.reset();
                document.getElementById('timeInModal').close();
                renderLogs();
            });
        });

        const NEEDED_HOURS_KEY = 'neededHours';

function getNeededHours() {
    return parseFloat(localStorage.getItem(NEEDED_HOURS_KEY)) || 0;
}

function saveNeededHours(value) {
    localStorage.setItem(NEEDED_HOURS_KEY, value);
}

function renderNeededHours() {
    const neededDisplay = document.getElementById('neededHours');
    const remainingDisplay = document.getElementById('remainingHours');
    const totalHours = parseFloat(document.getElementById('totalHours').textContent) || 0;
    const neededHours = getNeededHours();

    neededDisplay.textContent = neededHours.toFixed(2);
    remainingDisplay.textContent = Math.max(neededHours - totalHours, 0).toFixed(2);
}

// Handle edit total hours needed form
document.getElementById('editNeededForm').addEventListener('submit', function () {
    const neededHours = parseFloat(this.needed_hours.value) || 0;
    saveNeededHours(neededHours);
    document.getElementById('editNeededModal').close();
    renderNeededHours();
});

// Update after logs render
function renderLogs() {
    const logs = getLogs();
    const tbody = document.getElementById('logTable');
    const totalDisplay = document.getElementById('totalHours');
    tbody.innerHTML = '';
    let total = 0;

    logs.forEach((log, index) => {
        const row = document.createElement('tr');
        row.className = 'border-t';
        const hours = log.time_out ? calculateHours(log.time_in, log.time_out) : 0;
        total += parseFloat(hours);

        row.innerHTML = `
            <td class="py-2 px-2 sm:px-4 whitespace-nowrap">${log.date}</td>
            <td class="py-2 px-4">${log.time_in}</td>
            <td class="py-2 px-4">
                ${log.time_out ? log.time_out : `
                    <form onsubmit="return false;" class="flex items-center space-x-2">
                        <input type="time" onchange="updateTimeOut(${index}, this.value)" class="border rounded px-2 py-1 text-sm">
                        <button class="text-sm text-blue-600 hover:underline">Save</button>
                    </form>
                `}
            </td>
            <td class="py-2 px-4">${hours}</td>
            <td class="py-2 px-4">
                <button onclick="openEditModal(${index})" class="text-sm text-yellow-500 hover:underline mr-2">Edit</button>
                <button onclick="deleteLog(${index})" class="text-sm text-red-500 hover:underline">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    totalDisplay.textContent = total.toFixed(2);
    renderNeededHours(); // <-- Important: Recalculate needed/remaining after rendering logs
}

document.addEventListener('DOMContentLoaded', () => {
    renderLogs();
});

function exportToCSV() {
    const logs = getLogs();
    if (logs.length === 0) {
        alert("No logs to export!");
        return;
    }

    let csv = 'Date,Time In,Time Out,Hours\n';
    logs.forEach(log => {
        const hours = log.time_out ? calculateHours(log.time_in, log.time_out) : 0;
        csv += `${log.date},${log.time_in},${log.time_out || ''},${hours}\n`;
    });

    // Create downloadable link
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'ojt_logs.csv';
    a.style.display = 'none';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

    </script>

</x-app-layout>
