function creditCardAppear()
{
    document.getElementById("creditCardSide").style.display="block";
    document.getElementById("touchNGoSide").style.display="none";
    document.getElementById("payPalSide").style.display="none";
}

function touchNGoAppear()
{
    document.getElementById("creditCardSide").style.display="none";
    document.getElementById("touchNGoSide").style.display="block";
    document.getElementById("payPalSide").style.display="none";
}

function payPalAppear()
{
    document.getElementById("creditCardSide").style.display="none";
    document.getElementById("touchNGoSide").style.display="none";
    document.getElementById("payPalSide").style.display="block";
}