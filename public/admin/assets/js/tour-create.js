$(document).ready(function () {
    const form = document.getElementById("create-tour-form");

    if (!form || typeof $.fn.smartWizard === "undefined") {
        return;
    }

    const $wizard = $(".add-tours #wizard");
    const $timelineList = $("#timeline-list");
    let timelineCounter = 1;
    let maxTimelineDays = 0;
    let submitting = false;
    let resetting = false;

    Dropzone.autoDiscover = false;

    const imageDropzone = new Dropzone("#myDropzone", {
        url: form.action,
        paramName: "images[]",
        acceptedFiles: "image/jpeg,image/png,image/webp",
        maxFilesize: 5,
        maxFiles: 5,
        addRemoveLinks: true,
        dictRemoveFile: "Xóa ảnh",
        dictInvalidFileType: "Định dạng ảnh không hợp lệ.",
        dictFileTooBig: "Ảnh không được lớn hơn 5 MB.",
        dictMaxFilesExceeded: "Tour chỉ được có 5 ảnh.",
        autoProcessQueue: false,
    });

    function parseDisplayDate(value) {
        const parts = value.split("/");

        if (parts.length !== 3) {
            return null;
        }

        const day = Number(parts[0]);
        const month = Number(parts[1]);
        const year = Number(parts[2]);
        const date = new Date(Date.UTC(year, month - 1, day));

        if (
            date.getUTCFullYear() !== year ||
            date.getUTCMonth() !== month - 1 ||
            date.getUTCDate() !== day
        ) {
            return null;
        }

        return date;
    }

    function toIsoDate(date) {
        return [
            date.getUTCFullYear(),
            String(date.getUTCMonth() + 1).padStart(2, "0"),
            String(date.getUTCDate()).padStart(2, "0"),
        ].join("-");
    }

    function validateStepOne(showMessage = true) {
        let valid = true;

        $("#form-step1 input[required], #form-step1 select[required]").each(
            function () {
                const value = String($(this).val() || "").trim();
                $(this).toggleClass("is-invalid", value === "");

                if (value === "") {
                    valid = false;
                }
            },
        );

        const description = CKEDITOR.instances.description.getData().trim();
        const quantity = Number($("input[name='number']").val());
        const adultPrice = Number($("input[name='price_adult']").val());
        const childPrice = Number($("input[name='price_child']").val());
        const startDate = parseDisplayDate($("#start_date").val());
        const endDate = parseDisplayDate($("#end_date").val());

        if (!description) {
            valid = false;
        }

        if (quantity < 1 || adultPrice < 0 || childPrice < 0) {
            valid = false;
        }

        if (!startDate || !endDate || endDate <= startDate) {
            valid = false;
        } else {
            const today = new Date();
            const todayUtc = new Date(
                Date.UTC(
                    today.getFullYear(),
                    today.getMonth(),
                    today.getDate(),
                ),
            );

            if (startDate < todayUtc) {
                valid = false;
            }

            maxTimelineDays = Math.round(
                (endDate.getTime() - startDate.getTime()) / 86400000,
            );
        }

        if (!valid && showMessage) {
            toastr.error(
                "Vui lòng kiểm tra lại thông tin tour và ngày khởi hành.",
            );
        }

        return valid;
    }

    function validateStepTwo(showMessage = true) {
        const acceptedFiles = imageDropzone.getAcceptedFiles();
        const rejectedFiles = imageDropzone.getRejectedFiles();
        const valid = acceptedFiles.length === 5 && rejectedFiles.length === 0;

        if (!valid && showMessage) {
            toastr.error("Vui lòng chọn đúng 5 hình ảnh hợp lệ.");
        }

        return valid;
    }

    function addTimelineEntry() {
        const currentCount = $timelineList.find(".timeline-entry").length;

        if (maxTimelineDays > 0 && currentCount >= maxTimelineDays) {
            toastr.error(`Không thể thêm quá ${maxTimelineDays} ngày.`);
            return;
        }

        const entryId = timelineCounter++;
        const editorId = `itinerary-${entryId}`;
        const timelineEntry = `
            <div class="timeline-entry" id="timeline-entry-${entryId}" data-editor-id="${editorId}">
                <div class="timeline-entry-header">
                    <span class="timeline-day-marker">
                        <span class="timeline-day-number">${currentCount + 1}</span>
                    </span>
                    <div class="timeline-entry-heading">
                        <h3 class="timeline-day-title">Ngày ${currentCount + 1}</h3>
                        <p>Thông tin hoạt động và điểm đến trong ngày.</p>
                    </div>
                    <button type="button" class="timeline-remove-button remove-btn"
                        data-id="${entryId}" aria-label="Xóa ngày ${currentCount + 1}">
                        <i class="fa fa-trash-o"></i>
                        <span>Xóa</span>
                    </button>
                </div>
                <div class="timeline-entry-body">
                    <div class="timeline-field">
                        <label for="day-${entryId}">
                            Tiêu đề ngày <span class="timeline-required">*</span>
                        </label>
                        <input type="text" class="form-control timeline-title" id="day-${entryId}"
                            placeholder="Ví dụ: Khám phá trung tâm Đà Nẵng" required>
                        <small>Tóm tắt ngắn nội dung chính của ngày.</small>
                    </div>
                    <div class="timeline-field timeline-editor-field">
                        <label for="${editorId}">
                            Nội dung lộ trình <span class="timeline-required">*</span>
                        </label>
                        <textarea id="${editorId}" class="timeline-description"></textarea>
                    </div>
                </div>
            </div>
        `;

        $timelineList.append(timelineEntry);
        refreshTimelineSummary();

        const editor = CKEDITOR.replace(editorId, { height: 180 });

        editor.on("instanceReady", function () {
            $wizard.smartWizard("fixHeight");
        });

        $wizard.smartWizard("fixHeight");
    }

    function refreshTimelineSummary() {
        const $entries = $timelineList.find(".timeline-entry");

        $entries.each(function (index) {
            const dayNumber = index + 1;
            const $entry = $(this);

            $entry.find(".timeline-day-number").text(dayNumber);
            $entry.find(".timeline-day-title").text(`Ngày ${dayNumber}`);
            $entry
                .find(".timeline-remove-button")
                .attr("aria-label", `Xóa ngày ${dayNumber}`);
        });

        const total = $entries.length;
        const limit = maxTimelineDays > 0 ? ` / ${maxTimelineDays}` : "";
        $("#timeline-count").text(`${total}${limit} ngày`);
    }

    function ensureFirstTimeline() {
        if ($timelineList.find(".timeline-entry").length === 0) {
            addTimelineEntry();
        }
    }

    function readTimelines(showMessage = true) {
        const timelines = [];
        let valid = true;

        $timelineList.find(".timeline-entry").each(function () {
            const $entry = $(this);
            const editorId = $entry.data("editor-id");
            const title = String($entry.find(".timeline-title").val() || "").trim();
            const description = CKEDITOR.instances[editorId].getData().trim();

            $entry.find(".timeline-title").toggleClass("is-invalid", !title);

            if (!title || !description) {
                valid = false;
                return;
            }

            timelines.push({ title, description });
        });

        if (timelines.length === 0) {
            valid = false;
        }

        if (!valid && showMessage) {
            toastr.error("Vui lòng nhập đầy đủ tiêu đề và nội dung lộ trình.");
        }

        return valid ? timelines : false;
    }

    function destroyTimelineEditors() {
        $timelineList.find(".timeline-entry").each(function () {
            const editorId = $(this).data("editor-id");

            if (CKEDITOR.instances[editorId]) {
                CKEDITOR.instances[editorId].destroy(true);
            }
        });
    }

    function resetWizard() {
        form.reset();
        $(form).find(".is-invalid").removeClass("is-invalid");
        CKEDITOR.instances.description.setData("");
        imageDropzone.removeAllFiles(true);
        destroyTimelineEditors();
        $timelineList.empty();
        timelineCounter = 1;
        maxTimelineDays = 0;
        refreshTimelineSummary();
        resetting = true;
        $wizard.smartWizard("goToStep", 1);
    }

    function firstValidationMessage(xhr) {
        const errors = xhr.responseJSON && xhr.responseJSON.errors;

        if (!errors) {
            return null;
        }

        const firstKey = Object.keys(errors)[0];
        return firstKey && errors[firstKey] ? errors[firstKey][0] : null;
    }

    function submitTour() {
        if (submitting) {
            return false;
        }

        if (!validateStepOne()) {
            $wizard.smartWizard("goToStep", 1);
            return false;
        }

        if (!validateStepTwo()) {
            $wizard.smartWizard("goToStep", 2);
            return false;
        }

        const timelines = readTimelines();

        if (!timelines) {
            return false;
        }

        const startDate = parseDisplayDate($("#start_date").val());
        const endDate = parseDisplayDate($("#end_date").val());
        const formData = new FormData();

        formData.append("_token", $(form).find("input[name='_token']").val());
        formData.append("name", $(form).find("input[name='name']").val());
        formData.append(
            "destination",
            $(form).find("input[name='destination']").val(),
        );
        formData.append("domain", $(form).find("select[name='domain']").val());
        formData.append("number", $(form).find("input[name='number']").val());
        formData.append(
            "price_adult",
            $(form).find("input[name='price_adult']").val(),
        );
        formData.append(
            "price_child",
            $(form).find("input[name='price_child']").val(),
        );
        formData.append("start_date", toIsoDate(startDate));
        formData.append("end_date", toIsoDate(endDate));
        formData.append(
            "description",
            CKEDITOR.instances.description.getData(),
        );

        imageDropzone.getAcceptedFiles().forEach(function (file) {
            formData.append("images[]", file, file.name);
        });

        timelines.forEach(function (timeline, index) {
            formData.append(`timelines[${index}][title]`, timeline.title);
            formData.append(
                `timelines[${index}][description]`,
                timeline.description,
            );
        });

        submitting = true;
        $wizard.find(".buttonFinish").addClass("buttonDisabled");

        $.ajax({
            url: form.action,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                Accept: "application/json",
            },
            success: function (response) {
                toastr.success(response.message);
                resetWizard();
            },
            error: function (xhr) {
                toastr.error(
                    firstValidationMessage(xhr) ||
                        (xhr.responseJSON && xhr.responseJSON.message) ||
                        "Không thể thêm tour. Vui lòng thử lại.",
                );
            },
            complete: function () {
                submitting = false;

                if ($wizard.smartWizard("currentStep") === 3) {
                    $wizard.find(".buttonFinish").removeClass("buttonDisabled");
                }
            },
        });

        return false;
    }

    $wizard.smartWizard({
        onLeaveStep: function (obj, context) {
            if (context.toStep < context.fromStep) {
                return true;
            }

            if (context.fromStep === 1) {
                return validateStepOne();
            }

            if (context.fromStep === 2) {
                const valid = validateStepTwo();

                if (valid) {
                    ensureFirstTimeline();
                }

                return valid;
            }

            return true;
        },
        onFinish: submitTour,
        onShowStep: function (obj, context) {
            if (context.toStep === 3) {
                window.requestAnimationFrame(function () {
                    refreshTimelineSummary();
                    $wizard.smartWizard("fixHeight");
                });
            }

            if (resetting && context.toStep === 1) {
                $wizard.smartWizard("disableStep", 2);
                $wizard.smartWizard("disableStep", 3);
                $wizard.find(".buttonFinish").addClass("buttonDisabled");
                $wizard.smartWizard("fixHeight");
                resetting = false;
            }

            return true;
        },
    });

    $("#add-timeline").on("click", addTimelineEntry);

    $timelineList.on("click", ".remove-btn", function () {
        const entryId = $(this).data("id");
        const $entry = $(`#timeline-entry-${entryId}`);
        const editorId = $entry.data("editor-id");

        if (CKEDITOR.instances[editorId]) {
            CKEDITOR.instances[editorId].destroy(true);
        }

        $entry.remove();
        refreshTimelineSummary();
        $wizard.smartWizard("fixHeight");
    });

    $(form).on("submit", function (event) {
        event.preventDefault();
        submitTour();
    });

    $wizard.find(".buttonNext").addClass("btn btn-success");
    $wizard.find(".buttonPrevious").addClass("btn btn-primary");
    $wizard.find(".buttonFinish").addClass("btn btn-default");
});
