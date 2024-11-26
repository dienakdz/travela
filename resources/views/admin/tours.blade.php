@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>Quản lý <small>Tours</small></h3>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 ">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Tours</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card-box table-responsive">
                                            <p class="text-muted font-13 m-b-30">
                                                Chào mừng bạn đến với trang quản lý tour. Tại đây, bạn có thể thêm mới,
                                                chỉnh sửa, và quản lý tất cả các tour hiện có.
                                            </p>
                                            <table id="datatable-listTours" class="table table-striped table-bordered"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tên</th>
                                                        <th>Thời gian</th>
                                                        <th>Mô tả</th>
                                                        <th>Số lượng</th>
                                                        <th>Giá người lớn</th>
                                                        <th>Giá trẻ em</th>
                                                        <th>Điểm đến</th>
                                                        <th>Khả dụng</th>
                                                        <th>Ngày bắt đầu</th>
                                                        <th>Ngày kết thúc</th>
                                                        <th>Sửa</th>
                                                        <th>Xóa</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-listTours">
                                                    @include('admin.partials.list-tours')
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

    </div>
</div>
@include('admin.blocks.footer')
