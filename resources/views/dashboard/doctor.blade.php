@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Doctor Dashboard - Welcome, {{ auth()->user()->name }}</h1>
        <a href="{{ route('logout') }}" class="text-red-500 mb-4 inline-block">Logout</a>

        <h2 class="text-xl mb-2">Set Availability</h2>
        <form id="availabilityForm" class="mb-6">
            <div id="slots">
                <div class="slot mb-4">
                    <input type="date" name="date[]" class="p-2 border" required>
                    <input type="time" name="time_slot[]" class="p-2 border" required>
                </div>
            </div>
            <button type="button" id="addSlot" class="bg-gray-300 p-2 rounded">Add Another Slot</button>
            <button type="submit" class="bg-green-500 text-white p-2 rounded">Set Availability</button>
        </form>
        <div id="availabilityMessage"></div>
    </div>

    <script>
        document.getElementById('addSlot').addEventListener('click', () => {
            const slotDiv = document.createElement('div');
            slotDiv.className = 'slot mb-4';
            slotDiv.innerHTML = `
        <input type="date" name="date[]" class="p-2 border" required>
        <input type="time" name="time_slot[]" class="p-2 border" required>
    `;
            document.getElementById('slots').appendChild(slotDiv);
        });

        document.getElementById('availabilityForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const dates = document.getElementsByName('date[]');
            const times = document.getElementsByName('time_slot[]');
            const availabilities = Array.from(dates).map((date, i) => ({
                date: date.value,
                time_slot: times[i].value
            }));

            try {
                const token = localStorage.getItem('token') || ''; // Set token after login
                const response = await axios.post('/api/v1/availability', {
                    availabilities
                }, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                document.getElementById('availabilityMessage').innerHTML =
                    `<p class="text-green-500">${response.data.message}</p>`;
            } catch (error) {
                document.getElementById('availabilityMessage').innerHTML =
                    `<p class="text-red-500">${error.response?.data?.message || 'Error setting availability'}</p>`;
            }
        });
    </script>
@endsection
