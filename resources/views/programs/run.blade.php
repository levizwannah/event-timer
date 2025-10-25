@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-10 px-3 text-center">
        <div class="mb-3">
            <h1 class="text-3xl font-bold text-blue-700 mb-6">{{ $program->title }}</h1>
            <p class="text-gray-500 mt-1 flex items-center gap-2 justify-center">
                Program Code:
                <span id="programCode" class="font-medium">{{ $program->code ?? 'N/A' }}</span>

                @if ($program->code)
                    <button id="copyProgramCodeBtn" class="text-gray-500 hover:text-blue-600" title="Copy Code">
                        <i class="fas fa-copy"></i>
                    </button>
                @endif
            </p>
        </div>


        @if ($currentAgendum)
            <div id="agendum-container" class="shadow-md rounded-2xl p-4 mb-4 transition-colors duration-500 bg-green-50">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ $currentAgendum->title }}</h2>
                <p class="text-gray-500 mb-6">{{ $currentAgendum->description }}</p>

                <!-- Countdown Timer -->
                <div id="timerDisplay"
                    class="font-digital font-mono tracking-widest text-5xl sm:text-6xl md:text-7xl lg:text-8xl leading-none text-green-700 drop-shadow-md">
                    {{ $currentAgendum->duration > 9 ? $currentAgendum->duration : '0' . $currentAgendum->duration }}:00
                </div>

                <p class="text-sm text-gray-500 mt-2">Duration: {{ $currentAgendum->duration }} min</p>

                <!-- Navigation Buttons -->
                <div class="flex justify-between items-center mt-8">
                    {{-- Previous --}}
                    @if ($prevAgendum)
                        <a href="{{ route('programs.run', ['program' => $program, 'agendum' => $prevAgendum]) }}"
                            class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-600 px-4 py-2 rounded-md transition shadow-sm">
                            <i class="fas fa-arrow-left text-sm"></i>
                            <span class="text-sm font-medium">Prev</span>
                        </a>
                    @else
                        <div></div>
                    @endif

                    {{-- Start Button (Only show if not started) --}}
                    @if (is_null($currentAgendum->started_at))
                        <form method="POST"
                            action="{{ route('programs.agenda.start', ['program' => $program, 'agendum' => $currentAgendum]) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-green-100 border border-green-200 hover:bg-green-200 text-green-700 px-4 py-2 rounded-md transition shadow-sm font-medium">
                                <i class="fas fa-play text-sm"></i>
                                Start
                            </button>
                        </form>
                    @endif

                    {{-- Next --}}
                    @if ($nextAgendum)
                        <a href="{{ route('programs.run', ['program' => $program, 'agendum' => $nextAgendum]) }}"
                            class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 hover:bg-blue-100 text-blue-600 px-4 py-2 rounded-md transition shadow-sm">
                            <span class="text-sm font-medium">Next</span>
                            <i class="fas fa-arrow-right text-sm"></i>
                        </a>
                    @endif
                </div>

            </div>

            @if (!$program->hasEnded())
                <form method="POST" action="{{ route('programs.end', $program) }}">
                    @csrf
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 border text-red-500 font-semibold rounded-lg shadow-sm hover:bg-gray-200 transition">
                        <i class="fas fa-flag-checkered"></i> End Program
                    </button>
                </form>
            @endif
        @else
            <p class="text-gray-500 italic">No agenda available for this program.</p>
        @endif
    </div>

    <!-- Timer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

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

            const durationMinutes = {{ $currentAgendum->duration ?? 0 }};
            const agendumId = {{ $currentAgendum->id ?? 'null' }};
            const startedAt = @json($currentAgendum->started_at);
            const timerDisplay = document.getElementById('timerDisplay');
            const card = document.getElementById('agendum-container');

            if (!agendumId || !timerDisplay || !card) return;
            if (!startedAt) return; // Don’t start counting if agenda not started

            const startedAtTime = new Date(startedAt).getTime();
            const durationMs = durationMinutes * 60 * 1000;
            const endTime = startedAtTime + durationMs;

            // Allowance before showing red
            let allowanceMs = 0;
            if (durationMinutes >= 5) {
                allowanceMs = 2 * 60 * 1000; // 2 minutes
            } else if (durationMinutes >= 10) {
                allowanceMs = 3 * 60 * 1000; // 3 minutes
            } else if (durationMinutes >= 20) {
                allowanceMs = 5 * 60 * 1000; // 5 minutes
            } else if (durationMinutes >= 30) {
                allowanceMs = 10 * 60 * 1000; // 5 minutes
            }

            const updateTimer = () => {
                const now = Date.now();
                const diff = endTime - now; // remaining time
                const absDiff = Math.abs(diff);
                const minutes = Math.floor(absDiff / 60000);
                const seconds = Math.floor((absDiff % 60000) / 1000);

                // Format time as mm:ss
                const formatted = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                timerDisplay.textContent = diff >= 0 ? formatted : `-${formatted}`;

                // Reset color classes
                card.classList.remove('bg-green-50', 'bg-blue-50', 'bg-red-50');
                timerDisplay.classList.remove('text-green-700', 'text-blue-700', 'text-red-700');

                // Apply colors based on remaining time
                if (diff > 5 * 60 * 1000) {
                    // More than 5 min left
                    card.classList.add('bg-green-50');
                    timerDisplay.classList.add('text-green-700');
                } else if (diff > 0) {
                    // Less than 5 min left but still ongoing
                    card.classList.add('bg-blue-50');
                    timerDisplay.classList.add('text-blue-700');
                } else if (diff > -allowanceMs) {
                    // Within the allowance period — stay blue (soft warning)
                    card.classList.add('bg-blue-50');
                    timerDisplay.classList.add('text-blue-700');
                } else {
                    // Beyond allowance — danger
                    card.classList.add('bg-red-50');
                    timerDisplay.classList.add('text-red-700');
                }
            };

            updateTimer();
            setInterval(updateTimer, 1000);

            
        });
    </script>



@endsection
