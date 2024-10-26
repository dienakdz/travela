$(document).ready(function () {
    /****************************************
     *              PAGE LOGIN              *
     * ***************************************/
    //Handle click switch tab
    $("#sign-up").click(function () {
        $(".sign-in").hide();
        $(".signup").show();
    });
    $("#sign-in").click(function () {
        $(".signup").hide();
        $(".sign-in").show();
    });

    $("#message").hide();
    $("#error").hide();
    $("#error_login").hide();

    // Handle form submission for signin
    $("#login-form").on("submit", function (e) {
        e.preventDefault();
        var userName = $("#username_login").val().trim();
        var password = $("#password_login").val().trim();

        // Đặt lại nội dung thông báo lỗi và ẩn chúng
        $("#validate_username").hide().text("");
        $("#validate_password").hide().text("");

        var isValid = true;

        // Kiểm tra độ dài mật khẩu
        if (password.length < 6) {
            isValid = false;
            $("#validate_password")
                .show()
                .text("Mật khẩu phải có ít nhất 6 ký tự.");
        }

        // Kiểm tra tên đăng nhập và mật khẩu không chứa ký tự đặc biệt (SQL injection)
        var sqlInjectionPattern = /[<>'"%;()&+]/;
        if (sqlInjectionPattern.test(userName)) {
            isValid = false;
            $("#validate_username")
                .show()
                .text("Tên đăng nhập không được chứa ký tự đặc biệt.");
        }

        if (sqlInjectionPattern.test(password)) {
            isValid = false;
            $("#validate_password")
                .show()
                .text("Mật khẩu không được chứa ký tự đặc biệt.");
        }

        if (isValid) {
            var formData = {
                username: userName,
                password: password,
                _token: $('input[name="_token"]').val(),
            };
            console.log(formData, $(this).attr("action"));

            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: formData,
                success: function (response) {
                    if (response.success) {
                        window.location.href = "/";
                    } else {
                        $("#error_login").text(response.message).show();
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    alert("Có lỗi xảy ra!");
                },
            });
        }
    });

    // Handle form submission for signup
    $("#register-form").on("submit", function (e) {
        e.preventDefault();

        // Lấy giá trị của các trường nhập liệu
        var userName = $("#username_register").val().trim();
        var email = $("#email_register").val().trim();
        var password = $("#password_register").val().trim();
        var rePass = $("#re_pass").val().trim();

        // Đặt lại nội dung thông báo lỗi và ẩn chúng
        $("#validate_username_regis").hide().text("");
        $("#validate_email_regis").hide().text("");
        $("#validate_password_regis").hide().text("");
        $("#validate_repass").hide().text("");

        // Kiểm tra lỗi
        var isValid = true;

        // Kiểm tra tên đăng nhập không chứa ký tự SQL injection
        var sqlInjectionPattern = /[<>'"%;()&+]/;
        if (sqlInjectionPattern.test(userName)) {
            isValid = false;
            $("#validate_username_regis")
                .show()
                .text("Tên tài khoản không được chứa ký tự đặc biệt.");
        }

        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            isValid = false;
            $("#validate_email_regis").show().text("Email không hợp lệ.");
        }

        if (password.length < 6) {
            isValid = false;
            $("#validate_password_regis")
                .show()
                .text("Mật khẩu phải có ít nhất 6 ký tự.");
        }

        if (sqlInjectionPattern.test(password)) {
            isValid = false;
            $("#validate_password_regis")
                .show()
                .text("Mật khẩu không được chứa ký tự đặc biệt.");
        }

        // Kiểm tra nhập lại mật khẩu
        if (password !== rePass) {
            isValid = false;
            $("#validate_repass").show().text("Mật khẩu nhập lại không khớp.");
        }

        if (isValid) {
            var formData = {
                username_regis: userName,
                email: email,
                password_regis: password,
                _token: $('input[name="_token"]').val(),
            };
            console.log(formData, $(this).attr("action"));

            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: formData,
                success: function (response) {
                    if (response.success) {
                        $("#message").text(response.message).show();
                        $("#error").hide();
                        // Reset toàn bộ form
                        $("#register-form").trigger("reset");
                    } else {
                        $("#message").hide();
                        $("#error").text(response.message).show();
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    alert("Có lỗi xảy ra!");
                },
            });
        }
    });

    /****************************************
     *              HOME PAGE             *
     * ***************************************/
    $("#start_date, #end_date").datetimepicker({
        format: "d/m/Y",
        timepicker: false,
    });
    /****************************************
     *              HEADER                  *
     * ***************************************/
    $("#userDropdown").click(function () {
        $("#dropdownMenu").toggle(); // Toggle dropdown menu when user clicks
    });

    /****************************************
     *              PAGE TOURS              *
     * ***************************************/
    // Kiểm tra nếu thanh trượt đã tồn tại
    if ($(".price-slider-range").length) {
        $(".price-slider-range").on("slide", function (event, ui) {
            filterTours(ui.values[0], ui.values[1]);
        });
    }
    $('input[name="domain"]').on("change", filterTours);
    $('input[name="filter_star"]').on("change", filterTours);
    $('input[name="duration"]').on("change", filterTours);

    $('#sorting_tours').on("change", function () {
        filterTours(null, null);
    });

    function filterTours(minPrice = null, maxPrice = null) {
        $(".loader").show();
        $("#tours-container").addClass("hidden-content");

        if (minPrice === null || maxPrice === null) {
            minPrice = $(".price-slider-range").slider("values", 0);
            maxPrice = $(".price-slider-range").slider("values", 1);
        }

        var domain = $('input[name="domain"]:checked').val();
        var star = $('input[name="filter_star"]:checked').val();
        var duration = $('input[name="duration"]:checked').val();
        var sorting = $('#sorting_tours').val();

        formDataFilter = {
            minPrice: minPrice,
            maxPrice: maxPrice,
            domain: domain,
            star: star,
            time: duration,
            sorting : sorting
        };
        console.log(formDataFilter);

        $.ajax({
            url: filterToursUrl,
            method: "GET",
            data: formDataFilter,
            success: function (res) {
                $("#tours-container").html(res).removeClass("hidden-content");
                $("#tours-container .destination-item").addClass("aos-animate");
                $(".loader").hide();
            },
        });
    }

    // Hàm để clear các filter đã chọn
    $(".clear_filter").on("click", function (e) {
        e.preventDefault();

        // Reset slider giá về giá trị mặc định (ví dụ: 0 đến 20000000)
        $(".price-slider-range").slider("values", [0, 20000000]);

        // Bỏ chọn radio và checkbox
        $('input[name="domain"]').prop("checked", false);
        $('input[name="filter_star"]').prop("checked", false);
        $('input[name="duration"]').prop("checked", false);

        // Gọi lại hàm filterTours để áp dụng bộ lọc đã reset
        filterTours(0, 20000000);
    });
});
