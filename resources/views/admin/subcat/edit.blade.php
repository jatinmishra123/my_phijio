<input type="hidden" name="rowid" value="{{ $item->id }}">

<div class="row">
    <div class="col-lg-12">
        <div class="mb-3">
            <label class="form-label" for="steparrow-gen-info-email-input">Sub Category Name</label>
            <input type="text" class="form-control" name="category_name" value="{{ $item->sub_cat_name ?? '' }}">
            <div class="error" id="category_name_error2"></div>
        </div>
    </div>



    <div class="col-lg-12">
        <div class="mb-3">
            <label class="form-label" for="steparrow-gen-info-email-input">Location</label>
            <select class="form-control mb-3" name="location">
                <option value="">Select location</option>
                @foreach($ocategories as $ocategorie)
                <option value="{{$ocategorie->id}}" @php if(!empty($ocategorie->id)) { if($item->catId == $ocategorie->id) { echo 'selected'; } } @endphp >{{$ocategorie->category_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>