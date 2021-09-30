<!DOCTYPE html>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/index.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/index.js" type="text/javascript"></script>
        
    </head>
    <body>
        <?php include 'header.php'; include 'Function/index.php';?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <section>
            <div id="mainContent">
                <div id="imgModal">
                    <span id="close" onclick="closeZoom()">&times;</span>
                    <img id="zoomImg">
                </div>
                <div id="mainContent">
                    <div id="topPart">
                        <img width="100%" height="80%" src="Media/mainPage.png">
                        <div id="title"><h2>Welcome To Daily Market</h2></div>
                    </div>
                    <div class="slideSection">
                        <div class="slideshow">
                            <div id="back" class="slideIcon" onclick="changeSlide(-1)"><i class="fas fa-angle-left"></i></div>
                            <img src="Media/welcome.png" style="display:block" class="fadeInSlide" onclick="zoomSlide(this)">
                            <img src="Media/discount.jpg" class="fadeInSlide" onclick="zoomSlide(this)">
                            <img src="Media/login.png" class="fadeInSlide" onclick="zoomSlide(this)">
                            <div id="pause" onclick="pauseSlide()" style="display:block"><i class="fas fa-pause" title="Click to pause"></i></div>
                            <div id="play" onclick="playSlide()" style="display:none" title="Click to play"><i class="fas fa-play"></i></div>
                            <div id="next" class="slideIcon" onclick="changeSlide(1)"><i class="fas fa-angle-right"></i></div>
                        </div>
                        <div class="slideShowBar">
                            <img src="Media/welcome.png" class="active" onclick="specificSlide(0)">
                            <img src="Media/discount.jpg" onclick="specificSlide(1)">
                            <img src="Media/login.png" onclick="specificSlide(2)">
                        </div>
                    </div>
                    <br/>
                    <div id="productTable">
                        <table>
                            <?php categoryItem(); ?>
                            <tr>
                                <td>
                                    <a href="addsupport.php">
                                        <p><span class="symbol"><i class="fas fa-phone"></i></span><br>
                                            Contact Us</p>
                                    </a>
                                </td>
                                <td>
                                    <a href="register.php">
                                        <p><span class="symbol"><i class="fas fa-user"></i></span><br>
                                        Register Now</p>
                                    </a>
                                </td>
                                <td>
                                    <a href="productList.php">
                                        <p><span class="symbol"><i class="fas fa-angle-double-right"></i></span><br>
                                        More Products</p>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
