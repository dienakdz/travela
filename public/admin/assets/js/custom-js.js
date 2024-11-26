$(document).ready(function () {
    // $("#wizard").smartWizard("goToStep",3);
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
                        button
                            .parent()
                            .find("#btn-ban, #btn-delete, #btn-active")
                            .show();
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
    if ($("#description").length) {
        CKEDITOR.replace("description");
    }
    $("#start_date, #end_date").datetimepicker({
        format: "d/m/Y",
        timepicker: false,
    });

    $(document).on('click', '.delete-tour', function(e) {
        e.preventDefault();
        var tourId = $(this).data('tourid');
        var urlDelete = $(this).attr('href');

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: urlDelete,
            data: {
                '_token': csrfToken,
                'tourId' : tourId
            },
            success: function (response) {
                if (response.success) {
                    $("#tbody-listTours").html(response.data);
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
     * ADD TOURS                              *
     ********************************************/

    let timelineCounter = 1;
    let maxTimelineDays;

    $(document).on("dataUpdated", function (event, daysDifference) {
        maxTimelineDays = daysDifference;
    });
    // Hàm thêm một timeline entry mới
    function addTimelineEntry() {
        // Kiểm tra nếu số lượng timeline entries đã đạt giới hạn
        console.log(maxTimelineDays);

        if (timelineCounter > maxTimelineDays) {
            toastr.error(`Không thể thêm quá ${maxTimelineDays} ngày.`);
            return;
        }
        const timelineEntry = `
                <div class="timeline-entry" id="timeline-entry-${timelineCounter}">
                    <label for="day-${timelineCounter}">Ngày ${timelineCounter}</label>
                    <input type="text" class="form-control" id="day-${timelineCounter}" name="day-${timelineCounter}" placeholder="Ngày thứ..." required>
                    
                    <label for="itinerary-${timelineCounter}" style="margin-top: 10px; display: block;">Lộ trình:</label>
                    <textarea id="itinerary-${timelineCounter}" name="itinerary-${timelineCounter}" required></textarea>
                    
                    <button type="button" class="btn btn-round btn-danger remove-btn" data-id="${timelineCounter}">Xóa Timeline này</button>
                </div>
            `;

        // Thêm vào div#step-3
        $("#step-3").append(timelineEntry);

        // Khởi tạo CKEditor cho textarea vừa thêm
        if ($(`#itinerary-${timelineCounter}`).length) {
            CKEDITOR.replace(`itinerary-${timelineCounter}`);
        }

        timelineCounter++;
    }

    // Xử lý khi nhấn nút thêm timeline
    $("#step-3").on("click", "#add-timeline", function () {
        addTimelineEntry();
    });

    // Xử lý khi nhấn nút xóa timeline
    $("#step-3").on("click", ".remove-btn", function () {
        const id = $(this).data("id");

        $(`#timeline-entry-${id}`).remove(); // Xóa div chứa timeline entry
    });

    // Thêm nút thêm timeline vào div#step-3
    const addButton = `<button type="button" id="add-timeline" class="btn btn-round btn-info" style="margin-top: 20px;">Thêm Timeline</button>`;
    $("#step-3").append(addButton);

    // Thêm timeline đầu tiên khi trang tải xong
    addTimelineEntry();

    $("#wizard .buttonFinish").on("click", function () {
        // Lấy form cần kiểm tra
        const form = $("#timeline-form")[0]; // Chuyển đổi về DOM element để sử dụng checkValidity()

        // Kiểm tra tính hợp lệ của form
        if (form.checkValidity()) {
            // Form hợp lệ -> Gửi form
            $("#timeline-form").submit();
        } else {
            // Form không hợp lệ -> Hiện toastr
            toastr.error("Vui lòng điền đầy đủ thông tin trong form!");

            // Kích hoạt kiểm tra lỗi trên form để hiển thị lỗi HTML5
            form.reportValidity();
        }
    });
});
