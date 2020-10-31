jQuery(document).ready(function ($) {
    get_doctors($);
    submit_form($);
    search_result($);
})
/* Get doctors on selection disease type */
function get_doctors($) {
    var disease = $('#ab_plugin #p_disease');
    var doctor_field = $('#doctor_field');
    disease.change(function (e) {
        var value = $(e.currentTarget).val();
        $.ajax({
            url: file_url.admin_ajax,
            data: {
                action: 'get_doctor',
                value: value
            },
            type: 'post',
            success: res => {
                if (res == 'failed') {
                    alert('Something went wrong');
                }
                else {
                    var doctor_list = JSON.parse(res);
                    doctor_field.html(`
                            <strong>
                                <label for="p_doctor">Choose Doctor :</label>
                            </strong>
                            <br/>
                            <select required name="p_doctor" id="p_doctor">
					            <option selected disabled hidden >Choose Doctor</option>
                            </select>
                        `);
                    doctor_list.forEach(doctor => {
                        $('#p_doctor').append('<option value="' + doctor.term_id + '">' + doctor.name + '</option>')
                    });
                }
            },
            error: err => {
                alert('Something went wrong');
            }
        })
    })
}
/* Submit the form */
function submit_form($) {
    var form = $('#ab_plugin');
    form.on('submit', function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: file_url.admin_ajax,
            data: {
                action: 'ab_form_submit',
                form_data: form_data
            },
            type: 'post',
            success: res => {
                console.log(res);
                if (res == 'empty_field') {
                    alert('One or more field is empty');
                }
                else if (res == 'post_failed') {
                    alert('Post creation failed');
                }
                else if (res == 'post_created') {
                    alert('Appointment Created successfully');
                }
                else {
                    alert('Something went wrong');
                }
            },
            error: err => {
                alert('Something went wrong');
            }
        })
    })
}
/* Get search result */
function search_result($) {
    var form = $('#ab_search_form');
    form.on('submit', function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: file_url.admin_ajax,
            data: {
                action: 'ab_form_search',
                form_data: form_data
            },
            type: 'post',
            success: res => {
                console.log(res);
            },
            error: err => {
                alert('Something went wrong');
            }
        })
    })
}