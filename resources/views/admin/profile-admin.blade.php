@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
                <nav class="nav navbar-nav">
                    <ul class=" navbar-right">
                        <li class="nav-item dropdown open" style="padding-left: 15px;">
                            <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true"
                                id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                                <img src="images/img.jpg" alt="">John Doe
                            </a>
                            <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="javascript:;"> Profile</a>
                                <a class="dropdown-item" href="javascript:;">
                                    <span class="badge bg-red pull-right">50%</span>
                                    <span>Settings</span>
                                </a>
                                <a class="dropdown-item" href="javascript:;">Help</a>
                                <a class="dropdown-item" href="login.html"><i class="fa fa-sign-out pull-right"></i> Log
                                    Out</a>
                            </div>
                        </li>

                        <li role="presentation" class="nav-item dropdown open">
                            <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1"
                                data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="badge bg-green">6</span>
                            </a>
                            <ul class="dropdown-menu list-unstyled msg_list" role="menu"
                                aria-labelledby="navbarDropdown1">
                                <li class="nav-item">
                                    <a class="dropdown-item">
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                                            <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                        </span>
                                        <span class="message">
                                            Film festivals used to be do-or-die moments for movie makers. They were
                                            where...
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item">
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                                            <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                        </span>
                                        <span class="message">
                                            Film festivals used to be do-or-die moments for movie makers. They were
                                            where...
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item">
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                                            <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                        </span>
                                        <span class="message">
                                            Film festivals used to be do-or-die moments for movie makers. They were
                                            where...
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="dropdown-item">
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                                            <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                        </span>
                                        <span class="message">
                                            Film festivals used to be do-or-die moments for movie makers. They were
                                            where...
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <div class="text-center">
                                        <a class="dropdown-item">
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>Thông tin admin</h3>
                    </div>

                    <div class="title_right">
                        <div class="col-md-5 col-sm-5  form-group pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary" type="button">Go!</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 ">
                        <div class="x_panel">
                            <div class="x_title">
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="col-md-3 col-sm-3  profile_left">
                                    <div class="profile_img">
                                        <div id="crop-avatar">
                                            <!-- Current avatar -->
                                            <img class="img-responsive avatar-view" src="images/picture.jpg"
                                                alt="Avatar" title="Change the avatar">
                                        </div>
                                    </div>
                                    <h3>Samuel Doe</h3>

                                    <ul class="list-unstyled user_data">
                                        <li><i class="fa fa-map-marker user-profile-icon"></i> San Francisco,
                                            California, USA
                                        </li>

                                        <li>
                                            <i class="fa fa-briefcase user-profile-icon"></i> Software Engineer
                                        </li>

                                        <li class="m-top-xs">
                                            <i class="fa fa-external-link user-profile-icon"></i>
                                            <a href="http://www.kimlabs.com/profile/"
                                                target="_blank">www.kimlabs.com</a>
                                        </li>
                                    </ul>

                                    <a class="btn btn-success"><i class="fa fa-edit m-right-xs"></i>Edit Profile</a>
                                    <br />

                                    <!-- start skills -->
                                    <h4>Skills</h4>
                                    <ul class="list-unstyled user_data">
                                        <li>
                                            <p>Web Applications</p>
                                            <div class="progress progress_sm">
                                                <div class="progress-bar bg-green" role="progressbar"
                                                    data-transitiongoal="50"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>Website Design</p>
                                            <div class="progress progress_sm">
                                                <div class="progress-bar bg-green" role="progressbar"
                                                    data-transitiongoal="70"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>Automation & Testing</p>
                                            <div class="progress progress_sm">
                                                <div class="progress-bar bg-green" role="progressbar"
                                                    data-transitiongoal="30"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>UI / UX</p>
                                            <div class="progress progress_sm">
                                                <div class="progress-bar bg-green" role="progressbar"
                                                    data-transitiongoal="50"></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <!-- end of skills -->

                                </div>
                                <div class="col-md-9 col-sm-9 ">
                                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#tab_content1"
                                                    id="home-tab" role="tab" data-toggle="tab"
                                                    aria-expanded="true">Recent Activity</a>
                                            </li>
                                            <li role="presentation" class=""><a href="#tab_content2"
                                                    role="tab" id="profile-tab" data-toggle="tab"
                                                    aria-expanded="false">Projects Worked on</a>
                                            </li>
                                            <li role="presentation" class=""><a href="#tab_content3"
                                                    role="tab" id="profile-tab2" data-toggle="tab"
                                                    aria-expanded="false">Profile</a>
                                            </li>
                                        </ul>
                                        <div id="myTabContent" class="tab-content">
                                            <div role="tabpanel" class="tab-pane active " id="tab_content1"
                                                aria-labelledby="home-tab">

                                                <!-- start recent activity -->
                                                <ul class="messages">
                                                    <li>
                                                        <img src="images/img.jpg" class="avatar" alt="Avatar">
                                                        <div class="message_date">
                                                            <h3 class="date text-info">24</h3>
                                                            <p class="month">May</p>
                                                        </div>
                                                        <div class="message_wrapper">
                                                            <h4 class="heading">Desmond Davison</h4>
                                                            <blockquote class="message">Raw denim you probably haven't
                                                                heard of them jean shorts Austin. Nesciunt tofu
                                                                stumptown aliqua butcher retro keffiyeh dreamcatcher
                                                                synth.</blockquote>
                                                            <br />
                                                            <p class="url">
                                                                <span class="fs1 text-info" aria-hidden="true"
                                                                    data-icon=""></span>
                                                                <a href="#"><i class="fa fa-paperclip"></i> User
                                                                    Acceptance Test.doc </a>
                                                            </p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <img src="images/img.jpg" class="avatar" alt="Avatar">
                                                        <div class="message_date">
                                                            <h3 class="date text-error">21</h3>
                                                            <p class="month">May</p>
                                                        </div>
                                                        <div class="message_wrapper">
                                                            <h4 class="heading">Brian Michaels</h4>
                                                            <blockquote class="message">Raw denim you probably haven't
                                                                heard of them jean shorts Austin. Nesciunt tofu
                                                                stumptown aliqua butcher retro keffiyeh dreamcatcher
                                                                synth.</blockquote>
                                                            <br />
                                                            <p class="url">
                                                                <span class="fs1" aria-hidden="true"
                                                                    data-icon=""></span>
                                                                <a href="#" data-original-title="">Download</a>
                                                            </p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <img src="images/img.jpg" class="avatar" alt="Avatar">
                                                        <div class="message_date">
                                                            <h3 class="date text-info">24</h3>
                                                            <p class="month">May</p>
                                                        </div>
                                                        <div class="message_wrapper">
                                                            <h4 class="heading">Desmond Davison</h4>
                                                            <blockquote class="message">Raw denim you probably haven't
                                                                heard of them jean shorts Austin. Nesciunt tofu
                                                                stumptown aliqua butcher retro keffiyeh dreamcatcher
                                                                synth.</blockquote>
                                                            <br />
                                                            <p class="url">
                                                                <span class="fs1 text-info" aria-hidden="true"
                                                                    data-icon=""></span>
                                                                <a href="#"><i class="fa fa-paperclip"></i> User
                                                                    Acceptance Test.doc </a>
                                                            </p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <img src="images/img.jpg" class="avatar" alt="Avatar">
                                                        <div class="message_date">
                                                            <h3 class="date text-error">21</h3>
                                                            <p class="month">May</p>
                                                        </div>
                                                        <div class="message_wrapper">
                                                            <h4 class="heading">Brian Michaels</h4>
                                                            <blockquote class="message">Raw denim you probably haven't
                                                                heard of them jean shorts Austin. Nesciunt tofu
                                                                stumptown aliqua butcher retro keffiyeh dreamcatcher
                                                                synth.</blockquote>
                                                            <br />
                                                            <p class="url">
                                                                <span class="fs1" aria-hidden="true"
                                                                    data-icon=""></span>
                                                                <a href="#" data-original-title="">Download</a>
                                                            </p>
                                                        </div>
                                                    </li>

                                                </ul>
                                                <!-- end recent activity -->

                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="tab_content2"
                                                aria-labelledby="profile-tab">

                                                <!-- start user projects -->
                                                <table class="data table table-striped no-margin">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Project Name</th>
                                                            <th>Client Company</th>
                                                            <th class="hidden-phone">Hours Spent</th>
                                                            <th>Contribution</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>New Company Takeover Review</td>
                                                            <td>Deveint Inc</td>
                                                            <td class="hidden-phone">18</td>
                                                            <td class="vertical-align-mid">
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-success"
                                                                        data-transitiongoal="35"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>New Partner Contracts Consultanci</td>
                                                            <td>Deveint Inc</td>
                                                            <td class="hidden-phone">13</td>
                                                            <td class="vertical-align-mid">
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-danger"
                                                                        data-transitiongoal="15"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Partners and Inverstors report</td>
                                                            <td>Deveint Inc</td>
                                                            <td class="hidden-phone">30</td>
                                                            <td class="vertical-align-mid">
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-success"
                                                                        data-transitiongoal="45"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>New Company Takeover Review</td>
                                                            <td>Deveint Inc</td>
                                                            <td class="hidden-phone">28</td>
                                                            <td class="vertical-align-mid">
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-success"
                                                                        data-transitiongoal="75"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <!-- end user projects -->

                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="tab_content3"
                                                aria-labelledby="profile-tab">
                                                <p>xxFood truck fixie locavore, accusamus mcsweeney's marfa nulla
                                                    single-origin coffee squid. Exercitation +1 labore velit, blog
                                                    sartorial PBR leggings next level wes anderson artisan four loko
                                                    farm-to-table craft beer twee. Qui
                                                    photo booth letterpress, commodo enim craft beer mlkshk </p>
                                            </div>
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