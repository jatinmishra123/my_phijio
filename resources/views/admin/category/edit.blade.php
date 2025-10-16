<form action="{{ url('admin/category/update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="rowid" value="{{ $item->id }}">

    <div class="mb-3">
        <label class="form-label">Category Name</label>
        <input type="text" class="form-control" name="category_name" value="{{ $item->category_name ?? '' }}">
        <div class="error text-danger" id="category_name_error2"></div>
    </div>

    <div class="mb-3">
        <label class="form-label">Icon</label>
        <input type="file" class="form-control" name="icon">
        @if(!empty($item->icon))
        <img src="{{ asset('uploads/category/'.$item->icon) }}" style="width: 120px; margin-top: 10px;">
        @endif
    </div>

    <div class="mb-3">
        <label class="text-label form-label" for="validationCustomUsername"> Cover Image</label>
        <div class="input-group">
            <input type="file" class="form-control" id="coverimage" name="coverimage">
        </div>
        <div id="coverimage_error" class="text-danger error"> </div>
        @if(!empty($item->coverimage))
        <img src="{{ asset('uploads/category/'.$item->coverimage) }}" style="width: 120px; margin-top: 10px;">
        @endif

    </div>


    <div class="mb-3">
        <label class="text-label form-label" for="validationCustomUsername"> Description</label>
        <div class="input-group">
            <textarea class="form-control" name="description" placeholder="Enter Description">{{ $item->description ?? '' }}</textarea>
        </div>
        <div id="icon_error" class="text-danger error"> </div>
    </div>




    <div class="mb-3">
        <label class="form-label">Category For</label>
        <select class="form-control" name="category_for">
            <option value="">Select</option>
            <option value="Doctor" {{ !empty($item->category_for) && $item->category_for == 'Doctor' ? 'selected' : '' }}>Doctor</option>
            <!-- <option value="Nurse" {{ !empty($item->category_for) && $item->category_for == 'Nurse' ? 'selected' : '' }}>Nurse</option> -->
        </select>
        <div id="category_for_error" class="text-danger error"></div>
    </div>

    {{-- Levels & Sessions --}}
    <div class="mt-4">
        <label class="form-label fw-bold">Set Levels</label>

        @for($i = 1; $i <= 5; $i++)
            <div class="mb-4 border p-3 rounded shadow-sm">
            <h5>Level {{ $i }}</h5>

            @foreach(['weekly', 'monthly', 'yearly'] as $type)
            @php
            $level =DB::table('category_levels')->where('category_id',$item->id)->where('level', $i)->where('session_type', $type)->first();

            @endphp
            <div class="mb-3 bg-light p-3 rounded">
                <h6 class="mb-3 text-primary">{{ ucfirst($type) }} Session</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Number of Sessions</label>
                        <input type="number" class="form-control" name="levels[{{ $i }}][{{ $type }}][sessions]" value="{{ $level->sessions ?? '' }}" placeholder="Sessions">
                    </div>
                    <div class="col-md-6">
                        <label>Price</label>
                        <input type="number" class="form-control" name="levels[{{ $i }}][{{ $type }}][price]" step="0.01" value="{{ $level->price ?? '' }}" placeholder="Price">
                    </div>
                </div>
            </div>
            @endforeach
    </div>
    @endfor
    </div>



</form>