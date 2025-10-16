@extends('admin.layout')

@section('content')

<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold text-primary display-6">
        🧠 Question & Answer List
    </h1>

    @foreach ($sections as $section)
        <div class="card mb-5 shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-white">{{ $section->title }}</h4>
                <small class="text-light fst-italic">{{ $section->description }}</small>
            </div>
            <div class="card-body">
                @foreach ($section->questions as $question)
                    <div class="mb-4 p-3 border-start border-4  border-primary bg-light rounded">
                        <h6 class="fw-semibold">
                            <span class="badge bg-secondary me-2">Q{{ $loop->iteration }}</span>
                            {{ $question->text }}
                        </h6>
                        <ul class="list-group mt-3">
                            @foreach ($question->answers as $answer)
                                <li class="list-group-item d-flex justify-content-between align-items-center
                                    @if($answer->is_correct) list-group-item-success border-success @endif">
                                    <div>
                                        <strong>{{ $answer->label }}.</strong> {{ $answer->text }}
                                    </div>
                                    @if($answer->is_correct)
                                        <span class="badge bg-success">✔ Correct</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

{{-- Optional custom enhancements --}}
<style>
    .card-header {
        padding: 1rem 1.5rem;
    }

    .list-group-item {
        transition: background-color 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f9f9f9;
    }

    h6 {
        font-size: 1rem;
    }
</style>
@endsection
