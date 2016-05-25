<script language="javascript">
<!-- Ticker Tape in Java Script .. Cameron Gregory http://www.bloke.com/
// http://www.bloke.com/javascript/TickerTape/
// use/modify this code as look as you leave these three comment lines in
var tickertapeform
speed=100
len=75
space="                                                                                                    "
tid = 0;
message="TickerTape in JavaScript .. by Cameron Gregory .. visit my home page at http://www.bloke.com/"
c= -len;

function move() {
  cend=Math.min(c+len,message.length)
  if (c <0)
    cstart=0
  else
    cstart=c
  if (c < 0)
    f.scroll.value=space.substring(0,-c) + message.substring(cstart,cend)
  else
    f.scroll.value=message.substring(cstart,cend)
  c = c +1;
  if (c == message.length ) c = -len;
  tid=window.setTimeout("move()",speed);
}
 
function start(x) {
  f=x
  tid=window.setTimeout("move()",speed);
}
 
function cleartid() {
  window.clearTimeout(tid);
}
 
// for some reason on some pages this crashes netscape
function ticker(m,l,s)
{
message=m
len=l
speed=s
document.write('<FORM name=tickertapeform><input name=scroll size=')
document.write(len)
document.write(' value=""></FORM>')
start(document.tickertapeform);
}

// for some reason on some pages this crashes netscape
function ticker(m)
{
message=m
len=75
speed=100
document.write('<FORM name=tickertapeform><input name=scroll size=75></FORM>');
start(document.tickertapeform);
}

// end-->
</script>
