@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-12 px-6">
        {{-- Program Header --}}
        <div class="mb-10">
            <div class="flex justify-between items-start flex-wrap gap-3 flex-col">
                <div>
                    <h1 class="text-3xl font-bold text-blue-700">{{ $program->title }}</h1>
                    <p class="text-gray-500 mt-1 flex items-center gap-2">
                        Program Code:
                        <span id="programCode" class="font-medium">{{ $program->code ?? 'N/A' }}</span>

                        @if ($program->code)
                            <button id="copyProgramCodeBtn" class="text-gray-500 hover:text-blue-600" title="Copy Code">
                                <i class="fas fa-copy"></i>
                            </button>
                        @endif
                    </p>
                </div>

                <!-- Start / Continue Button -->
                @if ($program->agenda->isNotEmpty())
                    <div class="w-full mt-4">
                        @if ($program->ended_at)
                            {{-- Program Ended Label --}}
                            <div
                                class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 bg-gray-100 text-gray-600 font-semibold rounded-lg shadow-sm">
                                <i class="fas fa-flag-checkered text-gray-500"></i>
                                Program Ended
                            </div>
                        @elseif ($program->started_at)
                            {{-- Continue Program --}}
                            <a href="{{ route('programs.run', $program) }}"
                                class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 bg-green-100 hover:bg-green-200 text-green-700 font-semibold rounded-lg shadow-sm transition">
                                <i class="fas fa-clock"></i>
                                Continue Program
                            </a>
                        @else
                            {{-- Start Program --}}
                            <form method="POST" action="{{ route('programs.start', $program) }}" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 bg-green-100 hover:bg-green-200 text-green-700 font-semibold rounded-lg shadow-sm transition">
                                    <i class="fas fa-clock"></i>
                                    Start Program
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

        </div>


        {{-- Agenda List --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Agenda</h2>

            @if (session('success'))
                <div class="mb-4 bg-green-50 text-green-800 border border-green-100 rounded-lg p-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($program->agenda->isEmpty())
                <p class="text-gray-500 italic">No agenda added yet.</p>
            @else
                <ul id="agendaList" class="divide-y divide-gray-100">
                    @foreach ($program->agenda()->orderBy('order')->get() as $index => $agendum)
                        <li class="agenda-item py-4 flex items-start justify-between hover:bg-blue-50 transition rounded-lg cursor-pointer"
                            data-id="{{ $agendum->id }}" data-title="{{ e($agendum->title) }}"
                            data-description="{{ e($agendum->description) }}" data-duration="{{ $agendum->duration }}"
                            data-order="{{ $agendum->order }}">
                            <div class="flex items-start gap-4 flex-1 pr-6">
                                {{-- Number Circle --}}
                                <span
                                    class="flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white text-sm font-semibold shrink-0">
                                    {{ $index + 1 }}
                                </span>

                                {{-- Text --}}
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 leading-tight">{{ $agendum->title }}</p>
                                    @if ($agendum->description)
                                        <p class="text-[12px] text-gray-500 mt-1 leading-snug">
                                            {{ $agendum->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Duration --}}
                            <span class="text-sm font-medium text-blue-600 whitespace-nowrap">
                                {{ $agendum->duration }} min
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if ($program->agenda->isNotEmpty())
                @php
                    $totalMinutes = $program->agenda->sum('duration');

                    $months = floor($totalMinutes / (60 * 24 * 30));
                    $remainingMinutes = $totalMinutes % (60 * 24 * 30);

                    $days = floor($remainingMinutes / (60 * 24));
                    $remainingMinutes %= 60 * 24;

                    $hours = floor($remainingMinutes / 60);
                    $minutes = $remainingMinutes % 60;

                    $parts = [];
                    if ($months > 0) {
                        $parts[] = "$months " . Str::plural('month', $months);
                    }
                    if ($days > 0) {
                        $parts[] = "$days " . Str::plural('day', $days);
                    }
                    if ($hours > 0) {
                        $parts[] = "$hours " . Str::plural('hour', $hours);
                    }
                    if ($minutes > 0) {
                        $parts[] = "$minutes " . Str::plural('minute', $minutes);
                    }

                    $formattedDuration = implode(', ', $parts);
                @endphp

                <div class="mt-6 text-center text-gray-600 text-sm">
                    <span class="font-semibold text-gray-800">Total Duration:</span>
                    {{ $formattedDuration ?: '—' }}
                </div>
            @endif


            {{-- Add Agendum Button --}}
            @if (!$program->hasEnded())
                <div class="mt-6 text-center">
                    <button id="openAddModalBtn"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        + Add Agendum
                    </button>
                </div>
            @endif


            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:underline">← Back to Home</a>
            </div>
        </div>
    </div>

    {{-- Add Agendum Modal --}}
    <div id="addAgendumModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6 relative">
            <button id="closeAddModal"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Add New Agendum</h2>

            <form action="{{ route('agenda.store', $program) }}" method="POST" id="addAgendumForm">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" required
                        class="border border-gray-300 rounded-lg w-full px-4 py-2 mt-1 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3"
                        class="border border-gray-300 rounded-lg w-full px-4 py-2 mt-1 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                        <input type="number" name="duration" min="1" value="5"
                            class="border border-gray-300 rounded-lg w-full px-4 py-2 mt-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order</label>
                        <input type="number" name="order" min="1"
                            value="{{ $program->agenda->max('order') + 1 }}"
                            class="border border-gray-300 rounded-lg w-full px-4 py-2 mt-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" id="cancelAdd"
                        class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Agendum Modal --}}
    <div id="editAgendumModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6 relative">
            {{-- Delete icon at top-right (form below handles delete) --}}
            <form id="deleteAgendumForm" method="POST" style="position:absolute; right:1rem; top:0.75rem;">
                @csrf
                @method('DELETE')
                {{-- action will be set by JS --}}
                <button id="deleteAgendumBtn" title="Delete Agendum" type="button"
                    class="text-red-600 hover:text-red-800 p-2 rounded-md" data-id="{{ $agendum->id ?? '' }}"
                    data-program="{{ $program->code ?? '' }}">
                    <i class="fa-solid fa-trash text-lg"></i>
                </button>


            </form>

            <button id="closeEditModal"
                class="absolute top-4 right-12 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Edit Agendum</h2>

            <form id="editAgendumForm" method="POST">
                @csrf
                @method('PUT')
                {{-- action will be set by JS --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" required id="editTitle"
                        class="border border-gray-300 rounded-lg w-full px-4 py-2 mt-1 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="editDescription" rows="3"
                        class="border border-gray-300 rounded-lg w-full px-4 py-2 mt-1 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                        <input type="number" name="duration" min="1" id="editDuration" value="0"
                            class="border border-gray-300 rounded-lg w-full px-4 py-2 mt-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order</label>
                        <input type="number" name="order" min="1" id="editOrder" value="1"
                            class="border border-gray-300 rounded-lg w-full px-4 py-2 mt-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" id="cancelEdit"
                        class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium">Save
                        changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal JS --}}
    <script>
        const decodeHtml = (html) => {
            const txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        };

        (function() {
            const addModal = document.getElementById('addAgendumModal');
            const openAddBtn = document.getElementById('openAddModalBtn');
            const closeAdd = document.getElementById('closeAddModal');
            const cancelAdd = document.getElementById('cancelAdd');

            const editModal = document.getElementById('editAgendumModal');
            const openEditModalButtons = document.querySelectorAll('.agenda-item'); // each li
            const eventEnded = {{ (int) $program->hasEnded() }};

            const closeEdit = document.getElementById('closeEditModal');
            const cancelEdit = document.getElementById('cancelEdit');

            const editForm = document.getElementById('editAgendumForm');
            const deleteForm = document.getElementById('deleteAgendumForm');

            // open add
            openAddBtn?.addEventListener('click', () => {
                addModal.classList.remove('hidden');
                addModal.classList.add('flex');
            });

            closeAdd.addEventListener('click', () => {
                addModal.classList.remove('flex');
                addModal.classList.add('hidden');
            });

            cancelAdd.addEventListener('click', () => {
                addModal.classList.remove('flex');
                addModal.classList.add('hidden');
            });

            addModal.addEventListener('click', (e) => {
                if (e.target === addModal) {
                    addModal.classList.remove('flex');
                    addModal.classList.add('hidden');
                }
            });

            if (!eventEnded) {
                // open edit when clicking an agenda item
                openEditModalButtons.forEach(item => {
                    item.addEventListener('click', (e) => {
                        // read data attributes
                        const id = item.getAttribute('data-id');
                        const title = decodeHtml(item.getAttribute('data-title') || '');
                        const description = decodeHtml(item.getAttribute('data-description') || '');
                        const duration = item.getAttribute('data-duration') || 0;
                        const order = item.getAttribute('data-order') || 1;
                        const deleteBtn = document.getElementById('deleteAgendumBtn');

                        // populate form fields
                        document.getElementById('editTitle').value = title;
                        document.getElementById('editDescription').value = description;
                        document.getElementById('editDuration').value = duration;
                        document.getElementById('editOrder').value = order;

                        // set action URLs for edit & delete (use route pattern)
                        // Assuming routes: agenda.update -> /programs/{program}/agenda/{agendum}
                        const programCode = '{{ $program->code }}';
                        const editAction = `/programs/${programCode}/agenda/${id}`;
                        editForm.setAttribute('action', editAction);

                        // delete form action
                        deleteForm.setAttribute('action', editAction);
                        deleteBtn.setAttribute('data-id', id);
                        deleteBtn.setAttribute('data-program', programCode);

                        // show modal
                        editModal.classList.remove('hidden');
                        editModal.classList.add('flex');
                    });
                });
            }


            // close edit modal handlers
            closeEdit.addEventListener('click', () => {
                editModal.classList.remove('flex');
                editModal.classList.add('hidden');
            });
            cancelEdit.addEventListener('click', () => {
                editModal.classList.remove('flex');
                editModal.classList.add('hidden');
            });
            editModal.addEventListener('click', (e) => {
                if (e.target === editModal) {
                    editModal.classList.remove('flex');
                    editModal.classList.add('hidden');
                }
            });

        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteBtn = document.getElementById('deleteAgendumBtn');

            if (deleteBtn) {
                deleteBtn.addEventListener('click', async () => {
                    const id = deleteBtn.getAttribute('data-id');
                    const code = deleteBtn.getAttribute('data-program');
                    if (!id || !code) return;

                    const result = await Swal.fire({
                        title: 'Delete Agendum?',
                        text: 'This action cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it',
                        cancelButtonText: 'Cancel'
                    });

                    if (!result.isConfirmed) return;

                    try {
                        const response = await fetch(`/programs/${code}/agenda/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            await Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Agendum has been deleted.',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            window.location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: 'Failed to delete agendum. Please try again.'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                });
            }

            const copyBtn = document.getElementById('copyProgramCodeBtn');
            const codeEl = document.getElementById('programCode');

            if (copyBtn && codeEl) {
                copyBtn.addEventListener('click', async () => {
                    try {
                        await navigator.clipboard.writeText(codeEl.textContent.trim());
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Copied to clipboard!',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                        });
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed!',
                            text: 'Unable to copy to clipboard.'
                        });
                    }
                });
            }
        });
    </script>


@endsection
