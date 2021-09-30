<!DOCTYPE html>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/checkOut.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/checkOut.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'Function/checkOut.php';include 'header.php';?>
        <section id="checkOutBlock">
            <div id="mainContent">
                <h1 id="title">Select Payment Method</h1>
                <div id="checkOutContent">
                    <nav id="payMethodNav">
                        <ul id="payMethodList">
                            <li class="payMethod">
                                <span onclick="creditCardAppear()">
                                    <img src="Media/creditCard.png" class="paymentSymbol"><br>
                                    Credit Card
                                </span>
                            </li>
                            <li class="payMethod">
                                <span onclick="touchNGoAppear()">
                                    <img src="Media/touchNGo.png" class="paymentSymbol"><br>
                                    Touch 'n Go eWallet
                                </span>
                            </li>
                            <li class="payMethod">
                                <span onclick="payPalAppear()">
                                <img src="Media/paypal.png" class="paymentSymbol"><br>
                                PayPal
                                </span>
                            </li>
                        </ul>
                    </nav>
                    <!--Pay with Credit Card-->
                    <div id="creditCardSide" <?php showCreditCard(); ?>>
                        <h2>Credit Card</h2>
                        <?php displayCreditCardErrorMessage(); ?>
                        <form method="POST">
                            <label for="cardNumber"><h3><span class="important">*</span> Card Number <?php if (isset($errorCardNumber)){displayError();} ?> </h3></label>
                            <input type="text" name="cardNumber" id="cardNumber" class="inputCreditCard" value="<?php echo isset($cardNumber)?$cardNumber:""; ?>" maxlength="16" placeholder="Card Number" required="required">

                            <label for="nameOnCard"><h3><span class="important">*</span> Name on Card <?php if (isset($errorName)){displayError();} ?> </h3></label>
                            <input type="text" name="nameOnCard" id="nameOnCard" class="inputCreditCard" value="<?php echo isset($name)?$name:""; ?>" maxlength="40" placeholder="Name on card" required="required">

                            <table>
                                <tr>
                                    <td>
                                        <h3><label for="expirationDate"><span class="important">*</span> Expiration Date <?php if (isset($errorExpirationDate)){displayError();} ?></label></h3>
                                        <input type="text" name="expirationDate" id="expirationDate" value="<?php echo isset($expirationDate)?$expirationDate:""; ?>" class="inputCreditCard" maxlength="5" placeholder="MM/YY" required="required">
                                    </td>
                                    <td>
                                        <h3><label for="cvv"><span class="important">*</span> CVV <?php if (isset($errorCVV)){displayError();} ?></label></h3>
                                        <input type="text" name="cvv" id="cvv" class="inputCreditCard" value="<?php echo isset($cvv)?$cvv:""; ?>" size="6" maxlength="3" placeholder="CVV" required="required">
                                    </td>
                                </tr>
                            </table>
                            <p id="creditCardUserStatement">
                                I acknowledge that my card information is saved in my Daily Market
                                account and One Time Password might not be required for tansactions
                                on Daily Market
                            </p>
                            <!--Generate random 12 character of orderNo-->
                            <button type="submit" name="submitCreditCard" class="payBtn">Pay Now</button>
                        </form>
                    </div>
                    <!--Pay with Touch 'n Go eWallet-->
                    <div id="touchNGoSide" <?php showTouchNGo(); ?>>
                        <h2>Touch 'n Go</h2>
                        <p>Please take note of the following before you proceed:</p>
                        <ol id="tngStatement">
                            <li>You have an activated TNG eWallet account and have your TNG eWallet 6-digits PIN.</li>
                            <li>Ensure you have sufficient balance in your TNG eWallet account to cover the total cost of this order.</li>
                            <li>Maximum amount per transaction is capped aat RM5,000.</li>
                        </ol>
                        <form method="POST">
                            <!--Generate random 12 character of orderNo-->
                            <button type="submit" name="submitTouchNGo" class="payBtn">Pay Now</button>
                        </form>
                    </div>
                    <!--Pay with PayPal-->
                    <div id="payPalSide" <?php showPaypal(); ?>>
                        <h2>PayPal</h2>
                        <p><strong>Pay using your PayPal account</strong></p>
                        <p>You will be redirected to the PayPal system to complete the payment</p>
                        <br>
                        <p>Please choose your option <?php if (isset($errorChoice)){displayError();} ?></p>

                        <form method="POST">
                            <input type="radio" name="payPalChoices" id="loginPayPal" checked>
                            <label for="loginPayPal">Using your PayPal account (You need to log into PayPal)</label><br>
                            <input type="radio" name="payPalChoices" id="guestCheckout">
                            <label for="guestCheckout">Using your credit card (PayPal guest checkout)</label><br><br>
                            <a href="https://www.paypal.com/" id="payPalLink" target="_blank"><span id="payPalSymbol"><i class="fab fa-cc-paypal"></i></span> What is PayPal</a><br>
                            <!--Generate random 12 character of orderNo-->
                            <button type="submit" name="submitPaypal" class="payBtn">Pay Now</button>
                        </form>
                        <p>You will be notified regarding your order status via email and SMS.</p>
                    </div>
                </div>
                <div id="orderContent">
                    <h2 id="orderSummary">Order Summary</h2>
                    <table>
                        <?php displaySummary();?>
                    </table>
                </div>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
