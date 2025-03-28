@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Patient Dashboard - Welcome, {{ auth()->user()->name }}</h1>
    <a href="{{ route('logout') }}" class="text-red-500 mb-4 inline-block">Logout</a>

    <h2 class="text-xl mb-2">Book Appointment</h2>
    <form id="doctorSelectForm" class="mb-6">
        <select id="doctorId" name="doctor_id" class="p-2 border" required>
            <option value="">Select a Doctor</option>
            <!-- Populate dynamically or hardcode for demo -->
            <option value="1">Dr. Alice Smith</option>
            <option value="2">Dr. Bob Johnson</option>
        </select>
        <button type="submit" class="bg-blue-500 text-white p-2 rounded">View Availability</button>
    </form>

    <div id="availabilityList" class="mb-6"></div>
    <div id="bookingMessage"></div>

    <h2 class="text-xl mb-2">Upcoming Appointments</h2>
    <div id="appointmentsList"></div>
</div>

<script>
async function loadAppointments() {
    const token = localStorage.getItem('token') || '';
    try {
        const response = await axios.get(`/api/v1/appointments/${{{ auth()->id() }}}`, {
            headers: { Authorization: `Bearer ${token}` }
        });
        const appointments = response.data.appointments;
        document.getElementById('appointmentsList').innerHTML = appointments.length
            ? appointments.map(a => `<p>${a.date} ${a.time_slot} with ${a.doctor.name}</p>`).join('')
            : '<p>No upcoming appointments</p>';
    } catch (error) {
        document.getElementById('appointmentsList').innerHTML = '<p class="text-red-500">Error loading appointments</p>';
    }
}

document.getElementById('doctorSelectForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const doctorId = document.getElementById('doctorId').value;
    try {
        const response = await axios.get(`/api/v1/doctors/${doctorId}/availability`);
        const availabilities = response.data.availabilities;
        document.getElementById('availabilityList').innerHTML = availabilities.length
            ? availabilities.map(a => `
                <div class="mb-2">
                    ${a.date} ${a.time_slot}
                    <button class="bookBtn bg-green-500 text-white p-1 rounded" data-id="${a.id}" data-date="${a.date}" data-time="${a.time_slot}">Book</button>
                </div>`).join('')
            : '<p>No available slots</p>';

        document.querySelectorAll('.bookBtn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const token = localStorage.getItem('token') || '';
                try {
                    const response = await axios.post('/api/v1/appointments/book', {
                        doctor_id: doctorId,
                        date: btn.dataset.date,
                        time_slot: btn.dataset.time
                    }, { headers: { Authorization: `Bearer ${token}` } });
                    document.getElementById('bookingMessage').innerHTML = `<p class="text-green-500">${response.data.message}</p>`;
                    loadAppointments(); // Refresh appointments
                } catch (error) {
                    document.getElementById('bookingMessage').innerHTML = `<p class="text-red-500">${error.response?.data?.message || 'Error booking'}</p>`;
                }
            });
        });
    } catch (error) {
        document.getElementById('availabilityList').innerHTML = '<p class="text-red-500">Error loading availability</p>';
    }
});

loadAppointments(); // Load appointments on page load
</script>
@endsection