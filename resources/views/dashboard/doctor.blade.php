@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h1 class="card-title mb-0 fs-3">Doctor Dashboard - Welcome, {{ auth()->user()->name }}</h1>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-4">
                            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
                        </div>

                        <h2 class="fs-4 mb-3">Set Availability</h2>
                        <form id="availabilityForm" class="mb-4">
                            <div id="slots" class="mb-3">
                                <div class="slot row g-3 mb-3">
                                    <div class="col-md-6">
                                        <input type="date" name="date[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="time" name="time_slot[]" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="button" id="addSlot" class="btn btn-secondary">Add Another Slot</button>
                                <button type="submit" class="btn btn-success">Set Availability</button>
                            </div>
                        </form>
                        <div id="availabilityMessage"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('addSlot').addEventListener('click', () => {
            const slotDiv = document.createElement('div');
            slotDiv.className = 'slot row g-3 mb-3';
            slotDiv.innerHTML = `
        <div class="col-md-6">
            <input type="date" name="date[]" class="form-control" required>
        </div>
        <div class="col-md-6">
            <input type="time" name="time_slot[]" class="form-control" required>
        </div>
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
                const token = localStorage.getItem('token') || '';
                const response = await axios.post('/api/v1/availability', {
                    availabilities
                }, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                document.getElementById('availabilityMessage').innerHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ${response.data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
            } catch (error) {
                document.getElementById('availabilityMessage').innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${error.response?.data?.message || 'Error setting availability'}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
            }
        });
    </script>
@endsection
