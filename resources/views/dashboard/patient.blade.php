@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h1 class="card-title mb-0 fs-3">Patient Dashboard - Welcome, {{ auth()->user()->name }}</h1>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-4">
                            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
                        </div>

                        <h2 class="fs-4 mb-3">Book Appointment</h2>
                        <form id="doctorSelectForm" class="mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <select id="doctorId" name="doctor_id" class="form-select" required>
                                        <option value="">Select a Doctor</option>
                                        <option value="1">Dr. Alice Smith</option>
                                        <option value="2">Dr. Bob Johnson</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary w-100">View Availability</button>
                                </div>
                            </div>
                        </form>

                        <div id="availabilityList" class="mb-4"></div>
                        <div id="bookingMessage"></div>

                        <h2 class="fs-4 mb-3">Upcoming Appointments</h2>
                        <div id="appointmentsList" class="card p-3 bg-light"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function loadAppointments() {
            const token = localStorage.getItem('token') || '';
            try {
                const response = await axios.get(`/api/v1/appointments/{{ auth()->id() }}`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                const appointments = response.data.appointments;
                document.getElementById('appointmentsList').innerHTML = appointments.length ?
                    `<ul class="list-group">${appointments.map(a => `
                        <li class="list-group-item">${a.date} ${a.time_slot} with ${a.doctor.name}</li>
                    `).join('')}</ul>` :
                    '<p class="text-muted">No upcoming appointments</p>';
            } catch (error) {
                document.getElementById('appointmentsList').innerHTML = `
            <div class="alert alert-danger" role="alert">
                Error loading appointments
            </div>`;
            }
        }

        document.getElementById('doctorSelectForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const doctorId = document.getElementById('doctorId').value;
            try {
                const response = await axios.get(`/api/v1/doctors/${doctorId}/availability`);
                const availabilities = response.data.availabilities;
                document.getElementById('availabilityList').innerHTML = availabilities.length ?
                    `<div class="list-group">${availabilities.map(a => `
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            ${a.date} ${a.time_slot}
                            <button class="bookBtn btn btn-success btn-sm" data-id="${a.id}" data-date="${a.date}" data-time="${a.time_slot}">Book</button>
                        </div>`).join('')}</div>` :
                    '<p class="text-muted">No available slots</p>';

                document.querySelectorAll('.bookBtn').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const token = localStorage.getItem('token') || '';
                        try {
                            const response = await axios.post('/api/v1/appointments/book', {
                                doctor_id: doctorId,
                                date: btn.dataset.date,
                                time_slot: btn.dataset.time
                            }, {
                                headers: {
                                    Authorization: `Bearer ${token}`
                                }
                            });
                            document.getElementById('bookingMessage').innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${response.data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                            loadAppointments(); // Refresh appointments
                        } catch (error) {
                            document.getElementById('bookingMessage').innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${error.response?.data?.message || 'Error booking appointment'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                        }
                    });
                });
            } catch (error) {
                document.getElementById('availabilityList').innerHTML = `
            <div class="alert alert-danger" role="alert">
                Error loading availability
            </div>`;
            }
        });

        loadAppointments(); // Load appointments on page load
    </script>
@endsection
