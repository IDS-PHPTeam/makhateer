<?php
  class MokhtarResponse {

    public $No;
    public $Name;
    public $Type;
    public $Date_Of_Birth;
    public $region;
    public $City;
    public $number_in_the_judiciary;
    public $Marital_Status;
    public $Registered_In_NSSF;
    public $Has_Social_Benefits;
    public $Active_Election;
    public $Name_2;
    public $Responsibility_Center;
    public $Location_Code;
    public  $Post_Code;
    public $Country_Region_Code;
    public $Phone_No;
    public $IC_Partner_Code;
    public $Contact;
    public $Salesperson_Code;
    public $Customer_Posting_Group;
    public $Gen_Bus_Posting_Group;
    public $VAT_Bus_Posting_Group;
    public $Customer_Price_Group;
    public $Customer_Disc_Group;
    public $Payment_Terms_Code;
    public $Reminder_Terms_Code;
    public $Fin_Charge_Terms_Code;
    public $Currency_Code;
    public $Language_Code;
    public $Search_Name;
    public $Credit_Limit_LCY;
    public $Blocked;
    public $Privacy_Blocked;
    public $Last_Date_Modified;
    public $Application_Method;
    public $Combine_Shipments;
    public $Reserve;
    public $Ship_to_Code;
    public $Shipping_Advice;
    public $Shipping_Agent_Code;
    public $Base_Calendar_Code;
    public $Balance_LCY;
    public $Balance_Due_LCY;
    public $Sales_LCY;
    public $Payments_LCY;
    public $Global_Dimension_1_Filter;
    public $Global_Dimension_2_Filter;
    public $Currency_Filter;
    public $Date_Filter;
    public $ETag;

    public function __construct($Name,$No) {
        $this->Name = $Name;
        $this->No=$No;
      }
  
  }