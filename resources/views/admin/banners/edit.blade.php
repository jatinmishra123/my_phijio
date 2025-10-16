<input type="hidden" name="rowid" value="{{ $item->id }}">

<div class="row">
   

    <div class="col-lg-12">
        <div class="mb-3">
            <label class="form-label" for="steparrow-gen-info-email-input">Banner</label>
            <input type="file" class="form-control" id="icon" name="icon">
            @if(!empty($item->banner)) 
            <img src="{{ asset('uploads/category/'.$item->banner) }}" style="width: 120px;">
            @endif
        </div>
    </div>

   
      <div class="mb-3 mt-3">
                        <div class="input-group">
                            <label class="text-label form-label" for="validationCustomUsername"> Category For</label>
                            <div class="input-group">
                                <select class="form-control mb-3" name="category_for">
                                    <option value="">Select </option>
                                   
                                    <option value="doctor" @php if(!empty($item->category_for)) { if($item->category_for == 'doctor') { echo 'selected'; } } @endphp >Doctor</option>
                                    <option value="user" @php if(!empty($item->category_for)) { if($item->category_for == 'user') { echo 'selected'; } } @endphp >User</option>
                                    
                                </select>
                            </div>
                        </div>
                        <div id="category_for_error" class="text-danger error"> </div>
                    </div>
</div>