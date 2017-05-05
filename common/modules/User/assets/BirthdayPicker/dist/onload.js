$(document).ready(function(){
    $("#birthday").birthdaypicker({
      fieldId:'test',
      fieldName:'testname',
      //fieldClass: 'form-control',
      hiddenDate: false,

      placeholder: false,
      futureDates: false,
      maxAge: 110,
      defaultDate: "10-17-1980"
    });
});