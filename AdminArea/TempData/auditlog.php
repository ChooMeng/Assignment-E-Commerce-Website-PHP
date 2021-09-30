<?php
    $audit = array();
    $audit["A00001AA"] = array("time"=>"2020-08-27 14:50","staff"=>"S0001","type"=>"Orders","changes"=>"Updated orderID 'O000001' status.",
        "details"=>"Changed the status of orderID 'O000001' from Pending to Shipping","ip"=>"192.168.0.101");
    $audit["A00002AA"] = array("time"=>"2020-08-28 15:50","staff"=>"S0001","type"=>"Supports","changes"=>"Updated ticketID 'TA00001' status.",
        "details"=>"Changed the status of ticketID 'O000001' from Open to Pending","ip"=>"192.168.0.101");
    $audit["A00003AA"] = array("time"=>"2020-08-28 15:50","staff"=>"S0001","type"=>"Supports","changes"=>"Staff account logged in",
        "details"=>"S0001 has succesful logged in to the website","ip"=>"192.168.0.101");
