$(document).ready(function () {
    /********************************************
     * USER MANAGEMENT                          *
     ********************************************/
    $("#btn-active").click(function () {
        var button = $(this);
        let dataAttr = button.data("attr");

        let userId = dataAttr.userId;
        let actionUrl = dataAttr.action;

        let formData = {
            userId: userId,
            _token: $('meta[name="csrf-token"]').attr("content"),
        };

        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData,
            success: function (response) {
                if (response.success) {
                    button
                        .closest(".profile_view")
                        .find(".brief i")
                        .text("Đã kích hoạt");
                    button.hide();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                toastr.error("Có lỗi xảy ra. Vui lòng thử lại sau.");
            },
        });
    });

    $("#btn-ban, #btn-delete, #btn-unban, #btn-restore").click(function () {
        var button = $(this);
        let dataAttr = button.data("attr");

        let userId = dataAttr.userId;
        let status = dataAttr.status;
        let actionUrl = dataAttr.action;

        let formData = {
            userId: userId,
            status: status,
            _token: $('meta[name="csrf-token"]').attr("content"),
        };
        console.log(formData);

        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData,
            success: function (response) {
                if (response.success) {
                    button
                        .closest(".profile_view")
                        .find(".brief i")
                        .text(response.status);
                    button.parent().find("button").hide(); // Ẩn tất cả các nút
                    if (status === "b") {
                        button.parent().find("#btn-unban").show();
                    } else if (status === "d") {
                        button.parent().find("#btn-restore").show();
                    } else {
                        button.parent().find("#btn-ban, #btn-delete, #btn-active").show();
                    }
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                toastr.error("Có lỗi xảy ra. Vui lòng thử lại sau.");
            },
        });
    });
    /********************************************
     * TOURS MANAGEMENT                          *
     ********************************************/
    CKEDITOR.replace('description');
    $("#start_date, #end_date").datetimepicker({
        format: "d/m/Y",
        timepicker: false,
    });
    // $('#wizard .buttonNext').click(function(e) {
    //     e.preventDefault(); 
    //     var isValid = true;

    //     // Kiểm tra các trường bắt buộc
    //     $('#form-step1 input, #form-step1 select, #form-step1 textarea').each(function() {
    //         if ($(this).prop('required') && $(this).val() === '') {
    //             isValid = false;
    //             $(this).addClass('is-invalid');  // Thêm lớp lỗi
    //             toastr.error('Vui lòng điền đầy đủ các trường bắt buộc!', 'Lỗi!');
    //         } else {
    //             $(this).removeClass('is-invalid');
    //         }
    //     });

    //     // Kiểm tra ngày bắt đầu và ngày kết thúc
    //     var startDate = $('#start_date').val();
    //     var endDate = $('#end_date').val();

    //     if (startDate && endDate) {
    //         // Chuyển đổi các ngày thành kiểu Date để so sánh
    //         var startDateObj = new Date(startDate);
    //         var endDateObj = new Date(endDate);

    //         if (startDateObj >= endDateObj) {
    //             isValid = false;
    //             toastr.error('Ngày bắt đầu phải nhỏ hơn ngày kết thúc!', 'Lỗi!');
    //             $('#start_date').addClass('is-invalid');
    //             $('#end_date').addClass('is-invalid');
    //         } else {
    //             $('#start_date').removeClass('is-invalid');
    //             $('#end_date').removeClass('is-invalid');
    //         }
    //     }

    //     // Nếu có lỗi, ngừng chuyển bước
    // if (!isValid) {
    //     console.log(333333333333333333);
        
    //     return; // Không cho phép chuyển bước
    // }

        
    // });
});
