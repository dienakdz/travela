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
        <!-- Modal Edit Tour-->
        <div class="modal fade" id="edit-tour-modal" tabindex="-1" role="dialog" aria-labelledby="edit-tour-Label"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-tour-Label">Chỉnh sửa Tour</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="wizard" class="form_wizard wizard_horizontal wizard-edit-tour">
                            <ul class="wizard_steps">
                                <li>
                                    <a href="#step-1">
                                        <span class="step_no">1</span>
                                        <span class="step_descr">
                                            Bước 1<br />
                                            <small>Bước 1 Nhập thông tin </small>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#step-2">
                                        <span class="step_no">2</span>
                                        <span class="step_descr">
                                            Bước 2<br />
                                            <small>Bước 2 Thêm hình ảnh</small>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#step-3">
                                        <span class="step_no">3</span>
                                        <span class="step_descr">
                                            Bước 3<br />
                                            <small>Bước 3 Lộ trình</small>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <div id="step-1">
                                <form class="form-info-tour" method="POST"
                                    id="form-step1">
                                    @csrf
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Tên
                                            <span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" name="name" placeholder="Nhập tên Tour"
                                                required>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Điểm đến
                                            <span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" name="destination" placeholder="Điểm đến"
                                                required>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Khu
                                            vực<span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <select class="form-control" name="domain" id="domain">
                                                <option value="">Chọn khu vực</option>
                                                <option value="b">Miền Bắc</option>
                                                <option value="t">Miền Trung</option>
                                                <option value="n">Miền Nam</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Số lượng
                                            <span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" type="number" name="number" required>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Giá người
                                            lớn
                                            <span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" type="number" name="price_adult" required>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Giá trẻ em
                                            <span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" type="number" name="price_child" required>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Ngày khởi
                                            hành<span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control datetimepicker" id="start_date"
                                                name="start_date" disabled>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Ngày kết
                                            thúc<span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control datetimepicker" id="end_date"
                                                name="end_date" disabled>
                                        </div>
                                    </div>

                                    <div class="field item form-group bad">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Mô
                                            tả<span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <textarea name="description" id="description" rows="10" required></textarea>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div id="step-2">
                                <h2 class="StepTitle">Thêm hình ảnh</h2>
                                <form action="" class="dropzone dz-clickable"
                                    id="myDropzone-listTour" enctype="multipart/form-data">
                                    @csrf
                                    <div class="dz-default dz-message">
                                        <span>Chọn hình ảnh về tours để upload</span>
                                    </div>
                                </form>
                            </div>
                            <form action="{{ route('admin.edit-tour') }}" id="timeline-form" method="POST">
                                @csrf
                                <input type="hidden" name="tourId" class="hiddenTourId">
                                <div id="step-3">
                                    <h2 class="StepTitle">Nhập lộ trình</h2>

                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.blocks.footer')
