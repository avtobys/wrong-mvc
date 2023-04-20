<?php

/**
 * @file
 * @brief страница
 */

?>
<script>
    addEventListener('DOMContentLoaded', () => {
        $('body').addClass('sub_page');
    });
</script>
<div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg custom_nav-container">
                <a class="navbar-brand" href="/">
                    <span>
                        Delfood
                    </span>
                </a>
                <div class="" id="">
                    <div class="User_option">
                        <a href="">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>Login</span>
                        </a>
                        <form class="form-inline ">
                            <input type="search" placeholder="Search" />
                            <button class="btn  nav_search-btn" type="submit">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                    <div class="custom_menu-btn">
                        <button onclick="openNav()">
                            <img src="images/menu.png" alt="">
                        </button>
                    </div>
                    <div id="myNav" class="overlay">
                        <div class="overlay-content">
                            <a href="./index">Home</a>
                            <a href="./about">About</a>
                            <a href="./blog">Blog</a>
                            <a href="./testimonial">Testimonial</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- end header section -->
</div>


<!-- news section -->

<section class="news_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>
                Latest Blog
            </h2>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="img-box">
                        <img src="images/n1.jpg" class="box-img" alt="">
                    </div>
                    <div class="detail-box">
                        <h4>
                            Tasty Food For you
                        </h4>
                        <p>
                            there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined
                        </p>
                        <a href="">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="img-box">
                        <img src="images/n2.jpg" class="box-img" alt="">
                    </div>
                    <div class="detail-box">
                        <h4>
                            Breakfast For you
                        </h4>
                        <p>
                            there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined
                        </p>
                        <a href="">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- end news section -->