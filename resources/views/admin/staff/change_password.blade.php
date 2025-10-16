<input type="hidden" class="form-control" name="rowid" value="{{ $id }}">
<input type="hidden" class="form-control" name="table" value="{{ $table }}">

<div class="row">
    <div class="col-lg-6 mb-3">
        <div class="">
            <label for="teammembersName" class="form-label">New Password</label>
            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter Password">
        </div>
        <div class="error" id="new_password_error"></div>
    </div>
    <div class="col-lg-6 mb-3">
        <div class="">
            <label for="teammembersName" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter Confirm Password">
        </div>
        <div class="error" id="confirm_password_error"></div>
    </div>
</div>