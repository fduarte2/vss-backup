  function IsNumeric(sText){
     var ValidChars = "-0123456789.";
     var Char;
 
     for(i = 0; i < sText.length; i++){ 
        Char = sText.charAt(i); 
        if(ValidChars.indexOf(Char) == -1){
          return false;
        }
     }
     return true;
  }

