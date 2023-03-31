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
                            <a href="./">Home</a>
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


<!-- about section -->

<section class="about_section layout_padding">
    <div class="container">
        <div class="col-md-11 col-lg-10 mx-auto">
            <div class="heading_container heading_center">
                <h2>
                    About Us
                </h2>
            </div>
            <div class="box">
                <div class="col-md-7 mx-auto">
                    <div class="img-box">
                        <img src="images/about-img.jpg" class="box-img" alt="">
                    </div>
                </div>
                <div class="detail-box">
                    <p>
                        Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable
                    </p>
                    <a href="">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- end about section -->