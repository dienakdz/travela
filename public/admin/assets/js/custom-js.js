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

    var timelineCounter_edit;
    var formDataEdit = {};
    var tourIdSendingImage;
    $(document).on("click", ".edit-tour", function (e) {
        e.preventDefault();
        console.log("edittour-click");
        var tourId = $(this).data("tourid");
        var urlEdit = $(this).data("urledit");
        tourIdSendingImage = tourId;

        init_SmartWizard_Edit_Tour();

        var csrfToken = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            type: "GET",
            url: urlEdit,
            data: {
                _token: csrfToken,
                tourId: tourId,
            },
            success: function (response) {
                if (response.success) {
                    console.log(response);

                    const tour = response.tour;
                    const images = response.images;
                    const timeline = response.timeline;

                    loadOldImages(images);

                    // Gán giá trị cho datetimepicker
                    const startDate = moment(
                        tour.startDate,
                        "YYYY-MM-DD"
                    ).format("DD/MM/YYYY");
                    const endDate = moment(tour.endDate, "YYYY-MM-DD").format(
                        "DD/MM/YYYY"
                    );

                    // Điền dữ liệu vào các field
                    $("input[name='name']").val(tour.title);
                    $("input[name='destination']").val(tour.destination);
                    $("select[name='domain']").val(tour.domain); // Giá trị select
                    $("input[name='number']").val(tour.quantity);
                    $("input[name='price_adult']").val(tour.priceAdult);
                    $("input[name='price_child']").val(tour.priceChild);
                    $("#start_date").val(startDate);
                    $("#end_date").val(endDate);

                    // Đảm bảo CKEditor đã sẵn sàng
                    CKEDITOR.instances["description"].on(
                        "instanceReady",
                        function () {
                            CKEDITOR.instances["description"].setData(
                                tour.description
                            );
                        }
                    );
                    timelineCounter_edit = 1; // Đặt lại bộ đếm

                    // Xóa các timeline hiện tại trước khi load dữ liệu
                    $("#step-3").empty();

                    // Duyệt qua mảng timeline và thêm vào giao diện
                    timeline.forEach((item) => {
                        editTimelineEntry(item);
                    });
                } else {
                    toastr.error(response.message);
                    setTimeout(() => {
                        $("#edit-tour-modal").modal("hide");
                    }, 500); // Delay ngắn để đảm bảo modal đóng
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                toastr.error("Có lỗi xảy ra. Vui lòng thử lại sau.");
            },
        });
    });

    function getFormDataImages() {
        var formDataImages = [];

        // Lấy các ảnh cũ từ Dropzone (những ảnh đã được thêm vào)
        var oldImages = dropzoneOldImages.files.filter(function (file) {
            return file.status === "accepted" || file.status === "complete";
        });

        // Thêm các ảnh cũ vào formDataImages
        oldImages.forEach(function (file) {
            formDataImages.push(file.name); // Lấy tên của ảnh cũ
        });

        // Thêm các ảnh đã upload thành công từ Dropzone
        dropzoneOldImages.getAcceptedFiles().forEach(function (file) {
            if (file.xhr && file.xhr.responseText) {
                var response = JSON.parse(file.xhr.responseText); // Chuyển đổi JSON response nếu cần
                if (
                    response.success &&
                    response.data &&
                    response.data.filename
                ) {
                    var newImageName = response.data.filename; // Lấy tên file từ response
                    formDataImages.push(newImageName); // Thêm vào formDataImages
                }
            }
        });

        // Loại bỏ các tên ảnh trùng lặp
        formDataImages = [...new Set(formDataImages)];

        console.log(formDataImages);

        // Kiểm tra formDataImages đã chứa đủ ảnh chưa (ít nhất 5 ảnh)
        if (formDataImages.length < 5) {
            toastr.error("Vui lòng tải lên ít nhất 5 ảnh.");
            return false; // Dừng lại nếu số lượng ảnh không đủ
        }

        return formDataImages;
    }

    function init_SmartWizard_Edit_Tour() {
        if (typeof $.fn.smartWizard === "undefined") {
            return;
        }
        console.log("init_SmartWizard_Edit_Tour");
        // $("#edit-tour-modal #wizard").smartWizard("goToStep", 1);

        $("#edit-tour-modal #wizard").smartWizard({
            onLeaveStep: function (obj, context) {
                var stepIndex = context.fromStep; // Lấy chỉ số bước hiện tại khi rời khỏi bước
                var nextStepIndex = context.toStep; // Lấy chỉ số bước tiếp theo
                console.log("Leaving Step: " + stepIndex);
                console.log("Next Step: " + nextStepIndex);

                var finishStep1 = true;
                var finishStep2 = true;
                // Kiểm tra bước 1
                if (stepIndex === 1) {
                    // Kiểm tra các trường trong form step1
                    $(
                        "#form-step1 input, #form-step1 select, #form-step1 textarea"
                    ).each(function () {
                        if (
                            $(this).prop("required") &&
                            $(this).val().trim() === ""
                        ) {
                            finishStep1 = false; // Đặt finishStep1 thành false nếu có trường hợp không hợp lệ
                            $(this).addClass("is-invalid"); // Thêm lớp lỗi
                            toastr.error(
                                "Vui lòng điền đầy đủ các trường bắt buộc!",
                                "Lỗi!"
                            );
                        } else {
                            $(this).removeClass("is-invalid"); // Xóa lớp lỗi nếu trường hợp hợp lệ
                        }
                    });

                    // Kiểm tra trường domain
                    var domain = $("#domain").val();
                    if (!domain) {
                        finishStep1 = false;
                        $("#domain").addClass("is-invalid"); // Thêm lớp lỗi nếu không chọn khu vực
                        toastr.error("Vui lòng chọn khu vực!", "Lỗi!");
                    } else {
                        $("#domain").removeClass("is-invalid");
                    }

                    // Kiểm tra mô tả
                    var description =
                        CKEDITOR.instances["description"].getData();
                    if (!description) {
                        finishStep1 = false;
                        toastr.error("Vui lòng điền mô tả!");
                    }

                    console.log(finishStep1); // Kiểm tra giá trị của finishStep1

                    // Tạo formData từ các trường trong form bước 1
                    formDataEdit = {
                        tourId: tourIdSendingImage,
                        name: $("input[name='name']").val(),
                        destination: $("input[name='destination']").val(),
                        domain: $("#domain").val(),
                        number: $("input[name='number']").val(),
                        price_adult: $("input[name='price_adult']").val(),
                        price_child: $("input[name='price_child']").val(),
                        start_date: $("#start_date").val(),
                        end_date: $("#end_date").val(),
                        description: description,
                        _token: $('input[name="_token"]').val(),
                        images: [],
                        timeline: [],
                    };
                    console.log("formDataEdit step 1:");
                    console.log(formDataEdit);

                    // Nếu tất cả hợp lệ, cho phép chuyển bước
                    if (finishStep1) {
                        return true;
                    } else {
                        return false;
                    }
                }
                // Kiểm tra bước 2 (ảnh)
                if (stepIndex === 2) {
                    var formDataImages = getFormDataImages();

                    if (formDataImages === false) {
                        return false; // Dừng lại nếu ảnh không đủ
                    }

                    // Thêm ảnh vào formDataEdit
                    formDataEdit.images = formDataImages; // Gán danh sách ảnh cho formDataEdit
                    console.log("formDataEdit step 2:");
                    console.log(formDataEdit);
                    if (finishStep2) {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
        });

        // Khởi tạo Dropzone
        Dropzone.autoDiscover = false; // Ngăn Dropzone tự động init
        dropzoneOldImages = new Dropzone("#myDropzone-listTour", {
            url: "http://travela:8000/admin/add-temp-images", // URL upload ảnh
            method: "post",
            paramName: "image",
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            dictRemoveFile: "Xóa ảnh",
            autoProcessQueue: true, // Không tự động upload
            maxFiles: 5, // Giới hạn số file tối đa
            parallelUploads: 5, // Số file được upload song song
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Thêm CSRF token vào headers
            },
            init: function () {
                // Lắng nghe sự kiện 'sending' để thêm thông tin vào formData
                this.on("sending", function (file, xhr, formData) {
                    formData.append("tourId", tourIdSendingImage); // tourId là ID của tour mà bạn cần gửi
                });
            },
        });

        $("#wizard_verticle").smartWizard({
            transitionEffect: "slide",
        });

        $(".buttonNext").addClass("btn btn-success");
        $(".buttonPrevious").addClass("btn btn-primary");
        $(".buttonFinish").addClass("btn btn-default");
    }

    function loadOldImages(images) {
        images.forEach(function (image) {
            let imageUrl = `/admin/assets/images/gallery-tours/${image.imageURL}`; // Tạo đường dẫn đầy đủ

            let mockFile = {
                name: image.imageURL, // Tên tệp ảnh
                url: imageUrl, // Đường dẫn đầy đủ
                status: "accepted", // Đặt trạng thái của file là 'accepted'
            };

            // Thêm file vào Dropzone
            dropzoneOldImages.emit("addedfile", mockFile);
            dropzoneOldImages.emit("thumbnail", mockFile, imageUrl); // Hiển thị thumbnail
            dropzoneOldImages.emit("complete", mockFile);
            dropzoneOldImages.files.push(mockFile);
        });
    }

    function editTimelineEntry(data = null) {
        const title = data ? data.title : `Ngày ${timelineCounter_edit}`;
        const description = data ? data.description : "";

        const timelineEntry = `
        <div class="timeline-entry" id="timeline-entry-${timelineCounter_edit}">
            <label for="day-${timelineCounter_edit}">Ngày ${timelineCounter_edit}</label>
            <input type="text" class="form-control" id="day-${timelineCounter_edit}" 
                   name="day-${timelineCounter_edit}" 
                   placeholder="Ngày thứ..." 
                   value="${title}" 
                   required>
            
            <label for="itinerary-${timelineCounter_edit}" style="margin-top: 10px; display: block;">Lộ trình:</label>
            <textarea id="itinerary-${timelineCounter_edit}" name="itinerary-${timelineCounter_edit}" required>${description}</textarea>
        </div>
    `;

        // Thêm vào div#step-3
        $("#step-3").append(timelineEntry);

        // Khởi tạo CKEditor cho textarea vừa thêm
        if ($(`#itinerary-${timelineCounter_edit}`).length) {
            CKEDITOR.replace(`itinerary-${timelineCounter_edit}`);
        }
        // formDataEdit.timeline.push(itineraryData);

        timelineCounter_edit++;
    }

    $("#edit-tour-modal").on("shown.bs.modal", function () {
        $("#edit-tour-modal #wizard .buttonFinish")
            .off("click")
            .on("click", function (e) {
                // Thêm các timeline entries vào formDataEdit
                formDataEdit.timeline = []; // Xóa dữ liệu cũ nếu có
                $(".timeline-entry").each(function () {
                    const title = $(this).find('input[name^="day"]').val(); // Lấy title ngày
                    const itinerary =
                        CKEDITOR.instances[
                            $(this).find("textarea").attr("id")
                        ].getData(); // Lấy lộ trình từ CKEditor
                    formDataEdit.timeline.push({
                        title: title,
                        itinerary: itinerary,
                    });
                });

                console.log(
                    "formDataEdit sau khi nhấn hoàn thành:",
                    formDataEdit
                );
                var urlUpdate = $("#timeline-form").attr("action");

                $.ajax({
                    url: urlUpdate, // Thay đổi URL phù hợp với API của bạn
                    type: "POST",
                    data: formDataEdit,
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $("#edit-tour-modal").modal("hide");
                            // Reload lại toàn bộ trang
                            location.reload();
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        toastr.error("Có lỗi xảy ra. Vui lòng thử lại sau.");
                    },
                });
            });
    });
    // Khi modal đóng
    $("#edit-tour-modal").on("hidden.bs.modal", function () {
        console.log("close modal");

        // Reset SmartWizard về bước đầu tiên
        $("#edit-tour-modal #wizard").smartWizard("goToStep", 1);

        // Kiểm tra nếu Dropzone đã được khởi tạo
        if (Dropzone.forElement("#myDropzone-listTour") !== undefined) {
            // Hủy bỏ Dropzone cũ
            // $("#myDropzone-listTour .dz-preview").remove();
            Dropzone.forElement("#myDropzone-listTour").destroy();
        }
    });

    $(document).on("click", ".delete-tour", function (e) {
        e.preventDefault();
        var tourId = $(this).data("tourid");
        var urlDelete = $(this).attr("href");

        var csrfToken = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            type: "POST",
            url: urlDelete,
            data: {
                _token: csrfToken,
                tourId: tourId,
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

    $(".add-tours #wizard .buttonFinish").on("click", function () {
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

    /********************************************
     * BOOKING MANAGEMENT                          *
     ********************************************/
    $(document).on("click", ".confirm-booking", function (e) {
        e.preventDefault();
        console.log(11111111);

        const bookingId = $(this).data("bookingid");
        const urlConfirm = $(this).data("urlconfirm");
        console.log("Booking ID:", bookingId);
        console.log("urlConfirm:", urlConfirm);

        // Thực hiện các hành động khác, ví dụ gọi AJAX
        $.ajax({
            url: urlConfirm,
            method: "POST",
            data: {
                bookingId: bookingId,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $("#tbody-booking").html(response.data);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (error) {
                toastr.error("Có lỗi xảy ra. Vui lòng thử lại sau.");
            },
        });
    });
    /********************************************
     * BOOKING INVOICE                          *
     ********************************************/
    $('#send-pdf-btn').click(function () {
        // Lấy bookingId và email từ button
        const bookingId = $(this).data('bookingid');
        const email = $(this).data('email');
        const urlSendPdf = $(this).data('urlsendmail');

        // Gửi AJAX request
        $.ajax({
            url: urlSendPdf,
            type: 'POST',
            data: {
                bookingId: bookingId,
                email: email,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: function () {
                toastr.warning('Đang gửi mail!!!');
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message)
                } else {
                    toastr.error(response.message)
                }
            },
            error: function (xhr, status, error) {
                toastr.error('Đã xảy ra lỗi khi gửi email. Vui lòng thử lại!');
                console.error(xhr.responseText); // Log lỗi chi tiết trong console
            }
        });
    });
});
