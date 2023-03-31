<?php

/**
 * @file
 * @brief шаблон marshmallow
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';
$TEMPLATE_DATA = ob_get_contents();
ob_clean();

const USE_ASSETS_PATH = '/assets/examples/marshmallow';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $row->name ?></title>
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="vendors/owl.carousel/css/owl.carousel.css">
    <link rel="stylesheet" href="vendors/owl.carousel/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="vendors/aos/css/aos.css">
    <link rel="stylesheet" href="vendors/jquery-flipster/css/jquery.flipster.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">
    <div id="mobile-menu-overlay"></div>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="images/logo.svg" alt="Marshmallow"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><i class="mdi mdi-menu"> </i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <div class="d-lg-none d-flex justify-content-between px-4 py-3 align-items-center">
                    <img src="images/logo-dark.svg" class="logo-mobile-menu" alt="logo">
                    <a href="javascript:;" class="close-menu"><i class="mdi mdi-close"></i></a>
                </div>
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item active">
                        <a class="nav-link" href="#home">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#projects">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonial">Testimonial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#plans">Plans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-success" href="#contact">174-394-9560</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?= $TEMPLATE_DATA ?>

    <footer class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <address>
                            <p>143 castle road 517</p>
                            <p class="mb-4">district, kiyev port south Canada</p>
                            <div class="d-flex align-items-center">
                                <p class="mr-4 mb-0">+3 123 456 789</p>
                                <a href="mailto:info@yourmail.com" class="footer-link">info@yourmail.com</a>
                            </div>
                            <div class="d-flex align-items-center">
                                <p class="mr-4 mb-0">+1 222 345 342</p>
                                <a href="mailto:Marshmallow@yourmail.com" class="footer-link">Marshmallow@yourmail.com</a>
                            </div>
                        </address>
                        <div class="social-icons">
                            <h6 class="footer-title font-weight-bold">
                                Social Share
                            </h6>
                            <div class="d-flex">
                                <a href="#"><i class="mdi mdi-github-circle"></i></a>
                                <a href="#"><i class="mdi mdi-facebook-box"></i></a>
                                <a href="#"><i class="mdi mdi-twitter"></i></a>
                                <a href="#"><i class="mdi mdi-dribbble"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="footer-title">Social Share</h6>
                                <ul class="list-footer">
                                    <li><a href="#" class="footer-link">Home</a></li>
                                    <li><a href="#" class="footer-link">About</a></li>
                                    <li><a href="#" class="footer-link">Services</a></li>
                                    <li><a href="#" class="footer-link">Portfolio</a></li>
                                    <li><a href="#" class="footer-link">Contact</a></li>
                                </ul>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="footer-title">Product</h6>
                                <ul class="list-footer">
                                    <li><a href="#" class="footer-link">Digital Marketing</a></li>
                                    <li><a href="#" class="footer-link">Web Development</a></li>
                                    <li><a href="#" class="footer-link">App Development</a></li>
                                    <li><a href="#" class="footer-link">Design</a></li>
                                </ul>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="footer-title">Company</h6>
                                <ul class="list-footer">
                                    <li><a href="#" class="footer-link">Partners</a></li>
                                    <li><a href="#" class="footer-link">Investors</a></li>
                                    <li><a href="#" class="footer-link">Partners</a></li>
                                    <li><a href="#" class="footer-link">FAQ</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="images/logo.svg" alt="logo" class="mr-3"></br>
                        <p class="mb-0 text-small pt-1">© 2019-2020 <a href="https://www.bootstrapdash.com" class="text-white" target="_blank">BootstrapDash</a>. All rights reserved.</p>

                        <p class="mb-0 text-small pt-1 pl-4">Distributed By: <a href="https://www.themewagon.com" class="text-white" target="_blank">Themewagon</a></p>
                    </div>

                    <div>
                        <div class="d-flex">
                            <a href="#" class="text-small text-white mx-2 footer-link">Privacy Policy </a>
                            <a href="#" class="text-small text-white mx-2 footer-link">Customer Support </a>
                            <a href="#" class="text-small text-white mx-2 footer-link">Careers Guide</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <?= Wrong\Html\Get::script($_SERVER['DOCUMENT_ROOT'] . '/assets/system/js/main.min.js') ?>
    <script src="vendors/owl.carousel/js/owl.carousel.js"></script>
    <script src="vendors/aos/js/aos.js"></script>
    <script src="vendors/jquery-flipster/js/jquery.flipster.min.js"></script>
    <script src="js/template.js"></script>
</body>

</html>