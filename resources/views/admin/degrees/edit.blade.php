<input type="hidden" name="rowid" value="{{ $item->id }}">

<div class="row">
    <div class="col-lg-12">
        <div class="mb-3">
            <label class="form-label" for="steparrow-gen-info-email-input">Location Name</label>
            <input type="text" class="form-control" name="location_name" value="{{ $item->location_name ?? '' }}" required>
            <div class="error" id="location_name_error2"></div>
        </div>
    </div>
</div>
