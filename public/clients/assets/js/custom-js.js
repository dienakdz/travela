$(document).ready(function () {
    // handle Login
    $("#sign-up").click(function () {
        $(".sign-in").hide();
        $(".signup").show();
    });
    $("#sign-in").click(function () {
        $(".signup").hide();
        $(".sign-in").show();
    });

    // Home page
    $("#start_date, #end_date").datetimepicker({
        format: "d/m/Y",   // Định dạng ngày (dd/mm/yyyy)
        timepicker: false  // Tắt chức năng chọn giờ
    });
    // header icon login 
    $("#userDropdown").click(function() {
        $("#dropdownMenu").toggle(); // Toggle dropdown menu when user clicks
    });
    

});
