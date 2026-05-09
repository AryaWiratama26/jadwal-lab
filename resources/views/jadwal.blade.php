@extends('layouts.app')
@section('title', 'Jadwal Praktikum — ' . ($activeCourse->name ?? 'Lab Informatika'))

@section('content')

<div class="public-layout" style="max-width: 1120px; margin: 0 auto; padding: 0 1.5rem; display: flex; gap: 2.5rem;">

    {{-- LEFT SIDEBAR --}}
    <aside class="public-sidebar" style="width: 260px; flex-shrink: 0; display: flex; flex-direction: column; gap: 1.5rem;">
        
        {{-- Search --}}
        <div class="search-wrap">
            <i class="ph ph-magnifying-glass search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari jadwal, dosen, kelas..." onkeyup="filterSchedules()">
        </div>

        <div>
            <p class="text-label" style="margin-bottom: 10px; padding-left: 4px;">
                <i class="ph ph-books" style="margin-right: 2px;"></i> Mata Kuliah
            </p>

            <div style="display: flex; flex-direction: column; gap: 4px;">
                <a href="/" class="course-item {{ !$activeCourseId ? 'active' : '' }}">
                    <div class="course-icon" style="background: {{ !$activeCourseId ? 'var(--c-navy)' : 'var(--c-bg)' }}; color: {{ !$activeCourseId ? 'white' : 'var(--c-ink-3)' }};">
                        <i class="ph ph-list-bullets" style="font-size: 0.75rem;"></i>
                    </div>
                    <div style="min-width: 0;">
                        <p style="font-weight: 600; font-size: 0.8125rem; margin-bottom: 1px;">Semua</p>
                    </div>
                </a>

                @foreach($courses as $course)
                    <a href="?course={{ $course->id }}" class="course-item {{ $activeCourseId == $course->id ? 'active' : '' }}">
                        <div class="course-icon" style="background: {{ $activeCourseId == $course->id ? ($course->color ?? 'var(--c-navy)') : 'var(--c-bg)' }}; color: {{ $activeCourseId == $course->id ? 'white' : 'var(--c-ink-3)' }};">
                            {{ substr($course->code ?? $course->name, 0, 2) }}
                        </div>
                        <div style="min-width: 0;">
                            <p style="font-weight: 600; font-size: 0.8125rem; margin-bottom: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $course->name }}</p>
                            @if($course->semester)
                                <span style="font-size: 0.6875rem; color: var(--c-ink-3);">{{ $course->semester }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach

                @if($courses->isEmpty())
                    <p style="font-size: 0.8125rem; color: var(--c-ink-3); padding: 1rem; text-align: center;">
                        <i class="ph ph-warning" style="display: block; font-size: 1.25rem; margin-bottom: 4px;"></i>
                        Belum ada mata kuliah.
                    </p>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        @if($schedules->isNotEmpty())
        @php
            $rooms = $schedules->pluck('ruangan')->unique()->filter();
            $asstCount = $schedules->pluck('assistants')->flatten()->unique('id')->count();
        @endphp
        <div style="background: var(--c-surface); border: 1px solid var(--c-border); border-radius: var(--radius-lg); padding: 1.25rem;">
            <p class="text-label" style="margin-bottom: 12px;">
                <i class="ph ph-chart-bar" style="margin-right: 2px;"></i> Ringkasan
            </p>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; text-align: center;">
                <div>
                    <p style="font-size: 1.25rem; font-weight: 700; color: var(--c-navy);">{{ $schedules->count() }}</p>
                    <p style="font-size: 0.6875rem; color: var(--c-ink-3);">Kelas</p>
                </div>
                <div>
                    <p style="font-size: 1.25rem; font-weight: 700; color: var(--c-navy);">{{ $asstCount }}</p>
                    <p style="font-size: 0.6875rem; color: var(--c-ink-3);">Asisten</p>
                </div>
                <div>
                    <p style="font-size: 1.25rem; font-weight: 700; color: var(--c-navy);">{{ $rooms->count() }}</p>
                    <p style="font-size: 0.6875rem; color: var(--c-ink-3);">Ruangan</p>
                </div>
            </div>
        </div>
        @endif
        
    </aside>

    {{-- MAIN CONTENT --}}
    <div style="flex: 1; min-width: 0;">
        
        <div class="animate-fade-up" style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap;">
            <div>
                <h1 class="text-heading">{{ $activeCourse->name ?? 'Semua Jadwal' }}</h1>
                @if($activeCourse && $activeCourse->semester)
                    <p style="font-size: 0.8125rem; color: var(--c-ink-3); margin-top: 2px;">
                        <i class="ph ph-calendar-blank" style="margin-right: 2px;"></i> {{ $activeCourse->semester }}
                    </p>
                @endif
            </div>
            <span class="badge badge-navy" style="padding: 5px 14px; font-size: 0.75rem;">
                <i class="ph ph-list-bullets" style="margin-right: 3px;"></i>
                {{ $schedules->count() }} Jadwal
            </span>
        </div>

        {{-- No results message --}}
        <div id="noResults" style="display: none; background: var(--c-surface); border: 1px solid var(--c-border); border-radius: var(--radius-lg); text-align: center; padding: 3rem 2rem;">
            <i class="ph ph-magnifying-glass" style="font-size: 2rem; color: var(--c-ink-3); margin-bottom: 8px; display: block;"></i>
            <p style="font-size: 0.875rem; font-weight: 600; color: var(--c-ink-2);">Tidak ditemukan</p>
            <p style="font-size: 0.8125rem; color: var(--c-ink-3); margin-top: 4px;">Coba kata kunci lain.</p>
        </div>

        {{-- Group by day --}}
        @php $grouped = $schedules->groupBy('hari'); @endphp

        <div id="scheduleList" style="display: flex; flex-direction: column; gap: 1.5rem;">
            @forelse($grouped as $hari => $daySchedules)
                <div class="day-group">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--c-navy); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.6875rem; font-weight: 700;">
                            {{ substr($hari, 0, 2) }}
                        </div>
                        <div>
                            <p style="font-weight: 700; font-size: 0.9375rem; color: var(--c-ink);">{{ $hari }}</p>
                            <p style="font-size: 0.6875rem; color: var(--c-ink-3);">{{ $daySchedules->count() }} kelas</p>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 6px; padding-left: 42px;">
                        @foreach($daySchedules as $schedule)
                            <div class="schedule-entry" data-search="{{ strtolower($schedule->kelas . ' ' . $schedule->program . ' ' . $schedule->dosen . ' ' . $schedule->ruangan . ' ' . $schedule->course->name . ' ' . $schedule->assistants->pluck('name')->join(' ')) }}">
                                {{-- Time --}}
                                <div style="min-width: 80px; flex-shrink: 0;">
                                    <p style="font-size: 0.8125rem; font-weight: 700; color: var(--c-navy);">{{ $schedule->waktu_mulai }}</p>
                                    <p style="font-size: 0.6875rem; color: var(--c-ink-3);">{{ $schedule->waktu_selesai }}</p>
                                </div>

                                <div class="hide-mobile" style="width: 1px; background: var(--c-border); align-self: stretch;"></div>

                                {{-- Info --}}
                                <div style="flex: 1; min-width: 0;">
                                    <p style="font-weight: 600; font-size: 0.875rem; color: var(--c-ink);">
                                        {{ $schedule->kelas }}
                                        @if(!$activeCourseId)
                                            <span class="badge" style="background: {{ $schedule->course->color ?? 'var(--c-navy)' }}; color: white; padding: 1px 8px; font-size: 0.625rem; margin-left: 4px; vertical-align: middle;">{{ $schedule->course->code ?? $schedule->course->name }}</span>
                                        @endif
                                    </p>
                                    <div style="display: flex; align-items: center; gap: 12px; margin-top: 4px; flex-wrap: wrap;">
                                        <span style="font-size: 0.75rem; color: var(--c-ink-3); display: inline-flex; align-items: center; gap: 3px;">
                                            <i class="ph ph-chalkboard-teacher"></i> {{ $schedule->dosen }}
                                        </span>
                                        @if($schedule->ruangan)
                                            <span style="font-size: 0.75rem; color: var(--c-ink-3); display: inline-flex; align-items: center; gap: 3px;">
                                                <i class="ph ph-map-pin"></i> {{ $schedule->ruangan }}
                                            </span>
                                        @endif
                                        <span style="font-size: 0.75rem; color: var(--c-ink-3); display: inline-flex; align-items: center; gap: 3px;">
                                            <i class="ph ph-graduation-cap"></i> {{ $schedule->program }}
                                        </span>
                                    </div>

                                    {{-- Asisten List (FULL NAMES) --}}
                                    @if($schedule->assistants->isNotEmpty())
                                        <div style="display: flex; align-items: center; gap: 6px; margin-top: 8px; flex-wrap: wrap;">
                                            <i class="ph ph-users" style="font-size: 0.75rem; color: var(--c-ink-3);"></i>
                                            @foreach($schedule->assistants as $asst)
                                                <span style="font-size: 0.6875rem; background: var(--c-accent-t); color: var(--c-navy); padding: 2px 8px; border-radius: 99px; font-weight: 500;">
                                                    {{ $asst->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div style="background: var(--c-surface); border: 1px solid var(--c-border); border-radius: var(--radius-lg); text-align: center; padding: 4rem 2rem;">
                    <i class="ph ph-calendar-x" style="font-size: 2.5rem; color: var(--c-ink-3); margin-bottom: 12px; display: block;"></i>
                    <p style="font-size: 0.9375rem; font-weight: 600; color: var(--c-ink-2);">Belum ada jadwal</p>
                    <p style="font-size: 0.8125rem; color: var(--c-ink-3); margin-top: 4px;">Jadwal untuk mata kuliah ini belum tersedia.</p>
                </div>
            @endforelse
        </div>

    </div>

</div>

<script>
function filterSchedules() {
    const query = document.getElementById('searchInput').value.toLowerCase().trim();
    const entries = document.querySelectorAll('.schedule-entry');
    const dayGroups = document.querySelectorAll('.day-group');
    const noResults = document.getElementById('noResults');
    let totalVisible = 0;

    entries.forEach(entry => {
        const text = entry.getAttribute('data-search') || '';
        const match = !query || text.includes(query);
        entry.style.display = match ? '' : 'none';
        if (match) totalVisible++;
    });

    // Hide day groups with no visible entries
    dayGroups.forEach(group => {
        const visibleEntries = group.querySelectorAll('.schedule-entry:not([style*="display: none"])');
        group.style.display = visibleEntries.length > 0 ? '' : 'none';
    });

    noResults.style.display = totalVisible === 0 && query ? '' : 'none';
}
</script>
@endsection
