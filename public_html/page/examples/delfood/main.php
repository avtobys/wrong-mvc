<?php

/**
 * @file
 * @brief страница
 */

?>
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
                            <a href="/">Home</a>
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

    <!-- slider section -->
    <section class="slider_section ">
        <div class="container ">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="detail-box">
                        <h1>
                            Discover Restuarant And Food
                        </h1>
                        <p>
                            when looking at its layout. The point of using Lorem Ipsum
                        </p>
                    </div>
                    <div class="find_container ">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <form>
                                        <div class="form-row ">
                                            <div class="form-group col-lg-5">
                                                <input type="text" class="form-control" id="inputHotel" placeholder="Restaurant Name">
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <input type="text" class="form-control" id="inputLocation" placeholder="All Locations">
                                                <span class="location_icon">
                                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <div class="btn-box">
                                                    <button type="submit" class="btn ">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider_container">
            <div class="item">
                <div class="img-box">
                    <img src="images/slider-img1.png" alt="" />
                </div>
            </div>
            <div class="item">
                <div class="img-box">
                    <img src="images/slider-img2.png" alt="" />
                </div>
            </div>
            <div class="item">
                <div class="img-box">
                    <img src="images/slider-img3.png" alt="" />
                </div>
            </div>
            <div class="item">
                <div class="img-box">
                    <img src="images/slider-img4.png" alt="" />
                </div>
            </div>
            <div class="item">
                <div class="img-box">
                    <img src="images/slider-img1.png" alt="" />
                </div>
            </div>
            <div class="item">
                <div class="img-box">
                    <img src="images/slider-img2.png" alt="" />
                </div>
            </div>
            <div class="item">
                <div class="img-box">
                    <img src="images/slider-img3.png" alt="" />
                </div>
            </div>
            <div class="item">
                <div class="img-box">
                    <img src="images/slider-img4.png" alt="" />
                </div>
            </div>
        </div>
    </section>
    <!-- end slider section -->
</div>


<!-- recipe section -->

<section class="recipe_section layout_padding-top">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>
                Our Best Popular Recipes
            </h2>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-4 mx-auto">
                <div class="box">
                    <div class="img-box">
                        <img src="images/r1.jpg" class="box-img" alt="">
                    </div>
                    <div class="detail-box">
                        <h4>
                            Breakfast
                        </h4>
                        <a href="">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 mx-auto">
                <div class="box">
                    <div class="img-box">
                        <img src="images/r2.jpg" class="box-img" alt="">
                    </div>
                    <div class="detail-box">
                        <h4>
                            Lunch
                        </h4>
                        <a href="">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 mx-auto">
                <div class="box">
                    <div class="img-box">
                        <img src="images/r3.jpg" class="box-img" alt="">
                    </div>
                    <div class="detail-box">
                        <h4>
                            Dinner
                        </h4>
                        <a href="">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-box">
            <a href="">
                Order Now
            </a>
        </div>
    </div>
</section>

<!-- end recipe section -->

<!-- app section -->

<section class="app_section">
    <div class="container">
        <div class="col-md-9 mx-auto">
            <div class="row">
                <div class="col-md-7 col-lg-8">
                    <div class="detail-box">
                        <h2>
                            <span> Get the</span> <br>
                            Delfood App
                        </h2>
                        <p>
                            long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The poin
                        </p>
                        <div class="app_btn_box">
                            <a href="" class="mr-1">
                                <img src="images/google_play.png" class="box-img" alt="">
                            </a>
                            <a href="">
                                <img src="images/app_store.png" class="box-img" alt="">
                            </a>
                        </div>
                        <a href="" class="download_btn">
                            Download Now
                        </a>
                    </div>
                </div>
                <div class="col-md-5 col-lg-4">
                    <div class="img-box">
                        <img src="images/mobile.png" class="box-img" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- end app section -->

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

<!-- news section -->

<section class="news_section">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>
                Latest News
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

<!-- client section -->

<section class="client_section layout_padding">
    <div class="container">
        <div class="col-md-11 col-lg-10 mx-auto">
            <div class="heading_container heading_center">
                <h2>
                    Testimonial
                </h2>
            </div>
            <div id="customCarousel1" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="detail-box">
                            <h4>
                                Virginia
                            </h4>
                            <p>
                                Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and
                            </p>
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="detail-box">
                            <h4>
                                Virginia
                            </h4>
                            <p>
                                Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and
                            </p>
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="detail-box">
                            <h4>
                                Virginia
                            </h4>
                            <p>
                                Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and
                            </p>
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev d-none" href="#customCarousel1" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#customCarousel1" role="button" data-slide="next">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- end client section -->