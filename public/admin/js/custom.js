const base_url = window.location.origin;
const RELOAD_TIME = '300';

$(document).ready(function() {
    $.ajax({
        url: base_url + "admin/getsidebar",
        type: 'get',
        cache: true,
        contentType: false,
        processData: false,
        success: function(result) {
            alert(result);
            $("#navbar-nav").html(result);
        },
    })
});


$(document).on('submit', '#categoryadd', function(e) {
    e.preventDefault();
    _url = $(this).attr('action');
    $('.error').html('');
    var formData = new FormData(this);
    formData.append('action', "enqdet");

    $('#addcate').attr('disabled', true);
    $.ajax({
        url: _url,
        type: "post",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if (result.code == 401) {
                $('#addcate').attr('disabled', false);

                $.each(result.message, function(prefix, val) {
                    $('#' + prefix + '_error').text(val[0]);
                    $('#' + prefix + '_error1').text(val[0]);

                });
            } else {

                toastr.options = {
                    "closeButton": true,
                    "progressBar": false,
                    "duration": 1000,
                    "showEasing": "swing",
                    "positionClass": "toast-top-right",
                }
                toastr.success(result.message);
                setTimeout(function() {
                    location.reload(true);
                }, 2000);




            }


        }
    })



});

function getinfo(userid) {
    var _url = '/admin/appointment/userinfo';
    if (userid != '') {
        $.ajax({
            url: base_url + _url,
            type: 'post',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { userid: userid },
            success: function(result) {
                $('#showgender').val(result.gender);
                $('#dob').val(result.dob);
                $('#mobile').val(result.mobile);
                $('#age').val(result.age);
            }

        });
    } else {
        $('#showgender').val('');
        $('#dob').val('');
    }
}




function getdoctorinfo(userid) {
    var _url = '/admin/appointment/doctorinfo';
    if (userid != '') {
        $.ajax({
            url: base_url + _url,
            type: 'post',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { userid: userid },
            success: function(result) {
                $('#amount').val(result.fee);
            }
        });
    }

}







// get staff details 



// $("html").on("contextmenu", function(e) { return false; });

// jQuery(document).ready(function(e) {
//     (document.onkeypress = function(e) {
//         if (123 == (e = e || window.event).keyCode) return !1;
//     }),
//     e(document).keydown(function(e) {
//         var n = String.fromCharCode(e.keyCode).toLowerCase();
//         return !e.ctrlKey || ("c" != n && "u" != n) ? "{" == n ? (alert("Sorry, This Functionality Has Been Disabled!"), !1) : void 0 : (alert("Sorry, This Functionality Has Been Disabled!"), !1);
//     });
// });

// jQuery(document).ready(function(e) {
//     e(document).keydown(function(e) {
//         var n = String.fromCharCode(e.keyCode).toLowerCase();
//         return !e.ctrlKey || !e.shiftKey || ("i" != n) ? "{" == n ? (alert("Sorry, This Functionality Has Been Disabled!"), !1) : void 0 : (alert("Sorry, This Functionality Has Been Disabled!"), !1);
//     });
// });


// // onkeypress check username 
function allowAlphaNumericSpace(e) {
    var code = ('charCode' in e) ? e.charCode : e.keyCode;
    if (!(code > 47 && code < 58) && // numeric (0-9)
        !(code > 64 && code < 91) && // upper alpha (A-Z)
        !(code > 96 && code < 123)) { // lower alpha (a-z)
        e.preventDefault();
    }
}



// // reload page
function reloadpage() {
    setTimeout(function() {
        location.reload(true);
    }, 350);
}



// // show image on selecting
// function readURL(input) {
//     if (input.files && input.files[0]) {
//         var reader = new FileReader();

//         reader.onload = function(e) {
//             $('#companylogo-img').attr('src', e.target.result);
//         }

//         reader.readAsDataURL(input.files[0]);
//     }
// }

// $("#cat_img").change(function() {
//     $("#company-logo-input").css('display', 'none');
//     $("#companylogo-img").css('display', 'block');
//     readURL(this);
// });